package Olympiad;

import java.util.ArrayList;

public class Sport {

	private ArrayList<Discipline> disciplines; // disciplines that belong to this sport
	private String name; // name of the sport
	
	public Sport(String n) {
		name = n;
		disciplines = new ArrayList<Discipline>();
	}
	
	public Discipline findDiscipline(Discipline discipline) {
		if (disciplines.contains(discipline)) {
			return discipline;
		}
		else {
			return null;
		}
	}
	
	public String getName() {
		return name;
	}
	
	public void addDiscipline(Discipline discipline) {
		disciplines.add(discipline);
	}
	
	@Override
	public boolean equals(Object o) {
		if (!(o instanceof Sport)) {
			return false;
		}
		Sport s = (Sport)o;
		return s.name.equals(name);
		
	}
	
}
