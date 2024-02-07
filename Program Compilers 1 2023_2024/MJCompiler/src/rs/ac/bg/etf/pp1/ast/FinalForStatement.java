// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class FinalForStatement implements SyntaxNode {

    private SyntaxNode parent;
    private int line;
    private ForStatement ForStatement;

    public FinalForStatement (ForStatement ForStatement) {
        this.ForStatement=ForStatement;
        if(ForStatement!=null) ForStatement.setParent(this);
    }

    public ForStatement getForStatement() {
        return ForStatement;
    }

    public void setForStatement(ForStatement ForStatement) {
        this.ForStatement=ForStatement;
    }

    public SyntaxNode getParent() {
        return parent;
    }

    public void setParent(SyntaxNode parent) {
        this.parent=parent;
    }

    public int getLine() {
        return line;
    }

    public void setLine(int line) {
        this.line=line;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(ForStatement!=null) ForStatement.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(ForStatement!=null) ForStatement.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(ForStatement!=null) ForStatement.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("FinalForStatement(\n");

        if(ForStatement!=null)
            buffer.append(ForStatement.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [FinalForStatement]");
        return buffer.toString();
    }
}
