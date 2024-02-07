// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class StaticVarDecl implements SyntaxNode {

    private SyntaxNode parent;
    private int line;
    public rs.etf.pp1.symboltable.concepts.Obj obj = null;

    private StaticVarDeclStart StaticVarDeclStart;
    private VarDecl VarDecl;

    public StaticVarDecl (StaticVarDeclStart StaticVarDeclStart, VarDecl VarDecl) {
        this.StaticVarDeclStart=StaticVarDeclStart;
        if(StaticVarDeclStart!=null) StaticVarDeclStart.setParent(this);
        this.VarDecl=VarDecl;
        if(VarDecl!=null) VarDecl.setParent(this);
    }

    public StaticVarDeclStart getStaticVarDeclStart() {
        return StaticVarDeclStart;
    }

    public void setStaticVarDeclStart(StaticVarDeclStart StaticVarDeclStart) {
        this.StaticVarDeclStart=StaticVarDeclStart;
    }

    public VarDecl getVarDecl() {
        return VarDecl;
    }

    public void setVarDecl(VarDecl VarDecl) {
        this.VarDecl=VarDecl;
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
        if(StaticVarDeclStart!=null) StaticVarDeclStart.accept(visitor);
        if(VarDecl!=null) VarDecl.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(StaticVarDeclStart!=null) StaticVarDeclStart.traverseTopDown(visitor);
        if(VarDecl!=null) VarDecl.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(StaticVarDeclStart!=null) StaticVarDeclStart.traverseBottomUp(visitor);
        if(VarDecl!=null) VarDecl.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("StaticVarDecl(\n");

        if(StaticVarDeclStart!=null)
            buffer.append(StaticVarDeclStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(VarDecl!=null)
            buffer.append(VarDecl.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [StaticVarDecl]");
        return buffer.toString();
    }
}
