import argparse
import os
import re
import string
import traceback

from io import StringIO
from MicroJavaAST.SyntaxNodeGenerator import SyntaxNodeGenerator
from fuzzingbook.Grammars import Grammar
from MicroJavaFuzzer.MicroJavaFuzzer import MicroJavaFuzzer
from MicroJavaAST.exceptions import MicroJavaFuzzerException
from MicroJavaAST import ast_util
from MicroJavaAST.visitors.CodeVisitor import CodeVisitor

lex_file = os.path.abspath(os.getcwd()) + "\input\mjlexer.lex"
cup_file = os.path.abspath(os.getcwd()) + "\input\mjparser.cup"
constraint_file = os.path.abspath(os.getcwd()) + "\input\constraint"
output_file = os.path.abspath(os.getcwd()) + "\output\output.mj"

parser = argparse.ArgumentParser(description="Process start_symbol argument.")
parser.add_argument('start_symbol', type=str,
                    help="The start symbol for expanding the tree",
                    nargs='?', default="start_symbol=<Program>")
args = parser.parse_args()
try:
    start_symbol = args.start_symbol.split("=")[1]
    gen = SyntaxNodeGenerator(lex_file, cup_file)
    ast_grammar = gen.generate()
    constraint = ""
    with open(constraint_file, "r") as file:
        constraint = file.read().strip()
    fuzzer: MicroJavaFuzzer = MicroJavaFuzzer(start_symbol=start_symbol,
                                              grammar=ast_grammar,
                                              constraint=constraint if
                                                constraint else None)
    with open(output_file, "w") as file:
        file.write(fuzzer.fuzz())
    print("MicroJava program generated successfully!")
except MicroJavaFuzzerException as e:
    print(e)
except Exception as e:
    print("Unexpected error!")
    print(traceback.format_exc())
