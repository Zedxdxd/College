// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class NoDesignatorExtension extends DesignatorExtension {

    public NoDesignatorExtension () {
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("NoDesignatorExtension(\n");

        buffer.append(tab);
        buffer.append(") [NoDesignatorExtension]");
        return buffer.toString();
    }
}
