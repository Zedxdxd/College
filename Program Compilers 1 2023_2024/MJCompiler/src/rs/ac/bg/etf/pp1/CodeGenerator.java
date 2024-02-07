package rs.ac.bg.etf.pp1;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Queue;
import java.util.Stack;

import rs.ac.bg.etf.pp1.CounterVisitor.CondTermCounter;
import rs.ac.bg.etf.pp1.CounterVisitor.FormParamCounter;
import rs.ac.bg.etf.pp1.CounterVisitor.VarCounter;
import rs.ac.bg.etf.pp1.ast.*;
import rs.etf.pp1.mj.runtime.Code;
import rs.etf.pp1.symboltable.concepts.Obj;
import rs.etf.pp1.symboltable.concepts.Struct;

public class CodeGenerator extends VisitorAdaptor {

	int mainPc;

	// for designator extensions
	Stack<Obj> currDesignatorStack = new Stack<>();

	// promenljive za if
	int condTermCounter = 0;
	Stack<List<Integer>> adrToElse = new Stack<>();
	List<Integer> adrToIf = new ArrayList<>();
	Stack<Integer> adrToEndIf = new Stack<>();

	// helpers za for petlje :P
	Stack<Integer> conditionStartStack = new Stack<>();
	Stack<Integer> finalForStmtStartStack = new Stack<>();
	Stack<List<Integer>> adrToLoop = new Stack<>();
	Stack<List<Integer>> adrToEndLoop = new Stack<>();

	// za skokove na inicijalizaciju tabela virtuelnih fja i vracanje iz njih
	List<Integer> startVirtualTableList = new ArrayList<>();
	List<Integer> fixupAdrTvfToMain = new ArrayList<>();

	// za skokove na staticke inicijalizatore i vracanje iz njih
	List<Integer> startStaticInitializerList = new ArrayList<>();
	List<Integer> fixupAdrStaticInitToMain = new ArrayList<>();

	public int getMainPc() {
		return mainPc;
	}

	// DesignatorStatement smene
	public void visit(DesignatorIncrement designatorIncrement) {
		if (designatorIncrement.getDesignator().obj.getLevel() > 0
				&& designatorIncrement.getDesignator().obj.getKind() == Obj.Var) {
			Code.put(Code.inc);
			Code.put(designatorIncrement.getDesignator().obj.getAdr());
			Code.put(1);
		} else {
			if (designatorIncrement.getDesignator().getDesignatorExtension()
					.getClass() != NoDesignatorExtension.class) {
				designatorIncrement.getDesignator().getDesignatorName().traverseBottomUp(this);
				designatorIncrement.getDesignator().getDesignatorExtension().traverseBottomUp(this);
			}
			Code.load(designatorIncrement.getDesignator().obj);
			Code.put(Code.const_1);
			Code.put(Code.add);
			Code.store(designatorIncrement.getDesignator().obj);
		}
	}

	public void visit(DesignatorDecrement designatorDecrement) {
		if (designatorDecrement.getDesignator().getDesignatorExtension().getClass() != NoDesignatorExtension.class) {
			designatorDecrement.getDesignator().getDesignatorName().traverseBottomUp(this);
			designatorDecrement.getDesignator().getDesignatorExtension().traverseBottomUp(this);
		}
		Code.load(designatorDecrement.getDesignator().obj);
		Code.put(Code.const_1);
		Code.put(Code.sub);
		Code.store(designatorDecrement.getDesignator().obj);
	}

	public void visit(DesignatorAssignment designatorAssignment) {
		Code.store(designatorAssignment.getDesignator().obj);
	}

