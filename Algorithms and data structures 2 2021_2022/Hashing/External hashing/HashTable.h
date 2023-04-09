#ifndef _HashTable_h_
#define _HashTable_h_

#include <iostream>
#include <string>
#include <vector>
#include <list>

using namespace std;

struct Key {
	unsigned int index = 0;
	string name = "", surname = "";
	list <string> subjects;
	bool deleted = false;

	bool add_subject(string subject);
	bool remove_subject(string subject);
};

class Bucket {
private:
	int cap;
	vector<Key> keys;

	int depth, addr;

public:
	friend class HashTable;

	Bucket(int c, int d);

	int size() const;
	bool has_deleted() const;
	Key* find_key(Key k);
	bool insert_key(Key k);
	bool delete_key(Key k);
	int key_count();
	int cnt_deleted();

	friend ostream& operator<<(ostream& os, const Bucket& b);
};

class HashTable {
	int depth, n, cap;
	vector<Bucket*>entries;

	int h(Key k) const;
	int hash_d_bits(Key k, int d);
public:
	HashTable(int b, int cap);
	~HashTable();

	Key* find_key(Key k);
	bool extend(Key k, int idx);
	bool split(Key k, int idx, int idx0, int idx1);
	bool insert_key(Key k);
	void merge(int idx0,int idx1);
	void shrink();
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