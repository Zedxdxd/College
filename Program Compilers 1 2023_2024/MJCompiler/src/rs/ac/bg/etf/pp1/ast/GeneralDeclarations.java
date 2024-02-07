// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class GeneralDeclarations extends GeneralDeclList {

    private GeneralDeclList GeneralDeclList;
    private GeneralDecl GeneralDecl;

    public GeneralDeclarations (GeneralDeclList GeneralDeclList, GeneralDecl GeneralDecl) {
        this.GeneralDeclList=GeneralDeclList;
        if(GeneralDeclList!=null) GeneralDeclList.setParent(this);
        this.GeneralDecl=GeneralDecl;
        if(GeneralDecl!=null) GeneralDecl.setParent(this);
    }

    public GeneralDeclList getGeneralDeclList() {
        return GeneralDeclList;
    }

    public void setGeneralDeclList(GeneralDeclList GeneralDeclList) {
        this.GeneralDeclList=GeneralDeclList;
    }

    public GeneralDecl getGeneralDecl() {
        return GeneralDecl;
    }

    public void setGeneralDecl(GeneralDecl GeneralDecl) {
        this.GeneralDecl=GeneralDecl;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(GeneralDeclList!=null) GeneralDeclList.accept(visitor);
        if(GeneralDecl!=null) GeneralDecl.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(GeneralDeclList!=null) GeneralDeclList.traverseTopDown(visitor);
        if(GeneralDecl!=null) GeneralDecl.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(GeneralDeclList!=null) GeneralDeclList.traverseBottomUp(visitor);
        if(GeneralDecl!=null) GeneralDecl.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("GeneralDeclarations(\n");

        if(GeneralDeclList!=null)
            buffer.append(GeneralDeclList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(GeneralDecl!=null)
            buffer.append(GeneralDecl.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [GeneralDeclarations]");
        return buffer.toString();
    }
}
