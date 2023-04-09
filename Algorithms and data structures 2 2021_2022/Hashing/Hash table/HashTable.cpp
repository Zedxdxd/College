#include "HashTable.h"

/* Key */
bool Key::add_subject(string subject) {
	bool found = false;
	for (auto it = subjects.begin(); it != subjects.end(); it++) {
		if (*it == subject) {
			found = true;
			return false;
		}
	}
	if (found == false) {
		subjects.push_back(subject);
		return true;
	}
	else {
		return false;
	}
}

bool Key::remove_subject(string subject) {
	bool found = false;
	for (auto it = subjects.begin(); it != subjects.end(); it++) {
		if (*it == subject) {
			found = true;
			subjects.erase(it);
			return true;
		}
	}
	if (found == false) {
		cout << "Student nije prijavio taj ispit. " << endl;
		return false;
	}
	else {
		return true;
	}
}


/* Bucket */
Bucket::Bucket(int c) {
	cap = c;
}

Key Bucket::get_comparing_key() const  {
	return comparing_key;
}

bool Bucket::has_deleted() const {
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted == true) {
			return true;
		}
	}
	return false;
}

Key* Bucket::find_key(Key k) {
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted == false && keys[i].index == k.index) {
			return &keys[i];
		}
	}
	return nullptr;
}

bool Bucket::insert_key(Key k) {
	if (keys.size() == 0) {
		comparing_key = k;
		keys.push_back(k);
		return true;
	}
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted == true) {
			keys[i] = k;
			return true;
		}
	}
	if ((int)keys.size() < cap) {
		keys.push_back(k);
		return true;
	}
	return false;
}

bool Bucket::delete_key(Key k) {
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted == false && keys[i].index == k.index) {
			keys[i].deleted = true;
			return true;
		}
	}
	return false;
}

int Bucket::key_count() {
	int cnt = 0;
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted != true) {
			cnt++;
		}
	}
	return cnt;
}

ostream& operator<<(ostream& os, const Bucket& b) {
	for (unsigned int i = 0; i < b.keys.size(); i++) {
		if (b.keys[i].deleted == false) {
			os << "\t" << b.keys[i].index / 10000 << '/';
			for (int j = 4; j < 8; j++) {
				os << to_string(b.keys[i].index)[j];
			}
			os << " " << b.keys[i].name << " " << b.keys[i].surname << ": ";
			for (auto it = b.keys[i].subjects.begin(); it != b.keys[i].subjects.end(); it++) {
				os << *it << " ";
			}
		}
		else {
			cout << "\tdeleted key";
		}
		os << endl;
	}
	return os;
}


/* AddressFunction*/
SplitSequenceLinearHashing::SplitSequenceLinearHashing(int s1, int s2) {
	this->s1 = s1;
	this->s2 = s2;
}

int SplitSequenceLinearHashing::get_address(Key k, int address, int i, int n, const Bucket& b) {
	if (k < b.get_comparing_key()) {
		return (address + s1 * i) % n;
	}
	else {
		return (address + s2 * i) % n;
	}
}


/* HashTable */
int HashTable::h(Key k) const {
	return (n - 1) & k.index;   // == k mod 2^p
}

HashTable::HashTable(int p, int cap, AddressFunction* h) {
	hash = h;
	n = 1 << p;
	for (int i = 0; i < n; i++) {
		entries.push_back(Entry(cap));
	}
}

Key* HashTable::find_key(Key k) {
	int idx = h(k);
	if (entries[idx].empty == true) {
		return nullptr;
	}
	Key* tmp = entries[idx].bucket.find_key(k);
	int addr = idx, i = 1;
	if (tmp == nullptr) {
		while ((addr = hash->get_address(k, idx, i++, n, entries[addr].bucket)) != idx) {
			if (entries[addr].empty == true) {
				return nullptr;
			}
			else {
				tmp = entries[addr].bucket.find_key(k);
				if (tmp != nullptr) {
					return tmp;
				}
			}
		}
		return tmp;
	}
	else {
		return tmp;
	}
}

bool HashTable::insert_key(Key k) {
	if (find_key(k) == nullptr) {
		int idx = h(k);
		if (entries[idx].bucket.insert_key(k) == true) {
			entries[idx].empty = false;
			return true;
		}
		else {
			int addr = idx, i = 1;
			while ((addr = hash->get_address(k, idx, i++, n, entries[addr].bucket)) != idx) {
				if (entries[addr].bucket.insert_key(k) == true) {
					entries[addr].empty = false;
					return true;
				}
			}
			return false;
		}
	}
	else {
		return false;
	}
	
}

bool HashTable::delete_key(Key k) {
	int idx = h(k);
	if (entries[idx].bucket.delete_key(k) == true) {
		return true;
	}
	else {
		Key* tmp = find_key(k);
		if (tmp == nullptr) {
			return false;
		}
		else {
			tmp->deleted = true;
			return true;
		}
	}
}

void HashTable::clear() {
	for (int i = 0; i < n; i++) {
		if (entries[i].empty != true) {
			for (unsigned int j = 0; j < entries[i].bucket.keys.size(); j++) {
				entries[i].bucket.keys[j].deleted = true;
			}
		}
	}
}

int HashTable::key_count() {
	int cnt = 0;
	for (int i = 0; i < n; i++) {
		if (entries[i].empty != true) {
			cnt += entries[i].bucket.key_count();
		}
	}
	return cnt;
}

int HashTable::table_size() {
	return n;
}

double HashTable::fill_ratio() {
	return (double)key_count()/ (n * (double)entries[0].bucket.cap);
}

bool HashTable::add_subject(unsigned int ind, string subject) {
	Key tmp;
	tmp.index = ind;
	Key* student = find_key(tmp);
	if (student == nullptr) {
		cout << "Ne postoji student sa takvim indeksom. ";
		return false;
	}
	else {
		return student->add_subject(subject);
	}
}

bool HashTable::remove_subject(unsigned int ind, string subject) {
	Key tmp;
	tmp.index = ind;
	Key* student = find_key(tmp);
	if (student == nullptr) {
		cout << "Ne postoji student sa takvim indeksom. " << endl;
		return false;
	}
	else {
		return student->remove_subject(subject);
	}
}

ostream& operator<<(ostream& os, const HashTable& table) {
	for (int i = 0; i < table.n; i++) {
		os << i << ". ";
		if (table.entries[i].empty == true) {
			os << "EMPTY" << endl;
		}
		else {
			if (table.entries[i].bucket.has_deleted()) {
				cout << "DELETED" << endl;
			}
			os << table.entries[i].bucket;
		}
		os << endl;
	}
	return os;
}
