#ifndef _Games_h_

#define _Games_h_

#include <vector>
#include <string>
#include "Competitor.h"

using namespace std;

class Games {
public:
	enum class Season {WINTER, SUMMER};


	Games(int y, string c, Season s) {
		city = c;
		type = pair<int, Season>(y, s);
	}

	// if competitor already exists, don't add him and return false
	bool add_competitor(Competitor* competitor) {
		if (find_if(competitors.begin(), competitors.end(), [competitor](Competitor* comp) {return *comp == *competitor; })
					!= competitors.end()) {
			return false;
		}
		competitors.push_back(competitor);
		return true;
	}

	// size and operator[] allow for iterating through competitors
	int size() {
		return competitors.size();
	}

	Competitor* operator[](int i) {
		return competitors[i];
	}

	const Competitor* operator[](int i) const {
		return competitors[i];
	}

	int get_year() const {
		return type.first;
	}

	Season get_season() const {
		return type.second;
	}

	pair<int, Season> get_type() const {
		return type;
	}

	friend bool operator<(pair<int, Season> t1, pair<int, Season> t2) {
		return t1.first < t2.first || (t1.first == t2.first && t1.second == Season::WINTER && t2.second == Season::SUMMER);
	}

	string get_city() const {
		return city;
	}
	
	// only deleting competitors here 
	~Games() {
		for (Competitor* it : competitors) {
			delete it;
		}
	}

private:
	vector<Competitor*> competitors; // competitors who participated in games
	string city; // city where the games were held
	pair<int, Season> type; // year and season of games

};

#endif
