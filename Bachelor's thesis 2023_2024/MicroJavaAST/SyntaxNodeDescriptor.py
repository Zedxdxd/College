# Class that holds the information about syntax nodes. Based on the objects of
# this class the SyntaxNode classes are generated.

from typing import List, Dict, Set
from io import StringIO

class SyntaxNodeDescriptor():

    STANDARD_TERMINAL_CONSTRUCTOR_PARAM = "_value"

    def __init__(self, class_name: str, base_class_name: str, code: str,
                 constructor_params: List[str]):
        self.class_name = class_name
        self.base_class_name = base_class_name
        self.code = code
        self.constructor_params = constructor_params

    def __repr__(self) -> str:
        return self.class_name

    def emit_class(self, space_adders: Set[str],
                   newline_adders: Set[str])-> str:
        """
        Returns a string that is the definition of the class described with this
        object.

        Args:
            space_adders ({str}): Names of syntax nodes after which space is
                added to the code.
            newline_adders ({str}): Names of syntax nodes after which new line
                is added to the code.
        """
        buffer = StringIO()
        buffer.write(f"class {self.class_name}({self.base_class_name}):\n")
        buffer.write(f"\tdef __init__(self")
        # this is to ensure unique formal argument names and unique field names
        names_to_number: Dict[str, int] = dict()
        for name in self.constructor_params:
            if name in names_to_number:
                var_name = f"{name}{names_to_number[name]}"
                names_to_number[name] += 1
            else:
                var_name = f"{name}"
                names_to_number[name] = 1
            buffer.write(f", {var_name}")
            if name != self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM:
                buffer.write(f": {name}")
        buffer.write("):")
        names_to_number: Dict[str, int] = dict()
        for name in self.constructor_params:
            if name in names_to_number:
                var_name = f"{name}{names_to_number[name]}"
                names_to_number[name] += 1
            else:
                var_name = f"{name}"
                names_to_number[name] = 1
            buffer.write(f"\n\t\tself.{var_name} = {var_name}")

        if len(self.constructor_params) == 0:
            buffer.write("\n\t\tpass")
        buffer.write("\n\n")
        buffer.write("\tdef accept(self, visitor):\n")
        buffer.write(f"\t\tvisitor.visit_{self.class_name}(self)\n")

        buffer.write("\n")
        buffer.write("\tdef emit_code(self, tab):\n")
        if self.code is None:
            buffer.write('\t\treturn ""')
        elif self.code == self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM:
            buffer.write('\t\treturn str(self._value) + " "')
        else:
            buffer.write(f"\t\treturn '{self.code}'")
            if self._is_space_adder(space_adders):
                buffer.write(' + " "')
            elif self._is_newline_adder(newline_adders):
                buffer.write(' + "\\n" + tab')
        buffer.write('\n')

        buffer.write("\n")
        buffer.write("\tdef traverse_bottom_up(self, visitor):")
        names_to_number: Dict[str, int] = dict()
        for name in self.constructor_params:
            if name in names_to_number:
                var_name = f"{name}{names_to_number[name]}"
                names_to_number[name] += 1
            else:
                var_name = f"{name}"
                names_to_number[name] = 1
            if name != self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM:
                buffer.write(f"\n\t\tif self.{var_name} is not None: "
                         f"self.{var_name}.traverse_bottom_up(visitor)")

        buffer.write("\n\t\tself.accept(visitor)")
        return buffer.getvalue()

    def is_standard_terminal(self) -> bool:
        """
        Determines whether the SyntaxNode described by this object is a standard
        terminal. A standard terminal is a SyntaxNode which is emited like an
        arbitrary series of characters determined by its parameter _value.

        Returns:
            bool: True if the descriptor represents a standard terminal.
        """
        return (self.STANDARD_TERMINAL_CONSTRUCTOR_PARAM in
                self.constructor_params)

    def get_in_degree(self) -> int:
        """
        Determines the number of unique SyntaxNodes this object depends on (Used
        for determining the order SyntaxNode classes are written to the file so
        every SyntaxNode class has the SyntaxNodes that it depends on defined).

        Returns:
            int: Number of unique SyntaxNodes this class depends on.
        """
        # Depends on all parameters from the constructor plus the base class.
        # If the object is a standard terminal, that shouldn't be counted as
        # dependency.
        return (len(set(self.constructor_params + [self.base_class_name])) -
                self.is_standard_terminal())

    def is_dependent(self, class_name) -> bool:
        """
        Determines whether the object is dependent on the SyntaxNode class name
        passed as the argument.

        Args:
            class_name (str): Class name to determine the dependency.

        Returns:
            bool: True if the object is dependent on the class_name.
        """
        return (class_name == self.base_class_name or class_name in
                self.constructor_params)

    def _is_space_adder(self, space_adders: Set[str]) -> bool:
        """
        Determines whether while emitting code for the SyntaxNode described by
        this object a space must be added.

        Args:
            space_adders ({str}): Names of syntax nodes after which space is
                added to the code.

        Returns:
            bool: True if after emitting code a space must be added.
        """
        return self.code in space_adders or self.is_standard_terminal()

    def _is_newline_adder(self, newline_adders: Set[str]) -> bool:
        """
        Determines whether while emitting code for the SyntaxNode described by
        this object a new line must be added.

        Args:
            newline_adders ({str}): Names of syntax nodes after which a new line
                is added to the code.

        Returns:
            bool: True if after emitting code a new line must be added.
        """
        return self.code in newline_adders

    def _is_tab_adder(self, tab_adders: Set[str]) -> bool:
        """
        Determines whether while emitting code for the SyntaxNode described by
        this object a new line must be added and all the subseqeunt code must be
        idented by one more tab.

        Args:
            tab_adders ({str}): Names of syntax nodes after which the new line
                is added and the subsequent code is idented by one more tab.

        Returns:
            bool: True if after emitting code a new line must be added and the
                subsequent code must be idented by one more tab.
        """
        return self.code in tab_adders

    def _is_tab_remover(self, tab_removers: Set[str]) -> bool:
        """
        Determines whether while emitting code for the SyntaxNode described by
        this object a new line must be added and all the subseqeunt code must be
        idented by one less tab.

        Args:
            tab_removers ({str}): Names of syntax nodes after which new line is
                added to the code and the subsequent code is indented by one
                less tab.

        Returns:
            bool: True if after emitting code a new line must be added and the
                subsequent code must be idented by one less tab.
        """
        return self.code in tab_removers