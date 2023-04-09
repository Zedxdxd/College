#include <iostream>
#include <string>

using namespace std;

void find_range(double low, double high, int &my_low, int &my_high);

double find_number(int* arr, int &steps);

int main() {
	bool end = false;
	bool correct_range = false, correct_err = false;
	double low, high, err;

	while (!end) {
		cout << "1. Unesite opseg.\n"
				"2. Unesite tacnost.\n"
				"3. Zapocni pogadjanje.\n"
				"0. Zavrsi program.\n";
		int a;
		cin >> a;
		switch (a) {

		case 1:
			cout << "Donja granica? ";
			cin >> low;
			cout << "Gornja granica? ";
			cin >> high;
			if (low >= high) {
				cout << "Opseg nije validan.\n";
				correct_range = false;
			}
			else {
				cout << "Unet je opseg.\n";
				correct_range = true;
			}
			break;

		case 2:
			cout << "Tacnost? (pozitivan broj) ";
			cin >> err;
			if (err < 0) {
				cout << "Nevalidna tacnost.\n";
				correct_err = false;
			}
			else {
				cout << "Uneta je tacnost.\n";
				correct_err = true;
			}
			break;

		case 3:
			if (!correct_err || !correct_range) {
				if (!correct_err) {
					cout << "Nevalinda tacnost. Ne moze se zapoceti pogadjanje.\n";
				}
				if (!correct_range) {
					cout << "Nevalidan opseg. Ne moze se zapoceti pogadjanje.\n";
				}
			}
			else {
				int steps = 0;
				int my_low = 0, my_high = 0;
				find_range(low, high, my_low, my_high);
				int range[2] = { my_low, my_high };
				find_number(range, steps);
				cout << "Broj koraka: " << steps << ".\n";
			}
			break;

		case 4:
			end = true;
			break;
		}
	}

	return 0;
}


void find_range(double low, double high, int &my_low, int &my_high) {

	// trazenje opsega u kome je sadrzan uneti opseg
	if (low < 0 && high <= 0) {
		my_high = 0;
		my_low = -1;
		while (my_low > low) {
			my_low *= 2;
		}
	}
	else if (low < 0 && high > 0) {
		my_high = 1;
		my_low = -1;
		while (my_low > low) {
			my_low *= 2;
		}
		while (my_high < high) {
			my_high *= 2;
		}
	}
	else if (low >= 0 && high > 0) {
		my_low = 0;
		my_high = 1;
		while (my_high < high) {
			my_high *= 2;
		}
	}
}


double find_number(int* range, int &steps) {
	double low = range[0], high = range[1];
	double mid;
	string entry;

	// binarna pretraga u opsegu [range[0], range[1]]
	while (low <= high) {
		steps += 1;
		mid = (low + high) / 2;
		cout << "Trazeni broj je: " << mid << endl << "manje, vece, tacno? ";
		cin >> entry;
		if (entry == "tacno") {
			return mid;
		}
		else if (entry == "manje") {
			high = mid;
		}
		else if (entry == "vece") {
			low = mid;
		}
	}

}
