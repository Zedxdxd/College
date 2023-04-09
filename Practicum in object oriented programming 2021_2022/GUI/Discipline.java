package Olympiad;

public class Discipline {
	
	public enum Type {INDIVIDUAL, TEAM;
		
		@Override
		public String toString() {
			if (this == INDIVIDUAL) {
				return "Individual";
			}
			else {
				return "Team";
			}
		}
	}
	
	private Type type; // Individual or Team
	private String name; // name of the discipline
	
	public Discipline(String n, Type e) {
		name = n;
		type = e;
	}
	
	@Override
	public boolean equals(Object o) {
		if (!(o instanceof Discipline)) {
			return false;
		}
		Discipline d = (Discipline)o;
		return d.name.equals(name) && d.type == type;
	}
	
	public Type getType() {
		return type;
	}
	
	public String getName() {
		return name;
	}

}
