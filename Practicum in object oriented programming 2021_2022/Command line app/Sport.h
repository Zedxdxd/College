#ifndef _Sport_h_

#define _Sport_h_

#include <vector>
#include "Discipline.h"

class Sport {
public:

	Sport(string n) {
		name = n;
	}

	// determine if discipline belong to sport, if not return nullptr
	Discipline* find_discipline(Discipline* discipline) {
		auto ret_value = find_if(disciplines.begin(), disciplines.end(),
									[discipline](Discipline* disc) {return *discipline == *disc;  });
		if (ret_value == disciplines.end()) {
			return nullptr;
		}
		else {
			return *ret_value;
		}
	}

	void add_discipline(Discipline* disc) {
		disciplines.push_back(disc);
	}

	friend ostream& operator<<(ostream& os, const Sport& sport) {
		os << "Sport: " + sport.name + " ";
		for (auto disc : sport.disciplines) {
			os << disc->get_name() << " " << (disc->get_event() == Discipline::Event::INDIVIDUAL ? "Individual " : "Team ") << "; ";
		}
		return os << endl;
	}

	string get_name() const {
		return name;
	}

	~Sport() {
		for (Discipline* it : disciplines) {
			delete it;
		}
	}

private:
	vector<Discipline*> disciplines; // disciplines that belong to this sport
	string name; // name of the sport
};

#endif