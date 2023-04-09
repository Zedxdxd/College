package Olympiad;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.util.stream.Stream;

import javax.swing.JOptionPane;


public class Olympiad {
	
	static {
		System.loadLibrary("java_native_poop");
	}
	
	private static Olympiad instance;
	
	private boolean isLoaded = false;
	
	private HashMap<Integer, Athlete> allSportists;  // all athletes from file_athletes
	private HashMap<Integer, Athlete> sportists;  // just the athletes who participated
	private HashMap<String, Games> games; //string is yearSeason
	private HashMap<String, Sport> sports;
	private HashMap<String, Country> countries;
	
	private StringBuilder badLinesAthletesFile;
	private StringBuilder badLinesEventsFile;
	private StringBuilder missingAthletes;
	private int numberLine;
	
	// private constructor so only one instance can be created
	private Olympiad() {
		allSportists = new HashMap<Integer, Athlete>();
		sportists = new HashMap<Integer, Athlete>();
		games = new HashMap<String, Games>();
		sports = new HashMap<String, Sport>();
		countries = new HashMap<String, Country>();
		badLinesAthletesFile = new StringBuilder();
		badLinesEventsFile = new StringBuilder();
		missingAthletes = new StringBuilder();
	}
	
	
	public static Olympiad getInstance() {
		if (instance == null){
			instance = new Olympiad();
		}
		return instance;
	}
	
	public boolean loadedData() {
		return isLoaded;
	}
	
	// returns a country with the name == coutry_name
	// if it doesn't exist in the map, creates a new object
	private Country getCountry(String countryName) {
		if (!countries.containsKey(countryName)) {
			countries.put(countryName, new Country(countryName));
		}
		return countries.get(countryName);
	}

	// returns a sport with the name == sport_name
	// if it doesn't exist in the map, creates a new object
	private Sport getSport(String sportName) {
		if (!sports.containsKey(sportName)) {
			sports.put(sportName, new Sport(sportName));
		}
		return sports.get(sportName);
	}

	// returns a game that happened in given year, city and season
	// if it doesn't exist in the map, creates a new object
	Games getGames(int year, String city, Games.Season season) {
		String hashString = year + season.toString();
		if (!games.containsKey(hashString)) {
			games.put(hashString, new Games(year, city, season));
		}
		return games.get(hashString);
	}


	// returns a discipline with given name (discipline_name), event and that belongs to given sport
	// if it doesn't exist, creates a new object
	Discipline getDiscipline(String disciplineName, Discipline.Type type, Sport sport){
		
		// tries to find discipline made of given arguments in the given sport
		Discipline tmpDiscipline = new Discipline(disciplineName, type);
		Discipline discipline = sport.findDiscipline(tmpDiscipline);

		if (discipline == null) {
			// tmpDiscipline isn't in sport
			discipline = new Discipline(disciplineName, type);
			sport.addDiscipline(discipline);
		}
		return discipline;
	}
	
	// loads athletes from fileAthletes
	private void parseAthletes(File fileAthletes) {
		try {
			BufferedReader br = new BufferedReader(new FileReader(fileAthletes));
			Stream<String> stream = br.lines();
			numberLine = 1;
			stream.forEach(line -> {
				Pattern pat = Pattern.compile("([0-9]*)!(.*)!([MF])!([0-9\\.]+|NA)!([0-9\\.]+|NA)!([0-9\\.]+|NA)");
				Matcher m = pat.matcher(line);
				
				if (m.matches()) {
					Integer id = Integer.parseInt(m.group(1));
					String name = m.group(2);
					Athlete.Gender gender = m.group(3).equals("M") ? Athlete.Gender.M : Athlete.Gender.F;
					int age = m.group(4).equals("NA") ? 0 : Integer.parseInt(m.group(4));
					double height = m.group(5).equals("NA") ? 0 : Double.parseDouble(m.group(5));
					double weight = m.group(6).equals("NA") ? 0 : Double.parseDouble(m.group(6));
					allSportists.put(id, new Athlete(id, name, gender, age, height, weight));
				}
				else {
					if (badLinesAthletesFile.toString().equals("")) {
						badLinesAthletesFile.append(((Integer)numberLine).toString());
					}
					else {
						badLinesAthletesFile.append(", " + ((Integer)numberLine).toString());
					}
				}
				
				numberLine++;
				
			});
			
			br.close();
			
			if (!badLinesAthletesFile.toString().equals("")) {
				JOptionPane.showMessageDialog(null, "In athletes file, lines that have invalid format "
						+ "are: " + badLinesAthletesFile.toString() + ". Those lines were ignored.");
			}
			
		} 
		catch (FileNotFoundException e) {} 
		catch (IOException e) {}
		
		
	}
	
