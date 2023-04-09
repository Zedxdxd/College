#include "tree.h"
#include <cstdlib>
#include <ctime>
#include <fstream>
#include <chrono>

// rng intova za stablo
/*int num;
int count = 1;
unsigned seed = time(0);

srand(seed);

while (count <= 300)
{
	num = 100 + rand() % 899;
	t.insert(to_string(num));
	count++;
}
count = 1;
while (count < 0) {
	num = 100 + rand() % 899;
	t.remove(to_string(num));
	count++;
}*/

int main() {
	bool program_end = false;
	Tree t;

	while (!program_end) {
		cout << "1. Stvaranje B* stabla." << endl <<
				"2. Brisanje stvorenog B* stabla." << endl <<
				"3. Pretraga na kljuc." << endl <<
				"4. Ispis unetog stabla." << endl <<
				"5. Umetanje kljuca u stablo." << endl <<
				"6. Brisanje kljuca iz stabla." << endl <<
				"7. Nalazenje broja kljuceva manjih od zadatog kljuca." << endl <<
				"0. Kraj programa." << endl;
		int entry, deg;
		string key;
		ifstream f;
		cin >> entry;
		switch (entry) {

		// unos stabla
		case 1:
			cout << "Unesite red stabla" << endl;
			cin >> deg;
			cout << "Da li zelite uvoz kljuceva iz datoteke? 1 = DA, 0 = NE." << endl;
			cin >> entry;
			if (deg < 3 || deg > 10) {
				cout << "Nekorektan unos. Moguc red stabla je [3, 10]." << endl;
			}
			else {
				t.delete_tree();
				t = Tree(deg);
			}

			// uvoz kljuceva iz datoteke
			if (entry == 1) {
				f.open("rec.txt");
				if (!f.is_open()) {
					cout << "Nije bilo moguce otvaranje datoteke." << 
						"Stvoreno je stablo bez kljuceva reda " << deg << endl;
				}
				else {
					while (getline(f, key)) {
						t.insert(key);
					}
					f.close();
				}
			}
			else if (entry != 0){
				cout << "Izabrana opcija ne postoji." << endl;
			}
			break;

		// brisanje stabla
		case 2:
			if (t.get_root() != nullptr) {
				t.delete_tree();
				cout << "Stablo je obrisano" << endl;
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// pretraga
		case 3:
			if (t.get_root() != nullptr) {
				cout << "Unesite kljuc koji zelite da pretrazite." << endl;
				cin >> key;
				if (t.search(key)) {
					cout << "Kljuc se nalazi u stablu." << endl;
				}
				else {
					cout << "Kljuc se ne nalazi u stablu." << endl;
				}
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// ispis
		case 4:
			if (t.get_root() != nullptr) {
				cout << t << endl;
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// umetanje kljuca
		case 5:
			if (t.get_root() != nullptr) {
				cout << "Unesite kljuc koji zelite da umetnete." << endl;
				cin >> key;
				if (t.insert(key)) {
					cout << "Kljuc je uspesno unet." << endl;
				}
				else {
					cout << "Kljuc vec postoji u stablu." << endl;
				}
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// brisanje kljuca
		case 6:
			if (t.get_root() != nullptr) {
				cout << "Unesite kljuc koji zelite da obrisete." << endl;
				cin >> key;
				if (t.remove(key)) {
					cout << "Kljuc je uspesno obrisan." << endl;
				}
				else {
					cout << "Kljuc ne postoji u stablu." << endl;
				}
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// nalazenje broja manjih kljuceva
		case 7:
			if (t.get_root() != nullptr) {
				cout << "Unesite kljuc od koga zelite da trazite broj manjih kljuceva." << endl;
				cin >> key;
				cout << "Broj manjih kljuceva je: " << t.count_smaller_keys(key) << endl;
				break;
			}
			else {
				cout << "Ne postoji stablo." << endl;
			}
			break;

		// kraj programa
		case 0:
			program_end = true;
			break;

		default:
			cout << "Opcija sa unetim brojem ne postoji." << endl;
		}

	}

	return 0;
}