package Olympiad;

import java.util.ArrayList;


public class Games {
	
	public enum Season {
		WINTER, SUMMER ;		
			
		@Override
		public String toString() {	
			if (this == WINTER) {
				return "Winter";
			}
			else {
				return "Summer";
			}
		}
	}
	
	private int year;
	private Season season;
	
	
	private ArrayList<Competitor> competitors; // competitors who participated in games
	/*private*/ String city;  // city where the games were held
	String Event;
	
	public Games(int y, String c, Season s) {
		city = c;
		year = y;
		season = s;
		Event = y + s.toString();
		competitors = new ArrayList<Competitor>();
	}
	
	// if competitor already exists, don't add him and return false
	public boolean addCompetitor(Competitor competitor) {
		if (competitors.contains(competitor)) {
			return false;
		}
		competitors.add(competitor);
		return true;
	}
	
	public int getYear() {
		return year;
	}
	
	public Season getSeason() {
		return season;
	}
	
	public ArrayList<Competitor> getCompetitors(){
		return competitors;
	}
}
