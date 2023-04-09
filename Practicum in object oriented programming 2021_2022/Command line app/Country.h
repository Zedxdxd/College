#ifndef _Country_h_

#define _Country_h_

#include "Competitor.h"

using namespace std;

class Competitor;

class Country {
public:
	Country(string n) {
		name = n;
	}


	void add_competitor(Competitor* competitor) {
		competitors.push_back(competitor);
	}

	// probs delete??
	friend ostream& operator<<(ostream& os, const Country& country) {
		return os << country.name;
	}

	string get_name() const {
		return name;
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

private:
	vector<Competitor*> competitors; // competitors who competed for the country
	string name; // name of the country
};

#endif
