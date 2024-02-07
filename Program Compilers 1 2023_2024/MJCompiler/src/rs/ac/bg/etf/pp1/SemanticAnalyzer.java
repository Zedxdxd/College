package rs.ac.bg.etf.pp1;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Stack;

import org.apache.log4j.Logger;

import rs.ac.bg.etf.pp1.ast.*;
import rs.etf.pp1.symboltable.Tab;
import rs.etf.pp1.symboltable.concepts.*;

public class SemanticAnalyzer extends VisitorAdaptor {

	boolean errorDetected = false;
	Logger log = Logger.getLogger(getClass());

	public void report_error(String message, SyntaxNode info) {
		errorDetected = true;
		StringBuilder msg = new StringBuilder(message);
		int line = (info == null) ? 0 : info.getLine();
		if (line != 0)
			msg.append(" na liniji ").append(line);
		log.error(msg.toString());
	}

	public void report_info(String message, SyntaxNode info) {
		StringBuilder msg = new StringBuilder(message);
		int line = (info == null) ? 0 : info.getLine();
		if (line != 0)
			msg.append(" na liniji ").append(line);
		log.info(msg.toString());
	}

	int nVars;
	static int tvfStart;

	String currNamespace = null;

	// used in VarDecl and ConstDecl for types
	Struct currType = null;

	// helpers for determining the Expr type
	Stack<List<Struct>> factorListStack = new Stack<>();
	Stack<List<Struct>> termListStack = new Stack<>();

	// helper for DesignatorExtension
	Stack<List<String>> extensionsStack = new Stack<>();

	// general for designator
	Obj currDesignatorObj = null;
	boolean isIndexedArray = false;

	// current method definition
	Obj currMethod = null;

	// helper for active params
	Stack<List<Struct>> stackActualParams = new Stack<>();

	// helpers for determining the Condition type
	List<Struct> condFactorList = new ArrayList<>();
	List<Struct> condTermList = new ArrayList<>();

	// helper for for loops :)
	int forLevel = 0;

	// helper for DesignatorUnzip
	List<Struct> elementsUnzip;

	// helper for classDeclarations
	Obj currClass = null;

	// helpers for static stuff :(
	boolean isStaticVar = false;
	boolean isStaticInitializer = false;

	static Obj curr;

	// Program smene
	public void visit(ProgramName programName) {
		programName.obj = ExtendedTab.insert(Obj.Prog, programName.getProgName(), ExtendedTab.noType);
		ExtendedTab.openScope();
	}

	public void visit(Program program) {
		curr = ExtendedTab.insert(Obj.Var, "$curr", ExtendedTab.intType);
		nVars = ExtendedTab.currentScope.getnVars();
		tvfStart = nVars;
		Obj mainMethod = ExtendedTab.find("main");
		if (mainMethod == ExtendedTab.noObj) {
			report_error("Nije pronadjen metod main.", null);
		} else if (mainMethod.getKind() != Obj.Meth) {
			report_error("Nije pronadjen metod main.", null);
		} else if (mainMethod.getType() != ExtendedTab.noType) {
			report_error("Metod main ne sme da ima povratnu vrednost.", null);
		} else if (mainMethod.getLevel() > 0) {
			report_error("Metod main ne sme imati formalne parametre.", null);
		}
		ExtendedTab.chainLocalSymbols(program.getProgramName().obj);
		ExtendedTab.closeScope();

	}

	// Namespace smene
	public void visit(NamespaceName namespaceName) {
		currNamespace = namespaceName.getNamespaceName();
	}

	public void visit(Namespace namespace) {
		currNamespace = null;
	}

	// Type smene
	public void visit(NoScopeType noScopeType) {
		Obj typeNode = ExtendedTab.find(noScopeType.getTypeName());
		if (currNamespace != null) {
			typeNode = typeNode == ExtendedTab.noObj
					? ExtendedTab.find(currNamespace + "::" + noScopeType.getTypeName())
					: typeNode;
		}
		if (typeNode == ExtendedTab.noObj) {
			report_error("Na liniji " + noScopeType.getLine() + ": ne postoji tip sa imenom "
					+ noScopeType.getTypeName() + ".", null);
			currType = ExtendedTab.noType;
			noScopeType.struct = ExtendedTab.noType;
		} else {
			if (typeNode.getKind() != Obj.Type) {
				report_error(
						"Na liniji " + noScopeType.getLine() + ": ne postoji tip sa imenom " + typeNode.getName() + ".",
						null);
				currType = ExtendedTab.noType;
				noScopeType.struct = ExtendedTab.noType;
			} else {
				currType = typeNode.getType();
				noScopeType.struct = typeNode.getType();
				if (noScopeType.getParent() instanceof ClassFactor) {
					report_info("Pravi se objekat klase " + noScopeType.getTypeName() + " na " + noScopeType.getLine(),
							null);
				}
			}
		}
	}

	public void visit(ScopeType scopeType) {
		String fullTypeName = scopeType.getScopeName() + "::" + scopeType.getTypeName();
		Obj typeNode = ExtendedTab.find(fullTypeName);
		if (typeNode == ExtendedTab.noObj) {
			report_error("Na liniji " + scopeType.getLine() + ": ne postoji tip sa imenom " + fullTypeName + ".", null);
			currType = ExtendedTab.noType;
			scopeType.struct = ExtendedTab.noType;
		} else {
			if (typeNode.getKind() != Obj.Type) {
				report_error(
						"Na liniji " + scopeType.getLine() + ": ne postoji tip sa imenom " + typeNode.getName() + ".",
						null);
				currType = ExtendedTab.noType;
				scopeType.struct = ExtendedTab.noType;
			} else {
				currType = typeNode.getType();
				scopeType.struct = typeNode.getType();
				if (scopeType.getParent() instanceof ClassFactor) {
					report_info("Pravi se objekat klase " + fullTypeName + " na " + scopeType.getLine(), null);
				}
			}
		}
	}

	// sve vezano za constDecl
	public void visit(ConstNumber constNumber) {
		constNumber.obj = new Obj(Obj.Con, "$", ExtendedTab.intType, constNumber.getN1(), 0);
	}

	public void visit(ConstChar constChar) {
		constChar.obj = new Obj(Obj.Con, "$", ExtendedTab.charType, (int) constChar.getC1(), 0);
	}

