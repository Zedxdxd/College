#include "Olympiad.h"
#include <regex>

Olympiad* Olympiad::instance = nullptr;


void Olympiad::delete_instance() {
	if (instance) {

		// instance exists, delete all loaded data
		for (pair<const int, Athlete*>& it : instance->sportists) {
			delete it.second;
		}
		for (pair<const pair<int, Games::Season>, Games*>& it : instance->games) {
			delete it.second;
		}
		for (pair<const string, Sport*>& it : instance->sports) {
			delete it.second;
		}
		for (pair<const string, Country*>& it : instance->countries) {
			delete it.second;
		}
	}
	delete Olympiad::instance;
	Olympiad::instance = nullptr;
}


Sport* Olympiad::get_sport(string sport_name) {
	if (sports.find(sport_name) == sports.end()) {
		sports[sport_name] = new Sport(sport_name);
	}
	return sports[sport_name];
}


Country* Olympiad::get_country(string country_name) {
	if (countries.find(country_name) == countries.end()) {
		countries[country_name] = new Country(country_name);
	}
	return countries[country_name];
}


Games* Olympiad::get_games(int year, string city, Games::Season season) {
	if (games.find(pair<int, Games::Season>(year, season)) == games.end()) {
		games[pair<int, Games::Season>(year, season)] = new Games(year, city, season);
	}
	return games[pair<int, Games::Season>(year, season)];
}


Discipline* Olympiad::get_discipline(string discipline_name, Discipline::Event event, Sport* sport) {
	
	// tries to find discipline made of given arguments in the given sport
	Discipline tmp_discipline(discipline_name, event);
	Discipline* discipline = sport->find_discipline(&tmp_discipline);

	if (!discipline) {
		// tmp_discipline isn't in sport
		discipline = new Discipline(discipline_name, event);
		sport->add_discipline(discipline);
	}
	return discipline;
}



void Olympiad::std_parse(string file_athletes, string file_events, int* year_to_load) {
	ifstream f_athletes, f_events;

	f_athletes.open(file_athletes);
	if (!f_athletes.is_open()) {
		throw ExceptionNoFile(file_athletes);
	}

	f_events.open(file_events);
	if (!f_events.is_open()) {
		throw ExceptionNoFile(file_events);
	}

	std_parse_athletes(f_athletes);
	std_parse_games_data(f_events, year_to_load);
	f_athletes.close();
	f_events.close();
}


void Olympiad::std_parse_athletes(ifstream& f_athletes) {
	string line; // load line from f_athletes
	int line_num = 0; // number of the line maybe to signal an error
	const std::regex pattern = std::regex("([0-9]*)!(.*)!([MF])!([0-9\.]+|NA)!([0-9\.]+|NA)!([0-9\.]+|NA)");
	while (getline(f_athletes, line)) {
		line_num++;
		std::smatch line_match;

		if (std::regex_match(line, line_match, pattern)) {
			int id = stoi(line_match[1]);
			string name = line_match[2];
			Athlete::Gender gender = line_match[3] == "M" ? Athlete::Gender::M : Athlete::Gender::F;
			int age = stoi(line_match[4] != "NA" ? line_match[4] : (string)"0");
			double height = stod(line_match[5] != "NA" ? line_match[5] : (string)"0");
			double weight = stod(line_match[6] != "NA" ? line_match[6] : (string)"0");

			all_sportists.insert({ id, new Athlete(id, name, gender, age, height, weight) });

		}
		else {
			cout << "Nije dobar format linije broj: " << line_num << ", u datoteci sa atletama." << endl;
		}
	}
}

