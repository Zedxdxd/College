#ifndef _Olympiad_h_

#define _Olympiad_h_

#include <iostream>
#include <unordered_set>
#include <unordered_map>
#include <map>
#include <exception>
#include <fstream>
#include <algorithm>
//#include <boost\regex.hpp>
#include "Athlete.h"
#include "Games.h"
#include "Sport.h"
#include "Discipline.h"
#include "Country.h"
#include "Exceptions.h"

//using namespace boost;

// struct to filter data.. 
// if a pointer is nullptr, the filter doesn't count, 
// if it's different than nullptr, its dereferenced and checked
struct Parameters {
	string* sport_filter, * country_filter;
	int* year_filter;
	Discipline::Event* event_filter;
	Competitor::Medal* medal_filter;

	~Parameters() {
		delete sport_filter;
		sport_filter = nullptr;

		delete country_filter;
		country_filter = nullptr;
		
		delete year_filter;
		year_filter = nullptr;
		
		delete event_filter;
		event_filter = nullptr;

		delete medal_filter;
		medal_filter = nullptr;
	}
};


// singleton class, one instance is only available
class Olympiad {
public:
	// creates instance on heap, must call delete_instance to free that memory along with other allocated memory
	static Olympiad& get_instance() {
		if (Olympiad::instance == nullptr) {
			Olympiad::instance = new Olympiad();
		}
		return *instance;
	}
	
	// frees memory allocated for instance and for parsing
	static void delete_instance();

	// parses data from files to according data structures
	//void parse(string file_athletes, string file_events, int* year_to_load);
	void std_parse(string file_athletes, string file_events, int* year_to_load);

	// counts competitors who fulfill the requirements of filters
	int number_competitors(Parameters& filters);

	// counts competitors which fulfill the requirements of filters
	int number_disciplines(Parameters& filters);

	// calculates average height of athletes who fulfill the requirements of filters
	double avg_height(Parameters& filters);

	// calculates average weight of athletes who fulfill the requirements of filters
	double avg_weight(Parameters& filters);

	// number of sports in which a given country has won at least one medal
	int number_sports_with_at_least_one_medal(string country_name);

	// three countries that performed the best on the given games
	vector<Country*> three_best_countries(int year, string season);

	// countries that have been the best at least once
	unordered_set<Country*> best_countries();

	// returns 10 youngest athletes who won a medal on their first competition
	vector<Athlete*> ten_youngest_athletes();

	// returns pairs of countries and athletes who have won at least 1 medal in individual and group competition
	vector<pair<Country*, Athlete*>> pairs_countries_athletes();

	// returns all sportist that played in year1, season1 and year2, season2
	unordered_set<Athlete*> sportists_in_both_games(int year1, string season1, int year2, string season2);

	// returns teams for given country for given game, sorted bu the number of sportists descending and by 
	// ascending by the discipline they were competing in
	vector < pair<vector<Athlete*>, Discipline*>> teams_with_country_and_game(string country_name, int year, string season);

	// returns the cities that hosted games at least once
	unordered_set<string> cities_hosts();

private:
	// private constructor so only one instance can be created
	Olympiad() {}

	static Olympiad* instance;

	unordered_map<int, Athlete*> all_sportists; // all athletes from file_athletes (refer to function parse_athletes)
	unordered_map<int, Athlete*> sportists; // just the athletes who participated

	// function class used for hashing pair<int, Games::Season> for games
	class pair_games_hasher {
	public:
		size_t operator()(const pair<int, Games::Season>& p) const {
			unordered_map<string, int> map;
			return map.hash_function().operator()(to_string(p.first) + " " +
				(p.second == Games::Season::SUMMER ? "Summer" : "Winter"));
		}
	}; 
	unordered_map<pair<int, Games::Season>, Games*, pair_games_hasher> games; // all games from file_events with *year_to_load
	unordered_map<string, Sport*> sports; // all sports in games with *year_to_load
	unordered_map<string, Country*> countries; // all countries in games with *year_to_load

	// loads into sportists from f_athletes
	//void parse_athletes(ifstream& f_athletes);
	void std_parse_athletes(ifstream& f_athletes);

	// loads into games, sports and countries from f_events
	// only loads the data from games that happened in *year_to_load (if year_to_load == nullptr loads all)
	//void parse_games_data(ifstream& f_events, int* year_to_load);
	void std_parse_games_data(ifstream& f_events, int* year_to_load);

	// returns a country with the name == coutry_name
	// if it doesn't exist in the map, creates a new object
	Country* get_country(string country_name);

	// returns a sport with the name == sport_name
	// if it doesn't exist in the map, creates a new object
	Sport* get_sport(string sport_name);

	// returns a game that happened in given year, city and season
	// if it doesn't exist in the map, creates a new object
	Games* get_games(int year, string city, Games::Season season);


	// returns a discipline with given name (discipline_name), event and that belongs to given sport
	// if it doesn't exist, creates a new object
	Discipline* get_discipline(string discipline_name, Discipline::Event event, Sport* sport);

	// helper method, returns true if params fulfill the requirements given by filters
	bool compare_with_filters(Parameters& params, Parameters& filters);

	// creates Parameters* for every competitor, and adds it to data
	// only puts the Parameters* in vector, because it is only needed to count the competitors
	void pack_for_filtering_competitors(vector<Parameters*>& data);

	// add Athlete* to the unordered_set athletes if Athlete* fulfills the requirements of filters
	void pack_athletes_filtered(unordered_set<Athlete*>& athletes, Parameters& filters);

	// returns for every country that participated in game the number of each medal it won
	// vector<int> has the length three, [0] is number of Gold medals
	// [1] is number of Silver medals and [2] is number of Bronze medals
	vector<pair<Country*, vector<int> > > count_medals(Games* game);
};



#endif