	public void visit(ConstBool constBool) {
		constBool.obj = new Obj(Obj.Con, "$", ExtendedTab.boolType, constBool.getB1() ? 1 : 0, 0);
	}

	public void visit(ConstAssignment constAssignment) {
		String varName;
		if (currNamespace == null) {
			varName = constAssignment.getVarName();
		} else {
			varName = currNamespace + "::" + constAssignment.getVarName();
		}
		Obj varNode = ExtendedTab.currentScope.findSymbol(varName);
		if (varNode != null) {
			report_error("Na liniji " + constAssignment.getLine() + ": ime " + varName + " je vec deklarisano.", null);
		} else {
			if (constAssignment.getConstValue().obj.getType() != currType) {
				report_error("Na liniji " + constAssignment.getLine() + ": nekompatibilni tipovi pri dodeli.", null);
			} else {
				varNode = ExtendedTab.insert(Obj.Con, varName, currType);
				varNode.setAdr(constAssignment.getConstValue().obj.getAdr());
			}
		}
	}

	public void visit(ConstDecl constDecl) {
		currType = null;
	}

	// sve vezano za VarDecl
	public void visit(NonArrayDecl nonArrayDecl) {
		String varName;
		if (currNamespace == null || currMethod != null || currClass != null) {
			varName = nonArrayDecl.getVarName();
		} else {
			varName = currNamespace + "::" + nonArrayDecl.getVarName();
		}
		Obj varNode = ExtendedTab.currentScope.findSymbol(varName);
		if (varNode != null) {
			report_error("Na liniji " + nonArrayDecl.getLine() + ": ime " + varName + " je vec deklarisano.", null);
		} else {
			if (currClass != null && currMethod == null) {
				varNode = ExtendedTab.find(currClass.getName() + "." + varName);
				if (varNode != ExtendedTab.noObj) {
					report_error("Na liniji " + nonArrayDecl.getLine() + ": ime " + varName + " je vec deklarisano.",
							null);
					return;
				}
				if (isStaticVar) {
					varNode = new Obj(Obj.Var, currClass.getName() + "." + varName, currType);
					varNode.setLevel(0);
					ExtendedTab.currentScope.getOuter().addToLocals(varNode);
				} else {
					varNode = ExtendedTab.insert(Obj.Fld, varName, currType);
				}
			} else {
				varNode = ExtendedTab.insert(Obj.Var, varName, currType);
			}
		}
	}

	public void visit(ArrayDecl arrayDecl) {
		String varName;
		if (currNamespace == null || currMethod != null || currClass != null) {
			varName = arrayDecl.getVarName();
		} else {
			varName = currNamespace + "::" + arrayDecl.getVarName();
		}
		Obj varNode = ExtendedTab.currentScope.findSymbol(varName);
		if (varNode != null) {
			report_error("Na liniji " + arrayDecl.getLine() + ": ime " + varName + " je vec deklarisano.", null);
		} else {
			Struct arrayType = new Struct(Struct.Array, currType);
			if (currClass != null && currMethod == null) {
				varNode = ExtendedTab.find(currClass.getName() + "." + varName);
				if (varNode != ExtendedTab.noObj) {
					report_error("Na liniji " + arrayDecl.getLine() + ": ime " + varName + " je vec deklarisano.",
							null);
					return;
				}
				if (isStaticVar) {
					varNode = new Obj(Obj.Var, currClass.getName() + "." + varName, arrayType);
					varNode.setLevel(0);
					ExtendedTab.currentScope.getOuter().addToLocals(varNode);
				} else {
					varNode = ExtendedTab.insert(Obj.Fld, varName, arrayType);
				}
			} else {
				varNode = ExtendedTab.insert(Obj.Var, varName, arrayType);
			}
		}
	}

	public void visit(VarDecl varDecl) {
		currType = null;
	}

	// DesignatorStatement smene
	public void visit(DesignatorIncrement designatorIncrement) {
		Struct designatorStruct = designatorIncrement.getDesignator().obj.getType();
		if (designatorStruct != ExtendedTab.intType) {
			report_error("Na liniji " + designatorIncrement.getLine() + ": "
					+ designatorIncrement.getDesignator().obj.getName() + " mora biti tipa int.", null);
		} else if (designatorIncrement.getDesignator().obj.getKind() != Obj.Var
				&& designatorIncrement.getDesignator().obj.getKind() != Obj.Fld
				&& designatorIncrement.getDesignator().obj.getKind() != Obj.Elem) {
			report_error("Na liniji " + designatorIncrement.getLine() + ": "
					+ designatorIncrement.getDesignator().obj.getName() + " mora biti promenljiva.", null);
		}
	}

	public void visit(DesignatorDecrement designatorDecrement) {
		Struct designatorStruct = designatorDecrement.getDesignator().obj.getType();
		if (designatorStruct != ExtendedTab.intType) {
			report_error("Na liniji " + designatorDecrement.getLine() + ": "
					+ designatorDecrement.getDesignator().obj.getName() + " mora biti tipa int.", null);
		} else if (designatorDecrement.getDesignator().obj.getKind() != Obj.Var
				&& designatorDecrement.getDesignator().obj.getKind() != Obj.Fld
				&& designatorDecrement.getDesignator().obj.getKind() != Obj.Elem) {
			report_error("Na liniji " + designatorDecrement.getLine() + ": "
					+ designatorDecrement.getDesignator().obj.getName() + " mora biti promenljiva.", null);
		}
	}

	public void visit(DesignatorAssignment designatorAssignment) {
		Struct designatorStruct = designatorAssignment.getDesignator().obj.getType();
		if (!ExtendedTab.canAssign(designatorStruct, designatorAssignment.getExpr().struct)) {
			report_error("Na liniji " + designatorAssignment.getLine() + ": nekompatibilni tipovi u dodeli vrednosti.",
					null);
		}
	}

