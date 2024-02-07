// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class DesignatorUnzip extends DesignatorStatement {

    private DesignatorUnzipStart DesignatorUnzipStart;
    private ManyDesignators ManyDesignators;
    private Designator Designator;
    private Designator Designator1;

    public DesignatorUnzip (DesignatorUnzipStart DesignatorUnzipStart, ManyDesignators ManyDesignators, Designator Designator, Designator Designator1) {
        this.DesignatorUnzipStart=DesignatorUnzipStart;
        if(DesignatorUnzipStart!=null) DesignatorUnzipStart.setParent(this);
        this.ManyDesignators=ManyDesignators;
        if(ManyDesignators!=null) ManyDesignators.setParent(this);
        this.Designator=Designator;
        if(Designator!=null) Designator.setParent(this);
        this.Designator1=Designator1;
        if(Designator1!=null) Designator1.setParent(this);
    }

    public DesignatorUnzipStart getDesignatorUnzipStart() {
        return DesignatorUnzipStart;
    }

    public void setDesignatorUnzipStart(DesignatorUnzipStart DesignatorUnzipStart) {
        this.DesignatorUnzipStart=DesignatorUnzipStart;
    }

    public ManyDesignators getManyDesignators() {
        return ManyDesignators;
    }

    public void setManyDesignators(ManyDesignators ManyDesignators) {
        this.ManyDesignators=ManyDesignators;
    }

    public Designator getDesignator() {
        return Designator;
    }

    public void setDesignator(Designator Designator) {
        this.Designator=Designator;
    }

    public Designator getDesignator1() {
        return Designator1;
    }

    public void setDesignator1(Designator Designator1) {
        this.Designator1=Designator1;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(DesignatorUnzipStart!=null) DesignatorUnzipStart.accept(visitor);
        if(ManyDesignators!=null) ManyDesignators.accept(visitor);
        if(Designator!=null) Designator.accept(visitor);
        if(Designator1!=null) Designator1.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(DesignatorUnzipStart!=null) DesignatorUnzipStart.traverseTopDown(visitor);
        if(ManyDesignators!=null) ManyDesignators.traverseTopDown(visitor);
        if(Designator!=null) Designator.traverseTopDown(visitor);
        if(Designator1!=null) Designator1.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(DesignatorUnzipStart!=null) DesignatorUnzipStart.traverseBottomUp(visitor);
        if(ManyDesignators!=null) ManyDesignators.traverseBottomUp(visitor);
        if(Designator!=null) Designator.traverseBottomUp(visitor);
        if(Designator1!=null) Designator1.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("DesignatorUnzip(\n");

        if(DesignatorUnzipStart!=null)
            buffer.append(DesignatorUnzipStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(ManyDesignators!=null)
            buffer.append(ManyDesignators.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(Designator!=null)
            buffer.append(Designator.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(Designator1!=null)
            buffer.append(Designator1.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [DesignatorUnzip]");
        return buffer.toString();
    }
}