void Olympiad::std_parse_games_data(ifstream& f_events, int* year_to_load) {
	string line; // load line from f_athletes
	int line_num = 0; // number of the line maybe to signal an error
	std::regex pattern = std::regex("(^[0-9]{4}) (Summer|Winter)!(.*)!(.*)!(.*)!(Individual|Team)!(.*)!(.*)!(Gold|Silver|Bronze|)");
	while (getline(f_events, line)) {
		line_num++;
		std::smatch line_match;

		if (std::regex_match(line, line_match, pattern)) {

			int year = stoi(line_match[1]);
			// load only the given year in *year_to_load
			if (year_to_load != nullptr && *year_to_load != year) {
				continue;
			}
			Games::Season season = line_match[2] == "Winter" ? Games::Season::WINTER : Games::Season::SUMMER;
			string city = line_match[3];
			string sport_name = line_match[4];
			string discipline_name = line_match[5];
			Discipline::Event discipline_event = line_match[6] == "Team" ? Discipline::Event::TEAM : Discipline::Event::INDIVIDUAL;
			string country_name = line_match[7];
			string members_of_team = line_match[8];
			string medal_string = line_match[9];
			Competitor::Medal medal = Competitor::string_to_medal(medal_string);

			// getting/making information for creating the competitor 
			Country* country = get_country(country_name);
			Sport* sport = get_sport(sport_name);
			Games* games = get_games(year, city, season);
			Discipline* discipline = get_discipline(discipline_name, discipline_event, sport);
			Competitor* competitor = new Competitor(medal, discipline, country);

			// getting all athletes' ids who participated
			std::regex pattern_ids = std::regex("([0-9]+)");
			std::smatch id_match;

			auto ids_begin = std::sregex_iterator(members_of_team.begin(), members_of_team.end(), pattern_ids);
			auto ids_end = std::sregex_iterator();

			for (std::sregex_iterator i = ids_begin; i != ids_end; ++i) {
				int id = stoi(i->str());

				if (all_sportists.find(id) == all_sportists.end()) {
					cout << "U datoteci sa igrama se naislo na sportistu sa identifikatorom: " << id
						<< ", medjutim njega nema u ucitanim informacijama o sportistima. Proveriti fajl sa sportistima. "
						<< "Ovaj identifikator se sada ignorise." << endl;
					continue;
				}

				sportists[id] = all_sportists[id];
				competitor->add_athlete(sportists[id]);
			}

			// if competitor already exists (same country, discipline, medal and athletes in the team) delete him
			if (!games->add_competitor(competitor)) {
				delete competitor;
			}
			else {
				country->add_competitor(competitor);
			}

		}
		else {
			cout << "Nije dobar format linije: " << line_num << ", u datoteci." << endl;
		}
	}

	// delete the athletes who didn't participate
	for (pair<const int, Athlete*>& it : all_sportists) {
		if (sportists.find(it.first) == sportists.end()) {
			delete it.second;
		}
	}
	all_sportists.clear();
}


void Olympiad::pack_for_filtering_competitors(vector<Parameters*>& competitors_parameters) {
	// iterating through games to access every competitor
	for (const auto& games_it : games) {
		Games* game = games_it.second;

		// iterating through game
		for (int i = 0; i < game->size(); i++) {
			Competitor* cur_competitor = (*game)[i];

			// find sport that cur_competitor participated in
			Sport* sport = nullptr;
			for (const auto& sport_it : sports) {
				if (sport_it.second->find_discipline(cur_competitor->get_discipline())) {
					sport = sport_it.second;
					break;
				}
			}

			// creating params
			Parameters* params = new Parameters();
			params->sport_filter = new string(sport->get_name());
			params->country_filter = new string(cur_competitor->get_country()->get_name());
			params->year_filter = new int(game->get_year());
			params->event_filter = new Discipline::Event;
			*params->event_filter = cur_competitor->get_discipline()->get_event();
			params->medal_filter = new Competitor::Medal;
			*params->medal_filter = cur_competitor->get_medal();

			competitors_parameters.push_back(params);
		}
	}
}


int Olympiad::number_competitors(Parameters& filters) {
	vector<Parameters*> competitors_parameters;
	pack_for_filtering_competitors(competitors_parameters);

	int number = count_if(competitors_parameters.begin(), competitors_parameters.end(), [&filters](Parameters* params)
		{
			return
				(!filters.sport_filter || *filters.sport_filter == *params->sport_filter) &&
				(!filters.country_filter || *filters.country_filter == *params->country_filter) &&
				(!filters.year_filter || *filters.year_filter == *params->year_filter) &&
				(!filters.event_filter || *filters.event_filter == *params->event_filter) &&
				(!filters.medal_filter || *filters.medal_filter == *params->medal_filter);
		});

	// deleting allocated parameters in pack_for_filtering competitors;
	for (unsigned int i = 0; i < competitors_parameters.size(); i++) {
		delete competitors_parameters[i];
	}

	return number;
}


