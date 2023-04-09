#ifndef _HashTable_h_
#define _HashTable_h_

#include <iostream>
#include <string>
#include <list>
#include <vector>

using namespace std;

struct Key {
	unsigned int index = 0;
	string name = "", surname = "";
	list <string> subjects;
	bool deleted = false;
	
	bool operator<(Key k) {
		return this->index < k.index;
	}
	bool operator>(Key k) {
		return this->index > k.index;
	}
	bool add_subject(string subject);
	bool remove_subject(string subject);
};

class Bucket {
private:
	int cap;
	vector<Key> keys;
	Key comparing_key;

public:
	friend class HashTable;

	Bucket(int c);

	Key get_comparing_key() const;
	bool has_deleted() const;
	Key* find_key(Key k);
	bool insert_key(Key k);
	bool delete_key(Key k);
	int key_count();

	friend ostream& operator<<(ostream& os, const Bucket& b);
};

class AddressFunction {
public:
	virtual int get_address(Key k, int address, int i, int n, const Bucket& b) = 0;
};

class SplitSequenceLinearHashing : public AddressFunction {
	int s1;  // kljuc koji se umece manji od comparing_key
	int s2;  // kljuc koji se umcece veci od comparing_key
public:

	SplitSequenceLinearHashing(int s1, int s2);
	int get_address(Key k, int address, int i, int n, const Bucket& b) override;
};

class HashTable {

	struct Entry {
		bool empty;
		Bucket bucket;

		Entry(int cap) : bucket(cap) {
			empty = true;
		}
	};

	int n;
	vector <Entry> entries;
	AddressFunction* hash;

	int h(Key k) const;

public:
	HashTable(int p, int cap, AddressFunction* h);

	Key* find_key(Key k);
	bool insert_key(Key k);
	bool delete_key(Key k);
	void clear();
	int key_count();
	int table_size();
	double fill_ratio();
	bool add_subject(unsigned int ind, string subject);
	bool remove_subject(unsigned int ind, string subject);

	friend ostream& operator<<(ostream& os, const HashTable& table);

};

#endif