	public void visit(DesignatorFuncCall designatorFuncCall) {
		List<Struct> actualParams = stackActualParams.pop();
		if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getKind() != Obj.Meth) {
			report_error("Na liniji " + designatorFuncCall.getLine() + ": nedefinisana metoda "
					+ designatorFuncCall.getFuncCallStart().getDesignator().obj.getName() + ".", null);
		} else if (designatorFuncCall.getFuncCallStart().getDesignator().obj.getLevel() != actualParams.size()) {
			report_error("Na liniji " + designatorFuncCall.getLine() + ": metoda "
					+ designatorFuncCall.getFuncCallStart().getDesignator().obj.getName() + " ocekuje "
					+ designatorFuncCall.getFuncCallStart().getDesignator().obj.getLevel() + " parametara, a dobila je "
					+ actualParams.size() + ".", null);
		} else {
			int i = 0;
			for (Obj o : designatorFuncCall.getFuncCallStart().getDesignator().obj.getLocalSymbols()) {
				if (o.getName().equals("this")) {
					continue;
				}
				if (i >= actualParams.size()) {
					break;
				}
				if (!ExtendedTab.canAssign(o.getType(), actualParams.get(i))) {
					report_error("Na liniji " + designatorFuncCall.getLine() + ": parametar na poziciji " + (i + 1)
							+ " nije kompatibilan.", null);
				}
				i++;
			}
		}
		actualParams.clear();
	}

	// sve za DesignatorUnzip
	public void visit(DesignatorUnzipStart designatorUnzipStart) {
		elementsUnzip = new ArrayList<>();
	}

	public void visit(Des des) {
		if (des.getDesignator().obj.getKind() != Obj.Fld && des.getDesignator().obj.getKind() != Obj.Var
				&& des.getDesignator().obj.getKind() != Obj.Elem) {
			report_error("Na liniji " + des.getLine() + ": identifikator " + des.getDesignator().obj.getName()
					+ " ne oznacava promenljivu.", null);
			elementsUnzip.add(ExtendedTab.noType);
		} else {
			Struct desStruct = des.getDesignator().obj.getType();
			elementsUnzip.add(desStruct);
		}

	}

	public void visit(NoDesignator noDesignator) {
		elementsUnzip.add(null);
	}

	public void visit(DesignatorUnzip designatorUnzip) {
		Designator dstArray = designatorUnzip.getDesignator();
		Designator srcArray = designatorUnzip.getDesignator1();
		if (dstArray.obj.getType().getKind() != Struct.Array) {
			report_error("Na liniji " + designatorUnzip.getLine() + ": identifikator " + dstArray.obj.getName()
					+ " mora biti niz (nalazi se pod *).", null);
		} else if (srcArray.obj.getType().getKind() != Struct.Array) {
			report_error("Na liniji " + designatorUnzip.getLine() + ": identifikator " + srcArray.obj.getName()
					+ " mora biti niz.", null);
		} else if (!ExtendedTab.canAssign(dstArray.obj.getType().getElemType(), srcArray.obj.getType().getElemType())) {
			report_error("Na liniji " + designatorUnzip.getLine() + ": elementi niza " + dstArray.obj.getName()
					+ " i niza " + srcArray.obj.getName() + " nisu kompatibilni.", null);
		} else {
			boolean isCompatible = true;
			int pos = 0;
			for (Struct desStruct : elementsUnzip) {
				pos++;
				if (desStruct == null) {
					continue;
				}
				if (!ExtendedTab.canAssign(desStruct, srcArray.obj.getType().getElemType())) {
					isCompatible = false;
					report_error("Na liniji " + designatorUnzip.getLine() + ": element na poziciji " + (pos - 1)
							+ " nije kompatibilan za dodelu.", null);
				}
			}
		}
		elementsUnzip.clear();
	}

	// Designator smene
	public void visit(ScopeDesignatorName scopeDesignatorName) {
		extensionsStack.push(new ArrayList<>());
		String fullDesName = scopeDesignatorName.getScopeName() + "::" + scopeDesignatorName.getName();
		scopeDesignatorName.obj = ExtendedTab.find(fullDesName);
		if (scopeDesignatorName.obj == ExtendedTab.noObj) {
			report_error("Na liniji " + scopeDesignatorName.getLine() + ": ime " + fullDesName + " nije deklarisano.",
					null);
			currDesignatorObj = scopeDesignatorName.obj;
			return;
		}
		report_info("Pretraga na " + scopeDesignatorName.getLine() + "(" + fullDesName + "), nadjeno "
				+ ExtendedTab.printObj(scopeDesignatorName.obj), null);
		if (isStaticInitializer && scopeDesignatorName.obj.getKind() == Obj.Type
				&& !scopeDesignatorName.obj.getName().equals(currClass.getName())) {
			report_error("Na liniji " + scopeDesignatorName.getLine()
					+ ": u inicijalizatoru se mogu koristiti samo staticka polja trenutne klase.", null);
			scopeDesignatorName.obj = ExtendedTab.noObj;
			return;
		} else if (isStaticInitializer && scopeDesignatorName.obj.getKind() != Obj.Con) {
			report_error("Na liniji " + scopeDesignatorName.getLine()
					+ ": u inicijalizatoru se mogu koristiti samo staticka polja trenutne klase.", null);
			scopeDesignatorName.obj = ExtendedTab.noObj;
			return;
		}

		currDesignatorObj = scopeDesignatorName.obj;
	}

	public void visit(NoScopeDesignatorName noScopeDesignatorName) {
		extensionsStack.push(new ArrayList<>());
		/*
		 * noScopeDesignatorName.obj =
		 * ExtendedTab.find(noScopeDesignatorName.getName()); if (currNamespace != null)
		 * { noScopeDesignatorName.obj = noScopeDesignatorName.obj == ExtendedTab.noObj
		 * ? ExtendedTab.find(currNamespace + "::" + noScopeDesignatorName.getName()) :
		 * noScopeDesignatorName.obj; }
		 * 
		 * if (noScopeDesignatorName.obj == ExtendedTab.noObj) { report_error("Ime " +
		 * noScopeDesignatorName.getName() + " nije deklarisano!", null);
		 * noScopeDesignatorName.obj = ExtendedTab.noObj; return; } currDesignatorObj =
		 * noScopeDesignatorName.obj; if (currClass != null &&
		 * ExtendedTab.currentScope.getOuter().findSymbol(currDesignatorObj.getName())
		 * != null && ExtendedTab.currentScope.findSymbol(currDesignatorObj.getName())
		 * == null) { currDesignatorObj.setFpPos(100); }
		 */

		Obj localVar = null, localMember = null, staticMember = null, namespaceVar = null, globalVar = null;
		globalVar = ExtendedTab.find(noScopeDesignatorName.getName());
		if (globalVar == ExtendedTab.noObj) {
			globalVar = null;
		}
		if (currNamespace != null) {
			namespaceVar = ExtendedTab.find(currNamespace + "::" + noScopeDesignatorName.getName());
			if (namespaceVar == ExtendedTab.noObj) {
				namespaceVar = null;
			}
		}
		if (currClass != null) {
			staticMember = ExtendedTab.find(currClass.getName() + "." + noScopeDesignatorName.getName());
			if (staticMember == ExtendedTab.noObj) {
				staticMember = null;
			}
		}
		if (currClass != null && currMethod != null) {
			localMember = ExtendedTab.currentScope.getOuter().findSymbol(noScopeDesignatorName.getName());
		}
		if (currMethod != null) {
			localVar = ExtendedTab.currentScope.findSymbol(noScopeDesignatorName.getName());
		}

		if (localVar != null) {
			noScopeDesignatorName.obj = localVar;
		} else if (localMember != null) {
			noScopeDesignatorName.obj = localMember;
		} else if (staticMember != null) {
			noScopeDesignatorName.obj = staticMember;
		} else if (namespaceVar != null) {
			noScopeDesignatorName.obj = namespaceVar;
		} else if (globalVar != null) {
			noScopeDesignatorName.obj = globalVar;
		} else {
			noScopeDesignatorName.obj = ExtendedTab.noObj;
			report_error("Na liniji " + noScopeDesignatorName.getLine() + ": ime " + noScopeDesignatorName.getName()
					+ " nije deklarisano.", null);
			return;
		}

		report_info("Pretraga na " + noScopeDesignatorName.getLine() + "(" + noScopeDesignatorName.obj.getName()
				+ "), nadjeno "
				+ ((currMethod != null && currMethod.getLevel() > noScopeDesignatorName.obj.getAdr()
						&& noScopeDesignatorName.obj.getKind() == Obj.Var) ? "{Formalni argument} " : "")
				+ ExtendedTab.printObj(noScopeDesignatorName.obj), null);

		if (isStaticInitializer && staticMember == null
				&& (globalVar == null || globalVar.getKind() != Obj.Type
						|| !globalVar.getName().equals(currClass.getName()))
				&& (namespaceVar == null || namespaceVar.getKind() != Obj.Type
						|| !namespaceVar.getName().equals(currClass.getName()))) {

			if (noScopeDesignatorName.obj.getKind() != Obj.Con) {
				report_error("Na liniji " + noScopeDesignatorName.getLine()
						+ ": u inicijalizatoru se mogu koristiti samo staticka polja trenutne klase.", null);
				noScopeDesignatorName.obj = ExtendedTab.noObj;
				currDesignatorObj = ExtendedTab.noObj;
				return;
			}
		}

		currDesignatorObj = noScopeDesignatorName.obj;

		if (currClass != null && ExtendedTab.currentScope.getOuter().findSymbol(currDesignatorObj.getName()) != null
				&& ExtendedTab.currentScope.findSymbol(currDesignatorObj.getName()) == null && !isStaticInitializer) {
			currDesignatorObj.setFpPos(100);
		}
	}

	public void visit(SingleDesignatorExtensionClassField singleDesignatorExtensionClassField) {
		extensionsStack.peek().add(singleDesignatorExtensionClassField.getFieldName());
	}

	public void visit(SingleDesignatorExtensionArray singleDesignatorExtensionArray) {
		if (singleDesignatorExtensionArray.getExpr().struct != ExtendedTab.intType) {
			extensionsStack.peek().add("1err");
		} else {
			extensionsStack.peek().add("[]");
		}
	}

	public void visit(Designator designator) {
		Obj currDesignatorObj = designator.getDesignatorName().obj;
		List<String> extensions = extensionsStack.pop();
		/*
		 * if (isStaticInitializer) { // ovo znaci da se prvi deo designatora odnosi na
		 * klasu u kojoj smo if
		 * (currClass.getName().equals(currDesignatorObj.getName())) { if
		 * (extensions.size() > 0) { if (extensions.get(0).equals("[]") ||
		 * extensions.get(0).equals("1err")) { report_error("Ne moze se indeksirati!",
		 * designator); designator.obj = ExtendedTab.noObj; return; } else if
		 * (ExtendedTab.find(currClass.getName() + "." + extensions.get(0)) !=
		 * ExtendedTab.noObj){ currDesignatorObj = ExtendedTab.find(currClass.getName()
		 * + "." + extensions.get(0)); } else { report_error("To polje nije staticko!",
		 * designator); designator.obj = ExtendedTab.noObj; return; } } else {
		 * report_error("Ne moze koristiti samo klasa kao promenljiva", designator);
		 * designator.obj = ExtendedTab.noObj; return; } } else if
		 * (ExtendedTab.find(currClass.getName() + "." + currDesignatorObj.getName()) ==
		 * ExtendedTab.noObj) { report_error("To polje nije staticko!", designator);
		 * designator.obj = ExtendedTab.noObj; return; } }
		 */
		if (extensions.size() == 0 && currDesignatorObj.getKind() == Obj.Type) {
			report_error("Na liniji " + designator.getLine() + ": ime " + currDesignatorObj.getName()
					+ " je klasa i ne moze se koristiti kao promenljiva.", null);
			designator.obj = ExtendedTab.noObj;
			return;
		}
		for (String ext : extensions) {
			if (ext.equals("1err")) {
				report_error("Na liniji " + designator.getLine() + ": nevalidan izraz u []", null);
				currDesignatorObj = ExtendedTab.noObj;
				break;
			} else if (ext.equals("[]")) {
				if (currDesignatorObj.getType().getKind() == Struct.Array) {
					String arrayName = currDesignatorObj.getName();
					currDesignatorObj = new Obj(Obj.Elem, currDesignatorObj.getName(),
							currDesignatorObj.getType().getElemType(), currDesignatorObj.getAdr(),
							currDesignatorObj.getLevel());
					report_info("Indeksiran niz " + arrayName + " na " + designator.getLine() + ", nadjeno "
							+ ExtendedTab.printObj(currDesignatorObj), null);
				} else {
					report_error(
							"Na liniji " + designator.getLine() + ": " + currDesignatorObj.getName() + " nije niz.",
							null);
					currDesignatorObj = ExtendedTab.noObj;
					break;
				}
			} else {
				if (currDesignatorObj.getKind() == Obj.Type) {
					currDesignatorObj = ExtendedTab.find(currDesignatorObj.getName() + "." + ext);
					if (currDesignatorObj == ExtendedTab.noObj) {
						report_error("Na liniji " + designator.getLine() + ": polje " + ext
								+ " nije staticko polje klase " + currDesignatorObj.getName() + ".", null);
						currDesignatorObj = ExtendedTab.noObj;
						break;
					}
					report_info("Koriscenje statickog polja " + ext + " klase "
							+ currDesignatorObj.getName().split("\\.")[0] + " na " + designator.getLine() + ", nadjeno "
							+ ExtendedTab.printObj(currDesignatorObj), null);
				} else if (currDesignatorObj.getType().getKind() == Struct.Class) {
					if (currDesignatorObj.getType().getMembersTable().searchKey(ext) != null) {

						// odje stv nikad ne bi trebalo da udje.. zato sto se samo pristupa kao
						// Klasa.polje
						if (currDesignatorObj.getKind() == Obj.Type) {
							if (currDesignatorObj.getType().getMembersTable().searchKey(ext).getFpPos() == 1) {
								currDesignatorObj = currDesignatorObj.getType().getMembersTable().searchKey(ext);
							} else {
								report_error("Polje nije staticko!", designator);
								currDesignatorObj = ExtendedTab.noObj;
								break;
							}
						} else {
							String className = currDesignatorObj.getName();
							currDesignatorObj = currDesignatorObj.getType().getMembersTable().searchKey(ext);
							report_info("Koriscenje " + (currDesignatorObj.getKind() == Obj.Meth ? "metode " : "polja ")
									+ ext + " klase " + className + " na " + designator.getLine() + ", nadjeno "
									+ ExtendedTab.printObj(currDesignatorObj), null);

						}
					} else if (ExtendedTab.currentScope.getOuter().findSymbol(ext) != null) {
						currDesignatorObj = ExtendedTab.currentScope.getOuter().findSymbol(ext);
					} else {
						report_error("Na liniji " + designator.getLine() + ": ne postoji polje " + ext + " objekta "
								+ currDesignatorObj.getName() + ".", null);
						currDesignatorObj = ExtendedTab.noObj;
						break;
					}
				} else {
					report_error("Na liniji " + designator.getLine() + ": identifikator " + currDesignatorObj.getName()
							+ " nije objekat klase.", null);
					currDesignatorObj = ExtendedTab.noObj;
					break;
				}
			}
		}
		designator.obj = currDesignatorObj;
	}

	// Expr smene
	public void visit(StartExpr startExpr) {
		factorListStack.push(new ArrayList<Struct>());
		termListStack.push(new ArrayList<Struct>());
	}

	public void visit(NegativeExpr negativeExpr) {
		boolean allIntegers = true;
		for (Struct t : termListStack.peek()) {
			if (t != ExtendedTab.intType) {
				allIntegers = false;
				report_error("Na liniji " + negativeExpr.getLine()
						+ ": operandi aritmetickog izraza moraju da budu tipa int.", null);
				break;
			}
		}
		if (allIntegers) {
			negativeExpr.struct = ExtendedTab.intType;
		} else {
			negativeExpr.struct = ExtendedTab.noType;
		}
		termListStack.pop();
		factorListStack.pop();
	}

	public void visit(PositiveExpr positiveExpr) {
		if (termListStack.peek().size() == 1) {
			positiveExpr.struct = termListStack.peek().get(0);
		} else {
			boolean allIntegers = true;
			for (Struct t : termListStack.peek()) {
				if (t != ExtendedTab.intType) {
					allIntegers = false;
					report_error("Na liniji " + positiveExpr.getLine()
							+ ": operandi aritmetickog izraza moraju da budu tipa int.", null);
					break;
				}
			}
			if (allIntegers) {
				positiveExpr.struct = ExtendedTab.intType;
			} else {
				positiveExpr.struct = ExtendedTab.noType;
			}
		}
		termListStack.pop();
		factorListStack.pop();
	}

	// Factor smene
	public void visit(NumberFactor numberFactor) {
		numberFactor.struct = ExtendedTab.intType;
		factorListStack.peek().add(numberFactor.struct);
	}

	public void visit(CharFactor charFactor) {
		charFactor.struct = ExtendedTab.charType;
		factorListStack.peek().add(charFactor.struct);
	}

	public void visit(SubExprFactor subExprFactor) {
		if (subExprFactor.getExpr().struct != ExtendedTab.intType) {
			report_error("Na liniji " + subExprFactor.getLine() + ": izraz u zagradama mora biti tipa int.", null);
		} else {
			subExprFactor.struct = ExtendedTab.intType;
			factorListStack.peek().add(subExprFactor.struct);
		}
	}

	public void visit(BoolFactor boolFactor) {
		boolFactor.struct = ExtendedTab.boolType;
		factorListStack.peek().add(boolFactor.struct);
	}

	public void visit(DesignatorFactor designatorFactor) {
		designatorFactor.struct = designatorFactor.getDesignator().obj.getType();
		factorListStack.peek().add(designatorFactor.struct);
	}

	public void visit(ArrayFactor arrayFactor) {
		if (arrayFactor.getExpr().struct != ExtendedTab.intType) {
			report_error("Na liniji " + arrayFactor.getLine() + ": izraz u [] mora biti tipa int.", null);
		}
		arrayFactor.struct = new Struct(Struct.Array, arrayFactor.getType().struct);
		factorListStack.peek().add(arrayFactor.struct);
	}

	public void visit(ClassFactor classFactor) {
		classFactor.struct = classFactor.getType().struct;
		factorListStack.peek().add(classFactor.struct);
	}

	public void visit(FuncFactor funcFactor) {
		List<Struct> actualParams = stackActualParams.pop();
		if (funcFactor.getFuncCallStart().getDesignator().obj.getKind() != Obj.Meth) {
			report_error(
					"Na liniji " + funcFactor.getLine() + ": identifikator "
							+ funcFactor.getFuncCallStart().getDesignator().obj.getName() + " ne predstavlja metodu.",
					null);
		} else if (funcFactor.getFuncCallStart().getDesignator().obj.getLevel() != actualParams.size()) {
			report_error("Na liniji " + funcFactor.getLine() + ": metoda "
					+ funcFactor.getFuncCallStart().getDesignator().obj.getName() + " ocekuje "
					+ funcFactor.getFuncCallStart().getDesignator().obj.getLevel() + " parametara, a dobila je "
					+ actualParams.size() + ".", null);
		} else {
			if (funcFactor.getFuncCallStart().getDesignator().obj.getName().equals("len")) {
				if (actualParams.get(0).getKind() == Struct.Array) {
					return;
				}
			}
			int i = 0;
			for (Obj o : funcFactor.getFuncCallStart().getDesignator().obj.getLocalSymbols()) {
				if (o.getName().equals("this")) {
					continue;
				}
				if (i >= actualParams.size()) {
					break;
				}
				if (!ExtendedTab.canAssign(o.getType(), actualParams.get(i))) {
					report_error("Na liniji " + funcFactor.getLine() + ": parametar na poziciji " + (i + 1)
							+ " nije kompatibilan.", null);
				}
				i++;
			}
		}
		actualParams.clear();
		funcFactor.struct = funcFactor.getFuncCallStart().getDesignator().obj.getType();
		factorListStack.peek().add(funcFactor.struct);
	}

	public void visit(FuncCallStart funcCallStart) {
		stackActualParams.push(new ArrayList<Struct>());
	}

	// Term smene
	public void visit(Term term) {
		if (factorListStack.peek().size() == 1) {
			term.struct = factorListStack.peek().get(0);
		} else {
			boolean allIntegers = true;
			for (Struct f : factorListStack.peek()) {
				if (f != ExtendedTab.intType) {
					allIntegers = false;
					report_error(
							"Na liniji " + term.getLine() + ": operandi aritmetickog izraza moraju da budu tipa int.",
							null);
					break;
				}
			}
			if (allIntegers) {
				term.struct = ExtendedTab.intType;
			} else {
				term.struct = ExtendedTab.noType;
			}
		}
		factorListStack.peek().clear();
		termListStack.peek().add(term.struct);
	}

	// Statement smene
	public void visit(ReadStmt readStmt) {
		Struct designatorStruct = readStmt.getDesignator().obj.getType();
		if (designatorStruct != ExtendedTab.intType && designatorStruct != ExtendedTab.charType
				&& designatorStruct != ExtendedTab.boolType) {
			report_error("Na liniji " + readStmt.getLine() + ": " + readStmt.getDesignator().obj.getName()
					+ " mora biti int, char ili bool.", null);
		} else if (readStmt.getDesignator().obj.getKind() != Obj.Var
				&& readStmt.getDesignator().obj.getKind() != Obj.Fld
				&& readStmt.getDesignator().obj.getKind() != Obj.Elem) {
			report_error("Na liniji " + readStmt.getLine() + ": " + readStmt.getDesignator().obj.getName()
					+ " mora biti promenljiva.", null);
		}
	}

	public void visit(PrintSingleStmt printSingleStmt) {
		if (printSingleStmt.getExpr().struct != ExtendedTab.intType
				&& printSingleStmt.getExpr().struct != ExtendedTab.charType
				&& printSingleStmt.getExpr().struct != ExtendedTab.boolType) {
			report_error(
					"Na liniji " + printSingleStmt.getLine() + ": izraz sa ispis mora biti tipa int, char ili bool.",
					null);
		}
	}

	public void visit(PrintMultipleStmt printMultipleStmt) {
		if (printMultipleStmt.getExpr().struct != ExtendedTab.intType
				&& printMultipleStmt.getExpr().struct != ExtendedTab.charType
				&& printMultipleStmt.getExpr().struct != ExtendedTab.boolType) {
			report_error(
					"Na liniji " + printMultipleStmt.getLine() + ": izraz sa ispis mora biti tipa int, char ili bool.",
					null);
		}
	}

	public void visit(ReturnVoid returnVoid) {
		if (currMethod == null) {
			report_error("Na liniji " + returnVoid.getLine() + ": return iskaz van metode.", null);
		} else if (currMethod.getType() != ExtendedTab.noType) {
			report_error("Na liniji " + returnVoid.getLine() + ": return iskazu fali vrednost koja se vraca.", null);
		}
	}

	public void visit(ReturnNonVoid returnNonVoid) {
		if (currMethod == null) {
			report_error("Na liniji " + returnNonVoid.getLine() + ": return iskaz van metode.", null);
		} else if (currMethod.getType() == ExtendedTab.noType) {
			report_error("Na liniji " + returnNonVoid.getLine()
					+ ": return iskaz ima povratnu vrednost, a metoda je tipa void.", null);
		} else if (!ExtendedTab.canAssign(currMethod.getType(), returnNonVoid.getExpr().struct)) {
			report_error("Na liniji " + returnNonVoid.getLine()
					+ ": nekompatibilan tip izraza sa tipom povratne vrednosti metode.", null);
		}
	}

	public void visit(IfStmt ifStmt) {
		if (ifStmt.getCondition().struct != ExtendedTab.boolType) {
			report_error("Na liniji " + ifStmt.getLine() + ": uslovni izraz u if-u nije logickog tipa.", null);
		}
	}

	public void visit(IfElseStmt ifElseStmt) {
		if (ifElseStmt.getCondition().struct != ExtendedTab.boolType) {
			report_error("Na liniji " + ifElseStmt.getLine() + ": uslovni izraz u if-u nije logickog tipa.", null);
		}
	}

	public void visit(ForLoopStart forLoopStart) {
		forLevel++;
	}

	public void visit(ForConditionStmt forConditionStmt) {
		if (forConditionStmt.getCondFact().struct != ExtendedTab.boolType) {
			report_error("Na liniji " + forConditionStmt.getLine() + ": uslovni izraz u for-u nije logickog tipa.",
					null);
		}
		forLevel--;
	}

	public void visit(ForNoConditionStmt forNoConditionStmt) {
		forLevel--;
	}

	public void visit(BreakStmt breakStmt) {
		if (forLevel == 0) {
			report_error("Na liniji " + breakStmt.getLine() + ": pozvan break van for-a.", null);
		}
	}

	public void visit(ContinueStmt continueStmt) {
		if (forLevel == 0) {
			report_error("Na liniji " + continueStmt.getLine() + ": pozvan continue van for-a.", null);
		}
	}

	// sve vezano za MethodDecl
	public void visit(TypeMethodName typeMethodName) {
		String fullMethName;
		if (currNamespace == null || currClass != null) {
			fullMethName = typeMethodName.getMethName();
		} else {
			fullMethName = currNamespace + "::" + typeMethodName.getMethName();
		}
		Obj methNode = ExtendedTab.currentScope.findSymbol(fullMethName);
		if (methNode != null && currClass == null) {
			report_error("Na liniji " + typeMethodName.getLine() + ": ime " + typeMethodName.getMethName()
					+ " je vec deklarisano.", null);
			typeMethodName.obj = ExtendedTab.noObj;
			currMethod = ExtendedTab.noObj;
		} else {
			if (methNode != null && currClass != null) {
				for (Obj o : methNode.getLocalSymbols()) {
					// redefinicija
					if (o.getName().equals("this") && o.getType() != currClass.getType()) {
						break;
					} else {
						report_error("Na liniji " + typeMethodName.getLine() + ": ime " + typeMethodName.getMethName()
								+ " je vec deklarisano.", null);
						typeMethodName.obj = ExtendedTab.noObj;
						currMethod = ExtendedTab.noObj;
						return;
					}
				}
				methNode.getLocalSymbols().clear();
			}
			currMethod = ExtendedTab.insert(Obj.Meth, fullMethName, typeMethodName.getType().struct);
			currMethod.getLocalSymbols().clear();
			currMethod.setLevel(0); // ovde se broje formalni argumenti ig
			ExtendedTab.openScope();
			if (currClass != null) {
				ExtendedTab.insert(Obj.Var, "this", currClass.getType());
			}
			typeMethodName.obj = currMethod;
		}
	}

	public void visit(VoidMethodName voidMethodName) {
		String fullMethName;
		if (currNamespace == null || currClass != null) {
			fullMethName = voidMethodName.getMethName();
		} else {
			fullMethName = currNamespace + "::" + voidMethodName.getMethName();
		}
		Obj methNode = ExtendedTab.currentScope.findSymbol(fullMethName);
		if (methNode != null && currClass == null) {
			report_error("Na liniji " + voidMethodName.getLine() + ": ime " + voidMethodName.getMethName()
					+ " je vec deklarisano.", null);
			voidMethodName.obj = ExtendedTab.noObj;
			currMethod = ExtendedTab.noObj;
		} else {
			if (methNode != null && currClass != null) {
				for (Obj o : methNode.getLocalSymbols()) {
					// redefinicija
					if (o.getName().equals("this") && o.getType() != currClass.getType()) {
						break;
					} else {
						report_error("Na liniji " + voidMethodName.getLine() + ": ime " + voidMethodName.getMethName()
								+ " je vec deklarisano.", null);
						voidMethodName.obj = ExtendedTab.noObj;
						currMethod = ExtendedTab.noObj;
						return;
					}
				}
				methNode.getLocalSymbols().clear();
			}
			currMethod = ExtendedTab.insert(Obj.Meth, fullMethName, ExtendedTab.noType);
			currMethod.getLocalSymbols().clear();
			currMethod.setLevel(0); // ovde se broje formalni argumenti ig
			ExtendedTab.openScope();
			if (currClass != null) {
				ExtendedTab.insert(Obj.Var, "this", currClass.getType());
			}
			voidMethodName.obj = currMethod;
		}
	}

	public void visit(FormParNonArray formParNonArray) {
		Obj formParNode = ExtendedTab.currentScope.findSymbol(formParNonArray.getVarName());
		if (formParNode != null) {
			report_error("Na liniji " + formParNonArray.getLine() + ": ime " + formParNonArray.getVarName()
					+ " je vec deklarisano.", null);
		} else {
			formParNode = ExtendedTab.insert(Obj.Var, formParNonArray.getVarName(), formParNonArray.getType().struct);
			currMethod.setLevel(currMethod.getLevel() + 1);
		}
	}

	public void visit(FormParArray formParArray) {
		Obj formParNode = ExtendedTab.currentScope.findSymbol(formParArray.getVarName());
		if (formParNode != null) {
			report_error("Na liniji " + formParArray.getLine() + ": ime " + formParArray.getVarName()
					+ " je vec deklarisano.", null);
		} else {
			Struct array = new Struct(Struct.Array, formParArray.getType().struct);
			formParNode = ExtendedTab.insert(Obj.Var, formParArray.getVarName(), array);
			currMethod.setLevel(currMethod.getLevel() + 1);
		}
	}

	public void visit(MethodDecl methodDecl) {
		methodDecl.obj = currMethod;
		ExtendedTab.chainLocalSymbols(currMethod);
		ExtendedTab.closeScope();
		currMethod = null;
	}

	public void visit(OneActPar oneActPar) {
		stackActualParams.peek().add(oneActPar.getExpr().struct);
	}

	// sve vezano za condition
	public void visit(CondFactNoRelop condFactNoRelop) {
		if (condFactNoRelop.getExpr().struct != ExtendedTab.boolType) {
			report_error("Na liniji " + condFactNoRelop.getLine() + ": izraz mora biti logickog tipa.", null);
			condFactNoRelop.struct = ExtendedTab.noType;
		} else {
			condFactNoRelop.struct = ExtendedTab.boolType;
		}
	}

	public void visit(CondFactRelop condFactRelop) {
		Struct expr1Struct = condFactRelop.getExpr().struct;
		Struct expr2Struct = condFactRelop.getExpr1().struct;
		Relop relop = condFactRelop.getRelop();
		if (!expr1Struct.compatibleWith(expr2Struct)) {
			report_error("Na liniji " + condFactRelop.getLine() + ": izrazi nisu kompatibilni pa se ne mogu uporediti.",
					null);
			condFactRelop.struct = ExtendedTab.noType;
		} else if ((expr1Struct.getKind() == Struct.Class || expr1Struct.getKind() == Struct.Array)
				&& (relop instanceof Grt || relop instanceof Gre || relop instanceof Lss || relop instanceof Lse)) {
			report_error("Na liniji " + condFactRelop.getLine()
					+ ": za uporedjivanje klasa i nizova mogu se samo koristiti == i !=", null);
			condFactRelop.struct = ExtendedTab.noType;
		} else {
			condFactRelop.struct = ExtendedTab.boolType;
		}
	}

	public void visit(CondFact condFact) {
		condFactorList.add(condFact.struct);
	}

	public void visit(CondTerm condTerm) {
		boolean allBools = true;
		for (Struct f : condFactorList) {
			if (f != ExtendedTab.boolType) {
				allBools = false;
				break;
			}
		}
		if (allBools) {
			condTerm.struct = ExtendedTab.boolType;
		} else {
			condTerm.struct = ExtendedTab.noType;
		}
		condFactorList.clear();
		condTermList.add(condTerm.struct);
	}

	public void visit(Condition condition) {
		boolean allBools = true;
		for (Struct t : condTermList) {
			if (t != ExtendedTab.boolType) {
				allBools = false;
				break;
			}
		}
		if (allBools) {
			condition.struct = ExtendedTab.boolType;
		} else {
			condition.struct = ExtendedTab.noType;
		}
		condFactorList.clear();
		condTermList.clear();
	}

	// sve vezano za ClassDecl
	public void visit(BaseClassDeclStart baseClassDeclStart) {
		String fullClassName;
		if (currNamespace == null) {
			fullClassName = baseClassDeclStart.getClassName();
		} else {
			fullClassName = currNamespace + "::" + baseClassDeclStart.getClassName();
		}
		currClass = ExtendedTab.find(fullClassName);
		if (currClass != ExtendedTab.noObj) {
			report_error(
					"Na liniji: " + baseClassDeclStart.getLine() + ": ime " + fullClassName + " je vec deklarisano.",
					null);
		} else {
			Struct classStruct = new Struct(Struct.Class);
			currClass = ExtendedTab.insert(Obj.Type, fullClassName, classStruct);
			baseClassDeclStart.obj = currClass;
			ExtendedTab.openScope();
			ExtendedTab.insert(Obj.Fld, "tvf", ExtendedTab.intType);
		}
	}

	public void visit(ExtendedClassDeclStart extendedClassDeclStart) {
		String fullClassName;
		if (currNamespace == null) {
			fullClassName = extendedClassDeclStart.getClassName();
		} else {
			fullClassName = currNamespace + "::" + extendedClassDeclStart.getClassName();
		}
		currClass = ExtendedTab.find(fullClassName);
		if (currClass != ExtendedTab.noObj) {
			report_error("Na liniji: " + extendedClassDeclStart.getLine() + ": ime " + fullClassName
					+ " je vec deklarisano.", null);
		} else if (extendedClassDeclStart.getType().struct.getKind() != Struct.Class) {
			report_error(
					"Na liniji " + extendedClassDeclStart.getLine() + ": ne moze se izvoditi iz necega sto nije klasa.",
					null);
		} else {
			Struct classStruct = new Struct(Struct.Class, extendedClassDeclStart.getType().struct);
			classStruct.setElementType(extendedClassDeclStart.getType().struct);
			currClass = ExtendedTab.insert(Obj.Type, fullClassName, classStruct);
			extendedClassDeclStart.obj = currClass;
			ExtendedTab.openScope();
			ExtendedTab.insert(Obj.Fld, "tvf", ExtendedTab.intType);
			for (Obj field : extendedClassDeclStart.getType().struct.getMembers()) {
				if (field.getKind() == Obj.Fld) {
					ExtendedTab.insert(Obj.Fld, field.getName(), field.getType());
				} else {
					break;
				}
			}
		}
	}

	public void visit(ClassDecl classDecl) {
		for (Obj o : ExtendedTab.currentScope.values()) {
			if (o.getKind() == Obj.Meth) {
				boolean redefined = true;
				for (Obj var : o.getLocalSymbols()) {
					if (var.getName().equals("this") && var.getType() != currClass.getType()) {
						redefined = false;
					}
					break;
				}

				if (!redefined) {
					List<Obj> formalParams = new ArrayList<>();
					for (Obj var : o.getLocalSymbols()) {
						if (var.getName().equals("this")) {
							formalParams.add(new Obj(Obj.Var, "this", currClass.getType()));
						} else {
							formalParams.add(new Obj(Obj.Var, var.getName(), var.getType()));
						}
					}
					o.getLocalSymbols().clear();
					Obj meth = ExtendedTab.insert(Obj.Meth, o.getName(), o.getType());
					meth.setLevel(0);
					ExtendedTab.openScope();
					for (Obj var : formalParams) {
						if (var.getName().equals("this")) {
							ExtendedTab.currentScope.addToLocals(var);
						} else {
							ExtendedTab.insert(var.getKind(), var.getName(), var.getType());
							meth.setLevel(meth.getLevel() + 1);
						}
					}
					ExtendedTab.chainLocalSymbols(meth);
					ExtendedTab.closeScope();
				}
			}
		}
		classDecl.obj = classDecl.getClassDeclStart().obj;
		ExtendedTab.chainLocalSymbols(currClass.getType());
		ExtendedTab.closeScope();
		currClass = null;
	}

	public void visit(ClassVarDeclList classVarDeclList) {
		if (currClass != null) {
			if (currClass.getType().getElemType() != null) {
				for (Obj o : currClass.getType().getElemType().getMembers()) {
					if (o.getKind() == Obj.Meth && ExtendedTab.currentScope.findSymbol(o.getName()) == null) {
						Obj meth = ExtendedTab.insert(Obj.Meth, o.getName(), o.getType());
						meth.setLevel(0);
						ExtendedTab.openScope();
						for (Obj var : o.getLocalSymbols()) {
							if (var.getName().equals("this")) {
								ExtendedTab.currentScope.addToLocals(var);
							} else {
								ExtendedTab.insert(var.getKind(), var.getName(), var.getType());
								meth.setLevel(meth.getLevel() + 1);
							}
						}
						ExtendedTab.chainLocalSymbols(meth);
						ExtendedTab.closeScope();
					}
				}
			}
		}

	}

	// sve za Static stvari
	public void visit(StaticVarDeclStart staticVarDeclStart) {
		isStaticVar = true;
	}

	public void visit(StaticVarDecl staticVarDecl) {
		isStaticVar = false;
	}

	public void visit(OneStaticInitializerStart oneStaticInitializerStart) {
		isStaticInitializer = true;
	}

	public void visit(StaticInitializer staticInitializer) {
		isStaticInitializer = false;
	}

	public boolean passed() {
		return !errorDetected;
	}

}
