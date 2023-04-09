package Olympiad;

import java.util.ArrayList;

public class Country {
	
	private ArrayList<Competitor> competitors; // competitors who competed for the country
	private String name; // name of the country
	
	public Country(String n) {
		name = n;
		competitors = new ArrayList<Competitor>();
	}
	
	@Override
	public boolean equals(Object o) {
		if (!(o instanceof Country)) {
			return false;
		}
		Country c = (Country)o;
		return c.name.equals(name);
	}
	
	public String getName() {
		return name;
	}

	public void addCompetitor(Competitor competitor) {
		competitors.add(competitor);
	}
	
	public ArrayList<Competitor> returnCompetitors(){
		return competitors;
	}

}