	// load data about games from fileEvents, if yearToLoad != 0 load only that year else load all
	private void parseGamesData(File fileEvents, int yearToLoad) {
		try {
			BufferedReader br = new BufferedReader(new FileReader(fileEvents));
			Stream<String> stream = br.lines();
			numberLine = 1;
			stream.forEach(line -> {
				Pattern line_pattern = Pattern.compile("(^[0-9]{4}) (Summer|Winter)!(.*)!(.*)!(.*)!(Individual|Team)!(.*)!(.*)!(Gold|Silver|Bronze|)");
				Matcher line_matcher = line_pattern.matcher(line);
				
				if (line_matcher.matches()) {
					int year = Integer.parseInt(line_matcher.group(1));
					if (yearToLoad == 0 || yearToLoad == year) {
					
						Games.Season season = line_matcher.group(2).equals("Winter") ? Games.Season.WINTER : Games.Season.SUMMER;
						String city = line_matcher.group(3);
						String sportName = line_matcher.group(4);
						String disciplineName = line_matcher.group(5);
						Discipline.Type disciplineEvent = line_matcher.group(6).equals("Team") ? Discipline.Type.TEAM : Discipline.Type.INDIVIDUAL;
						String countryName = line_matcher.group(7);
						String membersOfTeam = line_matcher.group(8);
						String medalString = line_matcher.group(9);
						Competitor.Medal medal = Competitor.stringToMedal(medalString);
						
						// getting/making information for creating the competitor 
						Country country = getCountry(countryName);
						Sport sport = getSport(sportName);
						Games games = getGames(year, city, season);
						Discipline discipline = getDiscipline(disciplineName, disciplineEvent, sport);
						Competitor competitor = new Competitor(medal, discipline, country);
						
						// getting all athletes' ids who participated
						Pattern id_patttern = Pattern.compile("([0-9]+)");
						Matcher id_matcher = id_patttern.matcher(membersOfTeam);
						while (id_matcher.find()) {
							Integer id = Integer.parseInt(id_matcher.group());
	
							if (!allSportists.containsKey(id)) {
								if (missingAthletes.toString().equals("")) {
									missingAthletes.append(((Integer)id).toString());
								}
								else {
									if (!missingAthletes.toString().contains(((Integer)id).toString())) {
										missingAthletes.append(", " + ((Integer)id).toString());
									}
								}
							}
							else {
								sportists.put(id, allSportists.get(id));
								competitor.addAthlete(sportists.get(id));
							}
						}
						
						// if competitor already exists (same country, discipline, medal and athletes in the team) don't add him
						if (games.addCompetitor(competitor)) {
							country.addCompetitor(competitor);
						}
						
					}
					
				}
				else {
					if (badLinesEventsFile.toString().equals("")) {
						badLinesEventsFile.append(((Integer)numberLine).toString());
					}
					else {
						badLinesEventsFile.append(", " + ((Integer)numberLine).toString());
					}
				}
				
				numberLine++;
				
			});
			
			br.close();
			allSportists.clear();
			
			if (!badLinesAthletesFile.toString().equals("")) {
				JOptionPane.showMessageDialog(null, "In events file, lines that have invalid format "
						+ "are: " + badLinesEventsFile.toString() + ". Those lines were ignored.");
			}
			
			if (!missingAthletes.toString().equals("")) {
				JOptionPane.showMessageDialog(null, "Athletes with ids: " + missingAthletes.toString()
						+ " have appeared in events file, but they weren't in athletes file. "
						+ "Check both files.");
			}
		
		} 
		catch (FileNotFoundException e) {} 
		catch (IOException e) {}
		
	}
	
	// load all data
	public void parse(String fileAthletesName, String fileEventsName, int yearToLoad) {
		parseAthletes(new File(fileAthletesName));
		parseGamesData(new File(fileEventsName), yearToLoad);
		isLoaded = true;
	}