	public void visit(DesignatorFuncCall designatorFuncCall) {
		if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getName().equals("ord")
				|| designatorFuncCall.getFuncCallStart().getDesignator().obj.getName().equals("chr")) {
			return;
		}
		if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getName().equals("len")) {
			Code.put(Code.arraylength);
			return;
		}
		for (Obj o : designatorFuncCall.getFuncCallStart().getDesignator().obj.getLocalSymbols()) {
			if (o.getName().equals("this")) {
				designatorFuncCall.getFuncCallStart().getDesignator().traverseBottomUp(this);
				Code.put(Code.getfield);
				Code.put2(0);
				Code.put(Code.invokevirtual);
				for (char c : designatorFuncCall.getFuncCallStart().getDesignator().obj.getName().toCharArray()) {
					Code.put4((int) c);
				}
				Code.put4(-1);
				if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getType() != ExtendedTab.noType) {
					Code.put(Code.pop);
				}
				return;
			} else {
				break;
			}
		}
		Code.put(Code.call);
		Code.put2(designatorFuncCall.getFuncCallStart().getDesignator().obj.getAdr() - Code.pc + 1); // + 1 jer call
																										// uzme jedan
																										// bajt
		if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getType() != ExtendedTab.noType) {
			Code.put(Code.pop);
		}
	}

	Stack<Obj> elementsStack = new Stack<>();

	// sve za DesignatorUnzip
	public void visit(Des des) {
		elementsStack.push(des.getDesignator().obj);
	}

	public void visit(NoDesignator noDesignator) {
		elementsStack.push(null);
	}

	public void visit(DesignatorUnzip designatorUnzip) {
//		-----------------------------------------------------------------------------------------
//		OVAKO OVDE SE POPUNJAVA CEO DST ARRAY... 
//		-----------------------------------------------------------------------------------------
		Obj cnt = new Obj(Obj.Con, "$cnt", ExtendedTab.intType, elementsStack.size(), 0);
		Obj curr = SemanticAnalyzer.curr;
		// provera da li je broj elemenata ok!!
		Code.load(cnt);
		Code.put(Code.sub);
		int trapAdrFixup = Code.pc + 1;
		Code.putFalseJump(Code.gt, 0);
		Code.put(Code.trap);
		Code.put(1);
		Code.fixup(trapAdrFixup);

		Obj dstArray = designatorUnzip.getDesignator().obj;
		Obj srcArray = designatorUnzip.getDesignator1().obj;
		boolean isChar = dstArray.getType().getElemType() == ExtendedTab.charType;
		designatorUnzip.getDesignator().getDesignatorName().traverseBottomUp(this);
		designatorUnzip.getDesignator().getDesignatorExtension().traverseBottomUp(this);// Code.load(dstArray);
		Code.put(Code.arraylength);
		Code.put(Code.const_1);
		Code.put(Code.sub);
		Code.store(curr);
		int adrLoop = Code.pc; // ovde pocinje petlja u kojoj se ubacuju elementi iz srcarray u dstarray

		// e sad the notorius petlja
		Code.load(curr);
		Code.put(Code.const_n);
		int adrEndLoop = Code.pc + 1;
		Code.putFalseJump(Code.ge, 0);
		designatorUnzip.getDesignator().getDesignatorName().traverseBottomUp(this);
		designatorUnzip.getDesignator().getDesignatorExtension().traverseBottomUp(this);// Code.load(dstArray);
		Code.load(curr);
		designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
		designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this);// Code.load(srcArray);
		Code.load(curr);
		Code.load(cnt);
		Code.put(Code.add);
		Code.put(isChar ? Code.baload : Code.aload);
		Code.put(isChar ? Code.bastore : Code.astore);
		Code.load(curr);
		Code.put(Code.const_1);
		Code.put(Code.sub);
		Code.store(curr);
		Code.putJump(adrLoop);

		// sad se stavljaju elementi wohooo!!!
		Code.fixup(adrEndLoop);
		while (!elementsStack.empty()) {
			Obj tmp = elementsStack.pop();
			if (tmp == null) {
				continue;
			} else {
				designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
				designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this); // Code.load(srcArray);
				cnt.setAdr(elementsStack.size());
				Code.load(cnt);
				Code.put(isChar ? Code.baload : Code.aload);
				Code.store(tmp);
			}
		}

