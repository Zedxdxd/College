// generated with ast extension for cup
// version 0.8
// 12/0/2024 15:27:59


package rs.ac.bg.etf.pp1.ast;

public class NegativeExpr extends Expr {

    private StartExpr StartExpr;
    private Term Term;
    private TermList TermList;

    public NegativeExpr (StartExpr StartExpr, Term Term, TermList TermList) {
        this.StartExpr=StartExpr;
        if(StartExpr!=null) StartExpr.setParent(this);
        this.Term=Term;
        if(Term!=null) Term.setParent(this);
        this.TermList=TermList;
        if(TermList!=null) TermList.setParent(this);
    }

    public StartExpr getStartExpr() {
        return StartExpr;
    }

    public void setStartExpr(StartExpr StartExpr) {
        this.StartExpr=StartExpr;
    }

    public Term getTerm() {
        return Term;
    }

    public void setTerm(Term Term) {
        this.Term=Term;
    }

    public TermList getTermList() {
        return TermList;
    }

    public void setTermList(TermList TermList) {
        this.TermList=TermList;
    }

    public void accept(Visitor visitor) {
        visitor.visit(this);
    }

    public void childrenAccept(Visitor visitor) {
        if(StartExpr!=null) StartExpr.accept(visitor);
        if(Term!=null) Term.accept(visitor);
        if(TermList!=null) TermList.accept(visitor);
    }

    public void traverseTopDown(Visitor visitor) {
        accept(visitor);
        if(StartExpr!=null) StartExpr.traverseTopDown(visitor);
        if(Term!=null) Term.traverseTopDown(visitor);
        if(TermList!=null) TermList.traverseTopDown(visitor);
    }

    public void traverseBottomUp(Visitor visitor) {
        if(StartExpr!=null) StartExpr.traverseBottomUp(visitor);
        if(Term!=null) Term.traverseBottomUp(visitor);
        if(TermList!=null) TermList.traverseBottomUp(visitor);
        accept(visitor);
    }

    public String toString(String tab) {
        StringBuffer buffer=new StringBuffer();
        buffer.append(tab);
        buffer.append("NegativeExpr(\n");

        if(StartExpr!=null)
            buffer.append(StartExpr.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(Term!=null)
            buffer.append(Term.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        if(TermList!=null)
            buffer.append(TermList.toString("  "+tab));
        else
            buffer.append(tab+"  null");
        buffer.append("\n");

        buffer.append(tab);
        buffer.append(") [NegativeExpr]");
        return buffer.toString();
    }
}
