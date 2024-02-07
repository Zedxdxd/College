package rs.ac.bg.etf.pp1;

import rs.etf.pp1.symboltable.Tab;
import rs.etf.pp1.symboltable.concepts.Obj;
import rs.etf.pp1.symboltable.concepts.Scope;
import rs.etf.pp1.symboltable.concepts.Struct;

public class ExtendedTab extends Tab {

	public static final Struct boolType = new Struct(Struct.Bool);

	public static void init() {
		Tab.init();

		Scope universe = Tab.currentScope;
		universe.addToLocals(new Obj(Obj.Type, "bool", boolType));
		universe.addToLocals(new Obj(Obj.Con, "true", boolType, 1, 0));
		universe.addToLocals(new Obj(Obj.Con, "false", boolType, 0, 0));
	}

	public static boolean canAssign(Struct dst, Struct src) {

		if (dst.isRefType() && src == ExtendedTab.nullType) {
			return true;
		} else {
			if (dst.getKind() == Struct.Class && src.getKind() == Struct.Class) {
				boolean isSubclass = false;
				Struct curr = src;
				while (curr != null) {
					if (dst == curr) {
						isSubclass = true;
						return true;
					}
					curr = curr.getElemType();
				}
				return false;
			}
		}
		if (src.equals(dst)) {
			return true;
		}
		return false;
	}

	public static String printObj(Obj obj) {
		StringBuilder res = new StringBuilder();
		switch (obj.getKind()) {
		case Obj.Con:
			res.append("Simbolicka konstanta -> ");
			res.append("Con ");
			break;
		case Obj.Var:
			if (obj.getLevel() == 0) {
				res.append("Globalna " + (obj.getName().indexOf('.') == -1 ? "" : "staticka") + " promenljiva -> ");
			}
			else {
				res.append("Lokalna promenljiva -> ");
			}
			res.append("Var ");
			break;
		case Obj.Type:
			res.append("Type ");
			break;
		case Obj.Meth:
			res.append("Poziv metode -> ");
			res.append("Meth ");
			break;
		case Obj.Fld:
			res.append("Fld ");
			break;
		case Obj.Prog:
			res.append("Prog ");
			break;
		case Obj.Elem:
			res.append("Element niza -> ");
			res.append("Elem ");
			break;
		}
		res.append(obj.getName());
		res.append(": ");

		res.append(printStruct(obj.getType()));

		res.append(", ");
		res.append(obj.getAdr());
		res.append(", ");
		res.append(obj.getLevel());

		return res.toString();
	}

	public static String printStruct(Struct struct) {
		StringBuilder res = new StringBuilder();
		switch (struct.getKind()) {
		case Struct.None:
			res.append("notype");
			break;
		case Struct.Int:
			res.append("int");
			break;
		case Struct.Char:
			res.append("char");
			break;
		case Struct.Array:
			res.append("Arr of ");

			switch (struct.getElemType().getKind()) {
			case Struct.None:
				res.append("notype");
				break;
			case Struct.Int:
				res.append("int");
				break;
			case Struct.Char:
				res.append("char");
				break;
			case Struct.Class:
				res.append("Class");
				break;
			}
			break;
		case Struct.Class:
			res.append("Class [");
			for (Obj obj : struct.getMembers()) {
				printObj(obj);
			}
			res.append("]");
			break;
		}
		return res.toString();
	}

}
