# This file is auto generated.

from abc import ABC, abstractmethod

class Visitor(ABC):
	@abstractmethod
	def visit_PROGRAM(self, node):
		pass

	@abstractmethod
	def visit_BREAK(self, node):
		pass

	@abstractmethod
	def visit_CLASS(self, node):
		pass

	@abstractmethod
	def visit_ELSE(self, node):
		pass

	@abstractmethod
	def visit_CONST(self, node):
		pass

	@abstractmethod
	def visit_IF(self, node):
		pass

	@abstractmethod
	def visit_NEW(self, node):
		pass

	@abstractmethod
	def visit_PRINT(self, node):
		pass

	@abstractmethod
	def visit_READ(self, node):
		pass

	@abstractmethod
	def visit_RETURN(self, node):
		pass

	@abstractmethod
	def visit_VOID(self, node):
		pass

	@abstractmethod
	def visit_EXTENDS(self, node):
		pass

	@abstractmethod
	def visit_CONTINUE(self, node):
		pass

	@abstractmethod
	def visit_FOR(self, node):
		pass

	@abstractmethod
	def visit_STATIC(self, node):
		pass

	@abstractmethod
	def visit_NAMESPACE(self, node):
		pass

	@abstractmethod
	def visit_PLUS(self, node):
		pass

	@abstractmethod
	def visit_MINUS(self, node):
		pass

	@abstractmethod
	def visit_ASTERISK(self, node):
		pass

	@abstractmethod
	def visit_SLASH(self, node):
		pass

	@abstractmethod
	def visit_PERCENT(self, node):
		pass

	@abstractmethod
	def visit_LOGICAL_EQUALS(self, node):
		pass

	@abstractmethod
	def visit_LOGICAL_NOT_EQUALS(self, node):
		pass

	@abstractmethod
	def visit_GREATER_OR_EQUALS(self, node):
		pass

	@abstractmethod
	def visit_GREATER(self, node):
		pass

	@abstractmethod
	def visit_LESS_OR_EQUALS(self, node):
		pass

	@abstractmethod
	def visit_LESS(self, node):
		pass

	@abstractmethod
	def visit_LOGICAL_AND(self, node):
		pass

	@abstractmethod
	def visit_LOGICAL_OR(self, node):
		pass

	@abstractmethod
	def visit_INCREMENT(self, node):
		pass

	@abstractmethod
	def visit_DECREMENT(self, node):
		pass

	@abstractmethod
	def visit_SEMICOLON(self, node):
		pass

	@abstractmethod
	def visit_DOUBLE_COLON(self, node):
		pass

	@abstractmethod
	def visit_COMMA(self, node):
		pass

	@abstractmethod
	def visit_DOT(self, node):
		pass

	@abstractmethod
	def visit_LEFT_PAREN(self, node):
		pass

	@abstractmethod
	def visit_RIGHT_PAREN(self, node):
		pass

	@abstractmethod
	def visit_LEFT_BRACKET(self, node):
		pass

	@abstractmethod
	def visit_RIGHT_BRACKET(self, node):
		pass

	@abstractmethod
	def visit_LEFT_BRACE(self, node):
		pass

	@abstractmethod
	def visit_RIGHT_BRACE(self, node):
		pass

	@abstractmethod
	def visit_FOREACH(self, node):
		pass

	@abstractmethod
	def visit_EQUALS(self, node):
		pass

	@abstractmethod
	def visit_NUMBER(self, node):
		pass

	@abstractmethod
	def visit_BOOL(self, node):
		pass

	@abstractmethod
	def visit_IDENT(self, node):
		pass

	@abstractmethod
	def visit_CHAR(self, node):
		pass

	@abstractmethod
	def visit_Program(self, node):
		pass

	@abstractmethod
	def visit_ProgramName(self, node):
		pass

	@abstractmethod
	def visit_NamespaceDeclarations(self, node):
		pass

	@abstractmethod
	def visit_NamespaceList(self, node):
		pass

	@abstractmethod
	def visit_NoNamespace(self, node):
		pass

	@abstractmethod
	def visit_Namespace(self, node):
		pass

	@abstractmethod
	def visit_NamespaceName(self, node):
		pass

	@abstractmethod
	def visit_GeneralDeclarations(self, node):
		pass

	@abstractmethod
	def visit_GeneralDeclList(self, node):
		pass

	@abstractmethod
	def visit_NoGeneralDecl(self, node):
		pass

	@abstractmethod
	def visit_GenConstDecl(self, node):
		pass

	@abstractmethod
	def visit_GeneralDecl(self, node):
		pass

	@abstractmethod
	def visit_GenVarDecl(self, node):
		pass

	@abstractmethod
	def visit_GenClassDecl(self, node):
		pass

	@abstractmethod
	def visit_MethodDeclarations(self, node):
		pass

	@abstractmethod
	def visit_MethodDeclList(self, node):
		pass

	@abstractmethod
	def visit_NoMethodDecl(self, node):
		pass

	@abstractmethod
	def visit_ConstNumber(self, node):
		pass

	@abstractmethod
	def visit_ConstValue(self, node):
		pass

	@abstractmethod
	def visit_ConstChar(self, node):
		pass

	@abstractmethod
	def visit_ConstBool(self, node):
		pass

	@abstractmethod
	def visit_ConstDecl(self, node):
		pass

	@abstractmethod
	def visit_ConstAssignments(self, node):
		pass

	@abstractmethod
	def visit_ConstAssignmentList(self, node):
		pass

	@abstractmethod
	def visit_SingleConstAssignment(self, node):
		pass

	@abstractmethod
	def visit_ConstAssignment(self, node):
		pass

	@abstractmethod
	def visit_ScopeType(self, node):
		pass

	@abstractmethod
	def visit_Type(self, node):
		pass

	@abstractmethod
	def visit_NoScopeType(self, node):
		pass

	@abstractmethod
	def visit_VarDeclaration(self, node):
		pass

	@abstractmethod
	def visit_VarDecl(self, node):
		pass

	@abstractmethod
	def visit_SingleTypeVarDeclarations(self, node):
		pass

	@abstractmethod
	def visit_SingleTypeVarDeclList(self, node):
		pass

	@abstractmethod
	def visit_OneTypeVarDecl(self, node):
		pass

	@abstractmethod
	def visit_ArrayDecl(self, node):
		pass

	@abstractmethod
	def visit_SingleTypeVarDecl(self, node):
		pass

	@abstractmethod
	def visit_NonArrayDecl(self, node):
		pass

	@abstractmethod
	def visit_ClassDecl(self, node):
		pass

	@abstractmethod
	def visit_ClassVarDeclList(self, node):
		pass

	@abstractmethod
	def visit_ExtendedClassDeclStart(self, node):
		pass

	@abstractmethod
	def visit_ClassDeclStart(self, node):
		pass

	@abstractmethod
	def visit_BaseClassDeclStart(self, node):
		pass

	@abstractmethod
	def visit_MethodDecl(self, node):
		pass

	@abstractmethod
	def visit_TypeMethodName(self, node):
		pass

	@abstractmethod
	def visit_MethodName(self, node):
		pass

	@abstractmethod
	def visit_VoidMethodName(self, node):
		pass

	@abstractmethod
	def visit_VarDeclarations(self, node):
		pass

	@abstractmethod
	def visit_VarDeclList(self, node):
		pass

	@abstractmethod
	def visit_NoVarDecl(self, node):
		pass

	@abstractmethod
	def visit_FormParams(self, node):
		pass

	@abstractmethod
	def visit_FormPars(self, node):
		pass

	@abstractmethod
	def visit_NoFormPars(self, node):
		pass

	@abstractmethod
	def visit_FormParamsList(self, node):
		pass

	@abstractmethod
	def visit_FormParsList(self, node):
		pass

	@abstractmethod
	def visit_OneFormPar(self, node):
		pass

	@abstractmethod
	def visit_FormParNonArray(self, node):
		pass

	@abstractmethod
	def visit_SingleFormPar(self, node):
		pass

	@abstractmethod
	def visit_FormParArray(self, node):
		pass

	@abstractmethod
	def visit_DesignatorAssignment(self, node):
		pass

	@abstractmethod
	def visit_DesignatorStatement(self, node):
		pass

	@abstractmethod
	def visit_DesignatorIncrement(self, node):
		pass

	@abstractmethod
	def visit_DesignatorDecrement(self, node):
		pass

	@abstractmethod
	def visit_DesignatorFuncCall(self, node):
		pass

	@abstractmethod
	def visit_DesignatorUnzip(self, node):
		pass

	@abstractmethod
	def visit_DesignatorUnzipStart(self, node):
		pass

	@abstractmethod
	def visit_StmtList(self, node):
		pass

	@abstractmethod
	def visit_StatementList(self, node):
		pass

	@abstractmethod
	def visit_NoStatement(self, node):
		pass

	@abstractmethod
	def visit_DesignatorStmt(self, node):
		pass

	@abstractmethod
	def visit_Statement(self, node):
		pass

	@abstractmethod
	def visit_ReadStmt(self, node):
		pass

	@abstractmethod
	def visit_PrintSingleStmt(self, node):
		pass

	@abstractmethod
	def visit_PrintMultipleStmt(self, node):
		pass

	@abstractmethod
	def visit_IfStmt(self, node):
		pass

	@abstractmethod
	def visit_IfElseStmt(self, node):
		pass

	@abstractmethod
	def visit_BreakStmt(self, node):
		pass

	@abstractmethod
	def visit_ContinueStmt(self, node):
		pass

	@abstractmethod
	def visit_ReturnVoid(self, node):
		pass

	@abstractmethod
	def visit_ReturnNonVoid(self, node):
		pass

	@abstractmethod
	def visit_ForConditionStmt(self, node):
		pass

	@abstractmethod
	def visit_ForNoConditionStmt(self, node):
		pass

	@abstractmethod
	def visit_StmtBlock(self, node):
		pass

	@abstractmethod
	def visit_IfStart(self, node):
		pass

	@abstractmethod
	def visit_ElseStart(self, node):
		pass

	@abstractmethod
	def visit_ForLoopStart(self, node):
		pass

	@abstractmethod
	def visit_FirstForSemicolon(self, node):
		pass

	@abstractmethod
	def visit_SecondForSemicolon(self, node):
		pass

	@abstractmethod
	def visit_FinalForStatement(self, node):
		pass

	@abstractmethod
	def visit_NegativeExpr(self, node):
		pass

	@abstractmethod
	def visit_Expr(self, node):
		pass

	@abstractmethod
	def visit_PositiveExpr(self, node):
		pass

	@abstractmethod
	def visit_StartExpr(self, node):
		pass

	@abstractmethod
	def visit_TermsList(self, node):
		pass

	@abstractmethod
	def visit_TermList(self, node):
		pass

	@abstractmethod
	def visit_NoTerm(self, node):
		pass

	@abstractmethod
	def visit_Term(self, node):
		pass

	@abstractmethod
	def visit_FactorsList(self, node):
		pass

	@abstractmethod
	def visit_FactorList(self, node):
		pass

	@abstractmethod
	def visit_NoFactor(self, node):
		pass

	@abstractmethod
	def visit_NumberFactor(self, node):
		pass

	@abstractmethod
	def visit_Factor(self, node):
		pass

	@abstractmethod
	def visit_CharFactor(self, node):
		pass

	@abstractmethod
	def visit_SubExprFactor(self, node):
		pass

	@abstractmethod
	def visit_BoolFactor(self, node):
		pass

	@abstractmethod
	def visit_DesignatorFactor(self, node):
		pass

	@abstractmethod
	def visit_ArrayFactor(self, node):
		pass

	@abstractmethod
	def visit_ClassFactor(self, node):
		pass

	@abstractmethod
	def visit_FuncFactor(self, node):
		pass

	@abstractmethod
	def visit_FuncCallStart(self, node):
		pass

	@abstractmethod
	def visit_Designator(self, node):
		pass

	@abstractmethod
	def visit_ScopeDesignatorName(self, node):
		pass

	@abstractmethod
	def visit_DesignatorName(self, node):
		pass

	@abstractmethod
	def visit_NoScopeDesignatorName(self, node):
		pass

	@abstractmethod
	def visit_DesignatorExtensions(self, node):
		pass

	@abstractmethod
	def visit_DesignatorExtension(self, node):
		pass

	@abstractmethod
	def visit_NoDesignatorExtension(self, node):
		pass

	@abstractmethod
	def visit_SingleDesignatorExtensionClassField(self, node):
		pass

	@abstractmethod
	def visit_SingleDesignatorExtension(self, node):
		pass

	@abstractmethod
	def visit_SingleDesignatorExtensionArray(self, node):
		pass

	@abstractmethod
	def visit_Addition(self, node):
		pass

	@abstractmethod
	def visit_Addop(self, node):
		pass

	@abstractmethod
	def visit_Subtraction(self, node):
		pass

	@abstractmethod
	def visit_Multiplication(self, node):
		pass

	@abstractmethod
	def visit_Mulop(self, node):
		pass

	@abstractmethod
	def visit_Division(self, node):
		pass

	@abstractmethod
	def visit_Moduo(self, node):
		pass

	@abstractmethod
	def visit_ActParams(self, node):
		pass

	@abstractmethod
	def visit_ActPars(self, node):
		pass

	@abstractmethod
	def visit_NoActPars(self, node):
		pass

	@abstractmethod
	def visit_ActParamsList(self, node):
		pass

	@abstractmethod
	def visit_ActParsList(self, node):
		pass

	@abstractmethod
	def visit_OneActParam(self, node):
		pass

	@abstractmethod
	def visit_OneActPar(self, node):
		pass

	@abstractmethod
	def visit_ManyDes(self, node):
		pass

	@abstractmethod
	def visit_ManyDesignators(self, node):
		pass

	@abstractmethod
	def visit_NoManyDesignators(self, node):
		pass

	@abstractmethod
	def visit_DesignatorsList(self, node):
		pass

	@abstractmethod
	def visit_DesignatorList(self, node):
		pass

	@abstractmethod
	def visit_SingleDes(self, node):
		pass

	@abstractmethod
	def visit_Des(self, node):
		pass

	@abstractmethod
	def visit_SingleDesignator(self, node):
		pass

	@abstractmethod
	def visit_NoDesignator(self, node):
		pass

	@abstractmethod
	def visit_ForStmt(self, node):
		pass

	@abstractmethod
	def visit_ForStatement(self, node):
		pass

	@abstractmethod
	def visit_NoForStatement(self, node):
		pass

	@abstractmethod
	def visit_DesignatorStmtList(self, node):
		pass

	@abstractmethod
	def visit_DesignatorStatementList(self, node):
		pass

	@abstractmethod
	def visit_SingleDesignatorStmt(self, node):
		pass

	@abstractmethod
	def visit_Condition(self, node):
		pass

	@abstractmethod
	def visit_CondTerms(self, node):
		pass

	@abstractmethod
	def visit_CondTermList(self, node):
		pass

	@abstractmethod
	def visit_NoCondTerms(self, node):
		pass

	@abstractmethod
	def visit_CondTerm(self, node):
		pass

	@abstractmethod
	def visit_CondFacts(self, node):
		pass

	@abstractmethod
	def visit_CondFactList(self, node):
		pass

	@abstractmethod
	def visit_NoCondFacts(self, node):
		pass

	@abstractmethod
	def visit_CondFactRelop(self, node):
		pass

	@abstractmethod
	def visit_CondFact(self, node):
		pass

	@abstractmethod
	def visit_CondFactNoRelop(self, node):
		pass

	@abstractmethod
	def visit_LogEq(self, node):
		pass

	@abstractmethod
	def visit_Relop(self, node):
		pass

	@abstractmethod
	def visit_LogNotEq(self, node):
		pass

	@abstractmethod
	def visit_Grt(self, node):
		pass

	@abstractmethod
	def visit_Gre(self, node):
		pass

	@abstractmethod
	def visit_Lss(self, node):
		pass

	@abstractmethod
	def visit_Lse(self, node):
		pass

	@abstractmethod
	def visit_StaticVarDeclarations(self, node):
		pass

	@abstractmethod
	def visit_StaticVarDeclList(self, node):
		pass

	@abstractmethod
	def visit_NoStaticVarDeclaration(self, node):
		pass

	@abstractmethod
	def visit_StaticVarDeclStart(self, node):
		pass

	@abstractmethod
	def visit_StaticVarDecl(self, node):
		pass

	@abstractmethod
	def visit_StaticInitList(self, node):
		pass

	@abstractmethod
	def visit_StaticInitializerStart(self, node):
		pass

	@abstractmethod
	def visit_NoStaticInitList(self, node):
		pass

	@abstractmethod
	def visit_StaticInitializers(self, node):
		pass

	@abstractmethod
	def visit_StaticInitializerList(self, node):
		pass

	@abstractmethod
	def visit_NoStaticInitializer(self, node):
		pass

	@abstractmethod
	def visit_StaticInitializer(self, node):
		pass

	@abstractmethod
	def visit_OneStaticInitializerStart(self, node):
		pass

	@abstractmethod
	def visit_ClassMethods(self, node):
		pass

	@abstractmethod
	def visit_ClassMethodsList(self, node):
		pass

	@abstractmethod
	def visit_NoClassMethods(self, node):
		pass

