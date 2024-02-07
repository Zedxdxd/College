// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class ForNoConditionStmt extends Statement {

    private ForLoopStart ForLoopStart;
    private ForStatement ForStatement;
    private FirstForSemicolon FirstForSemicolon;
    private SecondForSemicolon SecondForSemicolon;
    private FinalForStatement FinalForStatement;
    private Statement Statement;

    public ForNoConditionStmt (ForLoopStart ForLoopStart, ForStatement ForStatement, FirstForSemicolon FirstForSemicolon, SecondForSemicolon SecondForSemicolon, FinalForStatement FinalForStatement, Statement Statement) {
        this.ForLoopStart=ForLoopStart;
        if(ForLoopStart!=null) ForLoopStart.setParent(this);
        this.ForStatement=ForStatement;
        if(ForStatement!=null) ForStatement.setParent(this);
        this.FirstForSemicolon=FirstForSemicolon;
        if(FirstForSemicolon!=null) FirstForSemicolon.setParent(this);
        this.SecondForSemicolon=SecondForSemicolon;
        if(SecondForSemicolon!=null) SecondForSemicolon.setParent(this);
        this.FinalForStatement=FinalForStatement;
        if(FinalForStatement!=null) FinalForStatement.setParent(this);
        this.Statement=Statement;
        if(Statement!=null) Statement.setParent(this);
    }

    public ForLoopStart getForLoopStart() {
        return ForLoopStart;
    }

    public void setForLoopStart(ForLoopStart ForLoopStart) {
        this.ForLoopStart=ForLoopStart;
    }

    public ForStatement getForStatement() {
        return ForStatement;
    }

    public void setForStatement(ForStatement ForStatement) {
        this.ForStatement=ForStatement;
    }

    public FirstForSemicolon getFirstForSemicolon() {
        return FirstForSemicolon;
    }

    public void setFirstForSemicolon(FirstForSemicolon FirstForSemicolon) {
        this.FirstForSemicolon=FirstForSemicolon;
    }

    public SecondForSemicolon getSecondForSemicolon() {
        return SecondForSemicolon;
    }

    public void setSecondForSemicolon(SecondForSemicolon SecondForSemicolon) {
        this.SecondForSemicolon=SecondForSemicolon;
    }

    public FinalForStatement getFinalForStatement() {
        return FinalForStatement;
    }

    public void setFinalForStatement(FinalForStatement FinalForStatement) {
        this.FinalForStatement=FinalForStatement;
    }

    public Statement getStatement() {
        return Statement;
    }

    public void setStatement(Statement Statement) {
        this.Statement=Statement;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(ForLoopStart!=null) ForLoopStart.accept(visitor);
        if(ForStatement!=null) ForStatement.accept(visitor);
        if(FirstForSemicolon!=null) FirstForSemicolon.accept(visitor);
        if(SecondForSemicolon!=null) SecondForSemicolon.accept(visitor);
        if(FinalForStatement!=null) FinalForStatement.accept(visitor);
        if(Statement!=null) Statement.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(ForLoopStart!=null) ForLoopStart.traverseTopDown(visitor);
        if(ForStatement!=null) ForStatement.traverseTopDown(visitor);
        if(FirstForSemicolon!=null) FirstForSemicolon.traverseTopDown(visitor);
        if(SecondForSemicolon!=null) SecondForSemicolon.traverseTopDown(visitor);
        if(FinalForStatement!=null) FinalForStatement.traverseTopDown(visitor);
        if(Statement!=null) Statement.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(ForLoopStart!=null) ForLoopStart.traverseBottomUp(visitor);
        if(ForStatement!=null) ForStatement.traverseBottomUp(visitor);
        if(FirstForSemicolon!=null) FirstForSemicolon.traverseBottomUp(visitor);
        if(SecondForSemicolon!=null) SecondForSemicolon.traverseBottomUp(visitor);
        if(FinalForStatement!=null) FinalForStatement.traverseBottomUp(visitor);
        if(Statement!=null) Statement.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("ForNoConditionStmt(\n");

        if(ForLoopStart!=null)
            buffer.append(ForLoopStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(ForStatement!=null)
            buffer.append(ForStatement.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(FirstForSemicolon!=null)
            buffer.append(FirstForSemicolon.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(SecondForSemicolon!=null)
            buffer.append(SecondForSemicolon.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(FinalForStatement!=null)
            buffer.append(FinalForStatement.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(Statement!=null)
            buffer.append(Statement.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [ForNoConditionStmt]");
        return buffer.toString();
    }
}