int Olympiad::number_disciplines(Parameters& filters) {
	unordered_set<Discipline*> disciplines;

	// iterating through games to access every competitor
	for (const auto& games_it : games) {
		Games* game = games_it.second;

		// iterating through every competitor
		for (int i = 0; i < game->size(); i++) {
			Sport* sport = nullptr;
			Competitor* curr_competitor = (*game)[i];

			// finding a sport which curr_competitor competed in
			for (const auto& sport_it : sports) {
				if (sport_it.second->find_discipline(curr_competitor->get_discipline())) {
					sport = sport_it.second;
					break;
				}
			}

			// creating params and comparing them to filters
			Parameters params;
			params.sport_filter = new string(sport->get_name());
			params.country_filter = new string(curr_competitor->get_country()->get_name());
			params.year_filter = new int(game->get_year());
			params.event_filter = new Discipline::Event;
			*params.event_filter = curr_competitor->get_discipline()->get_event();
			params.medal_filter = new Competitor::Medal;
			*params.medal_filter = curr_competitor->get_medal();

			if (compare_with_filters(params, filters)) {
				disciplines.insert(curr_competitor->get_discipline());
			}

		}
	}
	return disciplines.size();
	
}


bool Olympiad::compare_with_filters(Parameters& params, Parameters& filters) {
	// one field of params fulfills filters if either that field in filters is nullptr or they are equall
	return 
		(!filters.sport_filter || *filters.sport_filter	== *params.sport_filter) &&
		(!filters.country_filter || *filters.country_filter == *params.country_filter) &&
		(!filters.year_filter || *filters.year_filter == *params.year_filter) &&
		(!filters.event_filter || *filters.event_filter ==	*params.event_filter) &&
		(!filters.medal_filter || *filters.medal_filter == *params.medal_filter);
}


void Olympiad::pack_athletes_filtered(unordered_set<Athlete*>& athletes, Parameters& filters) {
	// iterating through games to access every competitor
	for (const auto& games_it : games) {
		Games* game = games_it.second;

		// iterating through game
		for (int i = 0; i < game->size(); i++) {
			Competitor* curr_competitor = (*game)[i];

			// finding the sport that curr_competitor participated in
			Sport* sport = nullptr;
			for (const auto& sport_it : sports) {
				if (sport_it.second->find_discipline(curr_competitor->get_discipline())) {
					sport = sport_it.second;
					break;
				}
			}

			// constructing parameters for every athlete in the team 
			// and if it fulfills the filters, athlete is added to athletes
			for (int i = 0; i < curr_competitor->size(); i++) {
				Parameters params;
				params.sport_filter = new string(sport->get_name());
				params.country_filter = new string(curr_competitor->get_country()->get_name());
				params.year_filter = new int(game->get_year());
				params.event_filter = new Discipline::Event;
				*params.event_filter = curr_competitor->get_discipline()->get_event();
				params.medal_filter = new Competitor::Medal;
				*params.medal_filter = curr_competitor->get_medal();

				if (compare_with_filters(params, filters)) {
					athletes.insert((*curr_competitor)[i]);
				}
			}
		}
	}
}


double Olympiad::avg_height(Parameters& filters) {
	unordered_set<Athlete*> athletes;
	int num = 0;
	double sum_height = 0;

	pack_athletes_filtered(athletes, filters);

	for_each(athletes.begin(), athletes.end(), [&num, &sum_height](Athlete* athlete) {
		if (athlete->get_height() != 0) {
			sum_height += athlete->get_height();
			num++;
		}
		});

	return (num != 0 ? sum_height / num : 0);
}


double Olympiad::avg_weight(Parameters& filters) {
	unordered_set<Athlete*> athletes;
	int num = 0;
	double sum_weight = 0;

	pack_athletes_filtered(athletes, filters);

	for_each(athletes.begin(), athletes.end(), [&num, &sum_weight](Athlete* athlete) {
		if (athlete->get_weight() != 0) {
			sum_weight += athlete->get_weight();
			num++;
		}
		});

	return (num != 0 ? sum_weight / num : 0);
}


