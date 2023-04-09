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
Bucket::Bucket(int c, int d) {
	depth = d;
	cap = c;
	addr = 0;
}

int Bucket::size() const {
	return keys.size();
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

int Bucket::cnt_deleted() {
	int cnt = 0;
	for (unsigned int i = 0; i < keys.size(); i++) {
		if (keys[i].deleted == true) {
			cnt++;
		}
	}
	return true;
}

ostream& operator<<(ostream& os, const Bucket& b) {
	for (unsigned int i = 0; i < b.keys.size(); i++) {
		if (b.keys[i].deleted == false) {
			os << "\t" << b.keys[i].index / 10000 << '/';
			for (int j = 4; j < 8; j++) {
				cout << to_string(b.keys[i].index)[j];
			}
			cout << " " << b.keys[i].name << " " << b.keys[i].surname << ": ";
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


/* HashTable */
int HashTable::h(Key k) const {
	return (n - 1) & k.index;
}

int HashTable::hash_d_bits(Key k, int d) {
	string tmp = "";
	int ind = k.index, res = 0;
	for (int i = 0; i < d; i++) {
		tmp += ((char)(ind & 1) + '0');
		ind = ind >> 1;
	}
	for (unsigned int i = 0; i < tmp.size(); i++) {
		res = res * 2 + tmp[i] - '0';
	}
	return res;
}

HashTable::HashTable(int b, int cap) {
	this->cap = cap;
	depth = b;
	n = 1 << b;
	for (int i = 0; i < n; i++) {
		Bucket* bucket = new Bucket(cap, b);
		bucket->addr = i;
		entries.push_back(bucket);
	}
}

HashTable::~HashTable() {
	for (unsigned int i = 0; i < entries.size(); i++) {
		if (i == entries.size() - 1 || entries[i] != entries[i + 1]) {
			delete entries[i];
		}
	}
}

Key* HashTable::find_key(Key k) {
	int idx = hash_d_bits(k, depth);
	return entries[idx]->find_key(k);
}

bool HashTable::extend(Key k, int idx) {
	vector<Bucket*> new_entries;
	for (unsigned int i = 0; i < entries.size(); i++) {
		new_entries.push_back(entries[i]);
		new_entries.push_back(entries[i]);
	}
	entries = new_entries;
	depth++;
	n = 2 * n;
	idx = 2 * idx;
	Bucket* b = new Bucket(cap, entries[idx]->depth + 1);
	entries[idx]->depth++;
	entries[idx]->addr = entries[idx]->addr << 1;
	b->addr = entries[idx]->addr + 1;
	entries[idx + 1] = b;
	vector<Key> tmp;
	for (int i = 0; i < entries[idx]->size(); i++) {
		tmp.push_back(entries[idx]->keys[i]);
	}
	tmp.push_back(k);
	entries[idx]->keys.clear();
	for (unsigned int i = 0; i < tmp.size(); i++) {
		int addr = hash_d_bits(tmp[i], b->depth);
		if (addr == b->addr) {
			if (b->insert_key(tmp[i]) == false) {
				return false;
			}
		}
		else if (addr == entries[idx]->addr) {
			if (entries[idx]->insert_key(tmp[i]) == false) {
				return false;
			}
		}
	}
	return true;
}

bool HashTable::split(Key k, int idx, int idx0, int idx1) {
	Bucket* b = new Bucket(cap, entries[idx]->depth + 1);
	entries[idx]->depth++;
	entries[idx]->addr = entries[idx]->addr << 1;
	b->addr = entries[idx]->addr + 1;
	for (int i = idx0 + (idx1 - idx0) / 2 + 1; i <= idx1; i++) {
		entries[i] = b;
	}
	vector<Key> tmp;
	for (int i = 0; i < entries[idx0]->size(); i++) {
		tmp.push_back(entries[idx0]->keys[i]);
	}
	tmp.push_back(k);
	entries[idx0]->keys.clear();
	for (unsigned int i = 0; i < tmp.size(); i++) {
		int addr = hash_d_bits(tmp[i], b->depth);
		if (addr == b->addr) {
			if (b->insert_key(tmp[i]) == false) {
				return false;
			}
		}
		else if (addr == entries[idx0]->addr) {
			if (entries[idx0]->insert_key(tmp[i]) == false) {
				return false;
			}
		}
	}
	return true;
}

bool HashTable::insert_key(Key k) {
	int idx = hash_d_bits(k, depth);
	if (find_key(k) != nullptr) {
		return false;
	}
	if (entries[idx]->insert_key(k) == true) {
		return true;
	}
	int idx0, idx1 = idx;
	idx0 = idx;
	while (idx0 > 0 && entries[idx] == entries[idx0]) {
		idx0--;
	}
	if (entries[idx0] != entries[idx]) {
		idx0++;
	}
	while (idx1 < (int)entries.size() - 1 && entries[idx1] == entries[idx]) {
		idx1++;
	}
	if (entries[idx1] != entries[idx]) {
		idx1--;
	}

	if (idx0 == idx && idx1 == idx) {
		while (extend(k, idx) == false) {
			idx = hash_d_bits(k, depth);
		}
		return true;
	}
	else {
		while (split(k, idx, idx0, idx1) == false) {
			idx = hash_d_bits(k, depth);
			while (idx1 > 0 && entries[idx] != entries[idx1]) {
				idx1--;
			}
			if (entries[idx1] != entries[idx]) {
				idx1++;
			}
			while (idx0 < (int)entries.size() - 1 && entries[idx0] != entries[idx]) {
				idx0++;
			}
			if (entries[idx0] != entries[idx]) {
				idx0--;
			}
			if (idx0 == idx && idx1 == idx) {
				while (extend(k, idx) == false) {
					idx = hash_d_bits(k, depth);
				}
				break;
			}
		}
		return true;
	}
}

void HashTable::merge(int idx0, int idx1) {
	if (entries[idx0]->key_count() + entries[idx1]->key_count() <= cap) {
		vector<Key> tmp;
		for (int i = 0; i < entries[idx0]->size(); i++) {
			if (entries[idx0]->keys[i].deleted == false) {
				tmp.push_back(entries[idx0]->keys[i]);
			}
		}
		for (int i = 0; i < entries[idx1]->size(); i++) {
			if (entries[idx1]->keys[i].deleted == false) {
				tmp.push_back(entries[idx1]->keys[i]);
			}
		}
		entries[idx0]->keys = tmp;
		Bucket* b = entries[idx1];
		for (int i = idx0 + (idx1 - idx0) / 2 + 1; i <= idx1; i++) {
			entries[i] = entries[idx0];
		}
		entries[idx0]->addr = entries[idx0]->addr >> 1;
		entries[idx0]->depth--;
	}
}

void HashTable::shrink() {
	for (unsigned int i = 0; i < entries.size() - 1; i += 2) {
		if (entries[i] != entries[i + 1]) {
			return;
		}
	}
	vector <Bucket*> new_entries;
	for (unsigned int i = 0; i < entries.size() - 1; i += 2) {
		new_entries.push_back(entries[i]);
	}
	entries = new_entries;
	depth--;
	n = n / 2;
}

bool HashTable::delete_key(Key k) {
	int idx = hash_d_bits(k, depth);
	if (entries[idx]->delete_key(k) == true) {
		int idx0 = idx, idx1 = idx;
		bool possible_shrink = false;
		if (entries[idx]->depth == depth) {
			possible_shrink = true;
		}
		while (idx0 > 0 && entries[idx]->addr>>1 == entries[idx0]->addr >> 1 
									&& entries[idx]->depth == entries[idx0]->depth) {
			idx0--;
		}
		if (entries[idx0]->addr>>1 != entries[idx]->addr>>1 || entries[idx]->depth != entries[idx0]->depth) {
			idx0++;
		}
		while (idx1 < (int)entries.size() - 1 && entries[idx1]->addr>>1 == entries[idx]->addr>>1
									&& entries[idx]->depth == entries[idx1]->depth) {
			idx1++;
		}
		if (entries[idx1]->addr>>1 != entries[idx]->addr>>1 || entries[idx]->depth != entries[idx1]->depth) {
			idx1--;
		}
		merge(idx0, idx1);
		if (possible_shrink) {
			shrink();
		}
		return true;
	}
	else {
		return false;
	}
}

int HashTable::table_size() {
	return n;
}

double HashTable::fill_ratio() {
	int cnt_buckets = 0;
	for (unsigned int i = 0; i < entries.size(); i++) {
		if (i == entries.size() - 1 || entries[i] != entries[i + 1]) {
			cnt_buckets++;
		}
	}
	return (double)key_count() / (cnt_buckets * (double)cap);
}

int HashTable::key_count() {
	int cnt = 0;
	for (unsigned int i = 0; i < entries.size(); i++) {
		if (i == 0 || entries[i - 1] != entries[i]) {
			cnt += entries[i]->key_count();
		}
	}
	return cnt;
}

void HashTable::clear() {
	for (unsigned int i = 0; i < entries.size(); i++) {
		if (i == entries.size() - 1 || entries[i] != entries[i + 1]) {
			delete entries[i];
		}
	}
	depth = 0;
	n = 1 << depth;
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
	for (unsigned int i = 0; i < table.entries.size(); i++) {
		os << i << ".\t";
		if (i != 0 && table.entries[i - 1] == table.entries[i]) {
			os << "Isto kao " << i - 1 << ".";
		}
		else {
			os << endl << *table.entries[i];
		}
		os << endl << endl;
	}
	return os;
}




