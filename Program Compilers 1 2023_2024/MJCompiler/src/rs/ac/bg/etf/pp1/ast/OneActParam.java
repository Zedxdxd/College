// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class OneActParam extends ActParsList {

    private OneActPar OneActPar;

    public OneActParam (OneActPar OneActPar) {
        this.OneActPar=OneActPar;
        if(OneActPar!=null) OneActPar.setParent(this);
    }

    public OneActPar getOneActPar() {
        return OneActPar;
    }

    public void setOneActPar(OneActPar OneActPar) {
        this.OneActPar=OneActPar;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(OneActPar!=null) OneActPar.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(OneActPar!=null) OneActPar.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(OneActPar!=null) OneActPar.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("OneActParam(\n");

        if(OneActPar!=null)
            buffer.append(OneActPar.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [OneActParam]");
        return buffer.toString();
    }
}