int Olympiad::number_sports_with_at_least_one_medal(string country_name) {

	// error
	if (countries.find(country_name) == countries.end()) {
		throw ExceptionNoCountry(country_name);
	}

	Country* country = countries[country_name];
	unordered_set<Sport*> set_sports;

	// iterating through competitors of country
	for (int i = 0; i < country->size(); i++) {
		Competitor* curr_competitor = (*country)[i];
		
		// curr_competitor has won a medal
		if (curr_competitor->get_medal() != Competitor::Medal::NONE) {

			// finding a sport with the curr_competitor->discipline
			// and adding it to set_sports
			for (const auto& sports_iterator : sports) {
				if (sports_iterator.second->find_discipline(curr_competitor->get_discipline()) != nullptr) {
					set_sports.insert(sports_iterator.second);
					break;
				}
			}
		}
	}

	return set_sports.size();
	
}


vector<pair<Country*, vector<int>>> Olympiad::count_medals(Games* game) {
	
	// result
	vector<pair<Country*, vector<int> > > countries_num_medals;

	// counting medals
	for (int i = 0; i < game->size(); i++) {

		Competitor* curr_competitor = (*game)[i];
		Country* curr_country = curr_competitor->get_country();

		// search if curr_country is already in countries_num_medals
		auto it = find_if(countries_num_medals.begin(), countries_num_medals.end(),
			[curr_country](pair<Country*, vector<int>> p) { return p.first == curr_country; });

		// add country in country_num_medals if it's not in there
		if (it == countries_num_medals.end()) {
			vector<int> vec(3); // vec is initialized with zeros
			countries_num_medals.push_back(make_pair(curr_country, vec));
			it = countries_num_medals.end() - 1;
		}

		if (curr_competitor->get_medal() != Competitor::Medal::NONE) {
			(*it).second[(int)curr_competitor->get_medal() - 1]++;
		}
	}

	return countries_num_medals;
}


vector<Country*> Olympiad::three_best_countries(int year, string season) {
	pair<int, Games::Season> type = make_pair(year, season == "Summer" ? Games::Season::SUMMER : Games::Season::WINTER);

	// error
	if (games.find(type) == games.end()) {
		throw ExceptionNoGames(type);
	}

	Games* game = games[type];

	// holds the number of medals for every country
	vector<pair<Country*, vector<int> > > countries_num_medals = count_medals(game);

	// sort so that the best countries are the last
	sort(countries_num_medals.begin(), countries_num_medals.end(),
		[](const pair<Country*, vector<int> >& p1, const pair<Country*, vector<int> >& p2)
		{
			return p1.second < p2.second;
		});

	// getting three best countries and storing them in the result vector
	vector<Country*> result_vector;
	for (unsigned int i = 0; i < countries_num_medals.size(); i++) {
		result_vector.push_back(countries_num_medals[countries_num_medals.size() - i - 1].first);
		if (i == 2) {
			break;
		}
	}

	return result_vector;
}


unordered_set<Country*> Olympiad::best_countries() {
	
	// result
	unordered_set<Country*> result_countries;

	// getting a best country for every game
	for (const auto& it : games) {
		Games* curr_game = it.second;

		// holds the number of medals for every country
		vector<pair<Country*, vector<int> > > countries_num_medals = count_medals(curr_game);

		// adding the best country to the result
		if (countries_num_medals.size() > 0) {
			result_countries.insert(max_element(countries_num_medals.begin(), countries_num_medals.end(),
				[](const pair<Country*, vector<int> > p1, const pair<Country*, vector<int> > p2) {
					return p1.second < p2.second;
				})->first);
		}
	}

	return result_countries;
}


