// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class StaticInitializers extends StaticInitializerList {

    private StaticInitializerList StaticInitializerList;
    private StaticInitializer StaticInitializer;

    public StaticInitializers (StaticInitializerList StaticInitializerList, StaticInitializer StaticInitializer) {
        this.StaticInitializerList=StaticInitializerList;
        if(StaticInitializerList!=null) StaticInitializerList.setParent(this);
        this.StaticInitializer=StaticInitializer;
        if(StaticInitializer!=null) StaticInitializer.setParent(this);
    }

    public StaticInitializerList getStaticInitializerList() {
        return StaticInitializerList;
    }

    public void setStaticInitializerList(StaticInitializerList StaticInitializerList) {
        this.StaticInitializerList=StaticInitializerList;
    }

    public StaticInitializer getStaticInitializer() {
        return StaticInitializer;
    }

    public void setStaticInitializer(StaticInitializer StaticInitializer) {
        this.StaticInitializer=StaticInitializer;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(StaticInitializerList!=null) StaticInitializerList.accept(visitor);
        if(StaticInitializer!=null) StaticInitializer.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(StaticInitializerList!=null) StaticInitializerList.traverseTopDown(visitor);
        if(StaticInitializer!=null) StaticInitializer.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(StaticInitializerList!=null) StaticInitializerList.traverseBottomUp(visitor);
        if(StaticInitializer!=null) StaticInitializer.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("StaticInitializers(\n");

        if(StaticInitializerList!=null)
            buffer.append(StaticInitializerList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(StaticInitializer!=null)
            buffer.append(StaticInitializer.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [StaticInitializers]");
        return buffer.toString();
    }
}
