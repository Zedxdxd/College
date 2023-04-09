package Olympiad;

import java.util.ArrayList;

public class Competitor {
	
	public enum Medal {NONE, GOLD, SILVER, BRONZE;
		
		@Override
		public String toString() {
		if (this == NONE) {
			return "None";
		}
		else if (this == GOLD) {
			return "Gold";
		}
		else if (this == SILVER) {
			return "Silver";
		}
		else {
			return "Bronze";
		}
	}
	}
	
	private ArrayList<Athlete> team; // athletes of which competitor is consisted
	private Medal medal; // won medal
	private Discipline discipline;  // in what discipline did the competitor compete
	private Country country; // for what country did the competitor compete
	
	public static Medal stringToMedal(String medal_string) {
		if (medal_string.equals("Gold")) {
			return Competitor.Medal.GOLD;
		}
		else if (medal_string.equals("Silver")) {
			return Competitor.Medal.SILVER;
		}
		else if (medal_string.equals("Bronze")) {
			return Competitor.Medal.BRONZE;
		}
		else {
			return Competitor.Medal.NONE;
		}
	}
	
	public Medal getMedal() {
		return medal;
	}
	
	public Discipline getDiscipline() {
		return discipline;
	}
	
	public Country getCountry() {
		return country;
	}
	
	public ArrayList<Athlete> getTeam(){
		return team;
	}
	
	public Competitor(Medal m, Discipline disc, Country c) {
		medal = m;
		discipline = disc;
		country = c;
		team = new ArrayList<Athlete>();
	}
	
	public void addAthlete(Athlete athlete) {
		team.add(athlete);
	}
	
	@Override
	public boolean equals(Object o) {
		if (!(o instanceof Competitor)) {
			return false;
		}
		Competitor c = (Competitor)o;
		return c.medal == medal && c.discipline.equals(discipline) && c.country.equals(country) && c.team.equals(team);
		
	}


}
