// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class SingleTypeVarDeclListDerived1 extends SingleTypeVarDeclList {

    private SingleTypeVarDecl SingleTypeVarDecl;

    public SingleTypeVarDeclListDerived1 (SingleTypeVarDecl SingleTypeVarDecl) {
        this.SingleTypeVarDecl=SingleTypeVarDecl;
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.setParent(this);
    }

    public SingleTypeVarDecl getSingleTypeVarDecl() {
        return SingleTypeVarDecl;
    }

    public void setSingleTypeVarDecl(SingleTypeVarDecl SingleTypeVarDecl) {
        this.SingleTypeVarDecl=SingleTypeVarDecl;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("SingleTypeVarDeclListDerived1(\n");

        if(SingleTypeVarDecl!=null)
            buffer.append(SingleTypeVarDecl.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [SingleTypeVarDeclListDerived1]");
        return buffer.toString();
    }
}
