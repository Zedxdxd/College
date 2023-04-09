#ifndef _Athlete_h_

#define _Athlete_h_

#include <string>
#include <iomanip>

using namespace std;

class Athlete{
public:
	enum class Gender { M, F };


	Athlete(int id, string n, Gender g, int a, double h, double w) {
		ID = id;
		name = n;
		gender = g;
		age = a;
		height = h;
		weight = w;
	}

	friend ostream& operator<<(ostream& os, const Athlete& a) {
		os << setw(6) << right << a.ID << '\t';
		os << setw(60) << left << a.name;
		os << setw(1) << (a.gender == Gender::M ? "M" : "F") << '\t';
		os << setw(2) << right << (a.age == 0 ? "NA" : to_string(a.age)) << '\t';
		os << setw(4) << right << (a.height == 0 ? "NA" : to_string(a.height).substr(0, 5)) << '\t';
		os << setw(4) << right << (a.weight == 0 ? "NA" : to_string(a.weight).substr(0, 5));
		return os;
	}

	double get_height() const {
		return height;
	}

	double get_weight() const {
		return weight;
	}

	int get_age() const {
		return age;
	}

	int get_ID() const {
		return ID;
	}

private:
	// if there isn't information about athlete's age/height/weight (NA in file_athletes), it will be saves as zero
	int ID, age;
	double height, weight;
	string name;
	Gender gender;
};

#endif