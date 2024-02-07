// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public interface Visitor { 

    public void visit(FormPars FormPars);
    public void visit(Factor Factor);
    public void visit(Statement Statement);
    public void visit(StaticInitializerStart StaticInitializerStart);
    public void visit(SingleDesignatorExtension SingleDesignatorExtension);
    public void visit(GeneralDeclList GeneralDeclList);
    public void visit(SingleTypeVarDeclList SingleTypeVarDeclList);
    public void visit(ConstAssignmentList ConstAssignmentList);
    public void visit(ClassMethodsList ClassMethodsList);
    public void visit(Relop Relop);
    public void visit(CondFactList CondFactList);
    public void visit(DesignatorStatementList DesignatorStatementList);
    public void visit(Expr Expr);
    public void visit(FactorList FactorList);
    public void visit(DesignatorExtension DesignatorExtension);
    public void visit(VarDecl VarDecl);
    public void visit(SingleVarDecl SingleVarDecl);
    public void visit(SingleFormPar SingleFormPar);
    public void visit(SingleTypeVarDecl SingleTypeVarDecl);
    public void visit(GeneralDecl GeneralDecl);
    public void visit(FormParsList FormParsList);
    public void visit(StaticInitializerList StaticInitializerList);
    public void visit(DesignatorList DesignatorList);
    public void visit(SingleDesignator SingleDesignator);
    public void visit(Mulop Mulop);
    public void visit(DesignatorStatement DesignatorStatement);
    public void visit(NamespaceList NamespaceList);
    public void visit(Addop Addop);
    public void visit(StatementList StatementList);
    public void visit(ClassDeclStart ClassDeclStart);
    public void visit(Type Type);
    public void visit(CondTermList CondTermList);
    public void visit(ManyDesignators ManyDesignators);
    public void visit(StaticVarDeclList StaticVarDeclList);
    public void visit(MethodDeclList MethodDeclList);
    public void visit(ConstValue ConstValue);
    public void visit(TermList TermList);
    public void visit(DesignatorName DesignatorName);
    public void visit(MethodName MethodName);
    public void visit(ActPars ActPars);
    public void visit(ForStatement ForStatement);
    public void visit(VarDeclList VarDeclList);
    public void visit(CondFact CondFact);
    public void visit(ActParsList ActParsList);
    public void visit(NoClassMethods NoClassMethods);
    public void visit(ClassMethods ClassMethods);
    public void visit(OneStaticInitializerStart OneStaticInitializerStart);
    public void visit(StaticInitializer StaticInitializer);
    public void visit(NoStaticInitializer NoStaticInitializer);
    public void visit(StaticInitializers StaticInitializers);
    public void visit(NoStaticInitList NoStaticInitList);
    public void visit(StaticInitList StaticInitList);
    public void visit(StaticVarDecl StaticVarDecl);
    public void visit(StaticVarDeclStart StaticVarDeclStart);
    public void visit(NoStaticVarDeclaration NoStaticVarDeclaration);
    public void visit(StaticVarDeclarations StaticVarDeclarations);
    public void visit(Lse Lse);
    public void visit(Lss Lss);
    public void visit(Gre Gre);
    public void visit(Grt Grt);
    public void visit(LogNotEq LogNotEq);
    public void visit(LogEq LogEq);
    public void visit(CondFactNoRelop CondFactNoRelop);
    public void visit(CondFactRelop CondFactRelop);
    public void visit(NoCondFacts NoCondFacts);
    public void visit(CondFacts CondFacts);
    public void visit(CondTerm CondTerm);
    public void visit(NoCondTerms NoCondTerms);
    public void visit(CondTerms CondTerms);
    public void visit(Condition Condition);
    public void visit(SingleDesignatorStmt SingleDesignatorStmt);
    public void visit(DesignatorStmtList DesignatorStmtList);
    public void visit(NoForStatement NoForStatement);
    public void visit(ForStmt ForStmt);
    public void visit(NoDesignator NoDesignator);
    public void visit(Des Des);
    public void visit(SingleDes SingleDes);
    public void visit(DesignatorsList DesignatorsList);
    public void visit(NoManyDesignators NoManyDesignators);
    public void visit(ManyDes ManyDes);
    public void visit(OneActPar OneActPar);
    public void visit(OneActParam OneActParam);
    public void visit(ActParamsList ActParamsList);
    public void visit(NoActPars NoActPars);
    public void visit(ActParams ActParams);
    public void visit(Moduo Moduo);
    public void visit(Division Division);
    public void visit(Multiplication Multiplication);
    public void visit(Subtraction Subtraction);
    public void visit(Addition Addition);
    public void visit(SingleDesignatorExtensionArray SingleDesignatorExtensionArray);
    public void visit(SingleDesignatorExtensionClassField SingleDesignatorExtensionClassField);
    public void visit(NoDesignatorExtension NoDesignatorExtension);
    public void visit(DesignatorExtensions DesignatorExtensions);
    public void visit(NoScopeDesignatorName NoScopeDesignatorName);
    public void visit(ScopeDesignatorName ScopeDesignatorName);
    public void visit(Designator Designator);
    public void visit(FuncCallStart FuncCallStart);
    public void visit(FuncFactor FuncFactor);
    public void visit(ClassFactor ClassFactor);
    public void visit(ArrayFactor ArrayFactor);
    public void visit(DesignatorFactor DesignatorFactor);
    public void visit(BoolFactor BoolFactor);
    public void visit(SubExprFactor SubExprFactor);
    public void visit(CharFactor CharFactor);
    public void visit(NumberFactor NumberFactor);
    public void visit(NoFactor NoFactor);
    public void visit(FactorsList FactorsList);
    public void visit(Term Term);
    public void visit(NoTerm NoTerm);
    public void visit(TermsList TermsList);
    public void visit(StartExpr StartExpr);
    public void visit(PositiveExpr PositiveExpr);
    public void visit(NegativeExpr NegativeExpr);
    public void visit(FinalForStatement FinalForStatement);
    public void visit(SecondForSemicolon SecondForSemicolon);
    public void visit(FirstForSemicolon FirstForSemicolon);
    public void visit(ForLoopStart ForLoopStart);
    public void visit(ElseStart ElseStart);
    public void visit(IfStart IfStart);
    public void visit(StmtBlock StmtBlock);
    public void visit(ForNoConditionStmt ForNoConditionStmt);
    public void visit(ForConditionStmt ForConditionStmt);
    public void visit(ReturnNonVoid ReturnNonVoid);
    public void visit(ReturnVoid ReturnVoid);
    public void visit(ContinueStmt ContinueStmt);
    public void visit(BreakStmt BreakStmt);
    public void visit(ErrorIfStmt ErrorIfStmt);
    public void visit(IfElseStmt IfElseStmt);
    public void visit(IfStmt IfStmt);
    public void visit(PrintMultipleStmt PrintMultipleStmt);
    public void visit(PrintSingleStmt PrintSingleStmt);
    public void visit(ReadStmt ReadStmt);
    public void visit(StatementDerived1 StatementDerived1);
    public void visit(DesignatorStmt DesignatorStmt);
    public void visit(NoStatement NoStatement);
    public void visit(StmtList StmtList);
    public void visit(DesignatorUnzipStart DesignatorUnzipStart);
    public void visit(DesignatorUnzip DesignatorUnzip);
    public void visit(DesignatorFuncCall DesignatorFuncCall);
    public void visit(DesignatorDecrement DesignatorDecrement);
    public void visit(DesignatorIncrement DesignatorIncrement);
    public void visit(DesignatorAssignment DesignatorAssignment);
    public void visit(FormParArray FormParArray);
    public void visit(FormParNonArray FormParNonArray);
    public void visit(FormParsListDerived1 FormParsListDerived1);
    public void visit(OneFormPar OneFormPar);
    public void visit(FormParamsList FormParamsList);
    public void visit(NoFormPars NoFormPars);
    public void visit(FormParsDerived1 FormParsDerived1);
    public void visit(FormParams FormParams);
    public void visit(NoVarDecl NoVarDecl);
    public void visit(VarDeclarations VarDeclarations);
    public void visit(VoidMethodName VoidMethodName);
    public void visit(TypeMethodName TypeMethodName);
    public void visit(MethodDecl MethodDecl);
    public void visit(BaseClassDeclStart BaseClassDeclStart);
    public void visit(ClassDeclStartDerived1 ClassDeclStartDerived1);
    public void visit(ExtendedClassDeclStart ExtendedClassDeclStart);
    public void visit(ClassVarDeclList ClassVarDeclList);
    public void visit(ClassDecl ClassDecl);
    public void visit(NonArrayDecl NonArrayDecl);
    public void visit(ArrayDecl ArrayDecl);
    public void visit(SingleTypeVarDeclListDerived1 SingleTypeVarDeclListDerived1);
    public void visit(OneTypeVarDecl OneTypeVarDecl);
    public void visit(SingleTypeVarDeclarations SingleTypeVarDeclarations);
    public void visit(VarDeclDerived1 VarDeclDerived1);
    public void visit(VarDeclaration VarDeclaration);
    public void visit(NoScopeType NoScopeType);
    public void visit(ScopeType ScopeType);
    public void visit(ConstAssignment ConstAssignment);
    public void visit(SingleConstAssignment SingleConstAssignment);
    public void visit(ConstAssignments ConstAssignments);
    public void visit(ConstDecl ConstDecl);
    public void visit(ConstBool ConstBool);
    public void visit(ConstChar ConstChar);
    public void visit(ConstNumber ConstNumber);
    public void visit(NoMethodDecl NoMethodDecl);
    public void visit(MethodDeclarations MethodDeclarations);
    public void visit(GenClassDecl GenClassDecl);
    public void visit(GenVarDecl GenVarDecl);
    public void visit(GenConstDecl GenConstDecl);
    public void visit(NoGeneralDecl NoGeneralDecl);
    public void visit(GeneralDeclarations GeneralDeclarations);
    public void visit(NamespaceName NamespaceName);
    public void visit(Namespace Namespace);
    public void visit(NoNamespace NoNamespace);
    public void visit(NamespaceDeclarations NamespaceDeclarations);
    public void visit(ProgramName ProgramName);
    public void visit(Program Program);

}