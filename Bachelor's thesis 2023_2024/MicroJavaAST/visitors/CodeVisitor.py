# This file is auto generated.

from MicroJavaAST.visitors.Visitor import Visitor
from io import StringIO

class CodeVisitor(Visitor):
	def __init__(self, buffer: StringIO):
		self.buffer = buffer
		self.tab = ''

	def get_code(self) -> str:
		return self.buffer.getvalue().replace('\0', '')

	def visit_PROGRAM(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_BREAK(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CLASS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ELSE(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CONST(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_IF(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NEW(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PRINT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_READ(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_RETURN(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VOID(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_EXTENDS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CONTINUE(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FOR(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_STATIC(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NAMESPACE(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PLUS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_MINUS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ASTERISK(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SLASH(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PERCENT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LOGICAL_EQUALS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LOGICAL_NOT_EQUALS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GREATER_OR_EQUALS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GREATER(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LESS_OR_EQUALS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LESS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LOGICAL_AND(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LOGICAL_OR(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_INCREMENT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DECREMENT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SEMICOLON(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DOUBLE_COLON(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_COMMA(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DOT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LEFT_PAREN(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_RIGHT_PAREN(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LEFT_BRACKET(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_RIGHT_BRACKET(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LEFT_BRACE(self, node):
		self.tab += '\t'
		self.buffer.write(node.emit_code(self.tab))

	def visit_RIGHT_BRACE(self, node):
		self.tab = self.tab[:-1]
		self.buffer.seek(0)
		content = self.buffer.read()
		self.buffer.truncate(0)
		self.buffer.write(content[:-1])
		self.buffer.write(node.emit_code(self.tab))

	def visit_FOREACH(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_EQUALS(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NUMBER(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_BOOL(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_IDENT(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CHAR(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Program(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ProgramName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NamespaceDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NamespaceList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoNamespace(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Namespace(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NamespaceName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GeneralDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GeneralDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoGeneralDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GenConstDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GeneralDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GenVarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_GenClassDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_MethodDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_MethodDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoMethodDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstNumber(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstValue(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstChar(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstBool(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstAssignments(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstAssignmentList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleConstAssignment(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ConstAssignment(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ScopeType(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Type(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoScopeType(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VarDeclaration(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleTypeVarDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleTypeVarDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_OneTypeVarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ArrayDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleTypeVarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NonArrayDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassVarDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ExtendedClassDeclStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassDeclStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_BaseClassDeclStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_MethodDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_TypeMethodName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_MethodName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VoidMethodName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VarDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_VarDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoVarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormParams(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormPars(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoFormPars(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormParamsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormParsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_OneFormPar(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormParNonArray(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleFormPar(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FormParArray(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorAssignment(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorStatement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorIncrement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorDecrement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorFuncCall(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorUnzip(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorUnzipStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StmtList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StatementList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoStatement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Statement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ReadStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PrintSingleStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PrintMultipleStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_IfStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_IfElseStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_BreakStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ContinueStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ReturnVoid(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ReturnNonVoid(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ForConditionStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ForNoConditionStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StmtBlock(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_IfStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ElseStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ForLoopStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FirstForSemicolon(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SecondForSemicolon(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FinalForStatement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NegativeExpr(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Expr(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_PositiveExpr(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StartExpr(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_TermsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_TermList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoTerm(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Term(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FactorsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FactorList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NumberFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Factor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CharFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SubExprFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_BoolFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ArrayFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FuncFactor(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_FuncCallStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Designator(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ScopeDesignatorName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoScopeDesignatorName(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorExtensions(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorExtension(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoDesignatorExtension(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDesignatorExtensionClassField(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDesignatorExtension(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDesignatorExtensionArray(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Addition(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Addop(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Subtraction(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Multiplication(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Mulop(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Division(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Moduo(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ActParams(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ActPars(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoActPars(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ActParamsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ActParsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_OneActParam(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_OneActPar(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ManyDes(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ManyDesignators(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoManyDesignators(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDes(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Des(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDesignator(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoDesignator(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ForStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ForStatement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoForStatement(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorStmtList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_DesignatorStatementList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_SingleDesignatorStmt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Condition(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondTerms(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondTermList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoCondTerms(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondTerm(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondFacts(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondFactList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoCondFacts(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondFactRelop(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondFact(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_CondFactNoRelop(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LogEq(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Relop(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_LogNotEq(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Grt(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Gre(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Lss(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_Lse(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticVarDeclarations(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticVarDeclList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoStaticVarDeclaration(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticVarDeclStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticVarDecl(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticInitList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticInitializerStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoStaticInitList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticInitializers(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticInitializerList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoStaticInitializer(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_StaticInitializer(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_OneStaticInitializerStart(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassMethods(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_ClassMethodsList(self, node):
		self.buffer.write(node.emit_code(self.tab))

	def visit_NoClassMethods(self, node):
		self.buffer.write(node.emit_code(self.tab))

