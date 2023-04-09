#ifndef _exceptions_h_

#define _exceptions_h_

#include <string>
#include <iostream>
#include "Olympiad.h"

// thrown when a file cannot be found
class ExceptionNoFile {
	std::string file_name;

public:
	ExceptionNoFile(std::string f);
	friend std::ostream& operator<<(std::ostream& os, const ExceptionNoFile& enf);
};


// thrown when specified country_name isn't loaded (doesn't exist in the file with events)
class ExceptionNoCountry {
	std::string country_name;
public:
	ExceptionNoCountry(std::string c_name);
	friend std::ostream& operator<<(std::ostream& os, const ExceptionNoCountry& enc);
};


// thrown when specified type (year and season) isn't loaded (doesn't exist in the file with events)
class ExceptionNoGames {
	pair<int, Games::Season> type;
public:
	ExceptionNoGames(pair<int, Games::Season> t);
	friend std::ostream& operator<<(std::ostream& os, const ExceptionNoGames& eng);
};

#endif