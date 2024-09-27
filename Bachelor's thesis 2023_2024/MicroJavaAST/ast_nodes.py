# This file is auto generated.

from MicroJavaAST.SyntaxNode import SyntaxNode

class PROGRAM(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_PROGRAM(self)

	def emit_code(self, tab):
		return 'program' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class BREAK(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_BREAK(self)

	def emit_code(self, tab):
		return 'break'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CLASS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CLASS(self)

	def emit_code(self, tab):
		return 'class' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ELSE(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ELSE(self)

	def emit_code(self, tab):
		return 'else' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CONST(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CONST(self)

	def emit_code(self, tab):
		return 'const' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class IF(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_IF(self)

	def emit_code(self, tab):
		return 'if' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NEW(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NEW(self)

	def emit_code(self, tab):
		return 'new'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class PRINT(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_PRINT(self)

	def emit_code(self, tab):
		return 'print'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class READ(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_READ(self)

	def emit_code(self, tab):
		return 'read'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class RETURN(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_RETURN(self)

	def emit_code(self, tab):
		return 'return' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class VOID(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_VOID(self)

	def emit_code(self, tab):
		return 'void' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class EXTENDS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_EXTENDS(self)

	def emit_code(self, tab):
		return 'extends' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CONTINUE(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CONTINUE(self)

	def emit_code(self, tab):
		return 'continue'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FOR(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_FOR(self)

	def emit_code(self, tab):
		return 'for' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class STATIC(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_STATIC(self)

	def emit_code(self, tab):
		return 'static' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NAMESPACE(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NAMESPACE(self)

	def emit_code(self, tab):
		return 'namespace' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class PLUS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_PLUS(self)

	def emit_code(self, tab):
		return '+' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class MINUS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_MINUS(self)

	def emit_code(self, tab):
		return '-' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ASTERISK(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ASTERISK(self)

	def emit_code(self, tab):
		return '*' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class SLASH(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SLASH(self)

	def emit_code(self, tab):
		return '/' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class PERCENT(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_PERCENT(self)

	def emit_code(self, tab):
		return '%' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LOGICAL_EQUALS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LOGICAL_EQUALS(self)

	def emit_code(self, tab):
		return '==' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LOGICAL_NOT_EQUALS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LOGICAL_NOT_EQUALS(self)

	def emit_code(self, tab):
		return '!=' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class GREATER_OR_EQUALS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_GREATER_OR_EQUALS(self)

	def emit_code(self, tab):
		return '>=' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class GREATER(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_GREATER(self)

	def emit_code(self, tab):
		return '>' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LESS_OR_EQUALS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LESS_OR_EQUALS(self)

	def emit_code(self, tab):
		return '<=' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LESS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LESS(self)

	def emit_code(self, tab):
		return '<' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LOGICAL_AND(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LOGICAL_AND(self)

	def emit_code(self, tab):
		return '&&' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LOGICAL_OR(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LOGICAL_OR(self)

	def emit_code(self, tab):
		return '||' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class INCREMENT(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_INCREMENT(self)

	def emit_code(self, tab):
		return '++' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DECREMENT(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DECREMENT(self)

	def emit_code(self, tab):
		return '--' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class SEMICOLON(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SEMICOLON(self)

	def emit_code(self, tab):
		return ';' + "\n" + tab

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DOUBLE_COLON(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DOUBLE_COLON(self)

	def emit_code(self, tab):
		return '::' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class COMMA(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_COMMA(self)

	def emit_code(self, tab):
		return ',' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DOT(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DOT(self)

	def emit_code(self, tab):
		return '.'

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LEFT_PAREN(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LEFT_PAREN(self)

	def emit_code(self, tab):
		return '('

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class RIGHT_PAREN(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_RIGHT_PAREN(self)

	def emit_code(self, tab):
		return ')' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LEFT_BRACKET(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LEFT_BRACKET(self)

	def emit_code(self, tab):
		return '['

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class RIGHT_BRACKET(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_RIGHT_BRACKET(self)

	def emit_code(self, tab):
		return ']' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class LEFT_BRACE(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_LEFT_BRACE(self)

	def emit_code(self, tab):
		return '{' + "\n" + tab

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class RIGHT_BRACE(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_RIGHT_BRACE(self)

	def emit_code(self, tab):
		return '}' + "\n" + tab

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FOREACH(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_FOREACH(self)

	def emit_code(self, tab):
		return '=>' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class EQUALS(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_EQUALS(self)

	def emit_code(self, tab):
		return '=' + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NUMBER(SyntaxNode):
	def __init__(self, _value):
		self._value = _value

	def accept(self, visitor):
		visitor.visit_NUMBER(self)

	def emit_code(self, tab):
		return str(self._value) + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class BOOL(SyntaxNode):
	def __init__(self, _value):
		self._value = _value

	def accept(self, visitor):
		visitor.visit_BOOL(self)

	def emit_code(self, tab):
		return str(self._value) + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class IDENT(SyntaxNode):
	def __init__(self, _value):
		self._value = _value

	def accept(self, visitor):
		visitor.visit_IDENT(self)

	def emit_code(self, tab):
		return str(self._value) + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CHAR(SyntaxNode):
	def __init__(self, _value):
		self._value = _value

	def accept(self, visitor):
		visitor.visit_CHAR(self)

	def emit_code(self, tab):
		return str(self._value) + " "

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ProgramName(SyntaxNode):
	def __init__(self, PROGRAM: PROGRAM, IDENT: IDENT):
		self.PROGRAM = PROGRAM
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_ProgramName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.PROGRAM is not None: self.PROGRAM.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class NamespaceList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NamespaceList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoNamespace(NamespaceList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoNamespace(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NamespaceName(SyntaxNode):
	def __init__(self, NAMESPACE: NAMESPACE, IDENT: IDENT):
		self.NAMESPACE = NAMESPACE
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_NamespaceName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NAMESPACE is not None: self.NAMESPACE.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class GeneralDeclList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_GeneralDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoGeneralDecl(GeneralDeclList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoGeneralDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class GeneralDecl(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_GeneralDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class GeneralDeclarations(GeneralDeclList):
	def __init__(self, GeneralDeclList: GeneralDeclList, GeneralDecl: GeneralDecl):
		self.GeneralDeclList = GeneralDeclList
		self.GeneralDecl = GeneralDecl

	def accept(self, visitor):
		visitor.visit_GeneralDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.GeneralDeclList is not None: self.GeneralDeclList.traverse_bottom_up(visitor)
		if self.GeneralDecl is not None: self.GeneralDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class MethodDeclList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_MethodDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class Program(SyntaxNode):
	def __init__(self, ProgramName: ProgramName, NamespaceList: NamespaceList, GeneralDeclList: GeneralDeclList, LEFT_BRACE: LEFT_BRACE, MethodDeclList: MethodDeclList, RIGHT_BRACE: RIGHT_BRACE):
		self.ProgramName = ProgramName
		self.NamespaceList = NamespaceList
		self.GeneralDeclList = GeneralDeclList
		self.LEFT_BRACE = LEFT_BRACE
		self.MethodDeclList = MethodDeclList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_Program(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ProgramName is not None: self.ProgramName.traverse_bottom_up(visitor)
		if self.NamespaceList is not None: self.NamespaceList.traverse_bottom_up(visitor)
		if self.GeneralDeclList is not None: self.GeneralDeclList.traverse_bottom_up(visitor)
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.MethodDeclList is not None: self.MethodDeclList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class Namespace(SyntaxNode):
	def __init__(self, NamespaceName: NamespaceName, LEFT_BRACE: LEFT_BRACE, GeneralDeclList: GeneralDeclList, LEFT_BRACE1: LEFT_BRACE, MethodDeclList: MethodDeclList, RIGHT_BRACE: RIGHT_BRACE, RIGHT_BRACE1: RIGHT_BRACE):
		self.NamespaceName = NamespaceName
		self.LEFT_BRACE = LEFT_BRACE
		self.GeneralDeclList = GeneralDeclList
		self.LEFT_BRACE1 = LEFT_BRACE1
		self.MethodDeclList = MethodDeclList
		self.RIGHT_BRACE = RIGHT_BRACE
		self.RIGHT_BRACE1 = RIGHT_BRACE1

	def accept(self, visitor):
		visitor.visit_Namespace(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NamespaceName is not None: self.NamespaceName.traverse_bottom_up(visitor)
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.GeneralDeclList is not None: self.GeneralDeclList.traverse_bottom_up(visitor)
		if self.LEFT_BRACE1 is not None: self.LEFT_BRACE1.traverse_bottom_up(visitor)
		if self.MethodDeclList is not None: self.MethodDeclList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE1 is not None: self.RIGHT_BRACE1.traverse_bottom_up(visitor)
		self.accept(visitor)

class NamespaceDeclarations(NamespaceList):
	def __init__(self, NamespaceList: NamespaceList, Namespace: Namespace):
		self.NamespaceList = NamespaceList
		self.Namespace = Namespace

	def accept(self, visitor):
		visitor.visit_NamespaceDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NamespaceList is not None: self.NamespaceList.traverse_bottom_up(visitor)
		if self.Namespace is not None: self.Namespace.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoMethodDecl(MethodDeclList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoMethodDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ConstValue(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ConstValue(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ConstNumber(ConstValue):
	def __init__(self, NUMBER: NUMBER):
		self.NUMBER = NUMBER

	def accept(self, visitor):
		visitor.visit_ConstNumber(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NUMBER is not None: self.NUMBER.traverse_bottom_up(visitor)
		self.accept(visitor)

class ConstChar(ConstValue):
	def __init__(self, CHAR: CHAR):
		self.CHAR = CHAR

	def accept(self, visitor):
		visitor.visit_ConstChar(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CHAR is not None: self.CHAR.traverse_bottom_up(visitor)
		self.accept(visitor)

class ConstBool(ConstValue):
	def __init__(self, BOOL: BOOL):
		self.BOOL = BOOL

	def accept(self, visitor):
		visitor.visit_ConstBool(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.BOOL is not None: self.BOOL.traverse_bottom_up(visitor)
		self.accept(visitor)

class ConstAssignmentList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ConstAssignmentList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ConstAssignment(SyntaxNode):
	def __init__(self, IDENT: IDENT, EQUALS: EQUALS, ConstValue: ConstValue):
		self.IDENT = IDENT
		self.EQUALS = EQUALS
		self.ConstValue = ConstValue

	def accept(self, visitor):
		visitor.visit_ConstAssignment(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.EQUALS is not None: self.EQUALS.traverse_bottom_up(visitor)
		if self.ConstValue is not None: self.ConstValue.traverse_bottom_up(visitor)
		self.accept(visitor)

class ConstAssignments(ConstAssignmentList):
	def __init__(self, ConstAssignmentList: ConstAssignmentList, COMMA: COMMA, ConstAssignment: ConstAssignment):
		self.ConstAssignmentList = ConstAssignmentList
		self.COMMA = COMMA
		self.ConstAssignment = ConstAssignment

	def accept(self, visitor):
		visitor.visit_ConstAssignments(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ConstAssignmentList is not None: self.ConstAssignmentList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.ConstAssignment is not None: self.ConstAssignment.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleConstAssignment(ConstAssignmentList):
	def __init__(self, ConstAssignment: ConstAssignment):
		self.ConstAssignment = ConstAssignment

	def accept(self, visitor):
		visitor.visit_SingleConstAssignment(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ConstAssignment is not None: self.ConstAssignment.traverse_bottom_up(visitor)
		self.accept(visitor)

class Type(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Type(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ConstDecl(SyntaxNode):
	def __init__(self, CONST: CONST, Type: Type, ConstAssignmentList: ConstAssignmentList, SEMICOLON: SEMICOLON):
		self.CONST = CONST
		self.Type = Type
		self.ConstAssignmentList = ConstAssignmentList
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_ConstDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CONST is not None: self.CONST.traverse_bottom_up(visitor)
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.ConstAssignmentList is not None: self.ConstAssignmentList.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class GenConstDecl(GeneralDecl):
	def __init__(self, ConstDecl: ConstDecl):
		self.ConstDecl = ConstDecl

	def accept(self, visitor):
		visitor.visit_GenConstDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ConstDecl is not None: self.ConstDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class ScopeType(Type):
	def __init__(self, IDENT: IDENT, DOUBLE_COLON: DOUBLE_COLON, IDENT1: IDENT):
		self.IDENT = IDENT
		self.DOUBLE_COLON = DOUBLE_COLON
		self.IDENT1 = IDENT1

	def accept(self, visitor):
		visitor.visit_ScopeType(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.DOUBLE_COLON is not None: self.DOUBLE_COLON.traverse_bottom_up(visitor)
		if self.IDENT1 is not None: self.IDENT1.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoScopeType(Type):
	def __init__(self, IDENT: IDENT):
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_NoScopeType(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class VarDecl(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_VarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class GenVarDecl(GeneralDecl):
	def __init__(self, VarDecl: VarDecl):
		self.VarDecl = VarDecl

	def accept(self, visitor):
		visitor.visit_GenVarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.VarDecl is not None: self.VarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleTypeVarDeclList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SingleTypeVarDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class VarDeclaration(VarDecl):
	def __init__(self, Type: Type, SingleTypeVarDeclList: SingleTypeVarDeclList, SEMICOLON: SEMICOLON):
		self.Type = Type
		self.SingleTypeVarDeclList = SingleTypeVarDeclList
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_VarDeclaration(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.SingleTypeVarDeclList is not None: self.SingleTypeVarDeclList.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleTypeVarDecl(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SingleTypeVarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class SingleTypeVarDeclarations(SingleTypeVarDeclList):
	def __init__(self, SingleTypeVarDeclList: SingleTypeVarDeclList, COMMA: COMMA, SingleTypeVarDecl: SingleTypeVarDecl):
		self.SingleTypeVarDeclList = SingleTypeVarDeclList
		self.COMMA = COMMA
		self.SingleTypeVarDecl = SingleTypeVarDecl

	def accept(self, visitor):
		visitor.visit_SingleTypeVarDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SingleTypeVarDeclList is not None: self.SingleTypeVarDeclList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.SingleTypeVarDecl is not None: self.SingleTypeVarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class OneTypeVarDecl(SingleTypeVarDeclList):
	def __init__(self, SingleTypeVarDecl: SingleTypeVarDecl):
		self.SingleTypeVarDecl = SingleTypeVarDecl

	def accept(self, visitor):
		visitor.visit_OneTypeVarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SingleTypeVarDecl is not None: self.SingleTypeVarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class ArrayDecl(SingleTypeVarDecl):
	def __init__(self, IDENT: IDENT, LEFT_BRACKET: LEFT_BRACKET, RIGHT_BRACKET: RIGHT_BRACKET):
		self.IDENT = IDENT
		self.LEFT_BRACKET = LEFT_BRACKET
		self.RIGHT_BRACKET = RIGHT_BRACKET

	def accept(self, visitor):
		visitor.visit_ArrayDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.LEFT_BRACKET is not None: self.LEFT_BRACKET.traverse_bottom_up(visitor)
		if self.RIGHT_BRACKET is not None: self.RIGHT_BRACKET.traverse_bottom_up(visitor)
		self.accept(visitor)

class NonArrayDecl(SingleTypeVarDecl):
	def __init__(self, IDENT: IDENT):
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_NonArrayDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class ClassDeclStart(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ClassDeclStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ExtendedClassDeclStart(ClassDeclStart):
	def __init__(self, CLASS: CLASS, IDENT: IDENT, EXTENDS: EXTENDS, Type: Type):
		self.CLASS = CLASS
		self.IDENT = IDENT
		self.EXTENDS = EXTENDS
		self.Type = Type

	def accept(self, visitor):
		visitor.visit_ExtendedClassDeclStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CLASS is not None: self.CLASS.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.EXTENDS is not None: self.EXTENDS.traverse_bottom_up(visitor)
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		self.accept(visitor)

class BaseClassDeclStart(ClassDeclStart):
	def __init__(self, CLASS: CLASS, IDENT: IDENT):
		self.CLASS = CLASS
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_BaseClassDeclStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CLASS is not None: self.CLASS.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class MethodName(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_MethodName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class TypeMethodName(MethodName):
	def __init__(self, Type: Type, IDENT: IDENT):
		self.Type = Type
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_TypeMethodName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class VoidMethodName(MethodName):
	def __init__(self, VOID: VOID, IDENT: IDENT):
		self.VOID = VOID
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_VoidMethodName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.VOID is not None: self.VOID.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class VarDeclList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_VarDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ClassVarDeclList(SyntaxNode):
	def __init__(self, VarDeclList: VarDeclList):
		self.VarDeclList = VarDeclList

	def accept(self, visitor):
		visitor.visit_ClassVarDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.VarDeclList is not None: self.VarDeclList.traverse_bottom_up(visitor)
		self.accept(visitor)

class VarDeclarations(VarDeclList):
	def __init__(self, VarDeclList: VarDeclList, VarDecl: VarDecl):
		self.VarDeclList = VarDeclList
		self.VarDecl = VarDecl

	def accept(self, visitor):
		visitor.visit_VarDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.VarDeclList is not None: self.VarDeclList.traverse_bottom_up(visitor)
		if self.VarDecl is not None: self.VarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoVarDecl(VarDeclList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoVarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FormPars(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_FormPars(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoFormPars(FormPars):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoFormPars(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FormParsList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_FormParsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FormParams(FormPars):
	def __init__(self, FormParsList: FormParsList):
		self.FormParsList = FormParsList

	def accept(self, visitor):
		visitor.visit_FormParams(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FormParsList is not None: self.FormParsList.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleFormPar(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SingleFormPar(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FormParamsList(FormParsList):
	def __init__(self, FormParsList: FormParsList, COMMA: COMMA, SingleFormPar: SingleFormPar):
		self.FormParsList = FormParsList
		self.COMMA = COMMA
		self.SingleFormPar = SingleFormPar

	def accept(self, visitor):
		visitor.visit_FormParamsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FormParsList is not None: self.FormParsList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.SingleFormPar is not None: self.SingleFormPar.traverse_bottom_up(visitor)
		self.accept(visitor)

class OneFormPar(FormParsList):
	def __init__(self, SingleFormPar: SingleFormPar):
		self.SingleFormPar = SingleFormPar

	def accept(self, visitor):
		visitor.visit_OneFormPar(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SingleFormPar is not None: self.SingleFormPar.traverse_bottom_up(visitor)
		self.accept(visitor)

class FormParNonArray(SingleFormPar):
	def __init__(self, Type: Type, IDENT: IDENT):
		self.Type = Type
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_FormParNonArray(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class FormParArray(SingleFormPar):
	def __init__(self, Type: Type, IDENT: IDENT, LEFT_BRACKET: LEFT_BRACKET, RIGHT_BRACKET: RIGHT_BRACKET):
		self.Type = Type
		self.IDENT = IDENT
		self.LEFT_BRACKET = LEFT_BRACKET
		self.RIGHT_BRACKET = RIGHT_BRACKET

	def accept(self, visitor):
		visitor.visit_FormParArray(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.LEFT_BRACKET is not None: self.LEFT_BRACKET.traverse_bottom_up(visitor)
		if self.RIGHT_BRACKET is not None: self.RIGHT_BRACKET.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorStatement(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DesignatorStatement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorUnzipStart(SyntaxNode):
	def __init__(self, LEFT_BRACKET: LEFT_BRACKET):
		self.LEFT_BRACKET = LEFT_BRACKET

	def accept(self, visitor):
		visitor.visit_DesignatorUnzipStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LEFT_BRACKET is not None: self.LEFT_BRACKET.traverse_bottom_up(visitor)
		self.accept(visitor)

class StatementList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_StatementList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class MethodDecl(SyntaxNode):
	def __init__(self, MethodName: MethodName, LEFT_PAREN: LEFT_PAREN, FormPars: FormPars, RIGHT_PAREN: RIGHT_PAREN, VarDeclList: VarDeclList, LEFT_BRACE: LEFT_BRACE, StatementList: StatementList, RIGHT_BRACE: RIGHT_BRACE):
		self.MethodName = MethodName
		self.LEFT_PAREN = LEFT_PAREN
		self.FormPars = FormPars
		self.RIGHT_PAREN = RIGHT_PAREN
		self.VarDeclList = VarDeclList
		self.LEFT_BRACE = LEFT_BRACE
		self.StatementList = StatementList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_MethodDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.MethodName is not None: self.MethodName.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.FormPars is not None: self.FormPars.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.VarDeclList is not None: self.VarDeclList.traverse_bottom_up(visitor)
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.StatementList is not None: self.StatementList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class MethodDeclarations(MethodDeclList):
	def __init__(self, MethodDeclList: MethodDeclList, MethodDecl: MethodDecl):
		self.MethodDeclList = MethodDeclList
		self.MethodDecl = MethodDecl

	def accept(self, visitor):
		visitor.visit_MethodDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.MethodDeclList is not None: self.MethodDeclList.traverse_bottom_up(visitor)
		if self.MethodDecl is not None: self.MethodDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoStatement(StatementList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoStatement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class Statement(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Statement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class StmtList(StatementList):
	def __init__(self, StatementList: StatementList, Statement: Statement):
		self.StatementList = StatementList
		self.Statement = Statement

	def accept(self, visitor):
		visitor.visit_StmtList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StatementList is not None: self.StatementList.traverse_bottom_up(visitor)
		if self.Statement is not None: self.Statement.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorStmt(Statement):
	def __init__(self, DesignatorStatement: DesignatorStatement, SEMICOLON: SEMICOLON):
		self.DesignatorStatement = DesignatorStatement
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_DesignatorStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorStatement is not None: self.DesignatorStatement.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class BreakStmt(Statement):
	def __init__(self, BREAK: BREAK, SEMICOLON: SEMICOLON):
		self.BREAK = BREAK
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_BreakStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.BREAK is not None: self.BREAK.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class ContinueStmt(Statement):
	def __init__(self, CONTINUE: CONTINUE, SEMICOLON: SEMICOLON):
		self.CONTINUE = CONTINUE
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_ContinueStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CONTINUE is not None: self.CONTINUE.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class ReturnVoid(Statement):
	def __init__(self, RETURN: RETURN, SEMICOLON: SEMICOLON):
		self.RETURN = RETURN
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_ReturnVoid(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.RETURN is not None: self.RETURN.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class StmtBlock(Statement):
	def __init__(self, LEFT_BRACE: LEFT_BRACE, StatementList: StatementList, RIGHT_BRACE: RIGHT_BRACE):
		self.LEFT_BRACE = LEFT_BRACE
		self.StatementList = StatementList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_StmtBlock(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.StatementList is not None: self.StatementList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class IfStart(SyntaxNode):
	def __init__(self, IF: IF):
		self.IF = IF

	def accept(self, visitor):
		visitor.visit_IfStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IF is not None: self.IF.traverse_bottom_up(visitor)
		self.accept(visitor)

class ElseStart(SyntaxNode):
	def __init__(self, ELSE: ELSE):
		self.ELSE = ELSE

	def accept(self, visitor):
		visitor.visit_ElseStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ELSE is not None: self.ELSE.traverse_bottom_up(visitor)
		self.accept(visitor)

class ForLoopStart(SyntaxNode):
	def __init__(self, FOR: FOR):
		self.FOR = FOR

	def accept(self, visitor):
		visitor.visit_ForLoopStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FOR is not None: self.FOR.traverse_bottom_up(visitor)
		self.accept(visitor)

class FirstForSemicolon(SyntaxNode):
	def __init__(self, SEMICOLON: SEMICOLON):
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_FirstForSemicolon(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class SecondForSemicolon(SyntaxNode):
	def __init__(self, SEMICOLON: SEMICOLON):
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_SecondForSemicolon(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class Expr(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Expr(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class PrintSingleStmt(Statement):
	def __init__(self, PRINT: PRINT, LEFT_PAREN: LEFT_PAREN, Expr: Expr, RIGHT_PAREN: RIGHT_PAREN, SEMICOLON: SEMICOLON):
		self.PRINT = PRINT
		self.LEFT_PAREN = LEFT_PAREN
		self.Expr = Expr
		self.RIGHT_PAREN = RIGHT_PAREN
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_PrintSingleStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.PRINT is not None: self.PRINT.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class PrintMultipleStmt(Statement):
	def __init__(self, PRINT: PRINT, LEFT_PAREN: LEFT_PAREN, Expr: Expr, COMMA: COMMA, NUMBER: NUMBER, RIGHT_PAREN: RIGHT_PAREN, SEMICOLON: SEMICOLON):
		self.PRINT = PRINT
		self.LEFT_PAREN = LEFT_PAREN
		self.Expr = Expr
		self.COMMA = COMMA
		self.NUMBER = NUMBER
		self.RIGHT_PAREN = RIGHT_PAREN
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_PrintMultipleStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.PRINT is not None: self.PRINT.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.NUMBER is not None: self.NUMBER.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class ReturnNonVoid(Statement):
	def __init__(self, RETURN: RETURN, Expr: Expr, SEMICOLON: SEMICOLON):
		self.RETURN = RETURN
		self.Expr = Expr
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_ReturnNonVoid(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.RETURN is not None: self.RETURN.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class StartExpr(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_StartExpr(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class TermList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_TermList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoTerm(TermList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoTerm(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FactorList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_FactorList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoFactor(FactorList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class Factor(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Factor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class Term(SyntaxNode):
	def __init__(self, Factor: Factor, FactorList: FactorList):
		self.Factor = Factor
		self.FactorList = FactorList

	def accept(self, visitor):
		visitor.visit_Term(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Factor is not None: self.Factor.traverse_bottom_up(visitor)
		if self.FactorList is not None: self.FactorList.traverse_bottom_up(visitor)
		self.accept(visitor)

class NegativeExpr(Expr):
	def __init__(self, MINUS: MINUS, StartExpr: StartExpr, Term: Term, TermList: TermList):
		self.MINUS = MINUS
		self.StartExpr = StartExpr
		self.Term = Term
		self.TermList = TermList

	def accept(self, visitor):
		visitor.visit_NegativeExpr(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.MINUS is not None: self.MINUS.traverse_bottom_up(visitor)
		if self.StartExpr is not None: self.StartExpr.traverse_bottom_up(visitor)
		if self.Term is not None: self.Term.traverse_bottom_up(visitor)
		if self.TermList is not None: self.TermList.traverse_bottom_up(visitor)
		self.accept(visitor)

class PositiveExpr(Expr):
	def __init__(self, StartExpr: StartExpr, Term: Term, TermList: TermList):
		self.StartExpr = StartExpr
		self.Term = Term
		self.TermList = TermList

	def accept(self, visitor):
		visitor.visit_PositiveExpr(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StartExpr is not None: self.StartExpr.traverse_bottom_up(visitor)
		if self.Term is not None: self.Term.traverse_bottom_up(visitor)
		if self.TermList is not None: self.TermList.traverse_bottom_up(visitor)
		self.accept(visitor)

class NumberFactor(Factor):
	def __init__(self, NUMBER: NUMBER):
		self.NUMBER = NUMBER

	def accept(self, visitor):
		visitor.visit_NumberFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NUMBER is not None: self.NUMBER.traverse_bottom_up(visitor)
		self.accept(visitor)

class CharFactor(Factor):
	def __init__(self, CHAR: CHAR):
		self.CHAR = CHAR

	def accept(self, visitor):
		visitor.visit_CharFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CHAR is not None: self.CHAR.traverse_bottom_up(visitor)
		self.accept(visitor)

class SubExprFactor(Factor):
	def __init__(self, LEFT_PAREN: LEFT_PAREN, Expr: Expr, RIGHT_PAREN: RIGHT_PAREN):
		self.LEFT_PAREN = LEFT_PAREN
		self.Expr = Expr
		self.RIGHT_PAREN = RIGHT_PAREN

	def accept(self, visitor):
		visitor.visit_SubExprFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		self.accept(visitor)

class BoolFactor(Factor):
	def __init__(self, BOOL: BOOL):
		self.BOOL = BOOL

	def accept(self, visitor):
		visitor.visit_BoolFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.BOOL is not None: self.BOOL.traverse_bottom_up(visitor)
		self.accept(visitor)

class ArrayFactor(Factor):
	def __init__(self, NEW: NEW, Type: Type, LEFT_BRACKET: LEFT_BRACKET, Expr: Expr, RIGHT_BRACKET: RIGHT_BRACKET):
		self.NEW = NEW
		self.Type = Type
		self.LEFT_BRACKET = LEFT_BRACKET
		self.Expr = Expr
		self.RIGHT_BRACKET = RIGHT_BRACKET

	def accept(self, visitor):
		visitor.visit_ArrayFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NEW is not None: self.NEW.traverse_bottom_up(visitor)
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.LEFT_BRACKET is not None: self.LEFT_BRACKET.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.RIGHT_BRACKET is not None: self.RIGHT_BRACKET.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorName(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DesignatorName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ScopeDesignatorName(DesignatorName):
	def __init__(self, IDENT: IDENT, DOUBLE_COLON: DOUBLE_COLON, IDENT1: IDENT):
		self.IDENT = IDENT
		self.DOUBLE_COLON = DOUBLE_COLON
		self.IDENT1 = IDENT1

	def accept(self, visitor):
		visitor.visit_ScopeDesignatorName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		if self.DOUBLE_COLON is not None: self.DOUBLE_COLON.traverse_bottom_up(visitor)
		if self.IDENT1 is not None: self.IDENT1.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoScopeDesignatorName(DesignatorName):
	def __init__(self, IDENT: IDENT):
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_NoScopeDesignatorName(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorExtension(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DesignatorExtension(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class Designator(SyntaxNode):
	def __init__(self, DesignatorName: DesignatorName, DesignatorExtension: DesignatorExtension):
		self.DesignatorName = DesignatorName
		self.DesignatorExtension = DesignatorExtension

	def accept(self, visitor):
		visitor.visit_Designator(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorName is not None: self.DesignatorName.traverse_bottom_up(visitor)
		if self.DesignatorExtension is not None: self.DesignatorExtension.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorAssignment(DesignatorStatement):
	def __init__(self, Designator: Designator, EQUALS: EQUALS, Expr: Expr):
		self.Designator = Designator
		self.EQUALS = EQUALS
		self.Expr = Expr

	def accept(self, visitor):
		visitor.visit_DesignatorAssignment(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.EQUALS is not None: self.EQUALS.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorIncrement(DesignatorStatement):
	def __init__(self, Designator: Designator, INCREMENT: INCREMENT):
		self.Designator = Designator
		self.INCREMENT = INCREMENT

	def accept(self, visitor):
		visitor.visit_DesignatorIncrement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.INCREMENT is not None: self.INCREMENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorDecrement(DesignatorStatement):
	def __init__(self, Designator: Designator, DECREMENT: DECREMENT):
		self.Designator = Designator
		self.DECREMENT = DECREMENT

	def accept(self, visitor):
		visitor.visit_DesignatorDecrement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.DECREMENT is not None: self.DECREMENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class ReadStmt(Statement):
	def __init__(self, READ: READ, LEFT_PAREN: LEFT_PAREN, Designator: Designator, RIGHT_PAREN: RIGHT_PAREN, SEMICOLON: SEMICOLON):
		self.READ = READ
		self.LEFT_PAREN = LEFT_PAREN
		self.Designator = Designator
		self.RIGHT_PAREN = RIGHT_PAREN
		self.SEMICOLON = SEMICOLON

	def accept(self, visitor):
		visitor.visit_ReadStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.READ is not None: self.READ.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.SEMICOLON is not None: self.SEMICOLON.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorFactor(Factor):
	def __init__(self, Designator: Designator):
		self.Designator = Designator

	def accept(self, visitor):
		visitor.visit_DesignatorFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		self.accept(visitor)

class FuncCallStart(SyntaxNode):
	def __init__(self, Designator: Designator, LEFT_PAREN: LEFT_PAREN):
		self.Designator = Designator
		self.LEFT_PAREN = LEFT_PAREN

	def accept(self, visitor):
		visitor.visit_FuncCallStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoDesignatorExtension(DesignatorExtension):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoDesignatorExtension(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class SingleDesignatorExtension(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SingleDesignatorExtension(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorExtensions(DesignatorExtension):
	def __init__(self, DesignatorExtension: DesignatorExtension, SingleDesignatorExtension: SingleDesignatorExtension):
		self.DesignatorExtension = DesignatorExtension
		self.SingleDesignatorExtension = SingleDesignatorExtension

	def accept(self, visitor):
		visitor.visit_DesignatorExtensions(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorExtension is not None: self.DesignatorExtension.traverse_bottom_up(visitor)
		if self.SingleDesignatorExtension is not None: self.SingleDesignatorExtension.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleDesignatorExtensionClassField(SingleDesignatorExtension):
	def __init__(self, DOT: DOT, IDENT: IDENT):
		self.DOT = DOT
		self.IDENT = IDENT

	def accept(self, visitor):
		visitor.visit_SingleDesignatorExtensionClassField(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DOT is not None: self.DOT.traverse_bottom_up(visitor)
		if self.IDENT is not None: self.IDENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleDesignatorExtensionArray(SingleDesignatorExtension):
	def __init__(self, LEFT_BRACKET: LEFT_BRACKET, Expr: Expr, RIGHT_BRACKET: RIGHT_BRACKET):
		self.LEFT_BRACKET = LEFT_BRACKET
		self.Expr = Expr
		self.RIGHT_BRACKET = RIGHT_BRACKET

	def accept(self, visitor):
		visitor.visit_SingleDesignatorExtensionArray(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LEFT_BRACKET is not None: self.LEFT_BRACKET.traverse_bottom_up(visitor)
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.RIGHT_BRACKET is not None: self.RIGHT_BRACKET.traverse_bottom_up(visitor)
		self.accept(visitor)

class Addop(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Addop(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class TermsList(TermList):
	def __init__(self, TermList: TermList, Addop: Addop, Term: Term):
		self.TermList = TermList
		self.Addop = Addop
		self.Term = Term

	def accept(self, visitor):
		visitor.visit_TermsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.TermList is not None: self.TermList.traverse_bottom_up(visitor)
		if self.Addop is not None: self.Addop.traverse_bottom_up(visitor)
		if self.Term is not None: self.Term.traverse_bottom_up(visitor)
		self.accept(visitor)

class Addition(Addop):
	def __init__(self, PLUS: PLUS):
		self.PLUS = PLUS

	def accept(self, visitor):
		visitor.visit_Addition(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.PLUS is not None: self.PLUS.traverse_bottom_up(visitor)
		self.accept(visitor)

class Subtraction(Addop):
	def __init__(self, MINUS: MINUS):
		self.MINUS = MINUS

	def accept(self, visitor):
		visitor.visit_Subtraction(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.MINUS is not None: self.MINUS.traverse_bottom_up(visitor)
		self.accept(visitor)

class Mulop(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Mulop(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FactorsList(FactorList):
	def __init__(self, FactorList: FactorList, Mulop: Mulop, Factor: Factor):
		self.FactorList = FactorList
		self.Mulop = Mulop
		self.Factor = Factor

	def accept(self, visitor):
		visitor.visit_FactorsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FactorList is not None: self.FactorList.traverse_bottom_up(visitor)
		if self.Mulop is not None: self.Mulop.traverse_bottom_up(visitor)
		if self.Factor is not None: self.Factor.traverse_bottom_up(visitor)
		self.accept(visitor)

class Multiplication(Mulop):
	def __init__(self, ASTERISK: ASTERISK):
		self.ASTERISK = ASTERISK

	def accept(self, visitor):
		visitor.visit_Multiplication(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ASTERISK is not None: self.ASTERISK.traverse_bottom_up(visitor)
		self.accept(visitor)

class Division(Mulop):
	def __init__(self, SLASH: SLASH):
		self.SLASH = SLASH

	def accept(self, visitor):
		visitor.visit_Division(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SLASH is not None: self.SLASH.traverse_bottom_up(visitor)
		self.accept(visitor)

class Moduo(Mulop):
	def __init__(self, PERCENT: PERCENT):
		self.PERCENT = PERCENT

	def accept(self, visitor):
		visitor.visit_Moduo(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.PERCENT is not None: self.PERCENT.traverse_bottom_up(visitor)
		self.accept(visitor)

class ActPars(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ActPars(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorFuncCall(DesignatorStatement):
	def __init__(self, FuncCallStart: FuncCallStart, ActPars: ActPars, RIGHT_PAREN: RIGHT_PAREN):
		self.FuncCallStart = FuncCallStart
		self.ActPars = ActPars
		self.RIGHT_PAREN = RIGHT_PAREN

	def accept(self, visitor):
		visitor.visit_DesignatorFuncCall(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FuncCallStart is not None: self.FuncCallStart.traverse_bottom_up(visitor)
		if self.ActPars is not None: self.ActPars.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		self.accept(visitor)

class ClassFactor(Factor):
	def __init__(self, NEW: NEW, Type: Type, LEFT_PAREN: LEFT_PAREN, ActPars: ActPars, RIGHT_PAREN: RIGHT_PAREN):
		self.NEW = NEW
		self.Type = Type
		self.LEFT_PAREN = LEFT_PAREN
		self.ActPars = ActPars
		self.RIGHT_PAREN = RIGHT_PAREN

	def accept(self, visitor):
		visitor.visit_ClassFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.NEW is not None: self.NEW.traverse_bottom_up(visitor)
		if self.Type is not None: self.Type.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.ActPars is not None: self.ActPars.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		self.accept(visitor)

class FuncFactor(Factor):
	def __init__(self, FuncCallStart: FuncCallStart, ActPars: ActPars, RIGHT_PAREN: RIGHT_PAREN):
		self.FuncCallStart = FuncCallStart
		self.ActPars = ActPars
		self.RIGHT_PAREN = RIGHT_PAREN

	def accept(self, visitor):
		visitor.visit_FuncFactor(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.FuncCallStart is not None: self.FuncCallStart.traverse_bottom_up(visitor)
		if self.ActPars is not None: self.ActPars.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoActPars(ActPars):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoActPars(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ActParsList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ActParsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ActParams(ActPars):
	def __init__(self, ActParsList: ActParsList):
		self.ActParsList = ActParsList

	def accept(self, visitor):
		visitor.visit_ActParams(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ActParsList is not None: self.ActParsList.traverse_bottom_up(visitor)
		self.accept(visitor)

class OneActPar(SyntaxNode):
	def __init__(self, Expr: Expr):
		self.Expr = Expr

	def accept(self, visitor):
		visitor.visit_OneActPar(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		self.accept(visitor)

class ActParamsList(ActParsList):
	def __init__(self, ActParsList: ActParsList, COMMA: COMMA, OneActPar: OneActPar):
		self.ActParsList = ActParsList
		self.COMMA = COMMA
		self.OneActPar = OneActPar

	def accept(self, visitor):
		visitor.visit_ActParamsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ActParsList is not None: self.ActParsList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.OneActPar is not None: self.OneActPar.traverse_bottom_up(visitor)
		self.accept(visitor)

class OneActParam(ActParsList):
	def __init__(self, OneActPar: OneActPar):
		self.OneActPar = OneActPar

	def accept(self, visitor):
		visitor.visit_OneActParam(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.OneActPar is not None: self.OneActPar.traverse_bottom_up(visitor)
		self.accept(visitor)

class ManyDesignators(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ManyDesignators(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorUnzip(DesignatorStatement):
	def __init__(self, DesignatorUnzipStart: DesignatorUnzipStart, ManyDesignators: ManyDesignators, ASTERISK: ASTERISK, Designator: Designator, RIGHT_BRACKET: RIGHT_BRACKET, EQUALS: EQUALS, Designator1: Designator):
		self.DesignatorUnzipStart = DesignatorUnzipStart
		self.ManyDesignators = ManyDesignators
		self.ASTERISK = ASTERISK
		self.Designator = Designator
		self.RIGHT_BRACKET = RIGHT_BRACKET
		self.EQUALS = EQUALS
		self.Designator1 = Designator1

	def accept(self, visitor):
		visitor.visit_DesignatorUnzip(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorUnzipStart is not None: self.DesignatorUnzipStart.traverse_bottom_up(visitor)
		if self.ManyDesignators is not None: self.ManyDesignators.traverse_bottom_up(visitor)
		if self.ASTERISK is not None: self.ASTERISK.traverse_bottom_up(visitor)
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		if self.RIGHT_BRACKET is not None: self.RIGHT_BRACKET.traverse_bottom_up(visitor)
		if self.EQUALS is not None: self.EQUALS.traverse_bottom_up(visitor)
		if self.Designator1 is not None: self.Designator1.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoManyDesignators(ManyDesignators):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoManyDesignators(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DesignatorList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ManyDes(ManyDesignators):
	def __init__(self, DesignatorList: DesignatorList, COMMA: COMMA):
		self.DesignatorList = DesignatorList
		self.COMMA = COMMA

	def accept(self, visitor):
		visitor.visit_ManyDes(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorList is not None: self.DesignatorList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleDesignator(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_SingleDesignator(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorsList(DesignatorList):
	def __init__(self, DesignatorList: DesignatorList, COMMA: COMMA, SingleDesignator: SingleDesignator):
		self.DesignatorList = DesignatorList
		self.COMMA = COMMA
		self.SingleDesignator = SingleDesignator

	def accept(self, visitor):
		visitor.visit_DesignatorsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorList is not None: self.DesignatorList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.SingleDesignator is not None: self.SingleDesignator.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleDes(DesignatorList):
	def __init__(self, SingleDesignator: SingleDesignator):
		self.SingleDesignator = SingleDesignator

	def accept(self, visitor):
		visitor.visit_SingleDes(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.SingleDesignator is not None: self.SingleDesignator.traverse_bottom_up(visitor)
		self.accept(visitor)

class Des(SingleDesignator):
	def __init__(self, Designator: Designator):
		self.Designator = Designator

	def accept(self, visitor):
		visitor.visit_Des(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Designator is not None: self.Designator.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoDesignator(SingleDesignator):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoDesignator(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ForStatement(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ForStatement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class FinalForStatement(SyntaxNode):
	def __init__(self, ForStatement: ForStatement):
		self.ForStatement = ForStatement

	def accept(self, visitor):
		visitor.visit_FinalForStatement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ForStatement is not None: self.ForStatement.traverse_bottom_up(visitor)
		self.accept(visitor)

class ForNoConditionStmt(Statement):
	def __init__(self, ForLoopStart: ForLoopStart, LEFT_PAREN: LEFT_PAREN, ForStatement: ForStatement, FirstForSemicolon: FirstForSemicolon, SecondForSemicolon: SecondForSemicolon, FinalForStatement: FinalForStatement, RIGHT_PAREN: RIGHT_PAREN, Statement: Statement):
		self.ForLoopStart = ForLoopStart
		self.LEFT_PAREN = LEFT_PAREN
		self.ForStatement = ForStatement
		self.FirstForSemicolon = FirstForSemicolon
		self.SecondForSemicolon = SecondForSemicolon
		self.FinalForStatement = FinalForStatement
		self.RIGHT_PAREN = RIGHT_PAREN
		self.Statement = Statement

	def accept(self, visitor):
		visitor.visit_ForNoConditionStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ForLoopStart is not None: self.ForLoopStart.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.ForStatement is not None: self.ForStatement.traverse_bottom_up(visitor)
		if self.FirstForSemicolon is not None: self.FirstForSemicolon.traverse_bottom_up(visitor)
		if self.SecondForSemicolon is not None: self.SecondForSemicolon.traverse_bottom_up(visitor)
		if self.FinalForStatement is not None: self.FinalForStatement.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.Statement is not None: self.Statement.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoForStatement(ForStatement):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoForStatement(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class DesignatorStatementList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_DesignatorStatementList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ForStmt(ForStatement):
	def __init__(self, DesignatorStatementList: DesignatorStatementList):
		self.DesignatorStatementList = DesignatorStatementList

	def accept(self, visitor):
		visitor.visit_ForStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorStatementList is not None: self.DesignatorStatementList.traverse_bottom_up(visitor)
		self.accept(visitor)

class DesignatorStmtList(DesignatorStatementList):
	def __init__(self, DesignatorStatementList: DesignatorStatementList, COMMA: COMMA, DesignatorStatement: DesignatorStatement):
		self.DesignatorStatementList = DesignatorStatementList
		self.COMMA = COMMA
		self.DesignatorStatement = DesignatorStatement

	def accept(self, visitor):
		visitor.visit_DesignatorStmtList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorStatementList is not None: self.DesignatorStatementList.traverse_bottom_up(visitor)
		if self.COMMA is not None: self.COMMA.traverse_bottom_up(visitor)
		if self.DesignatorStatement is not None: self.DesignatorStatement.traverse_bottom_up(visitor)
		self.accept(visitor)

class SingleDesignatorStmt(DesignatorStatementList):
	def __init__(self, DesignatorStatement: DesignatorStatement):
		self.DesignatorStatement = DesignatorStatement

	def accept(self, visitor):
		visitor.visit_SingleDesignatorStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.DesignatorStatement is not None: self.DesignatorStatement.traverse_bottom_up(visitor)
		self.accept(visitor)

class CondTermList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CondTermList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoCondTerms(CondTermList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoCondTerms(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CondFactList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CondFactList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoCondFacts(CondFactList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoCondFacts(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CondFact(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_CondFact(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ForConditionStmt(Statement):
	def __init__(self, ForLoopStart: ForLoopStart, LEFT_PAREN: LEFT_PAREN, ForStatement: ForStatement, FirstForSemicolon: FirstForSemicolon, CondFact: CondFact, SecondForSemicolon: SecondForSemicolon, FinalForStatement: FinalForStatement, RIGHT_PAREN: RIGHT_PAREN, Statement: Statement):
		self.ForLoopStart = ForLoopStart
		self.LEFT_PAREN = LEFT_PAREN
		self.ForStatement = ForStatement
		self.FirstForSemicolon = FirstForSemicolon
		self.CondFact = CondFact
		self.SecondForSemicolon = SecondForSemicolon
		self.FinalForStatement = FinalForStatement
		self.RIGHT_PAREN = RIGHT_PAREN
		self.Statement = Statement

	def accept(self, visitor):
		visitor.visit_ForConditionStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ForLoopStart is not None: self.ForLoopStart.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.ForStatement is not None: self.ForStatement.traverse_bottom_up(visitor)
		if self.FirstForSemicolon is not None: self.FirstForSemicolon.traverse_bottom_up(visitor)
		if self.CondFact is not None: self.CondFact.traverse_bottom_up(visitor)
		if self.SecondForSemicolon is not None: self.SecondForSemicolon.traverse_bottom_up(visitor)
		if self.FinalForStatement is not None: self.FinalForStatement.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.Statement is not None: self.Statement.traverse_bottom_up(visitor)
		self.accept(visitor)

class CondTerm(SyntaxNode):
	def __init__(self, CondFact: CondFact, CondFactList: CondFactList):
		self.CondFact = CondFact
		self.CondFactList = CondFactList

	def accept(self, visitor):
		visitor.visit_CondTerm(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CondFact is not None: self.CondFact.traverse_bottom_up(visitor)
		if self.CondFactList is not None: self.CondFactList.traverse_bottom_up(visitor)
		self.accept(visitor)

class Condition(SyntaxNode):
	def __init__(self, CondTerm: CondTerm, CondTermList: CondTermList):
		self.CondTerm = CondTerm
		self.CondTermList = CondTermList

	def accept(self, visitor):
		visitor.visit_Condition(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CondTerm is not None: self.CondTerm.traverse_bottom_up(visitor)
		if self.CondTermList is not None: self.CondTermList.traverse_bottom_up(visitor)
		self.accept(visitor)

class IfStmt(Statement):
	def __init__(self, IfStart: IfStart, LEFT_PAREN: LEFT_PAREN, Condition: Condition, RIGHT_PAREN: RIGHT_PAREN, Statement: Statement):
		self.IfStart = IfStart
		self.LEFT_PAREN = LEFT_PAREN
		self.Condition = Condition
		self.RIGHT_PAREN = RIGHT_PAREN
		self.Statement = Statement

	def accept(self, visitor):
		visitor.visit_IfStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IfStart is not None: self.IfStart.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Condition is not None: self.Condition.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.Statement is not None: self.Statement.traverse_bottom_up(visitor)
		self.accept(visitor)

class IfElseStmt(Statement):
	def __init__(self, IfStart: IfStart, LEFT_PAREN: LEFT_PAREN, Condition: Condition, RIGHT_PAREN: RIGHT_PAREN, Statement: Statement, ElseStart: ElseStart, Statement1: Statement):
		self.IfStart = IfStart
		self.LEFT_PAREN = LEFT_PAREN
		self.Condition = Condition
		self.RIGHT_PAREN = RIGHT_PAREN
		self.Statement = Statement
		self.ElseStart = ElseStart
		self.Statement1 = Statement1

	def accept(self, visitor):
		visitor.visit_IfElseStmt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.IfStart is not None: self.IfStart.traverse_bottom_up(visitor)
		if self.LEFT_PAREN is not None: self.LEFT_PAREN.traverse_bottom_up(visitor)
		if self.Condition is not None: self.Condition.traverse_bottom_up(visitor)
		if self.RIGHT_PAREN is not None: self.RIGHT_PAREN.traverse_bottom_up(visitor)
		if self.Statement is not None: self.Statement.traverse_bottom_up(visitor)
		if self.ElseStart is not None: self.ElseStart.traverse_bottom_up(visitor)
		if self.Statement1 is not None: self.Statement1.traverse_bottom_up(visitor)
		self.accept(visitor)

class CondTerms(CondTermList):
	def __init__(self, CondTermList: CondTermList, LOGICAL_OR: LOGICAL_OR, CondTerm: CondTerm):
		self.CondTermList = CondTermList
		self.LOGICAL_OR = LOGICAL_OR
		self.CondTerm = CondTerm

	def accept(self, visitor):
		visitor.visit_CondTerms(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CondTermList is not None: self.CondTermList.traverse_bottom_up(visitor)
		if self.LOGICAL_OR is not None: self.LOGICAL_OR.traverse_bottom_up(visitor)
		if self.CondTerm is not None: self.CondTerm.traverse_bottom_up(visitor)
		self.accept(visitor)

class CondFacts(CondFactList):
	def __init__(self, CondFactList: CondFactList, LOGICAL_AND: LOGICAL_AND, CondFact: CondFact):
		self.CondFactList = CondFactList
		self.LOGICAL_AND = LOGICAL_AND
		self.CondFact = CondFact

	def accept(self, visitor):
		visitor.visit_CondFacts(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.CondFactList is not None: self.CondFactList.traverse_bottom_up(visitor)
		if self.LOGICAL_AND is not None: self.LOGICAL_AND.traverse_bottom_up(visitor)
		if self.CondFact is not None: self.CondFact.traverse_bottom_up(visitor)
		self.accept(visitor)

class CondFactNoRelop(CondFact):
	def __init__(self, Expr: Expr):
		self.Expr = Expr

	def accept(self, visitor):
		visitor.visit_CondFactNoRelop(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		self.accept(visitor)

class Relop(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_Relop(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class CondFactRelop(CondFact):
	def __init__(self, Expr: Expr, Relop: Relop, Expr1: Expr):
		self.Expr = Expr
		self.Relop = Relop
		self.Expr1 = Expr1

	def accept(self, visitor):
		visitor.visit_CondFactRelop(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.Expr is not None: self.Expr.traverse_bottom_up(visitor)
		if self.Relop is not None: self.Relop.traverse_bottom_up(visitor)
		if self.Expr1 is not None: self.Expr1.traverse_bottom_up(visitor)
		self.accept(visitor)

class LogEq(Relop):
	def __init__(self, LOGICAL_EQUALS: LOGICAL_EQUALS):
		self.LOGICAL_EQUALS = LOGICAL_EQUALS

	def accept(self, visitor):
		visitor.visit_LogEq(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LOGICAL_EQUALS is not None: self.LOGICAL_EQUALS.traverse_bottom_up(visitor)
		self.accept(visitor)

class LogNotEq(Relop):
	def __init__(self, LOGICAL_NOT_EQUALS: LOGICAL_NOT_EQUALS):
		self.LOGICAL_NOT_EQUALS = LOGICAL_NOT_EQUALS

	def accept(self, visitor):
		visitor.visit_LogNotEq(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LOGICAL_NOT_EQUALS is not None: self.LOGICAL_NOT_EQUALS.traverse_bottom_up(visitor)
		self.accept(visitor)

class Grt(Relop):
	def __init__(self, GREATER: GREATER):
		self.GREATER = GREATER

	def accept(self, visitor):
		visitor.visit_Grt(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.GREATER is not None: self.GREATER.traverse_bottom_up(visitor)
		self.accept(visitor)

class Gre(Relop):
	def __init__(self, GREATER_OR_EQUALS: GREATER_OR_EQUALS):
		self.GREATER_OR_EQUALS = GREATER_OR_EQUALS

	def accept(self, visitor):
		visitor.visit_Gre(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.GREATER_OR_EQUALS is not None: self.GREATER_OR_EQUALS.traverse_bottom_up(visitor)
		self.accept(visitor)

class Lss(Relop):
	def __init__(self, LESS: LESS):
		self.LESS = LESS

	def accept(self, visitor):
		visitor.visit_Lss(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LESS is not None: self.LESS.traverse_bottom_up(visitor)
		self.accept(visitor)

class Lse(Relop):
	def __init__(self, LESS_OR_EQUALS: LESS_OR_EQUALS):
		self.LESS_OR_EQUALS = LESS_OR_EQUALS

	def accept(self, visitor):
		visitor.visit_Lse(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LESS_OR_EQUALS is not None: self.LESS_OR_EQUALS.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticVarDeclList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_StaticVarDeclList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoStaticVarDeclaration(StaticVarDeclList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoStaticVarDeclaration(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class StaticVarDeclStart(SyntaxNode):
	def __init__(self, STATIC: STATIC):
		self.STATIC = STATIC

	def accept(self, visitor):
		visitor.visit_StaticVarDeclStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.STATIC is not None: self.STATIC.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticVarDecl(SyntaxNode):
	def __init__(self, StaticVarDeclStart: StaticVarDeclStart, VarDecl: VarDecl):
		self.StaticVarDeclStart = StaticVarDeclStart
		self.VarDecl = VarDecl

	def accept(self, visitor):
		visitor.visit_StaticVarDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StaticVarDeclStart is not None: self.StaticVarDeclStart.traverse_bottom_up(visitor)
		if self.VarDecl is not None: self.VarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticVarDeclarations(StaticVarDeclList):
	def __init__(self, StaticVarDeclList: StaticVarDeclList, StaticVarDecl: StaticVarDecl):
		self.StaticVarDeclList = StaticVarDeclList
		self.StaticVarDecl = StaticVarDecl

	def accept(self, visitor):
		visitor.visit_StaticVarDeclarations(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StaticVarDeclList is not None: self.StaticVarDeclList.traverse_bottom_up(visitor)
		if self.StaticVarDecl is not None: self.StaticVarDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticInitializerStart(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_StaticInitializerStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class NoStaticInitList(StaticInitializerStart):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoStaticInitList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class StaticInitializerList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_StaticInitializerList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class StaticInitList(StaticInitializerStart):
	def __init__(self, StaticInitializerList: StaticInitializerList):
		self.StaticInitializerList = StaticInitializerList

	def accept(self, visitor):
		visitor.visit_StaticInitList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StaticInitializerList is not None: self.StaticInitializerList.traverse_bottom_up(visitor)
		self.accept(visitor)

class OneStaticInitializerStart(SyntaxNode):
	def __init__(self, STATIC: STATIC, LEFT_BRACE: LEFT_BRACE):
		self.STATIC = STATIC
		self.LEFT_BRACE = LEFT_BRACE

	def accept(self, visitor):
		visitor.visit_OneStaticInitializerStart(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.STATIC is not None: self.STATIC.traverse_bottom_up(visitor)
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticInitializer(SyntaxNode):
	def __init__(self, OneStaticInitializerStart: OneStaticInitializerStart, StatementList: StatementList, RIGHT_BRACE: RIGHT_BRACE):
		self.OneStaticInitializerStart = OneStaticInitializerStart
		self.StatementList = StatementList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_StaticInitializer(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.OneStaticInitializerStart is not None: self.OneStaticInitializerStart.traverse_bottom_up(visitor)
		if self.StatementList is not None: self.StatementList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class StaticInitializers(StaticInitializerList):
	def __init__(self, StaticInitializerList: StaticInitializerList, StaticInitializer: StaticInitializer):
		self.StaticInitializerList = StaticInitializerList
		self.StaticInitializer = StaticInitializer

	def accept(self, visitor):
		visitor.visit_StaticInitializers(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StaticInitializerList is not None: self.StaticInitializerList.traverse_bottom_up(visitor)
		if self.StaticInitializer is not None: self.StaticInitializer.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoStaticInitializer(StaticInitializerList):
	def __init__(self, StaticInitializer: StaticInitializer):
		self.StaticInitializer = StaticInitializer

	def accept(self, visitor):
		visitor.visit_NoStaticInitializer(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.StaticInitializer is not None: self.StaticInitializer.traverse_bottom_up(visitor)
		self.accept(visitor)

class ClassMethodsList(SyntaxNode):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_ClassMethodsList(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

class ClassDecl(SyntaxNode):
	def __init__(self, ClassDeclStart: ClassDeclStart, LEFT_BRACE: LEFT_BRACE, StaticVarDeclList: StaticVarDeclList, StaticInitializerStart: StaticInitializerStart, ClassVarDeclList: ClassVarDeclList, ClassMethodsList: ClassMethodsList, RIGHT_BRACE: RIGHT_BRACE):
		self.ClassDeclStart = ClassDeclStart
		self.LEFT_BRACE = LEFT_BRACE
		self.StaticVarDeclList = StaticVarDeclList
		self.StaticInitializerStart = StaticInitializerStart
		self.ClassVarDeclList = ClassVarDeclList
		self.ClassMethodsList = ClassMethodsList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_ClassDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ClassDeclStart is not None: self.ClassDeclStart.traverse_bottom_up(visitor)
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.StaticVarDeclList is not None: self.StaticVarDeclList.traverse_bottom_up(visitor)
		if self.StaticInitializerStart is not None: self.StaticInitializerStart.traverse_bottom_up(visitor)
		if self.ClassVarDeclList is not None: self.ClassVarDeclList.traverse_bottom_up(visitor)
		if self.ClassMethodsList is not None: self.ClassMethodsList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class GenClassDecl(GeneralDecl):
	def __init__(self, ClassDecl: ClassDecl):
		self.ClassDecl = ClassDecl

	def accept(self, visitor):
		visitor.visit_GenClassDecl(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.ClassDecl is not None: self.ClassDecl.traverse_bottom_up(visitor)
		self.accept(visitor)

class ClassMethods(ClassMethodsList):
	def __init__(self, LEFT_BRACE: LEFT_BRACE, MethodDeclList: MethodDeclList, RIGHT_BRACE: RIGHT_BRACE):
		self.LEFT_BRACE = LEFT_BRACE
		self.MethodDeclList = MethodDeclList
		self.RIGHT_BRACE = RIGHT_BRACE

	def accept(self, visitor):
		visitor.visit_ClassMethods(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		if self.LEFT_BRACE is not None: self.LEFT_BRACE.traverse_bottom_up(visitor)
		if self.MethodDeclList is not None: self.MethodDeclList.traverse_bottom_up(visitor)
		if self.RIGHT_BRACE is not None: self.RIGHT_BRACE.traverse_bottom_up(visitor)
		self.accept(visitor)

class NoClassMethods(ClassMethodsList):
	def __init__(self):
		pass

	def accept(self, visitor):
		visitor.visit_NoClassMethods(self)

	def emit_code(self, tab):
		return ""

	def traverse_bottom_up(self, visitor):
		self.accept(visitor)

