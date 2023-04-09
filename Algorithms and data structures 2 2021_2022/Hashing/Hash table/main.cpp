#include "HashTable.h"
#include <fstream>

Key read_key() {
	Key student;
	unsigned int ind, a;
	string name, surname, subj;
	cout << "Unesite indeks u formatu ggggbbbb: ";
	cin >> ind;
	cout << "Unesite ime studenta: ";
	cin >> name;
	cout << "Unesite prezime studenta: ";
	cin >> surname;
	student.index = ind;
	student.name = name;
	student.surname = surname;
	while (true) {
		cout << "1. Unesite predmet koji je student prijavio. " << endl <<
				"2. Kraj unosa predmeta. " << endl;
		cin >> a;
		if (a == 1) {
			cout << "Predmet: ";
			cin >> subj;
			student.add_subject(subj);
		}
		else if (a == 2) {
			break;
		}
		else {
			cout << "Izabrana opcija ne postoji. Kraj unosa predmeta. " << endl;
			break;
		}
	}
	return student;
}

void read_keys_from_file(ifstream& file, HashTable& table) {
	Key student;
	unsigned int ind, i;
	string name, surname, subj, tmp;
	getline(file, tmp);
	while (getline(file, tmp)) {
		ind = 0;
		for (i = 0; i < 8; i++) {
			ind = ind * 10 + (tmp[i] - '0');
		}
		name = "";
		i = 9;
		while (tmp[i] != ' ') {
			name += tmp[i++];
		}
		i++;
		surname = "";
		while (tmp[i] != ',') {
			surname += tmp[i++];
		}
		student.index = ind;
		student.name = name;
		student.surname = surname;
		i++;
		subj = "";
		while (true) {
			if (i == tmp.size()) {
				break;
			}
			else if (tmp[i] == ' ') {
				student.add_subject(subj);
				subj = "";
				i++;
			}
			else {
				subj += tmp[i++];
			}
		}
		if (table.insert_key(student) == false) {
			/*cout << "Nije bilo moguce umetnuti studenta sa indeksom: " << student.index / 10000 << "/";
			for (int j = 4; j < 8; j++) {
				cout << to_string(student.index)[j];
			}
			cout << endl;*/
		}
		student.subjects.clear();
	}
}

int main() {

	HashTable* table = nullptr, t(0, 0, nullptr);
	SplitSequenceLinearHashing func(0, 0);
	AddressFunction* hash;
	int a; //unos
	int p, cap, s1, s2;
	unsigned int ind;
	string subject;
	Key student, *tmp;
	ifstream file;

	while (true) {
		cout << "1. Kreiranje prazne hes tabele. " << endl <<
				"2. Ubacivanje kljuca/kljuceva. " << endl <<
				"3. Nalazenje kljuca. " << endl <<
				"4. Brisanje kljuca." << endl <<
				"5. Dodavanje ispita studentu. " << endl <<
				"6. Brisanje ispita iz liste prijavljenih. " << endl <<
				"7. Praznjenje tabele (brisanje svih kljuceva). " << endl <<
				"8. Prebrojavanje umetnutih kljuceva. " << endl <<
				"9. Velicina tabele. " << endl <<
				"10. Ispisi tabelu. " << endl <<
				"11. Odredjivanje stepena popunjenosti tabele. " << endl <<
				"0. Kraj programa. " << endl;
		cin >> a;

		if (a == 1) {
			cout << "Unesite p: (velicina tabele ce biti 2^p) ";
			cin >> p;
			cout << "Unesite kapacitet baketa: ";
			cin >> cap;
			cout << "Unesite prvi parametar adresne funkcije s1: ";
			cin >> s1;
			cout << "Unesite drugi parametar adresne funkcije s2: ";
			cin >> s2;

			func = SplitSequenceLinearHashing(s1, s2);
			hash = &func;
			t = HashTable(p, cap, hash);
			table = &t;
		}
		else if (a == 2) {
			if (table != nullptr) {
				cout << "1. Uvoz kljuceva iz datoteke. " << endl <<
						"2. Unos jednog kljuca. " << endl;
				cin >> a;
				if (a == 1) {
					string file_name;
					cout << "Unesite ime datoteke: " << endl;
					cin >> file_name;
					file.open(file_name);
					if (file.is_open()) {
						read_keys_from_file(file, *table);
					}
					else {
						cout << "Nije bilo moguce otvoriti datoteku. " << endl;
					}
					file.close();
				}
				else if (a == 2) {
					student = read_key();
					if (table->insert_key(student) == true) {
						cout << "Uspesno umetanje." << endl;
					}
					else {
						cout << "Nije uspelo umetanje studenta." << endl;
					}
				}
				else {
					cout << "Izabrana opcija ne postoji. " << endl;
				}
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 3) {
			if (table != nullptr) {
				cout << "Unesite indeks studenta koga zelite da nadjete u formatu ggggbbbb: ";
				cin >> ind;
				student.index = ind;
				tmp = table->find_key(student);
				if (tmp != nullptr) {
					cout << "Student je nadjen: " << tmp->index / 10000 << '/';
					for (int j = 4; j < 8; j++) {
						cout << to_string(tmp->index)[j];
					}
					cout << " " << tmp->name << " " << tmp->surname << ": ";
					for (auto it = tmp->subjects.begin(); it != tmp->subjects.end(); it++) {
						cout << *it << " ";
					}
					cout << endl;
				}
				else {
					cout << "Student nije nadjen. " << endl;
				}
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 4) {
			if (table != nullptr) {
				cout << "Unesite indeks studenta kojeg zelite da obrisete u formatu ggggbbbb: ";
				cin >> ind;
				student.index = ind;
				if (table->delete_key(student) == true) {
					cout << "Uspesno je obrisan student. " << endl;
				}
				else {
					cout << "Student nije uspesno obrisan. (nije u tabeli)" << endl;
				}
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 5) {
			if (table != nullptr) {
				cout << "Unesite indeks studenta u formatu ggggbbbb: ";
				cin >> ind;
				cout << "Unesite predmet: ";
				cin >> subject;
				table->add_subject(ind, subject);
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 6) {
			if (table != nullptr) {
				cout << "Unesite indeks studenta u formatu ggggbbbb: ";
				cin >> ind;
				cout << "Unesite predmet koji se brise: ";
				cin >> subject;
				table->remove_subject(ind, subject);
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 7) {
			if (table != nullptr) {
				table->clear();
				cout << "Ispraznjena je tabela.";
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 8){
			if (table != nullptr) {
				cout << "Broj kljuceva je: " << table->key_count() << "." << endl;
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 9) {
			if (table != nullptr) {
				cout << "Velicina tabele je: " << table->table_size() << "." << endl;
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 10) {
			if (table == nullptr) {
				cout << "Nije kreirana tabela." << endl;
			}
			else {
				cout << *table;
			}
		}
		else if (a == 11) {
			if (table != nullptr) {
				cout << "Stepen popunjenosti tabele je: " << table->fill_ratio() << "." << endl;
			}
			else {
				cout << "Nije kreirana tabela." << endl;
			}
		}
		else if (a == 0) {
			break;
		}
		else {
			cout << "Ne postoji izabrana opcija. " << endl;
		}
	}

	return 0;
}