#include <iostream>
#include <string>
#include <list>
#include <queue>
#include <stack>
#include <fstream>

using namespace std;

using LinkedList = list <string>;  // izveden tip ulancane liste koja cuva stringove

void print_list(LinkedList lst);

bool same_prefix(string str, string prefix);

void split(string line, string& word1, string& word2, char splitter);

class TreeNode {
	string key;
	TreeNode *left, *right, *parent;
	LinkedList lst;

public:
	TreeNode();
	void insert(TreeNode *&root, string key, string info);
	LinkedList search(TreeNode* root, string key);
	void remove(TreeNode*& root, string key);
	TreeNode* inorder_succ();
	void delete_tree(TreeNode*& root);
	void print_words_prefixes(TreeNode* root, string prefix);
	void print_tree(TreeNode* root);
};

int main() {

	TreeNode* root = nullptr;
	bool end = false;

	while (!end) {
		cout << "1. Formiranje stabla na osnovu zadatog skupa reci.\n"
				"2. Pretraga zadate reci.\n"
				"3. Umetanje nove reci.\n"
				"4. Ispis stabla.\n"
				"5. Brisanje zadatog kljuca.\n"
				"6. Ispis svih reci i njihovih prevoda sa zadatim prefiksom.\n"
				"7. Brisanje stabla iz memorije.\n"
				"0. Kraj rada.\n\n"
				"Izabrana opcija: ";

		int a; // pamcenje unosa
		string word, translation, prefix, file_name, line;
		ifstream dict;
		LinkedList temp; // ulancana lista za ispis
		cin >> a;

		switch (a) {
		case 1:
			cout << "Unesite iz koje datoteke zelite uvoz reci: ";
			cin >> file_name;
			dict.open(file_name);

			if (dict.is_open()) {
				while (getline(dict, line)) {
					split(line, word, translation, ' ');
					root->insert(root, word, translation);
				}
				dict.close();
			}
			else {
				cout << "Nije bilo moguce otvoriti fajl sa takvim imenom.\n";
			}
			break;

		case 2:
			if (!root) {
				cout << "Nema stabla u memoriji.\n";
				break;
			}
			cout << "Unesite rec: ";
			cin >> word;
			temp = root->search(root, word);
			if (temp.empty()) {
				cout << "Nema takve reci.\n";
			}
			else {
				cout << word << " -> ";
				print_list(temp);
			}
			break;

		case 3:
			cout << "Unesite rec: ";
			cin >> word;
			cout << "Unesite prevod: ";
			cin >> translation;
			root->insert(root, word, translation);
			break;

		case 4:
			if (!root) {
				cout << "Nema stabla u memoriji.\n";
				break;
			}
			root->print_tree(root);
			break;

		case 5:
			if (!root) {
				cout << "Nema stabla u memoriji.\n";
				break;
			}
			cout << "Unesite rec: ";
			cin >> word;
			root->remove(root, word);
			break;

		case 6:
			if (!root) {
				cout << "Nema stabla u memoriji.\n";
				break;
			}
			cout << "Unesite prefiks: ";
			cin >> prefix;
			root->print_words_prefixes(root, prefix);
			break;

		case 7:
			root->delete_tree(root);
			cout << "Stablo je obrisano!\n";
			break;

		case 0:
			root->delete_tree(root);
			end = true;
			break;
		}
		cout << endl;
	}
	return 0;
}

TreeNode::TreeNode() {
	left = nullptr; right = nullptr; parent = nullptr;
}


void TreeNode::insert(TreeNode*& root, string key, string info) {
	// ubacivanje novog para reci i prevoda
	TreeNode* curr = root, *prev = nullptr;

	// pretraga za cvor sa kljucem key
	while (curr) {
		if (key > curr->key) {
			prev = curr;
			curr = curr->right;
		}
		else if (key < curr->key) {
			prev = curr;
			curr = curr->left;
		}
		else {
			break;
		}
	}

	// ako cvor nije nadjen, curr == nullptr pa se alocira novi cvor
	if (!curr) {
		TreeNode* node = new TreeNode;
		node->key = key;
		node->parent = prev;
		node->lst.push_back(info);
		
		// ulancavanje u stablo
		if (!prev) {
			root = node;
		}
		else if (key > prev->key) {
			prev->right = node;
		}
		else if (key < prev->key) {
			prev->left = node;
		}
	}

	// ako je cvor sa key nadjen, u listu se umece novi prevod
	else {

		// ako vec postoji prevod, nemoj da ga dodajes:
		bool found = false;
		for (string i : curr->lst) {
			if (i == info) {
				found = true;
				break;
			}
		}
		if (!found) {
			curr->lst.push_back(info);
		}
	}
}


LinkedList TreeNode::search(TreeNode* root, string key) {
	TreeNode* curr = root;
	while (curr) {
		if (key > curr->key) {
			curr = curr->right;
		}
		else if (key < curr->key) {
			curr = curr->left;
		}
		else {
			return curr->lst;
		}
	}
	LinkedList temp;  // prazna lista ako se ne nadje kljuc
	return temp;
}


TreeNode* TreeNode::inorder_succ() {
	// sledbenik u inorderu

	TreeNode* succ = this->right;
	while (succ->left) {
		succ = succ->left;
	}
	return succ;
}