vector<Athlete*> Olympiad::ten_youngest_athletes() {

	// maps Athlete to the pair of the earliest games he participated in and whether he has won a medal in those games
	unordered_map<Athlete*, pair<pair<int, Games::Season>, bool>> athletes_year_won_medal;

	// iterating through all games
	for (const auto& it : games) {
		Games* curr_game = it.second;
		int a = curr_game->get_year();
		// iterating through competitors of curr_game
		for (int i = 0; i < curr_game->size(); i++) {
			Competitor* curr_competitor = (*curr_game)[i];

			// iterating through competitors to form data for every Athlete
			for (int j = 0; j < curr_competitor->size(); j++) {
				Athlete* curr_athlete = (*curr_competitor)[j];
				int curr_id = curr_athlete->get_ID();

				// athletes that don't have info about their age won't be taken into consideration
				if (curr_athlete->get_age() == 0) {
					continue;
				}
				
				if (athletes_year_won_medal.find(curr_athlete) == athletes_year_won_medal.end() ||
					curr_game->get_type() < athletes_year_won_medal[curr_athlete].first) {
					athletes_year_won_medal[curr_athlete] = make_pair(curr_game->get_type(),
						curr_competitor->get_medal() == Competitor::Medal::NONE ? false : true);
				}
			}
		}
	}

	// forming the result by extracting only athletes
	vector<Athlete*> tmp_vector;
	for (const pair<Athlete*, pair<pair<int, Games::Season>, bool>>& p : athletes_year_won_medal) {
		if (p.second.second) {
			tmp_vector.push_back(p.first);
		}
	}

	sort(tmp_vector.begin(), tmp_vector.end(),
		[](const Athlete* athlete1, const Athlete* athlete2) {
			return athlete1->get_age() < athlete2->get_age();
		});
	vector<Athlete*> res_vector;
	for (Athlete* athlete : tmp_vector) {
		res_vector.push_back(athlete);
		if (res_vector.size() == 10) {
			break;
		}
	}

	return res_vector;
}


vector<pair<Country*, Athlete*>> Olympiad::pairs_countries_athletes() {

	// function class to hash pair<Country*, Athlete*>
	struct pair_country_athlete_hasher {
		size_t operator()(const pair<Country*, Athlete*>& p) const {
			unordered_map<string, int> map;
			return map.hash_function().operator()(p.first->get_name() + to_string(p.second->get_ID()));
		}
	};

	// maps pair<Country*, Athlete*> to two integers
	// first integer represents how many times Athlete competed as an individual, second represents in a team
	unordered_map<pair<Country*, Athlete*>, pair<int, int>, pair_country_athlete_hasher> pairs_num_medals;

	// iterating through games
	for (const auto& it : games) {
		Games* curr_game = it.second;

		// iterating through competitors
		for (int i = 0; i < curr_game->size(); i++) {
			Competitor* curr_competitor = (*curr_game)[i];

			if (curr_competitor->get_medal() == Competitor::Medal::NONE) {
				continue;
			}

			Country* curr_country = curr_competitor->get_country();
			Discipline* curr_discipline = curr_competitor->get_discipline();

			// iterating through athletes and calculating information
			for (int j = 0; j < curr_competitor->size(); j++) {
				Athlete* curr_athlete = (*curr_competitor)[j];

				// check if pair curr_country, curr_athlete is loaded
				// if it's not, then load it
				if (pairs_num_medals.find(make_pair(curr_country, curr_athlete)) == pairs_num_medals.end()) {
					pairs_num_medals[make_pair(curr_country, curr_athlete)] = make_pair(0, 0);
				}

				if (curr_discipline->get_event() == Discipline::Event::INDIVIDUAL) {
					pairs_num_medals[make_pair(curr_country, curr_athlete)].first++;
				}
				else if (curr_discipline->get_event() == Discipline::Event::TEAM) {
					pairs_num_medals[make_pair(curr_country, curr_athlete)].second++;
				}
			}
		}

	}

	// result
	vector<pair<Country*, Athlete*>> result_vector;

	// forming the result by extracting only pairs of country and athlete
	for (const auto& it : pairs_num_medals) {
		if (it.second.first > 0 && it.second.second > 0) {
			result_vector.push_back(it.first);
		}
	}

	sort(result_vector.begin(), result_vector.end(),
		[](const pair<Country*, Athlete*> p1, const pair<Country*, Athlete*> p2) {
			/*return p1.first->get_name() < p2.first->get_name() ||
				(p1.first->get_name() == p2.first->get_name() && p1.second->get_ID() < p2.second->get_ID());*/
			return p1.second->get_ID() < p2.second->get_ID();
		});

	return result_vector;
}


