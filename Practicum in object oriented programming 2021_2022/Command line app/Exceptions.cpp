#include "Exceptions.h"

ExceptionNoFile::ExceptionNoFile(std::string f_name) : file_name(f_name) {}

std::ostream& operator<<(std::ostream& os, const ExceptionNoFile& enf) {
	return os << "Ne postoji fajl sa imenom: " + enf.file_name;
}


ExceptionNoCountry::ExceptionNoCountry(std::string c_name) : country_name(c_name) {}

std::ostream& operator<<(std::ostream& os, const ExceptionNoCountry& enc) {
	return os << "Zemlja sa imenom: " << enc.country_name << " nije ucitana, pa se ne moze nista sa njom raditi " <<
		"(Nema je u pocetnom fajlu).";
}


ExceptionNoGames::ExceptionNoGames(pair<int, Games::Season> t) : type(t) {}

std::ostream& operator<<(std::ostream& os, const ExceptionNoGames& eng) {
	return os << "Igre godine: " << eng.type.first << ", sezone: " <<
		(eng.type.second == Games::Season::SUMMER ? "Summer" : "Winter") << " nisu ucitane, pa se ne moze sa njima raditi " <<
		"(nema ih u pocetnom fajlu).";
}