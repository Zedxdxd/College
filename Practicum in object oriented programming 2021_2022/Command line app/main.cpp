#include <iostream>
#include "Olympiad.h"
//#include <boost\regex.hpp>
#include <regex>
#include "Exceptions.h"
#include <chrono>
/*struct Parameters {
	string* sport_filter, * country_filter;
	int* year_filter;
	Discipline::Event* event_filter;
	Competitor::Medal* medal_filter;
};*/

// only checks if str is a positive number
bool is_number(string str) {
	return !str.empty() && std::find_if(str.begin(), str.end(), 
		[](unsigned char c) { return !std::isdigit(c); }) == str.end();
}

int main() {
	int year = 1992;
	//Olympiad& o = Olympiad::get_instance();
	//o.parse("athletes1.txt", "events1.txt", nullptr);
	//cout << o;

	Competitor::Medal* medal = new Competitor::Medal();
	*medal = Competitor::Medal::NONE;
	Discipline::Event* event = new Discipline::Event();
	*event = Discipline::Event::INDIVIDUAL;
	Parameters p = { nullptr, nullptr, nullptr, nullptr, nullptr};

	bool program_end = false, parsed_data = false;
	int *year_to_load = nullptr;
	Olympiad& o = Olympiad::get_instance();

	while (!program_end) {
		cout	<< "--------------------------------------------------------------------------------------------------------------" << endl
				<< "1. Ucitaj podatke iz fajlova." << endl
				<< "2. Ispis osnovnih metrika (moguce filtriranje)." << endl
				<< "3. Ispis dodatnih metrika (bez mogucnosti filtriranja)." << endl
				<< "0. Kraj rada." << endl
				<< "--------------------------------------------------------------------------------------------------------------" << endl
				<< "Unesite broj opcije: " << endl;
		string main_option;
		getline(cin, main_option);

		// option to load data
		if (main_option == "1") {
			if (parsed_data) {
				cout << "Postoje vec neki ucitani podaci. Da li zelite da pisete preko njih?" << endl << "1. Da." << endl << "x. Ne" << endl;
				string entry;
				getline(cin, entry);
				if (entry != "1") {
					continue;
				}
				Olympiad::delete_instance();
				o = Olympiad::get_instance();
			}
			string file_athletes, file_events, mode;
			cout << "Unesite fajl iz koga zelite da ucitate podatke o igrama: ";
			getline(cin, file_events);
			cout << "Unesite fajl iz koga zelite da ucitate podatke o atletama: ";
			getline(cin, file_athletes);
			cout << "Izaberite rezim rada: " << endl << "1. Pojedinacni. " << endl << "x. Grupni. " << endl;
			getline(cin, mode);
			if (mode == "1") {
				cout << "Unesite godinu za koju zelite ucitane podatke: ";
				getline(cin, main_option);
				*year_to_load = stoi(main_option);
			}
			else {
				cout << "Ucitava se podrazumevano svaka godina." << endl;
			}
			cout << "Pocinje ucitavanje podataka iz fajlova..." << endl;
			try {
				o.std_parse(file_athletes, file_events, year_to_load);
				system("cls");
				cout << "Uspesno ucitani podaci!" << endl;
				parsed_data = true;
			}
			catch (ExceptionNoFile& enf) {
				cout << enf << endl;
				cout << "Ucitavanje podataka neuspesno!" << endl;
			}
		}

		// option for basic metric
		else if (main_option == "2") {

			// ako podaci nisu ucitani
			if (!parsed_data) {
				cout << "Podaci nisu ucitani, prvo ucitajte podatke pre nego sto pokusate rad sa njima." << endl;
				continue;
			}

			string basic_metric_option;
			cout << "Izaberite koju osnovnu metriku zelite da ispisete:" << endl
				<< "1. Broj ucesnika na svim dogadjajima na Olimpijskim igrama." << endl
				<< "2. Ukupan broj disciplina na Olimpijskim igrama." << endl
				<< "3. Prosecnu visinu svih sportista." << endl
				<< "4. Prosecnu tezinu svih sportista." << endl
				<< "Unesite opciju: ";
			getline(cin, basic_metric_option);

			if (basic_metric_option != "1" && basic_metric_option != "2" && basic_metric_option != "3"
				&& basic_metric_option != "4") {
				cout << "Uneta opcija ne postoji. Povratak na pocetni meni." << endl;
				continue;
			}

			Parameters filters;
			string entry;

			cout << "Unos filtera (uneti -1 ako ne zelite da se taj filter uzima u obzir):" << endl;

			// filter for sport
			cout << "Sport: ";
			getline(cin, entry);

			if (entry == "-1") {
				filters.sport_filter = nullptr;
			}
			else {
				filters.sport_filter = new string(entry);
			}

			// filter for country
			cout << "Zemlja za koju nastupa takmicar: ";
			getline(cin, entry);
			if (entry == "-1") {
				filters.country_filter = nullptr;
			}
			else {
				filters.country_filter = new string(entry);
			}

			// filter for year
			cout << "Godina odrzavanja Olimpijskih igara: ";
			getline(cin, entry);
			if (entry == "-1") {
				filters.year_filter = nullptr;
			}
			else if (!is_number(entry)) {
				cout << "Godina nije uneta kao pozitivan broj. Povratak na pocetni meni." << endl;
				continue;
			}
			else {
				filters.year_filter = new int(stoi(entry));
			}

			// filter for type of event
			cout << "Tip dogadjaja (individualan ili timski, ostale vrednosti se ne uzimaju u obzir): ";
			getline(cin, entry);
			if (entry == "individualan") {
				filters.event_filter = new Discipline::Event(Discipline::Event::INDIVIDUAL);
			}
			else if (entry == "timski") {
				filters.event_filter = new Discipline::Event(Discipline::Event::TEAM);
			}
			else {
				filters.event_filter = nullptr;
			}

			// filter for the medal
			cout << "Osvojena medalja (zlatna, srebrna, bronzana ili bez, ostale vrednosti se ne uzimaju u obzir): ";
			getline(cin, entry);
			if (entry == "zlatna") {
				filters.medal_filter = new Competitor::Medal(Competitor::Medal::GOLD);
			}
			else if (entry == "srebrna") {
				filters.medal_filter = new Competitor::Medal(Competitor::Medal::SILVER);
			}
			else if (entry == "bronzana") {
				filters.medal_filter = new Competitor::Medal(Competitor::Medal::BRONZE);
			}
			else if (entry == "bez") {
				filters.medal_filter = new Competitor::Medal(Competitor::Medal::NONE);
			}
			else {
				filters.medal_filter = nullptr;
			}

			// number of participants
			if (basic_metric_option == "1") {
				cout << "Broj ucesnika na svim dogadjajima na Olimpijskim igrama koji ispunjavaju prethodno zadate filtere je: "
					<< (o.number_competitors(filters)) << "." << endl;
			}

			// number of disciplines
			else if (basic_metric_option == "2") {
				cout << "Broj disciplina na Olimpijskim igrama koje ispunjavaju prethodno zadate filtere je: "
					<< o.number_disciplines(filters) << "." << endl;
			}

			// average height
			else if (basic_metric_option == "3") {
				cout << "Prosecna visina svih sportista koji ispunjavaju prethodno zadate filtere je: "
					<< o.avg_height(filters) << "." << endl;
			}

			// average weight
			else if (basic_metric_option == "4") {
				cout << "Prosecna tezina svih sportista koji ispunjavaju prethodno zadate filtere je: "
					<< o.avg_weight(filters) << "." << endl;
			}
		}

		// option for additional metric
		else if (main_option == "3") {

			// ako podaci nisu ucitani
			if (!parsed_data) {
				cout << "Podaci nisu ucitani, prvo ucitajte podatke pre nego sto pokusate rad sa njima." << endl;
				continue;
			}

			string additional_metric_option;
			cout << "Izaberite koju dodatnu metriku zelite da ispisete:" << endl
				<< "1. Broj razlicitih sportova u kojima je uneta drzava osvojila barem jednu medalju." << endl
				<< "2. Ispis tri najbolje drzave na zadatim Olimpijskim igrama." << endl
				<< "3. Ispis svih drzava koje su barem na jednim igrama ostvarile najbolji uspeh." << endl
				<< "4. Ispis deset najmladjih ucesnika koji su na svom prvom ucescu osvojili medalju." << endl
				<< "5. Ispis svih parova drzava-sportista, za sve sportiste koji su osvojili barem jednu medalju u" << endl
					<< "pojedinacnoj i barem jednu medalju grupnoj konkurenciji." << endl
				<< "6. Ispis svih sportista koji su ucestvovali na zadatom paru Olimpijskih igara." << endl
				<< "7. Ispis svih timova koje je zadata drzava imala na zadatim igrama." << endl
				<< "8. Ispis svih gradova u kojima su Olimpijske igre odrzane barem jednom." << endl
				<< "Unesite opciju: ";
			getline(cin, additional_metric_option); 

			if (additional_metric_option != "1" && additional_metric_option != "2" && additional_metric_option != "3"
				&& additional_metric_option != "4" && additional_metric_option != "5" && additional_metric_option != "6" 
				&& additional_metric_option != "7" && additional_metric_option != "8") {
				cout << "Uneta opcija ne postoji. Povratak na pocetni meni." << endl;
				continue;
			}

			// number sports with at least one medal
			if (additional_metric_option == "1") {
				string country_name;
				cout << "Unesite za koju drzavu zelite da ispisete broj razlicitih sportova: ";
				getline(cin, country_name);

				try {
					cout << "Broj razlicitih sportova u kojima je drzava " + country_name + " osvojila barem jednu medalju je: "
						<< o.number_sports_with_at_least_one_medal(country_name) << endl;
				}
				catch (ExceptionNoCountry& enc) {
					cout << enc << endl;
				}
			}

			// three best countries
			else if (additional_metric_option == "2") {
				string year, season;
				cout << "Unesite godinu odrzavanja Olimpijskih igara: ";
				getline(cin, year);
				if (!is_number(year)) {
					cout << "Godina nije uneta kao pozitivan broj. Povratak na pocetni meni." << endl;
					continue;
				}
				cout << "Unesite sezonu odrzavanja Olimpijskih igara (Summer ili Winter): ";
				getline(cin, season);

				if (season != "Winter" && season != "Summer") {
					cout << "Nevalidna sezona. Povratak na pocetni meni!" << endl;
					continue;
				}

				try {
					vector<Country*> three_best_countries = o.three_best_countries(stoi(year), season);
					cout << "Tri najbolje drzave su: " << endl;
					for (unsigned int i = 0; i < three_best_countries.size(); i++) {
						cout << i + 1 << ". " << * three_best_countries[i] << endl;
					}
					cout << endl;
				}
				catch (ExceptionNoGames& eng) {
					cout << eng << endl;
				}
			}

			// best countries
			else if (additional_metric_option == "3") {
				cout << "Drzave koje su na barem jednim igrama ostvarile najbolji uspeh: " << endl;
				unordered_set<Country*> countries = o.best_countries();
				for (Country* it : countries) {
					cout << *it << endl;
				}
				cout << endl;
			}

			// ten youngest athletes
			else if (additional_metric_option == "4") {
				cout << "Deset najmladjih ucesnika koji su na svom prvom ucescu osvojili medalju: " << endl;
				vector<Athlete*> youngest = o.ten_youngest_athletes();
				for (Athlete* it : youngest) {
					cout << *it << endl;
				}
				cout << endl;
			}

			// pairs country-sportist
			else if (additional_metric_option == "5") {
				cout << "Parovi drzava-sportista koji su osvojili bar jednu medalju u grupnoj i u individualnoj konkurenciji:" << endl;
				vector<pair<Country*, Athlete*>> pairs = o.pairs_countries_athletes();

				for (pair<Country*, Athlete*> p : pairs) {
					cout << setw(25) << left << *p.first << " " << *p.second << endl;
				}
				cout << "broj parova je " << pairs.size() << endl;
			}

			// all sportists in given pair of games
			else if (additional_metric_option == "6") {
				string year1, season1, year2, season2;
				cout << "Unesite godinu odrzavanja prvih Olimpijskih igara: ";
				getline(cin, year1);
				if (!is_number(year1)) {
					cout << "Godina nije uneta kao broj. Povratak na pocetni meni." << endl;
					continue;
				}
				cout << "Unesite sezonu odrzavanja prvih Olimpijskih igara (Summer ili Winter): ";
				getline(cin, season1);

				if (season1 != "Winter" && season1 != "Summer") {
					cout << "Nevalidna sezona. Povratak na pocetni meni!" << endl;
					continue;
				}

				cout << "Unesite godinu odrzavanja drugih Olimpijskih igara: ";
				getline(cin, year2);
				if (!is_number(year2)) {
					cout << "Godina nije uneta kao broj. Povratak na pocetni meni." << endl;
					continue;
				}
				cout << "Unesite sezonu odrzavanja drugih Olimpijskih igara (Summer ili Winter): ";
				getline(cin, season2);

				if (season2 != "Winter" && season2 != "Summer") {
					cout << "Nevalidna sezona. Povratak na pocetni meni!" << endl;
					continue;
				}

				try {
					unordered_set<Athlete*> athletes = o.sportists_in_both_games(stoi(year1), season1, stoi(year2), season2);

					for (Athlete* athlete : athletes) {
						cout << *athlete << endl;
					}
				}
				catch (ExceptionNoGames& eng) {
					cout << eng << endl;
				}
			}

			// teams with given country and games
			else if (additional_metric_option == "7") {
				string year, season, country_name;
				cout << "Unesite drzavu za koju zelite timove:";
				getline(cin, country_name);
				cout << "Unesite godinu odrzavanja Olimpijskih igara: ";
				getline(cin, year);
				if (!is_number(year)) {
					cout << "Godina nije uneta kao broj. Povratak na pocetni meni." << endl;
					continue;
				}
				cout << "Unesite sezonu odrzavanja Olimpijskih igara (Summer ili Winter): ";
				getline(cin, season);

				if (season != "Winter" && season != "Summer") {
					cout << "Nevalidna sezona. Povratak na pocetni meni!" << endl;
					continue;
				}

				try {
					vector < pair<vector<Athlete*>, Discipline*>> teams = o.teams_with_country_and_game(country_name, stoi(year), season);

					for (pair<vector<Athlete*>, Discipline*> p : teams) {
						cout << "Disciplina je: " << p.second->get_name() << endl;
						for (Athlete* athlete : p.first) {
							cout << *athlete << endl;
						}
						cout << endl << endl;
					}
				}
				catch (ExceptionNoCountry& enc) {
					cout << enc << endl;
				}
				catch (ExceptionNoGames& eng) {
					cout << eng << endl;
				}
			}

			// cities
			else if (additional_metric_option == "8") {
				cout << "Gradovi koji su bili bar jednom domacini: " << endl;
				unordered_set<string> cities = o.cities_hosts();
				int cnt = 0;
				for (string city : cities) {
					cout << setw(25)<< left << city;
					if (++cnt == 3) {
						cout << endl;
						cnt = 0;
					}
				}
				cout << endl;
			}

		}

		// option to and the program
		else if (main_option == "0") {
			string yes_no;
			cout << "Da li ste sigurni da zelite da zavrsite program? (1 je potvrdan odgovor, sve ostalo odrican) ";
			getline(cin, yes_no);
			if (yes_no == "1") {
				program_end = true;
			}
			system("cls");
		}
		else {
			cout << "Opcija ne postoji ili nije validan unos. Povratak na pocetni meni." << endl;
		}
	}

	Olympiad::delete_instance();
	return 0;

}