unordered_set<Athlete*> Olympiad::sportists_in_both_games(int year1, string season1, int year2, string season2) {
	pair<int, Games::Season> type1 = make_pair(year1, season1 == "Summer" ? Games::Season::SUMMER : Games::Season::WINTER);

	// error
	if (games.find(type1) == games.end()) {
		throw ExceptionNoGames(type1);
	}

	pair<int, Games::Season> type2 = make_pair(year2, season2 == "Summer" ? Games::Season::SUMMER : Games::Season::WINTER);

	// error
	if (games.find(type2) == games.end()) {
		throw ExceptionNoGames(type2);
	}

	Games* game1 = games[type1];

	// store the unique athletes from the game1 here
	unordered_set<Athlete*> athletes_game1;

	for (int i = 0; i < game1->size(); i++) {
		Competitor* curr_competitor = (*game1)[i];

		for (int j = 0; j < curr_competitor->size(); j++) {
			Athlete* curr_athlete = (*curr_competitor)[j];
			athletes_game1.insert(curr_athlete);
		}
	}

	Games* game2 = games[type2];

	// result
	unordered_set<Athlete*> res_set;

	// grab athletes from game2, if the athlete is in athletes_game1, add him to the result
	for (int i = 0; i < game2->size(); i++) {
		Competitor* curr_competitor = (*game2)[i];

		for (int j = 0; j < curr_competitor->size(); j++) {
			Athlete* curr_athlete = (*curr_competitor)[j];
			if (athletes_game1.find(curr_athlete) != athletes_game1.end()) {
				res_set.insert(curr_athlete);
			}
		}
	}

	return res_set;
}


vector<pair<vector<Athlete*>, Discipline*>> Olympiad::teams_with_country_and_game(string country_name, int year, string season) {
	pair<int, Games::Season> type = make_pair(year, season == "Summer" ? Games::Season::SUMMER : Games::Season::WINTER);

	// error
	if (games.find(type) == games.end()) {
		throw ExceptionNoGames(type);
	}

	// error
	if (countries.find(country_name) == countries.end()) {
		throw ExceptionNoCountry(country_name);
	}

	Games* game = games[type];
	Country* country = countries[country_name];

	// storing as pair of vector<Athlete*> (team) and Discipline*, because Discipline* is needed for sorting
	vector < pair<vector<Athlete*>, Discipline*>> teams_discipline;

	for (int i = 0; i < game->size(); i++) {
		Competitor* curr_competitor = (*game)[i];

		// competitor competed for country and is a team (more than one member)
		if (curr_competitor->get_country() == country && curr_competitor->size() > 1) {

			vector<Athlete*> team;
			for (int j = 0; j < curr_competitor->size(); j++) {
				team.push_back((*curr_competitor)[j]);
			}
			
			// add team and Discipline* if they aren't already added
			if (teams_discipline.end() == find_if(teams_discipline.begin(), teams_discipline.end(),
				[team, curr_competitor](pair<vector<Athlete*>, Discipline*> p) {
					return p.second == curr_competitor->get_discipline() && team == p.first;
				})) {
				teams_discipline.push_back(make_pair(team, curr_competitor->get_discipline()));
			}
		}
	}

	// sorting with the given criteria
	sort(teams_discipline.begin(), teams_discipline.end(),
		[](pair<vector<Athlete*>, Discipline*> p1, pair<vector<Athlete*>, Discipline*> p2) {
			return p1.first.size() > p2.first.size() ||
				(p1.first.size() == p2.first.size() && p1.second->get_name() < p2.second->get_name());
		});

	return teams_discipline;
}


unordered_set<string> Olympiad::cities_hosts() {
	
	// result
	unordered_set<string> res_set;

	// adding all cities to the result
	for (const auto& it : games) {
		res_set.insert(it.second->get_city());
	}

	return res_set;
}


