#ifndef _Competitor_h_

#define _Competitor_h_

#include "Country.h"
#include "Athlete.h"
#include "Discipline.h"

using namespace std;

class Competitor {
public:
	enum class Medal {NONE, GOLD, SILVER, BRONZE}; // GOLD = 1, SILVER = 2, BRONZE = 3
	
	// probs delete??
	friend ostream& operator<<(ostream& os, const Medal& medal) {
		switch (medal) {
		case Medal::GOLD:
			os << "Gold";
			break;
		case Medal::SILVER:
			os << "Silver";
			break;
		case Medal::BRONZE:
			os << "Bronze";
			break;
		}
		return os;
	}

	static Medal string_to_medal(string medal_string) {
		if (medal_string == "Gold") {
			return Competitor::Medal::GOLD;
		}
		else if (medal_string == "Silver") {
			return Competitor::Medal::SILVER;
		}
		else if (medal_string == "Bronze") {
			return Competitor::Medal::BRONZE;
		}
		else {
			return Competitor::Medal::NONE;
		}
	}


	Competitor(Medal m, Discipline* disc, Country* c) {
		medal = m;
		discipline = disc;
		country = c;
	}

	void add_athlete(Athlete* athlete) {
		team.push_back(athlete);
	}

	Medal get_medal() {
		return medal;
	}

	Discipline* get_discipline() {
		return discipline;
	}

	Country* get_country() {
		return country;
	}

	// useful for the method find_if;
	friend bool operator==(const Competitor& competitor1, const Competitor& competitor2) {
		return competitor1.medal == competitor2.medal && *competitor1.discipline == *competitor2.discipline &&
				competitor1.country == competitor2.country && competitor1.team == competitor2.team;
	}

	// size and operator[] allow for iterating through team
	int size() {
		return team.size();
	}

	Athlete* operator[](int i) {
		return team[i];
	}

	const Athlete* operator[](int i) const {
		return team[i];
	}

private:
	vector<Athlete*> team; // athletes of which competitor is consisted
	Medal medal; // won medal
	Discipline* discipline; // in what discipline did the competitor compete
	Country* country; // for what country did the competitor compete
};

#endif
