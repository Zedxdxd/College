#ifndef _Discipline_h_

#define _Discipline_h_

#include <string>

using namespace std;

class Discipline {
public:
	enum class Event {INDIVIDUAL, TEAM};

	Discipline(string n, Event ev) {
		name = n;
		event = ev;
	}

	Event get_event() const {
		return event;
	}

	string get_name() const {
		return name;
	}

	friend ostream& operator<<(ostream& os, const Discipline& discipline) {
		return os << discipline.name << " " << (discipline.event == Event::INDIVIDUAL ? "Individual" : "Team") << " ";
	}

	// useful for the method find_if
	friend bool operator==(const Discipline& disc1, const Discipline& disc2) {
		return disc1.event == disc2.event && disc1.name == disc2.name;
	}

private:
	Event event; // Individual or Team
	string name; // name od the discipline
};


#endif