// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class DesignatorExtensions extends DesignatorExtension {

    private DesignatorExtension DesignatorExtension;
    private SingleDesignatorExtension SingleDesignatorExtension;

    public DesignatorExtensions (DesignatorExtension DesignatorExtension, SingleDesignatorExtension SingleDesignatorExtension) {
        this.DesignatorExtension=DesignatorExtension;
        if(DesignatorExtension!=null) DesignatorExtension.setParent(this);
        this.SingleDesignatorExtension=SingleDesignatorExtension;
        if(SingleDesignatorExtension!=null) SingleDesignatorExtension.setParent(this);
    }

    public DesignatorExtension getDesignatorExtension() {
        return DesignatorExtension;
    }

    public void setDesignatorExtension(DesignatorExtension DesignatorExtension) {
        this.DesignatorExtension=DesignatorExtension;
    }

    public SingleDesignatorExtension getSingleDesignatorExtension() {
        return SingleDesignatorExtension;
    }

    public void setSingleDesignatorExtension(SingleDesignatorExtension SingleDesignatorExtension) {
        this.SingleDesignatorExtension=SingleDesignatorExtension;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(DesignatorExtension!=null) DesignatorExtension.accept(visitor);
        if(SingleDesignatorExtension!=null) SingleDesignatorExtension.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(DesignatorExtension!=null) DesignatorExtension.traverseTopDown(visitor);
        if(SingleDesignatorExtension!=null) SingleDesignatorExtension.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(DesignatorExtension!=null) DesignatorExtension.traverseBottomUp(visitor);
        if(SingleDesignatorExtension!=null) SingleDesignatorExtension.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("DesignatorExtensions(\n");

        if(DesignatorExtension!=null)
            buffer.append(DesignatorExtension.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(SingleDesignatorExtension!=null)
            buffer.append(SingleDesignatorExtension.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [DesignatorExtensions]");
        return buffer.toString();
    }
}
