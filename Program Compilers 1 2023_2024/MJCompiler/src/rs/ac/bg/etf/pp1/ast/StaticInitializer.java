// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class StaticInitializer implements SyntaxNode {

    private SyntaxNode parent;
    private int line;
    private OneStaticInitializerStart OneStaticInitializerStart;
    private StatementList StatementList;

    public StaticInitializer (OneStaticInitializerStart OneStaticInitializerStart, StatementList StatementList) {
        this.OneStaticInitializerStart=OneStaticInitializerStart;
        if(OneStaticInitializerStart!=null) OneStaticInitializerStart.setParent(this);
        this.StatementList=StatementList;
        if(StatementList!=null) StatementList.setParent(this);
    }

    public OneStaticInitializerStart getOneStaticInitializerStart() {
        return OneStaticInitializerStart;
    }

    public void setOneStaticInitializerStart(OneStaticInitializerStart OneStaticInitializerStart) {
        this.OneStaticInitializerStart=OneStaticInitializerStart;
    }

    public StatementList getStatementList() {
        return StatementList;
    }

    public void setStatementList(StatementList StatementList) {
        this.StatementList=StatementList;
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
        if(OneStaticInitializerStart!=null) OneStaticInitializerStart.accept(visitor);
        if(StatementList!=null) StatementList.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(OneStaticInitializerStart!=null) OneStaticInitializerStart.traverseTopDown(visitor);
        if(StatementList!=null) StatementList.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(OneStaticInitializerStart!=null) OneStaticInitializerStart.traverseBottomUp(visitor);
        if(StatementList!=null) StatementList.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("StaticInitializer(\n");

        if(OneStaticInitializerStart!=null)
            buffer.append(OneStaticInitializerStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(StatementList!=null)
            buffer.append(StatementList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [StaticInitializer]");
        return buffer.toString();
    }
}
