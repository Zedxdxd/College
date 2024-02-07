// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class SingleTypeVarDeclarations extends SingleTypeVarDeclList {

    private SingleTypeVarDeclList SingleTypeVarDeclList;
    private SingleTypeVarDecl SingleTypeVarDecl;

    public SingleTypeVarDeclarations (SingleTypeVarDeclList SingleTypeVarDeclList, SingleTypeVarDecl SingleTypeVarDecl) {
        this.SingleTypeVarDeclList=SingleTypeVarDeclList;
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.setParent(this);
        this.SingleTypeVarDecl=SingleTypeVarDecl;
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.setParent(this);
    }

    public SingleTypeVarDeclList getSingleTypeVarDeclList() {
        return SingleTypeVarDeclList;
    }

    public void setSingleTypeVarDeclList(SingleTypeVarDeclList SingleTypeVarDeclList) {
        this.SingleTypeVarDeclList=SingleTypeVarDeclList;
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
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.accept(visitor);
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.traverseTopDown(visitor);
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.traverseBottomUp(visitor);
        if(SingleTypeVarDecl!=null) SingleTypeVarDecl.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("SingleTypeVarDeclarations(\n");

        if(SingleTypeVarDeclList!=null)
            buffer.append(SingleTypeVarDeclList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(SingleTypeVarDecl!=null)
            buffer.append(SingleTypeVarDecl.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [SingleTypeVarDeclarations]");
        return buffer.toString();
    }
}
