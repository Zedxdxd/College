import os

from typing import List, Dict, Set
from MicroJavaAST.SyntaxNodeDescriptor import SyntaxNodeDescriptor
from MicroJavaAST.SyntaxNode import SyntaxNode
from MicroJavaAST.exceptions import CyclicDescriptorDependency

DEFAULT_BASE_CLASS = "SyntaxNode"

_ast_nodes_file = os.path.abspath(os.getcwd()) + "\\MicroJavaAST\\ast_nodes.py"
_ast_visitors_dir = os.path.abspath(os.getcwd()) + "\\MicroJavaAST\\visitors"

def write_syntax_node_classes(descriptors: List[SyntaxNodeDescriptor],
                              space_adders: Set[str],
                              newline_adders: Set[str]):
    """
    Generates all syntax node classes given the descriptors.

    Args:
        descriptors ([SyntaxNodeDescriptor]): List of all syntax nodes to be
            generated.
        space_adders ({str}): Names of syntax nodes after which space is added
            to the code.
        newline_adders ({str}): Names of syntax nodes after which new line is
            added to the code.

    Raises:
        CyclicDescriptorDependency
    """
    with open(_ast_nodes_file, "w") as file:
        file.write("# This file is auto generated.\n\n")
        file.write("from MicroJavaAST.SyntaxNode import SyntaxNode\n\n")
    map_descriptor_to_in_deg: Dict[SyntaxNodeDescriptor, int] = {}
    for descriptor in descriptors:
        map_descriptor_to_in_deg[descriptor] = (
            descriptor.get_in_degree() - descriptor.is_dependent(
                DEFAULT_BASE_CLASS))
    with open(_ast_nodes_file, "a") as file:
        for _ in range(len(descriptors)):
            descriptor = next((desc for desc, in_degree in
                               map_descriptor_to_in_deg.items() if
                               in_degree == 0), None)
            if descriptor == None:
                print(map_descriptor_to_in_deg)
                raise CyclicDescriptorDependency
            del map_descriptor_to_in_deg[descriptor]
            for desc in map_descriptor_to_in_deg.keys():
                if desc.is_dependent(descriptor.class_name):
                    map_descriptor_to_in_deg[desc] -= 1
            file.write(descriptor.emit_class(space_adders, newline_adders))
            file.write("\n\n")

def write_visitors(descriptors: List[SyntaxNodeDescriptor],
                   tab_adders: Set[str],
                   tab_removers: Set[str]):
    """
    Generates visitors (abstract and the visitor that emits code).

    Args
        descriptors ([SyntaxNodeDescriptor]): List of all syntax nodes to be
            generated.
        tab_adders ({str}): Names of syntax nodes after which the new line is
            added and the subsequent code is idented by one more tab.
        tab_removers ({str}): Names of syntax nodes after which new line is
            added to the code and the subsequent code is indented by one less
            tab.
    """
    ast_base_visitor_file = _ast_visitors_dir + "\\Visitor.py"
    with open(ast_base_visitor_file, "w") as file:
        file.write("# This file is auto generated.\n\n")
        file.write("from abc import ABC, abstractmethod\n\n")
        file.write("class Visitor(ABC):\n")
        for desc in descriptors:
            file.write("\t@abstractmethod\n")
            file.write(f"\tdef visit_{desc.class_name}(self, node):\n")
            file.write("\t\tpass")
            file.write("\n\n")

    ast_code_visitor_file = _ast_visitors_dir + "\\CodeVisitor.py"
    with open(ast_code_visitor_file, "w") as file:
        file.write("# This file is auto generated.\n\n")
        file.write("from MicroJavaAST.visitors.Visitor import Visitor\n")
        file.write("from io import StringIO\n\n")
        file.write("class CodeVisitor(Visitor):\n")
        file.write("\tdef __init__(self, buffer: StringIO):\n")
        file.write("\t\tself.buffer = buffer\n")
        file.write("\t\tself.tab = ''\n\n")
        file.write("\tdef get_code(self) -> str:\n")
        file.write("\t\treturn self.buffer.getvalue().replace('\\0', '')\n\n")
        for desc in descriptors:
            file.write(f"\tdef visit_{desc.class_name}(self, node):\n")
            if desc.code in tab_adders:
                file.write(f"\t\tself.tab += '\\t'\n")
            elif desc.code in tab_removers:
                file.write("\t\tself.tab = self.tab[:-1]\n")
                file.write("\t\tself.buffer.seek(0)\n")
                file.write("\t\tcontent = self.buffer.read()\n")
                file.write("\t\tself.buffer.truncate(0)\n")
                file.write("\t\tself.buffer.write(content[:-1])\n")
            file.write("\t\tself.buffer.write(node.emit_code(self.tab))\n")
            file.write("\n")

def construct_ast(fuzzed_ast: str) -> SyntaxNode:
    """
    Constructs the abstract syntax tree and returns the root

    Args:
        fuzzed_ast (str): String representing the fuzzed abstract syntax tree
            from which the actual tree is built.

    Returns:
        SyntaxNode: Root of the abstract syntax tree.
    """
    import MicroJavaAST.ast_nodes

    # A helper class to keep track of the current class plus its constructor
    # arguments.
    class Info():
        def __init__(self, class_name):
            self.class_name = class_name
            self.constructor_params = []
        def __repr__(self):
            return self.class_name + str(self.constructor_params)

    stack: List[Info] = []
    current_class_name = ""
    for c in fuzzed_ast:
        if c == "(":
            stack.append(Info(class_name=current_class_name))
            current_class_name = ""
        elif c == ")":
            current_info = stack.pop()
            if current_class_name != "":
                current_info.constructor_params.append(current_class_name)
            cls = getattr(MicroJavaAST.ast_nodes, current_info.class_name, None)
            if not cls:
                return None
            current_class_name = ""
            node = cls(*current_info.constructor_params)
            if len(stack) > 0:
                stack[-1].constructor_params.append(node)
            else:
                return node
        elif c == ",":
            current_class_name = ""
        else:
            current_class_name += c
    return None
