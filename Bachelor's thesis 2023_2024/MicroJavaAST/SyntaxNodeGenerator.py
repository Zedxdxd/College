# A class that based on the input from .lex and .cup files generates a grammar
# for the abstract syntax tree.

import re
import os
import string

from MicroJavaAST import ast_util
from MicroJavaAST.exceptions import *
from MicroJavaAST.SyntaxNodeDescriptor import SyntaxNodeDescriptor
from fuzzingbook.Grammars import Grammar, is_valid_grammar, trim_grammar
from typing import List, TextIO, Tuple, Set, Dict


class SyntaxNodeGenerator():

    STANDARD_TERMINAL_CONSTRUCTOR_PARAM = (
        SyntaxNodeDescriptor.STANDARD_TERMINAL_CONSTRUCTOR_PARAM)

    def __init__(self, lex_file: str, cup_file: str):
        self._lex_file: str = lex_file
        self._cup_file: str = cup_file
        self._ast_grammar: Grammar = {}

        # pattern for example line (from .lex):
        # "program" 	{ return new_symbol(sym.PROGRAM, yytext()); }
        self._lex_terminal_regex_pattern = (r'"([^"]+)"\s*{\s*return\s*new_symb'
                                            r'ol\(\s*sym\.([a-zA-Z][a-zA-Z0-9_]'
                                            r'*),\s*yytext\(\)\s*\);\s*}')

        # pattern for example line (from .cup)
        # terminal PROGRAM, BREAK, CLASS, ELSE;
        self._cup_terminal_regex_pattern = (r'^terminal\s*([a-zA-Z][a-zA-Z0-9_]'
                                            r'*(?:,\s*[a-zA-Z][a-zA-Z0-9_]*)*)'
                                            r'\s*;')

        # pattern for example line (from .cup)
        # terminal Integer Number;
        self._cup_standard_terminal_regex_pattern = (r'^terminal\s*(Integer|'
                                                     r'Boolean|Character|String'
                                                     r')\s*([a-zA-Z][a-zA-Z0-9_'
                                                     r']*);')

        # production pattern for example (from .cup)
        self._cup_derived_class_name_pattern = r'\(([a-zA-Z][a-zA-Z0-9_]*)\)'

        self._descriptors: List[SyntaxNodeDescriptor] = []
        self._descriptor_names: Set[str] = set()

        self._config_folder = (os.path.abspath(os.getcwd()) + "\\config")
        self._space_adders: Set[str] = set()
        self._tab_adders: Set[str] = set()
        self._newline_adders: Set[str] = set()
        self._tab_removers: Set[str] = set()
        self._read_config()

    def _read_config(self):
        """
        Reads the configuration from the config folder
        """
        space_adders_file = (self._config_folder + "\\space_adders")
        tab_adders_file = (self._config_folder + "\\tab_adders")
        newline_adders_file = (self._config_folder + "\\newline_adders")
        tab_removers_file = (self._config_folder + "\\tab_removers")
        with open(space_adders_file, "r") as file:
            for line in file:
                self._space_adders.add(line.strip())
        with open(tab_adders_file, "r") as file:
            for line in file:
                self._tab_adders.add(line.strip())
        with open(newline_adders_file, "r") as file:
            for line in file:
                self._newline_adders.add(line.strip())
        with open(tab_removers_file, "r") as file:
            for line in file:
                self._tab_removers.add(line.strip())

    def _lex_register_terminal(self, line: str):
        """
        If the line matches the regex pattern for the terminal, add it to the
        grammar and to the descriptors.

        Args:
            line (str): The line from .lex file to be checked.
        """
        match = re.search(self._lex_terminal_regex_pattern, line)
        if not match:
            # Skipping if the line doesn't match the pattern
            return
        code = match.group(1)
        class_name = match.group(2)
        non_terminal = f"<{class_name}>"
        self._ast_grammar[non_terminal] = [f"{class_name}()"]

        descriptor = SyntaxNodeDescriptor(class_name=class_name,
                                          base_class_name=(
                                              ast_util.DEFAULT_BASE_CLASS),
                                          constructor_params=[],
                                          code=code)
        self._descriptors.append(descriptor)
        self._descriptor_names.add(class_name)

    def _cup_check_terminals(self, line: str):
        """
        Checks if the line matches the terminal declaration regex for cup. If it
        does check if the terminals from the declaration are defined in the
        .lex file.

        Args:
            line (str): The line from .cup file to be checked.

        Raises:
            TerminalNotInLex
        """
        match = re.search(self._cup_terminal_regex_pattern, line)
        if not match:
            return
        terminal_names = match.group(1).replace(" ", "")
        terminal_names_list = terminal_names.split(",")
        for terminal_name in terminal_names_list:
            if f"<{terminal_name}>" not in self._ast_grammar:
                raise TerminalNotInLex(
                    message=f"Error: terminal {terminal_name} not in the .lex "
                    "file")

    def _cup_maybe_add_standard_terminal(self, line: str):
        """
        Checks if the line matches the standard terminal declaration regex for
        .cup specification. If it does add the standard terminal to the grammar
        and the  descriptors only if it's a well known type (Interger, Boolean,
        Char, String).

        Args:
            line (str): The line from .cup file to be checked.
        """
        match = re.match(self._cup_standard_terminal_regex_pattern, line)
        if not match:
            return
        type = match.group(1)
        class_name = match.group(2)
        terminal_name = f"<{class_name}>"
        if type == "Integer":
            self._ast_grammar[terminal_name] = [f"{class_name}(<_integer>)"]
            self._ast_grammar["<_integer>"] = ["<_non_zero_digit><_digit>*"]
            self._ast_grammar["<_digit>"] = ["0", "1", "2", "3", "4", "5", "6",
                                            "7", "8", "9"]
            self._ast_grammar["<_non_zero_digit>"] = ["1", "2", "3", "4", "5",
                                                      "6", "7", "8", "9"]
        elif type == "Boolean":
            self._ast_grammar[terminal_name] = [f"{class_name}(<_boolean>)"]
            self._ast_grammar["<_boolean>"] = ["true", "false"]
        elif type == "Character":
            self._ast_grammar[terminal_name] = [f"{class_name}('<_character>')"]
            self._ast_grammar["<_character>"] = [ch for ch in
                                                 list(string.printable) if
                                                 ch.isprintable()]
        elif type == "String":
            if "ident" in terminal_name.lower():
                self._ast_grammar[terminal_name] = [f'{class_name}(<_ident>)']
                self._ast_grammar["<_ident>"] = ['<_start_ident_char>'
                                                 '<_ident_char>*']
                self._ast_grammar["<_start_ident_char>"] = (
                    list(string.ascii_letters))
                self._ast_grammar["<_ident_char>"] = (
                    list(string.ascii_letters) + ["_"] + list(string.digits))
            else:
                self._ast_grammar[terminal_name] = [f'{class_name}("<_string>")']
                self._ast_grammar["<_string>"] = ['<_character>*']
                self._ast_grammar["<_character>"] = [c for c in
                                                    list(string.printable) if
                                                    c.isprintable()]
        else:
            print(f"Not well known type: {type}.")
            return
        descriptor = SyntaxNodeDescriptor(
            class_name=class_name, base_class_name=ast_util.DEFAULT_BASE_CLASS,
            constructor_params=[self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM],
            code=self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM)
        self._descriptors.append(descriptor)
        self._descriptor_names.add(class_name)

    def _cup_process_one_production(self, production: str,
                                    abstract_class_name: str):
        """
        Read the data from the production and add it to the descriptor and
        grammar. If there the string error in the production, skip that
        production since we want to produce syntactically correct code.

        Args:
            production (str): The production to be processed.
            abstract_class_name (str): Name of the production. If the derived
                class name matches this, then one descriptor is added. Otherwise
                the descriptor created is where this is the abstract class.

        Raises:
            UnexpectedError
        """
        if "error" in production:
            return
        production = re.sub(r'\s+', ' ', production)
        match = re.match(self._cup_derived_class_name_pattern, production)
        if not match:
            raise UnexpectedError
        derived_class_name = match.group(1)
        constructor_params = [param.split(":")[0] for param in
                              production.split(" ")[1:]]
        if "epsilon" in production:
            constructor_params = []
        base_class_name = (abstract_class_name if abstract_class_name !=
                               derived_class_name else
                               ast_util.DEFAULT_BASE_CLASS)
        descriptor = SyntaxNodeDescriptor(
                class_name=derived_class_name,
                base_class_name=base_class_name,
                constructor_params=constructor_params,
                code=None)
        self._descriptors.append(descriptor)
        self._descriptor_names.add(derived_class_name)
        if abstract_class_name not in self._descriptor_names:
            descriptor = SyntaxNodeDescriptor(
                class_name=abstract_class_name,
                base_class_name=ast_util.DEFAULT_BASE_CLASS,
                constructor_params=[],
                code=None)
            self._descriptors.append(descriptor)
            self._descriptor_names.add(abstract_class_name)

        non_terminal_name = f"<{abstract_class_name}>"
        if non_terminal_name not in self._ast_grammar:
            self._ast_grammar[non_terminal_name] = []
        grammar_production = f"{derived_class_name}("
        for ii, param in enumerate(constructor_params):
            if ii != 0:
                grammar_production += ","
            grammar_production += f"<{param}>"
        grammar_production += ")"
        self._ast_grammar[non_terminal_name].append(grammar_production)

    @staticmethod
    def _cup_replace_semicolons_after_error(content: str):
        """
        Method to fix the content of the file since we are splitting it by the ;
        and parts of the file that are not in our interest interfere with that.

        Args:
            content (str): Content of the .cup file.

        Returns:
            (str): Content of the .cup file without the semicolons that would
                interfere with the splitting logic.
        """
        def replace(match):
            matched_string = match.group(0)
            replaced_string = re.sub(r';', ':', matched_string)
            return replaced_string

        result = re.sub(r'\s*error[^}]*?\}', replace, content)
        return result

    def _cup_process_productions_from_file(self, cup_file_content: str):
        """
        Read every production from the cup file content and process it.

        Args:
            cup_file_content (str): Content of the .cup file.
        """
        cup_file_content = self._cup_replace_semicolons_after_error(
            cup_file_content)
        cup_file_content_list = cup_file_content.split(";")
        non_terminal_productions_list = [production.strip() for production in
                                         cup_file_content_list if "::="
                                         in production]

        for non_terminal_production in non_terminal_productions_list:
            left_side = non_terminal_production.split("::=")[0]
            right_side = non_terminal_production.split("::=")[1].strip()
            abstract_class_name = left_side.strip()
            right_side_list = right_side.split("|")
            for right_side in right_side_list:
                right_side = right_side.strip()
                self._cup_process_one_production(right_side,
                                                 abstract_class_name)

    def generate(self) -> Grammar:
        """
        Read the .lex and .cup files, form the grammar, SyntaxNode classes and
        Visitors to emit the MicroJava code.

        Returns:
            (Grammar): Grammar of the string representation of the abstract
                syntax tree.
        """
        with open(self._lex_file, "r") as file:
            for line in file:
                self._lex_register_terminal(line)
        cup_file_content = ""
        with open(self._cup_file, "r") as file:
            for line in file:
                self._cup_check_terminals(line)
                self._cup_maybe_add_standard_terminal(line)
                if "::=" in line:
                    break
            file.seek(0)
            cup_file_content = file.read()

        self._cup_process_productions_from_file(cup_file_content)
        ast_util.write_syntax_node_classes(self._descriptors,
                                           self._space_adders,
                                           self._newline_adders)
        ast_util.write_visitors(self._descriptors,
                                self._tab_adders,
                                self._tab_removers)

        return self._ast_grammar
