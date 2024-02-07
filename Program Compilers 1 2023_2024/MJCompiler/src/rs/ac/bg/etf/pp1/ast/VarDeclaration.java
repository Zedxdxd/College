// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class VarDeclaration extends VarDecl {

    private Type Type;
    private SingleTypeVarDeclList SingleTypeVarDeclList;

    public VarDeclaration (Type Type, SingleTypeVarDeclList SingleTypeVarDeclList) {
        this.Type=Type;
        if(Type!=null) Type.setParent(this);
        this.SingleTypeVarDeclList=SingleTypeVarDeclList;
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.setParent(this);
    }

    public Type getType() {
        return Type;
    }

    public void setType(Type Type) {
        this.Type=Type;
    }

    public SingleTypeVarDeclList getSingleTypeVarDeclList() {
        return SingleTypeVarDeclList;
    }

    public void setSingleTypeVarDeclList(SingleTypeVarDeclList SingleTypeVarDeclList) {
        this.SingleTypeVarDeclList=SingleTypeVarDeclList;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(Type!=null) Type.accept(visitor);
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(Type!=null) Type.traverseTopDown(visitor);
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(Type!=null) Type.traverseBottomUp(visitor);
        if(SingleTypeVarDeclList!=null) SingleTypeVarDeclList.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("VarDeclaration(\n");

        if(Type!=null)
            buffer.append(Type.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(SingleTypeVarDeclList!=null)
            buffer.append(SingleTypeVarDeclList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [VarDeclaration]");
        return buffer.toString();
    }
}
