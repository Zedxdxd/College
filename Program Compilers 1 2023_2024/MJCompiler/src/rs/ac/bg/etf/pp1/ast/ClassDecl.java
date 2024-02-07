// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class ClassDecl implements SyntaxNode {

    private SyntaxNode parent;
    private int line;
    public rs.etf.pp1.symboltable.concepts.Obj obj = null;

    private ClassDeclStart ClassDeclStart;
    private StaticVarDeclList StaticVarDeclList;
    private StaticInitializerStart StaticInitializerStart;
    private ClassVarDeclList ClassVarDeclList;
    private ClassMethodsList ClassMethodsList;

    public ClassDecl (ClassDeclStart ClassDeclStart, StaticVarDeclList StaticVarDeclList, StaticInitializerStart StaticInitializerStart, ClassVarDeclList ClassVarDeclList, ClassMethodsList ClassMethodsList) {
        this.ClassDeclStart=ClassDeclStart;
        if(ClassDeclStart!=null) ClassDeclStart.setParent(this);
        this.StaticVarDeclList=StaticVarDeclList;
        if(StaticVarDeclList!=null) StaticVarDeclList.setParent(this);
        this.StaticInitializerStart=StaticInitializerStart;
        if(StaticInitializerStart!=null) StaticInitializerStart.setParent(this);
        this.ClassVarDeclList=ClassVarDeclList;
        if(ClassVarDeclList!=null) ClassVarDeclList.setParent(this);
        this.ClassMethodsList=ClassMethodsList;
        if(ClassMethodsList!=null) ClassMethodsList.setParent(this);
    }

    public ClassDeclStart getClassDeclStart() {
        return ClassDeclStart;
    }

    public void setClassDeclStart(ClassDeclStart ClassDeclStart) {
        this.ClassDeclStart=ClassDeclStart;
    }

    public StaticVarDeclList getStaticVarDeclList() {
        return StaticVarDeclList;
    }

    public void setStaticVarDeclList(StaticVarDeclList StaticVarDeclList) {
        this.StaticVarDeclList=StaticVarDeclList;
    }

    public StaticInitializerStart getStaticInitializerStart() {
        return StaticInitializerStart;
    }

    public void setStaticInitializerStart(StaticInitializerStart StaticInitializerStart) {
        this.StaticInitializerStart=StaticInitializerStart;
    }

    public ClassVarDeclList getClassVarDeclList() {
        return ClassVarDeclList;
    }

    public void setClassVarDeclList(ClassVarDeclList ClassVarDeclList) {
        this.ClassVarDeclList=ClassVarDeclList;
    }

    public ClassMethodsList getClassMethodsList() {
        return ClassMethodsList;
    }

    public void setClassMethodsList(ClassMethodsList ClassMethodsList) {
        this.ClassMethodsList=ClassMethodsList;
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
        if(ClassDeclStart!=null) ClassDeclStart.accept(visitor);
        if(StaticVarDeclList!=null) StaticVarDeclList.accept(visitor);
        if(StaticInitializerStart!=null) StaticInitializerStart.accept(visitor);
        if(ClassVarDeclList!=null) ClassVarDeclList.accept(visitor);
        if(ClassMethodsList!=null) ClassMethodsList.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(ClassDeclStart!=null) ClassDeclStart.traverseTopDown(visitor);
        if(StaticVarDeclList!=null) StaticVarDeclList.traverseTopDown(visitor);
        if(StaticInitializerStart!=null) StaticInitializerStart.traverseTopDown(visitor);
        if(ClassVarDeclList!=null) ClassVarDeclList.traverseTopDown(visitor);
        if(ClassMethodsList!=null) ClassMethodsList.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(ClassDeclStart!=null) ClassDeclStart.traverseBottomUp(visitor);
        if(StaticVarDeclList!=null) StaticVarDeclList.traverseBottomUp(visitor);
        if(StaticInitializerStart!=null) StaticInitializerStart.traverseBottomUp(visitor);
        if(ClassVarDeclList!=null) ClassVarDeclList.traverseBottomUp(visitor);
        if(ClassMethodsList!=null) ClassMethodsList.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("ClassDecl(\n");

        if(ClassDeclStart!=null)
            buffer.append(ClassDeclStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(StaticVarDeclList!=null)
            buffer.append(StaticVarDeclList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(StaticInitializerStart!=null)
            buffer.append(StaticInitializerStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(ClassVarDeclList!=null)
            buffer.append(ClassVarDeclList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(ClassMethodsList!=null)
            buffer.append(ClassMethodsList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [ClassDecl]");
        return buffer.toString();
    }
}
