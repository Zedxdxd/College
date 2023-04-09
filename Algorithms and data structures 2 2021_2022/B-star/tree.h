#ifndef _tree_h_
#define _tree_h_

#include <iostream>
#include <string>
#include <vector>
#include <queue>
#include <stack>
#include <cmath>

using namespace std;

class Tree {
	struct Node {
		vector <string> keys;
		vector <Node*> ptrs;

		Node(int deg) {
			for (int i = 0; i < deg; i++) {
				ptrs.push_back(nullptr);
			}
		}

		bool is_leaf();
		ostream& print_keys(ostream& os);
		int count_ptrs();
	};

	int m, max_keys_root, min_keys;
	Node* root;

private:
	friend int binary_search(vector <string> vec, string key);
	void redistribute(Node* left, Node* parent, int idx_p, Node* right, string key);
	string split(Node* left, Node* mid, Node* parent, int idx_p, Node* right, string key);
	Node* swap_with_successor(Node* curr, int idx, stack <pair <Node*, int>>& S);
	void lend(Node* dst, Node* src, Node *parent, int idx, string oc);
	void merge(Node* left, Node* mid, Node* right, Node* parent, int idx);
	void merge_two(Node* left, Node* right, Node* parent, int idx);
	int count_keys(Node* root);
	void copy_tree(const Tree& t);
	void move_tree(Tree& t);
	

public:
	Tree(int deg = 0);
	Tree(const Tree& t);
	Tree(Tree&& t);
	Tree& operator=(const Tree& t);
	Tree& operator=(Tree&& t);
	~Tree();

	bool search(string key);
	bool insert(string key);
	bool remove(string key);
	void inorder_rec(Node * root);
	int count_smaller_keys(string X);
	void delete_tree();
	Node* get_root();

	friend ostream& operator<< (ostream& os, const Tree& t);
};

#endif