//		----------------------------------------------------------------------------------------------
//		U SLUCAJU DA NISAM LEPO PROTUMACIO PA IPAK NE TREBA SKROZ DA SE POPUNI LEVI
//		NIZ ODNOSNO NIZ DST ARRAY, VEC SE POPUNJAVA DOK GOD U SRCARRAY IMA CLANOVA
//		---------------------------------------------------------------------------------------------
//		Obj cnt = new Obj(Obj.Con, "$cnt", ExtendedTab.intType, elementsStack.size() + 1, 0);
//		Obj curr = SemanticAnalyzer.curr;
//		// provera da li je broj elemenata ok!!
//		Code.load(cnt);
//		int trapAdrFixup = Code.pc + 1;
//		Code.putFalseJump(Code.lt, 0);
//		Code.put(Code.trap);
//		Code.put(1);
//		Code.fixup(trapAdrFixup);
//		
//		Code.put(Code.pop);
//		designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this); // niz2
//		Code.put(Code.arraylength); // len(niz2)
//		
//		Code.loadConst(elementsStack.size()); // len(niz2) br
//		designatorUnzip.getDesignator().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator().getDesignatorExtension().traverseBottomUp(this); // len(niz2) br niz1
//		Code.put(Code.arraylength); // len(niz2) br len(niz1)
//		Code.put(Code.add); // len(niz2) br+len(niz1)
//		int adrLoadDst = Code.pc + 1;
//		Code.putFalseJump(Code.lt, 0);
//		// ako se dstArray ne moze popuniti do kraja, 
//		//onda se krece od kraja srcArray sa ubacivanjem u dstArray
//		// dstArray[len(niz2) - 1 - cnt] = srcArray[len(niz2) - 1]
//		designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this); // Code.load(srcArray)
//		Code.put(Code.arraylength);
//		Code.put(Code.const_1);
//		Code.put(Code.sub);
//		Code.loadConst(elementsStack.size());
//		Code.put(Code.sub);
//		Code.store(curr);
//		
//		int adrToGetIndex = Code.pc + 1;
//		Code.putJump(0);
//		Code.fixup(adrLoadDst);
//		// u suprotnom se krece od kraja dstArray
//		// dstArray[len(niz1) - 1] = srcArray[len(niz1) - 1 + cnt]
//		designatorUnzip.getDesignator().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator().getDesignatorExtension().traverseBottomUp(this); // Code.load(dstArray)
//		Code.put(Code.arraylength);
//		Code.put(Code.const_1);
//		Code.put(Code.sub);
//		Code.store(curr);
//		Code.fixup(adrToGetIndex);
//		
//		
//		Obj dstArray = designatorUnzip.getDesignator().obj;
//		Obj srcArray = designatorUnzip.getDesignator1().obj;
//		boolean isChar = dstArray.getType().getElemType() == ExtendedTab.charType;
//		
//		int adrLoop = Code.pc; // ovde pocinje petlja u kojoj se ubacuju elementi iz srcarray u dstarray
//
//		// e sad the notorius petlja
//		Code.load(curr);
//		Code.put(Code.const_n);
//		int adrEndLoop = Code.pc + 1;
//		Code.putFalseJump(Code.ge, 0);
//		designatorUnzip.getDesignator().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator().getDesignatorExtension().traverseBottomUp(this);// Code.load(dstArray);
//		Code.load(curr);
//		designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
//		designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this);// Code.load(srcArray);
//		Code.load(curr);
//		Code.loadConst(elementsStack.size());
//		Code.put(Code.add);
//		Code.put(isChar ? Code.baload : Code.aload);
//		Code.put(isChar ? Code.bastore : Code.astore);
//		Code.load(curr);
//		Code.put(Code.const_1);
//		Code.put(Code.sub);
//		Code.store(curr);
//		Code.putJump(adrLoop);
//
//		// sad se stavljaju elementi wohooo!!!
//		Code.fixup(adrEndLoop);
//		while (!elementsStack.empty()) {
//			Obj tmp = elementsStack.pop();
//			if (tmp == null) {
//				continue;
//			} else {
//				designatorUnzip.getDesignator1().getDesignatorName().traverseBottomUp(this);
//				designatorUnzip.getDesignator1().getDesignatorExtension().traverseBottomUp(this); // Code.load(srcArray);
//				cnt.setAdr(elementsStack.size());
//				Code.load(cnt);
//				Code.put(isChar ? Code.baload : Code.aload);
//				Code.store(tmp);
//			}
//		}

	}

	// Designator smene
	public void visit(ScopeDesignatorName scopeDesignatorName) {
		Designator designator = (Designator) scopeDesignatorName.getParent();
		SyntaxNode parent = designator.getParent();
		currDesignatorStack.push(scopeDesignatorName.obj);

		if ((parent.getClass() != FuncCallStart.class || parent.getClass() == FuncCallStart.class
				&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorAssignment.class || parent.getClass() == DesignatorAssignment.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != ReadStmt.class || parent.getClass() == ReadStmt.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorIncrement.class || parent.getClass() == DesignatorIncrement.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorDecrement.class || parent.getClass() == DesignatorDecrement.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != Des.class || parent.getClass() == Des.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& scopeDesignatorName.obj.getKind() != Obj.Type) {
			Code.load(scopeDesignatorName.obj);
		}
	}

	public void visit(NoScopeDesignatorName noScopeDesignatorName) {
		Designator designator = (Designator) noScopeDesignatorName.getParent();
		SyntaxNode parent = designator.getParent();
		currDesignatorStack.push(noScopeDesignatorName.obj);

		if (noScopeDesignatorName.obj.getFpPos() == 100) {
			Code.put(Code.load_n);
		}

		if ((parent.getClass() != FuncCallStart.class || parent.getClass() == FuncCallStart.class
				&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorAssignment.class || parent.getClass() == DesignatorAssignment.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != ReadStmt.class || parent.getClass() == ReadStmt.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorIncrement.class || parent.getClass() == DesignatorIncrement.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != DesignatorDecrement.class || parent.getClass() == DesignatorDecrement.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& (parent.getClass() != Des.class || parent.getClass() == Des.class
						&& designator.getDesignatorExtension().getClass() != NoDesignatorExtension.class)
				&& noScopeDesignatorName.obj.getKind() != Obj.Type) {

			Code.load(noScopeDesignatorName.obj);
		}
	}

	public void visit(SingleDesignatorExtensionClassField singleDesignatorExtensionClassField) {
		Obj currObj = currDesignatorStack.pop();
		Obj newObj = currObj.getType().getMembersTable().searchKey(singleDesignatorExtensionClassField.getFieldName());
		if (currObj.getKind() == Obj.Type) {
			// newObj =
			// ((Designator)singleDesignatorExtensionClassField.getParent().getParent()).obj;
			Obj progObj = null;
			for (Obj o : ExtendedTab.currentScope.values()) {
				if (o.getKind() == Obj.Prog) {
					progObj = o;
					break;
				}
			}
			// valjda nece nikad bude null
			if (progObj == null) {
				System.out.println("kuku");
			}
			for (Obj o : progObj.getLocalSymbols()) {
				if (o.getName().equals(currObj.getName() + "." + singleDesignatorExtensionClassField.getFieldName())) {
					newObj = o;
					break;
				}
			}
		}
		currDesignatorStack.push(newObj);
		if (!(singleDesignatorExtensionClassField.getParent().getParent().getClass() == Designator.class
				&& (singleDesignatorExtensionClassField.getParent().getParent().getParent()
						.getClass() == DesignatorAssignment.class
						|| singleDesignatorExtensionClassField.getParent().getParent().getParent()
								.getClass() == Des.class
						|| singleDesignatorExtensionClassField.getParent().getParent().getParent()
								.getClass() == ReadStmt.class
						|| singleDesignatorExtensionClassField.getParent().getParent().getParent()
								.getClass() == FuncCallStart.class
						|| singleDesignatorExtensionClassField.getParent().getParent().getParent()
								.getClass() == DesignatorDecrement.class
						|| singleDesignatorExtensionClassField.getParent().getParent().getParent()
								.getClass() == DesignatorIncrement.class))) {
			if (currObj.getKind() == Obj.Type) {
				Code.load(newObj);
			} else {
				Code.put(Code.getfield);
				Code.put2(newObj.getAdr());
			}
		}
	}

	public void visit(SingleDesignatorExtensionArray singleDesignatorExtensionArray) {
		Obj currObj = currDesignatorStack.pop();
		currDesignatorStack.push(new Obj(Obj.Elem, currObj.getName(), currObj.getType().getElemType(), currObj.getAdr(),
				currObj.getLevel()));
		if (!(singleDesignatorExtensionArray.getParent().getParent().getClass() == Designator.class
				&& (singleDesignatorExtensionArray.getParent().getParent().getParent()
						.getClass() == DesignatorAssignment.class
						|| singleDesignatorExtensionArray.getParent().getParent().getParent().getClass() == Des.class
						|| singleDesignatorExtensionArray.getParent().getParent().getParent()
								.getClass() == ReadStmt.class
						|| singleDesignatorExtensionArray.getParent().getParent().getParent()
								.getClass() == DesignatorDecrement.class
						|| singleDesignatorExtensionArray.getParent().getParent().getParent()
								.getClass() == DesignatorIncrement.class))) {
			if (currDesignatorStack.peek().getType() == ExtendedTab.charType) {
				Code.put(Code.baload);
			} else {
				Code.put(Code.aload);
			}
		}
	}

	public void visit(Designator designator) {
		currDesignatorStack.pop();
		if (designator.getParent().getClass() == DesignatorUnzip.class) {
			Code.put(Code.arraylength);
		}
	}

	// Factor smene
	public void visit(NumberFactor numberFactor) {
		Obj con = new Obj(Obj.Con, "$", numberFactor.struct, numberFactor.getN1(), 0);
		Code.load(con);
	}

	public void visit(CharFactor charFactor) {
		Obj con = new Obj(Obj.Con, "$", ExtendedTab.charType, (int) charFactor.getC1(), 0);
		Code.load(con);
	}

	public void visit(BoolFactor boolFactor) {
		Obj con = new Obj(Obj.Con, "$", ExtendedTab.boolType, boolFactor.getB1() ? 1 : 0, 0);
		Code.load(con);
	}

	public void visit(ArrayFactor arrayFactor) {
		Code.put(Code.newarray);
		if (arrayFactor.struct == ExtendedTab.charType) {
			Code.put(0);
		} else {
			Code.put(1);
		}
	}

	public void visit(ClassFactor classFactor) {
		int numBytes = 0;
		int i = 0;
		int tvfAddress = 0;
		for (Obj o : classFactor.struct.getMembers()) {
			if (o.getName().equals("tvf")) {
				tvfAddress = o.getAdr();
			}
			if (o.getType() == ExtendedTab.charType) {
				numBytes++;
			} else {
				numBytes += 4;
			}
			i++;
			if (i == classFactor.struct.getNumberOfFields()) {
				break;
			}
		}
		Code.put(Code.new_);
		Code.put2(numBytes);
		Code.put(Code.dup);
		Obj tmp = new Obj(Obj.Con, "$$", ExtendedTab.intType);
		tmp.setAdr(tvfAddress);
		Code.load(tmp);
		Code.put(Code.putfield);
		Code.put2(0);
	}

	public void visit(FuncFactor funcFactor) {
		if (funcFactor.getFuncCallStart().getDesignator().obj.getName().equals("ord")
				|| funcFactor.getFuncCallStart().getDesignator().obj.getName().equals("chr")) {
			return;
		}
		if (funcFactor.getFuncCallStart().getDesignator().obj.getName().equals("len")) {
			Code.put(Code.arraylength);
			return;
		}
		for (Obj o : funcFactor.getFuncCallStart().getDesignator().obj.getLocalSymbols()) {
			if (o.getName().equals("this")) {
				funcFactor.getFuncCallStart().getDesignator().traverseBottomUp(this);
				Code.put(Code.getfield);
				Code.put2(0);
				Code.put(Code.invokevirtual);
				for (char c : funcFactor.getFuncCallStart().getDesignator().obj.getName().toCharArray()) {
					Code.put4((int) c);
				}
				Code.put4(-1);
				return;
			} else {
				break;
			}
		}
		Code.put(Code.call);
		Code.put2(funcFactor.getFuncCallStart().getDesignator().obj.getAdr() - Code.pc + 1);
	}

	public void visit(FactorsList factorsList) {
		if (factorsList.getMulop() instanceof Multiplication) {
			Code.put(Code.mul);
		} else if (factorsList.getMulop() instanceof Division) {
			Code.put(Code.div);
		} else if (factorsList.getMulop() instanceof Moduo) {
			Code.put(Code.rem);
		}
	}

	// Term smene
	public void visit(Term term) {
		SyntaxNode parent = term.getParent();

		if (parent.getClass() == NegativeExpr.class) {
			Code.put(Code.neg);
		}
	}

	public void visit(TermsList termsList) {
		if (termsList.getAddop() instanceof Addition) {
			Code.put(Code.add);
		} else if (termsList.getAddop() instanceof Subtraction) {
			Code.put(Code.sub);
		}
	}

	// Statement smene
	public void visit(ReadStmt readStmt) {
		if (readStmt.getDesignator().obj.getType() == ExtendedTab.charType) {
			Code.put(Code.bread);
		} else {
			Code.put(Code.read);
		}
		Code.store(readStmt.getDesignator().obj);
	}

	public void visit(PrintSingleStmt printSingleStmt) {
		if (printSingleStmt.getExpr().struct == ExtendedTab.intType
				|| printSingleStmt.getExpr().struct == ExtendedTab.boolType) {
			Code.loadConst(4);
			Code.put(Code.print);
		} else {
			Code.loadConst(1);
			Code.put(Code.bprint);
		}
	}

	public void visit(PrintMultipleStmt printMultipleStmt) {
		/*
		 * if (printMultipleStmt.getExpr().struct == ExtendedTab.intType ||
		 * printMultipleStmt.getExpr().struct == ExtendedTab.boolType) { for (int i = 0;
		 * i < printMultipleStmt.getN2(); i++) { if (i != printMultipleStmt.getN2() - 1)
		 * { Code.put(Code.dup); } Code.loadConst(4); Code.put(Code.print); } } else {
		 * for (int i = 0; i < printMultipleStmt.getN2(); i++) { if (i !=
		 * printMultipleStmt.getN2() - 1) { Code.put(Code.dup); } Code.loadConst(1);
		 * Code.put(Code.bprint); } }
		 */
		if (printMultipleStmt.getN2() <= 0) {
			return;
		} else {
			Obj width = new Obj(Obj.Con, "$$", ExtendedTab.intType);
			width.setAdr(printMultipleStmt.getN2());
			Code.load(width);
			if (printMultipleStmt.getExpr().struct == ExtendedTab.intType
					|| printMultipleStmt.getExpr().struct == ExtendedTab.boolType) {
				Code.put(Code.print);
			} else {
				Code.put(Code.bprint);
			}
		}
	}

	public void visit(ReturnVoid returnVoid) {
		Code.put(Code.exit);
		Code.put(Code.return_);
	}

	public void visit(ReturnNonVoid returnNonVoid) {
		Code.put(Code.exit);
		Code.put(Code.return_);
	}

	// Sve vezano za if
	public void visit(IfStart ifStart) {
		condTermCounter = 0;
		adrToElse.push(new ArrayList<>());
		SyntaxNode parent = ifStart.getParent();
		if (parent instanceof IfStmt) {
			IfStmt stmt = (IfStmt) parent;
			CondTermCounter counter = new CondTermCounter();
			stmt.getCondition().traverseTopDown(counter);
			condTermCounter = counter.count;
		} else if (parent instanceof IfElseStmt) {
			IfElseStmt stmt = (IfElseStmt) parent;
			CondTermCounter counter = new CondTermCounter();
			stmt.getCondition().traverseTopDown(counter);
			condTermCounter = counter.count;
		}
	}

	public void visit(IfStmt ifStmt) {
		for (Integer adr : adrToElse.peek()) {
			Code.fixup(adr);
		}
		adrToElse.pop();
	}

	public void visit(IfElseStmt ifElseStmt) {
		Code.fixup(adrToEndIf.pop());
	}

	public void visit(ForLoopStart forLoopStart) {
		adrToLoop.push(new ArrayList<>());
		adrToElse.push(new ArrayList<>());
		adrToEndLoop.push(new ArrayList<>());
	}

	public void visit(ForConditionStmt forConditionStmt) {
		Code.putJump(finalForStmtStartStack.peek());
		for (Integer adr : adrToElse.peek()) {
			Code.fixup(adr);
		}
		for (Integer adr : adrToEndLoop.peek()) {
			Code.fixup(adr);
		}

		adrToLoop.pop();
		adrToEndLoop.pop();
		conditionStartStack.pop();
		finalForStmtStartStack.pop();
		adrToElse.pop();
	}

	public void visit(ForNoConditionStmt forNoConditionStmt) {
		Code.putJump(finalForStmtStartStack.peek());
		for (Integer adr : adrToElse.peek()) {
			Code.fixup(adr);
		}
		for (Integer adr : adrToEndLoop.peek()) {
			Code.fixup(adr);
		}

		adrToLoop.pop();
		adrToEndLoop.pop();
		conditionStartStack.pop();
		finalForStmtStartStack.pop();
		adrToElse.pop();
	}

	public void visit(BreakStmt breakStmt) {
		adrToEndLoop.peek().add(Code.pc + 1);
		Code.putJump(0);
	}

	public void visit(ContinueStmt continueStmt) {
		Code.putJump(finalForStmtStartStack.peek());
	}

	// sve vezano za MethodDecl
	public void visit(TypeMethodName typeMethodName) {
		if (typeMethodName.getMethName().equals("main")) {
			mainPc = Code.pc;
		}

		typeMethodName.obj.setAdr(Code.pc);

		int formParamCount = typeMethodName.obj.getLevel();
		for (Obj o : typeMethodName.obj.getLocalSymbols()) {
			if (o.getName().equals("this")) {
				formParamCount++;
			}
			break;
		}
		Code.put(Code.enter);
		Code.put(formParamCount);
		Code.put(typeMethodName.obj.getLocalSymbols().size());
	}

	public void visit(VoidMethodName voidMethodName) {
		boolean isMain = false;
		if (voidMethodName.getMethName().equals("main")) {
			isMain = true;
			mainPc = Code.pc;
		}

		voidMethodName.obj.setAdr(Code.pc);

		int formParamCount = voidMethodName.obj.getLevel();
		for (Obj o : voidMethodName.obj.getLocalSymbols()) {
			if (o.getName().equals("this")) {
				formParamCount++;
			}
			break;
		}
		Code.put(Code.enter);
		Code.put(formParamCount);
		Code.put(voidMethodName.obj.getLocalSymbols().size());
		if (isMain) {
			for (int i = 0; i < startVirtualTableList.size(); i++) {
				Code.putJump(startVirtualTableList.get(i));
				Code.fixup(fixupAdrTvfToMain.get(i));
			}
			for (int i = 0; i < startStaticInitializerList.size(); i++) {
				Code.putJump(startStaticInitializerList.get(i));
				Code.fixup(fixupAdrStaticInitToMain.get(i));
			}
		}
	}

	public void visit(MethodDecl methodDecl) {
		if (methodDecl.obj.getType() == ExtendedTab.noType) {
			Code.put(Code.exit);
			Code.put(Code.return_);
		} else {
			Code.put(Code.trap);
			Code.put(1);
		}
	}

	// sve vezano za condition
	public void visit(CondFactNoRelop condFactNoRelop) {
		Code.put(Code.const_1);
		adrToElse.peek().add(Code.pc + 1);
		Code.putFalseJump(Code.eq, 0);
	}

	public void visit(CondFactRelop condFactRelop) {
		adrToElse.peek().add(Code.pc + 1);
		if (condFactRelop.getRelop() instanceof LogEq) {
			Code.putFalseJump(Code.eq, 0);
		} else if (condFactRelop.getRelop() instanceof LogNotEq) {
			Code.putFalseJump(Code.ne, 0);
		} else if (condFactRelop.getRelop() instanceof Grt) {
			Code.putFalseJump(Code.gt, 0);
		} else if (condFactRelop.getRelop() instanceof Gre) {
			Code.putFalseJump(Code.ge, 0);
		} else if (condFactRelop.getRelop() instanceof Lss) {
			Code.putFalseJump(Code.lt, 0);
		} else if (condFactRelop.getRelop() instanceof Lse) {
			Code.putFalseJump(Code.le, 0);
		}
	}

	public void visit(CondTerm condTerm) {
		condTermCounter--;
		if (condTermCounter > 0) {
			adrToIf.add(Code.pc + 1);
			Code.putJump(0);
			for (Integer adr : adrToElse.peek()) {
				Code.fixup(adr);
			}
			adrToElse.peek().clear();
		} else {
			for (Integer adr : adrToIf) {
				Code.fixup(adr);
			}
			adrToIf.clear();
		}
	}

	public void visit(ElseStart elseStart) {
		adrToEndIf.push(Code.pc + 1);
		Code.putJump(0);
		for (Integer adr : adrToElse.peek()) {
			Code.fixup(adr);
		}
		adrToElse.pop();
	}

	public void visit(FirstForSemicolon firstForSemicolon) {
		conditionStartStack.push(Code.pc);
	}

	public void visit(SecondForSemicolon secondForSemicolon) {
		adrToLoop.peek().add(Code.pc + 1);
		Code.putJump(0);
		finalForStmtStartStack.push(Code.pc);
	}

	public void visit(FinalForStatement finalForStatement) {
		if (finalForStmtStartStack.peek() - conditionStartStack.peek() > 3) {
			Code.putJump(conditionStartStack.peek());
		}
		for (Integer adr : adrToLoop.peek()) {
			Code.fixup(adr);
		}
		adrToLoop.peek().clear();
	}

	// sve vezano za ClassDecl
	public void visit(ClassDecl classDecl) {
		Obj o = classDecl.obj;
		int currLocation = SemanticAnalyzer.tvfStart;
		startVirtualTableList.add(Code.pc);
		for (Obj member : classDecl.obj.getType().getMembers()) {
			if (member.getName().equals("tvf")) {
				member.setAdr(currLocation);
			} else if (member.getKind() == Obj.Meth) {
				if (o.getType().getElemType() != null && member.getAdr() == 0) {
					Struct baseClass = o.getType().getElemType();
					Obj baseMethod = baseClass.getMembersTable().searchKey(member.getName());
					member.setAdr(baseMethod.getAdr());
				}

				for (char c : member.getName().toCharArray()) {
					Obj tmp = new Obj(Obj.Con, "$$", ExtendedTab.charType);
					tmp.setAdr((int) c);
					Code.load(tmp);
					Code.put(Code.putstatic);
					Code.put2(currLocation++);
				}
				Code.put(Code.const_m1);
				Code.put(Code.putstatic);
				Code.put2(currLocation++);
				Obj tmp = new Obj(Obj.Con, "$$", ExtendedTab.intType);
				tmp.setAdr(member.getAdr());
				Code.load(tmp);
				Code.put(Code.putstatic);
				Code.put2(currLocation++);
			}
		}
		Obj tmp = new Obj(Obj.Con, "$$", ExtendedTab.intType);
		tmp.setAdr(-2);
		Code.load(tmp);
		Code.put(Code.putstatic);
		Code.put2(currLocation++);
		SemanticAnalyzer.tvfStart = currLocation;
		fixupAdrTvfToMain.add(Code.pc + 1);
		Code.putJump(0);
		// Code.dataSize = currLocation;
	}

	public void visit(OneStaticInitializerStart oneStaticInitializerStart) {
		startStaticInitializerList.add(Code.pc);
	}

	public void visit(StaticInitializer staticInitializer) {
		fixupAdrStaticInitToMain.add(Code.pc + 1);
		Code.putJump(0);
	}

}
