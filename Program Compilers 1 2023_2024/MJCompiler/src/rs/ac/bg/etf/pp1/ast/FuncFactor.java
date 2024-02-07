// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class FuncFactor extends Factor {

    private FuncCallStart FuncCallStart;
    private ActPars ActPars;

    public FuncFactor (FuncCallStart FuncCallStart, ActPars ActPars) {
        this.FuncCallStart=FuncCallStart;
        if(FuncCallStart!=null) FuncCallStart.setParent(this);
        this.ActPars=ActPars;
        if(ActPars!=null) ActPars.setParent(this);
    }

    public FuncCallStart getFuncCallStart() {
        return FuncCallStart;
    }

    public void setFuncCallStart(FuncCallStart FuncCallStart) {
        this.FuncCallStart=FuncCallStart;
    }

    public ActPars getActPars() {
        return ActPars;
    }

    public void setActPars(ActPars ActPars) {
        this.ActPars=ActPars;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(FuncCallStart!=null) FuncCallStart.accept(visitor);
        if(ActPars!=null) ActPars.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(FuncCallStart!=null) FuncCallStart.traverseTopDown(visitor);
        if(ActPars!=null) ActPars.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(FuncCallStart!=null) FuncCallStart.traverseBottomUp(visitor);
        if(ActPars!=null) ActPars.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("FuncFactor(\n");

        if(FuncCallStart!=null)
            buffer.append(FuncCallStart.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(ActPars!=null)
            buffer.append(ActPars.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [FuncFactor]");
        return buffer.toString();
    }
}