// boost
/*void Olympiad::parse(string file_athletes, string file_events, int* year_to_load) {
	ifstream f_athletes, f_events;

	f_athletes.open(file_athletes);
	if (!f_athletes.is_open()) {
		throw ExceptionNoFile(file_athletes);
	}

	f_events.open(file_events);
	if (!f_events.is_open()) {
		throw ExceptionNoFile(file_events);
	}

	parse_athletes(f_athletes);
	parse_games_data(f_events, year_to_load);
	f_athletes.close();
	f_events.close();
}


void Olympiad::parse_athletes(ifstream& f_athletes) {

	string line; // load line from f_athletes
	int line_num = 0; // number of the line maybe to signal an error
	const boost::regex pattern = boost::regex("([0-9]*)!(.*)!([MF])!([0-9\.]+|NA)!([0-9\.]+|NA)!([0-9\.]+|NA)");
	while (getline(f_athletes, line)) {
		line_num++;
		boost::smatch line_match;

		if (boost::regex_match(line, line_match, pattern)) {
			int id = stoi(line_match[1]);
			string name = line_match[2];
			Athlete::Gender gender = line_match[3] == "M" ? Athlete::Gender::M : Athlete::Gender::F;
			int age = stoi(line_match[4] != "NA" ? line_match[4] : (string)"0");
			double height = stod(line_match[5] != "NA" ? line_match[5] : (string)"0");
			double weight = stod(line_match[6] != "NA" ? line_match[6] : (string)"0");

			all_sportists.insert({ id, new Athlete(id, name, gender, age, height, weight) });

		}
		else {
			cout << "Nije dobar format linije broj: " << line_num << ", u datoteci sa atletama." << endl;
		}
	}
}


void Olympiad::parse_games_data(ifstream& f_events, int* year_to_load) {

	string line; // load line from f_athletes
	int line_num = 0; // number of the line maybe to signal an error
	boost::regex pattern = boost::regex("(^[0-9]{4}) (Summer|Winter)!(.*)!(.*)!(.*)!(Individual|Team)!(.*)!(.*)!(Gold|Silver|Bronze|)");
	while (getline(f_events, line)) {
		line_num++;
		boost::smatch line_match;

		if (boost::regex_match(line, line_match, pattern)) {

			int year = stoi(line_match[1]);
			// load only the given year in *year_to_load
			if (year_to_load != nullptr && *year_to_load != year) {
				continue;
			}
			Games::Season season = line_match[2] == "Winter" ? Games::Season::WINTER : Games::Season::SUMMER;
			string city = line_match[3];
			string sport_name = line_match[4];
			string discipline_name = line_match[5];
			Discipline::Event discipline_event = line_match[6] == "Team" ? Discipline::Event::TEAM : Discipline::Event::INDIVIDUAL;
			string country_name = line_match[7];
			string members_of_team = line_match[8];
			string medal_string = line_match[9];
			Competitor::Medal medal = Competitor::string_to_medal(medal_string);

			// getting/making information for creating the competitor
			Country* country = get_country(country_name);
			Sport* sport = get_sport(sport_name);
			Games* games = get_games(year, city, season);
			Discipline* discipline = get_discipline(discipline_name, discipline_event, sport);
			Competitor* competitor = new Competitor(medal, discipline, country);

			// getting all athletes' ids who participated
			boost::regex pattern_ids = boost::regex("([0-9]+)");
			boost::smatch id_match;

			auto ids_begin = boost::sregex_iterator(members_of_team.begin(), members_of_team.end(), pattern_ids);
			auto ids_end = boost::sregex_iterator();

			for (boost::sregex_iterator i = ids_begin; i != ids_end; ++i) {
				int id = stoi(i->str());

				if (all_sportists.find(id) == all_sportists.end()) {
					cout << "U datoteci sa igrama se naislo na sportistu sa identifikatorom: " << id
						<< ", medjutim njega nema u ucitanim informacijama o sportistima. Proveriti fajl sa sportistima. "
						<< "Ovaj identifikator se sada ignorise." << endl;
					continue;
				}

				sportists[id] = all_sportists[id];
				competitor->add_athlete(sportists[id]);
			}

			// if competitor already exists (same country, discipline, medal and athletes in the team) delete him
			if (!games->add_competitor(competitor)) {
				delete competitor;
			}
			else {
				country->add_competitor(competitor);
			}

		}
		else {
			cout << "Nije dobar format linije: " << line_num << ", u datoteci." << endl;
		}
	}

	// delete the athletes who didn't participate
	for (pair<const int, Athlete*>& it : all_sportists) {
		if (sportists.find(it.first) == sportists.end()) {
			delete it.second;
		}
	}
	all_sportists.clear();
}*/