void TreeNode::remove(TreeNode*& root, string key) {
	TreeNode* curr = root;

	// trazenje cvora koji treba da se obrise
	while (curr) {
		if (key > curr->key) {
			curr = curr->right;
		}
		else if (key < curr->key) {
			curr = curr->left;
		}
		else {
			break;
		}
	}
	if (!curr) {
		cout << "Nema zadate reci u stablu.\n";
	}
	else {
		
		// cvor nema nijednog potomka, samo se podesavaju pokazivaci roditelja
		if (curr->right == nullptr && curr->left == nullptr) {
			TreeNode* p = curr->parent;
			if (!p) {
				root = nullptr;
			}
			else if (p->left == curr) {
				p->left = nullptr;
			}
			else {
				p->right = nullptr;
			}
			curr->parent = nullptr;
			curr->lst.clear();
			delete curr;
		}

		// cvor ima levog potomka, njegov roditelj preuzima tog potomka
		else if (curr->right == nullptr && curr->left) {
			TreeNode* p = curr->parent;
			if (!p) {
				root = curr->left;
			}
			else if (p->left == curr) {
				p->left = curr->left;
			}
			else {
				p->right = curr->left;
			}

			// dealokacija
			curr->left->parent = p;
			curr->lst.clear();
			delete curr;
		}

		// cvor ima desnog potomka, njegov roditelj preuzima tog potomka
		else if (curr->right && curr->left == nullptr) {
			TreeNode* p = curr->parent;
			if (!p) {
				root = curr->right;
			}
			else if (p->left == curr) {
				p->left = curr->right;
			}
			else {
				p->right = curr->right;
			}

			//dealokacija
			curr->right->parent = p;
			curr->lst.clear();
			delete curr;
		}

		// cvor ima oba potomka
		else {
			TreeNode* succ = curr->inorder_succ();

			// roditelj od succ preuzima podstablo cvora succ
			if (succ->parent->left == succ) {
				succ->parent->left = succ->right;
			}
			else {
				succ->parent->right = succ->right;
			}

			// prevezivanje stabla
			succ->left = curr->left;
			succ->right = curr->right;
			succ->parent = curr->parent;


			succ->left->parent = succ;

			/*if (succ->parent == curr) { //????????????
				succ->right->parent = succ;
			}*/

			// vezivanje roditelja sa succ, ako je roditelj nullptr, succ postaje koren
			if (!succ->parent) {
				root = succ;
			}
			else if (succ->parent->left == curr) {
				succ->parent->left = succ;
			}
			else {
				succ->parent->right = succ;
			}
			
			// ako postoji, desnom podstablu cvora koji se uklanja se dodeljuje roditelj succ
			if (curr->right) {
				curr->right->parent = succ;
			}

			// dealokacija
			curr->parent = nullptr;
			curr->lst.clear();
			delete curr;
		}
	}
}


void TreeNode::delete_tree(TreeNode*& root) {
	// brisanje korena dok se ne izbrisu svi cvorovi iz stabla.
	if (!root) {
		return;
	}

	string key = root->key;
	while (root) {
		root->remove(root, key);
		if (!root) {
			break;
		}
		key = root->key;
	}
}


void TreeNode::print_words_prefixes(TreeNode* root, string prefix) {
	// po level orderu se obilazi stablo i za svaki cvor se proveravaju prefiksi

	queue <TreeNode*> Q;
	bool found_word = false;
	if (!root) {
		cout << "Prazno stablo!" << endl;
	}

	else {
		TreeNode* curr = root;
		Q.push(curr);
		while (!Q.empty()) {
			curr = Q.front();
			Q.pop();

			if (same_prefix(curr->key, prefix)) {
				found_word = true;
				cout << curr->key << " -> ";
				print_list(curr->lst);
			}

			if (curr->left) {
				Q.push(curr->left);
			}
			if (curr->right) {
				Q.push(curr->right);
			}
		}
	}
	if (!found_word) {
		cout << "Nema nijedne reci sa zadatim prefiksom.\n";
	}
}


void TreeNode::print_tree(TreeNode* root) {

	// obrnut inorder, stablo se ispisuje s leva na desno 
	stack <TreeNode*> S;
	stack <int> S_level;  // stek za nivo na osnovu koga se zakljucuje razmak
	TreeNode* curr = root;
	int level = -1, curr_level;

	while (true) {

		// trazenje najdesnijeg cvora
		while (curr) {
			level++;
			S.push(curr);
			S_level.push(level);
			curr = curr->right;
		}

		// obrada najdesnijeg cvora
		if (!S.empty()) {
			curr = S.top();
			S.pop();
			curr_level = S_level.top();
			S_level.pop();

			// ispis
			//cout << endl;
			for (int i = 0; i < curr_level * 10; i++) {
				cout << " ";
			}
			cout << curr->key << endl;

			level = curr_level;
			curr = curr->left;
		}
		else {
			break;
		}
	}
}


void print_list(LinkedList lst) {
	for (string i : lst) {
		cout << i << " ";
	}
	cout << endl;
}


bool same_prefix(string str, string prefix) {
	for (unsigned int i = 0; i < prefix.length(); i++) {
		if (str[i] != prefix[i]) {
			return false;
		}
	}
	return true;
}


void split(string line, string& word1, string& word2, char splitter) {
	unsigned int i, idx = 0;
	word1 = ""; word2 = "";
	for (i = 0; i < line.size(); i++) {
		if (line[i] != splitter) {
			word1 += line[i];
		}
		else {
			idx = i + 1;
			break;
		}
	}
	for (i = idx; i < line.size(); i++) {
		word2 += line[i];
	}
}