	// counts athletes who fulfill filters
	// -1 if the filter doesn't count
	// filters[0] is countryName
	// filters[1] is sportName
	// filters[2] is year
	// filters[3] is INDIVIDUAL/TEAM
	// filters[4] is wonMedal
	// filters[5] is Season (Winter/Summer), useful for XY graphicon
	public int numberCompetitors(String[] filters) {
		
		// format for the native method
		// id!countryName!sportName!year!INDIVIDUAL/TEAM!wonMedal!Season
		
		// store all data about every competitor in the format from above
		ArrayList<String> competitorsData = new ArrayList<String>();
		
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			Games.Season season = game.getSeason();
			ArrayList<Competitor> competitors = game.getCompetitors();
			for (Competitor competitor : competitors) {
				Competitor.Medal medal = competitor.getMedal();
				Discipline.Type type = competitor.getDiscipline().getType();
				String countryName = competitor.getCountry().getName();
				String sportName = null;
				
				for (String name : sports.keySet()) {
					if (sports.get(name).findDiscipline(competitor.getDiscipline()) != null) {
						sportName = name;
						break;
					}
				}

				ArrayList<Athlete> team = competitor.getTeam();
				for (Athlete athlete : team) {
					StringBuilder sb = new StringBuilder();
					int id = athlete.getId();
					sb.append(id + "!" + countryName + "!" + sportName + "!" + year + "!" + 
							type.toString() + "!" + medal.toString() + "!" + season.toString());
					competitorsData.add(sb.toString());
				}
				
			}
		});
		
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		return evaluateNumber(filters, competitors);
		
	}
	
	// counts disciplines that fulfill filters
	// -1 if the filter doesn't count
	// filters[0] is countryName
	// filters[1] is sportName
	// filters[2] is year
	// filters[3] is INDIVIDUAL/TEAM
	// filters[4] is wonMedal
	// filters[5] is Season (Winter/Summer), useful for XY graphicon
	public int numberDisciplines(String[] filters) {
		// format for the native method
		// disciplineName!countryName!sportName!year!INDIVIDUAL/TEAM!wonMedal!Season
		
		ArrayList<String> disciplinesData = new ArrayList<String>();
			
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			Games.Season season = game.getSeason();
			ArrayList<Competitor> competitors = game.getCompetitors();
			for (Competitor competitor : competitors) {
				Competitor.Medal medal = competitor.getMedal();
				Discipline.Type type = competitor.getDiscipline().getType();
				String countryName = competitor.getCountry().getName();
				String sportName = null;
					
				for (String name : sports.keySet()) {
					if (sports.get(name).findDiscipline(competitor.getDiscipline()) != null) {
						sportName = name;
						break;
					}
				}
				
				StringBuilder sb = new StringBuilder();
					
				// adding type to the name because there are disciplines with the same name but different type
				sb.append(competitor.getDiscipline().getName() + " " + type.toString() + "!" + countryName + 
						"!"+ sportName + "!" + year + "!" + type.toString() + "!" + medal.toString() + "!" + season.toString());
				disciplinesData.add(sb.toString());
			}
		});
			
		String[] disciplines = new String[disciplinesData.size()];
		disciplines = disciplinesData.toArray(disciplines);
		return evaluateNumber(filters, disciplines);
			
	}
	
	// calculates average weight of all athletes
	// -1 if the filter doesn't count
	// filters[0] is countryName
	// filters[1] is sportName
	// filters[2] is year
	// filters[3] is INDIVIDUAL/TEAM
	// filters[4] is wonMedal
	// filters[5] is Season (Winter/Summer), useful for XY graphicon
	public double avgWeight(String[] filters) {
		// format for the native method
		// id!countryName!sportName!year!INDIVIDUAL/TEAM!Season!weight
		
		ArrayList<String> competitorsData = new ArrayList<String>();
		
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			Games.Season season = game.getSeason();
			ArrayList<Competitor> competitors = game.getCompetitors();
			for (Competitor competitor : competitors) {
				Competitor.Medal medal = competitor.getMedal();
				Discipline.Type type = competitor.getDiscipline().getType();
				String countryName = competitor.getCountry().getName();
				String sportName = null;
				
				for (String name : sports.keySet()) {
					if (sports.get(name).findDiscipline(competitor.getDiscipline()) != null) {
						sportName = name;
						break;
					}
				}

				ArrayList<Athlete> team = competitor.getTeam();
				for (Athlete athlete : team) {
					StringBuilder sb = new StringBuilder();
					int id = athlete.getId();
					sb.append(id + "!" + countryName + "!" + sportName + "!" + year + "!" + 
					type.toString() + "!" + medal.toString() + "!" + season.toString() + "!" + athlete.getWeight());
					competitorsData.add(sb.toString());
				}
				
			}
		});
		
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		return average(filters, competitors);
	}
	
	public double avgHeight(String[] filters) {
		// format for the native method
		// id!countryName!sportName!year!INDIVIDUAL/TEAM!wonMedal!Season!height
		
		ArrayList<String> competitorsData = new ArrayList<String>();
		
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			Games.Season season = game.getSeason();
			ArrayList<Competitor> competitors = game.getCompetitors();
			for (Competitor competitor : competitors) {
				Competitor.Medal medal = competitor.getMedal();
				Discipline.Type type = competitor.getDiscipline().getType();
				String countryName = competitor.getCountry().getName();
				String sportName = null;
				
				for (String name : sports.keySet()) {
					if (sports.get(name).findDiscipline(competitor.getDiscipline()) != null) {
						sportName = name;
						break;
					}
				}

				ArrayList<Athlete> team = competitor.getTeam();
				for (Athlete athlete : team) {
					StringBuilder sb = new StringBuilder();
					int id = athlete.getId();
					sb.append(id + "!" + countryName + "!" + sportName + "!" + year + "!" + 
					type.toString() + "!" + medal.toString() + "!" + season.toString() + "!"+ athlete.getHeight());
					competitorsData.add(sb.toString());
				}
				
			}
		});
		
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		return average(filters, competitors);
	}
	
	// this counts how much data satisfies filters
	private native int evaluateNumber(String[] filters, String[] data);
	
	// this returns the average weight/height depending on where it's called
	private native double average(String[] filters, String[] competitors);
	
	// used in methods for data for graphs, delimiter will be !, and the string will end with '!'
	private ArrayList<String> split(String str, char delimiter) {
		ArrayList<String> result = new ArrayList<String>();
		String tmp = "";
		for (int i = 0; i < str.length(); i++) {
			if (str.charAt(i)== delimiter) {
				result.add(tmp);
				tmp = "";
			}
			else {
				tmp += str.charAt(i);
			}
		}
		if (!tmp.equals("")) {
			result.add(tmp);
		}
		return result;
	}
	
	private native String nativeNumberCompetitorsByCountry(String[] filters, String[] competitors);
	
	// groups number of competitors with country and returns
	public LinkedHashMap<String, Double> numberCompetitorsByCountry(String[] filters){
		
		LinkedHashMap<String, Double> countryNumCompetitors = new LinkedHashMap<>();
		
		
		// format for the native method
		// id!countryName!sportName!year!INDIVIDUAL/TEAM!wonMedal!Season
				
		// store all data about every competitor in the format from above
		ArrayList<String> competitorsData = new ArrayList<String>();
				
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			Games.Season season = game.getSeason();
			ArrayList<Competitor> competitors = game.getCompetitors();
			for (Competitor competitor : competitors) {
				Competitor.Medal medal = competitor.getMedal();
				Discipline.Type type = competitor.getDiscipline().getType();
				String countryName = competitor.getCountry().getName();
				String sportName = null;
					
				for (String name : sports.keySet()) {
					if (sports.get(name).findDiscipline(competitor.getDiscipline()) != null) {
						sportName = name;
						break;
						}
				}

				ArrayList<Athlete> team = competitor.getTeam();
				for (Athlete athlete : team) {
					StringBuilder sb = new StringBuilder();
					int id = athlete.getId();
					sb.append(id + "!" + countryName + "!" + sportName + "!" + year + "!" + 
							type.toString() + "!" + medal.toString() + "!" + season.toString());
					competitorsData.add(sb.toString());
				}
				
			}
		});
				
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		String finalData = nativeNumberCompetitorsByCountry(filters, competitors);
		ArrayList<String> separatedData = split(finalData, '!');
		
		for (String str : separatedData) {
			ArrayList<String> dataOneCountry = split(str, '$');
			String countryName = dataOneCountry.get(0);
			double number = Double.parseDouble(dataOneCountry.get(1));
			countryNumCompetitors.put(countryName, number);
		}
		
		return countryNumCompetitors;
	}

	private native String nativeNumberDisciplinesByYearSeason(String[] data);

	// groups for every yearSeason number of disciplines
	public LinkedHashMap<String, Double> numberDisciplinesByYearSeason(int startYear, int endYear){
		LinkedHashMap<String, Double> yearSeasonNumDisciplines = new LinkedHashMap<>();
		
		// format for the native method
		// disciplineName!yearSeason
				
		ArrayList<String> disciplinesData = new ArrayList<String>();
					
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			if (year >= startYear && year <= endYear) {
				Games.Season season = game.getSeason();
				ArrayList<Competitor> competitors = game.getCompetitors();
				for (Competitor competitor : competitors) {
					Discipline.Type type = competitor.getDiscipline().getType();
							
					StringBuilder sb = new StringBuilder();
								
					// adding type to the name because there are disciplines with the same name but different type
					sb.append(competitor.getDiscipline().getName() + " " + type.toString() + 
							"!" + year + season.toString());
					disciplinesData.add(sb.toString());
				}
			}
		});
					
		String[] disciplines = new String[disciplinesData.size()];
		disciplines = disciplinesData.toArray(disciplines);
		
		String finalData = this.nativeNumberDisciplinesByYearSeason(disciplines);
		ArrayList<String> separatedData = split(finalData, '!');
		
		for (String str : separatedData) {
			ArrayList<String> dataOneYearSeason = split(str, '$');
			String yearSeason = dataOneYearSeason.get(0);
			double number = Double.parseDouble(dataOneYearSeason.get(1));
			yearSeasonNumDisciplines.put(yearSeason, number);
		}
		
		return yearSeasonNumDisciplines;
	}

	private native String nativeAvgByYearSeason(String[] data);
	
	public LinkedHashMap<String, Double> avgHeightByYearSeason(int startYear, int endYear){
		LinkedHashMap<String, Double> yearSeasonAvgHeight = new LinkedHashMap<>();
		
		// format for the native method
		// id!yearSeason!height
				
		ArrayList<String> competitorsData = new ArrayList<String>();
				
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			if (year >= startYear && year <= endYear) {
				Games.Season season = game.getSeason();
				ArrayList<Competitor> competitors = game.getCompetitors();
				for (Competitor competitor : competitors) {
	
					ArrayList<Athlete> team = competitor.getTeam();
					for (Athlete athlete : team) {
						StringBuilder sb = new StringBuilder();
						int id = athlete.getId();
						sb.append(id + "!" + year + season.toString() + "!" + athlete.getHeight());
						competitorsData.add(sb.toString());
					}
							
				}
			}
		});
				
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		
		String finalData = this.nativeAvgByYearSeason(competitors);
		ArrayList<String> separatedData = split(finalData, '!');
		
		for (String str : separatedData) {
			ArrayList<String> dataOneYearSeason = split(str, '$');
			String yearSeason = dataOneYearSeason.get(0);
			double avgHeight = Double.parseDouble(dataOneYearSeason.get(1));
			yearSeasonAvgHeight.put(yearSeason, avgHeight);
		}
		
		return yearSeasonAvgHeight;
	}
	
	public LinkedHashMap<String, Double> avgWeightByYearSeason(int startYear, int endYear){
		LinkedHashMap<String, Double> yearSeasonAvgWeight = new LinkedHashMap<>();
		
		// format for the native method
		// id!yearSeason!weight
				
		ArrayList<String> competitorsData = new ArrayList<String>();
				
		games.forEach((String event, Games game) -> {
			int year = game.getYear();
			if (year >= startYear && year <= endYear) {
				Games.Season season = game.getSeason();
				ArrayList<Competitor> competitors = game.getCompetitors();
				for (Competitor competitor : competitors) {
	
					ArrayList<Athlete> team = competitor.getTeam();
					for (Athlete athlete : team) {
						StringBuilder sb = new StringBuilder();
						int id = athlete.getId();
						sb.append(id + "!" + year + season.toString() + "!" + athlete.getWeight());
						competitorsData.add(sb.toString());
					}
							
				}
			}
		});
				
		String[] competitors = new String[competitorsData.size()];
		competitors = competitorsData.toArray(competitors);
		
		String finalData = this.nativeAvgByYearSeason(competitors);
		ArrayList<String> separatedData = split(finalData, '!');
		
		for (String str : separatedData) {
			ArrayList<String> dataOneYearSeason = split(str, '$');
			String yearSeason = dataOneYearSeason.get(0);
			double avgWeight = Double.parseDouble(dataOneYearSeason.get(1));
			yearSeasonAvgWeight.put(yearSeason, avgWeight);
		}
		
		return yearSeasonAvgWeight;
		
	}
}
