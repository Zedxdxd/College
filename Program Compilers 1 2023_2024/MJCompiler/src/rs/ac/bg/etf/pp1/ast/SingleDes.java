// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class SingleDes extends DesignatorList {

    private SingleDesignator SingleDesignator;

    public SingleDes (SingleDesignator SingleDesignator) {
        this.SingleDesignator=SingleDesignator;
        if(SingleDesignator!=null) SingleDesignator.setParent(this);
    }

    public SingleDesignator getSingleDesignator() {
        return SingleDesignator;
    }

    public void setSingleDesignator(SingleDesignator SingleDesignator) {
        this.SingleDesignator=SingleDesignator;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(SingleDesignator!=null) SingleDesignator.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(SingleDesignator!=null) SingleDesignator.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(SingleDesignator!=null) SingleDesignator.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("SingleDes(\n");

        if(SingleDesignator!=null)
            buffer.append(SingleDesignator.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [SingleDes]");
        return buffer.toString();
    }
}
