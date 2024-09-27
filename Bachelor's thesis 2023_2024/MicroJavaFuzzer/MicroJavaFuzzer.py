# Usage:
#
# Construct a grammar generator
# gen = SyntaxNodeGenerator(lex_file, cup_file)
# grammar = gen.generate()
# fuzzer = MicroJavaFuzzer(start_symbol=..., grammar=grammar)

from MicroJavaAST import ast_util
from MicroJavaAST.exceptions import *
from fuzzingbook.Grammars import Grammar, convert_ebnf_grammar
from isla.solver import ISLaSolver
from typing import Optional
from io import StringIO

class MicroJavaFuzzer(ISLaSolver):
    # Class that produces MicroJava code.

    def __init__(self, *, start_symbol: str, grammar: Grammar,
                 constraint: Optional[str]=None, **kw_params) -> None:
        """
        Initialize the fuzzer for MicroJava code. Additional keyword parameters
        are passed to the `ISLaSolver` superclass.

        Args:
            start_symbol (str): The grammatical entity to be generated.
            grammar (Grammar): The EBNF grammar to be used.
            constraint (str): an ISLa constraint.
        """
        assert start_symbol in grammar

        g = convert_ebnf_grammar(grammar)
        if constraint is None:
            super().__init__(g, start_symbol=start_symbol, **kw_params)
        else:
            super().__init__(g, constraint, start_symbol=start_symbol,
                             **kw_params)

    def fuzz(self) -> str:
        """
        Produce a MicroJava code string.

        Returns:
            str: Fuzzed MicroJava code.
        """
        abstract_syntax_tree_string = str(self.solve())
        abstract_syntax_tree = ast_util.construct_ast(
            abstract_syntax_tree_string)
        if abstract_syntax_tree is None:
            raise UnexpectedError
        buffer = StringIO()
        from MicroJavaAST.visitors.CodeVisitor import CodeVisitor
        visitor = CodeVisitor(buffer)
        abstract_syntax_tree.traverse_bottom_up(visitor)
        return visitor.get_code()