package Olympiad;

public class Athlete {

	public enum Gender {M, F}
	
	// if there isn't information about athlete's age/height/weight (NA in file_athletes), it will be saves as zero
	private int ID, age;
	private double height, weight;
	private String name;
	private Gender gender;
	
	public Athlete(int id, String n, Gender g, int a, double h, double w) {
		ID = id;
		name = n;
		gender = g;
		age = a;
		height = h;
		weight = w;
	}
	
	@Override
	public boolean equals(Object o) {
		if (!(o instanceof Athlete)) {
			return false;
		}
		Athlete a = (Athlete)o;
		return a.ID == ID && a.age == age && a.height == height && a.weight == weight && a.name.equals(name)
				&& a.gender == gender;
	}
	
	public int getId() {
		return ID;
	}
	
	public double getWeight() {
		return weight;
	}
	
	public double getHeight() {
		return height;
	}
}
