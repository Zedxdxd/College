#include "tree.h"

int binary_search(vector <string> vec, string key) {
	// binarna pretraga niza kljuceva, ako postoji vraca poziciju kljuca key, ako ne postoji vraca poziciju gde
	// bi bio da postoji; ako je prazan niz vraca -1
	int n = vec.size();
	if (n == 0) {
		return -1;
	}
	int low = 0, high = n - 1, mid = 0;
	while (low <= high) {
		mid = (low + high) / 2;
		if (vec[mid] == key) {
			return mid;
		}
		else if (key > vec[mid]) {
			low = mid + 1;
		}
		else {
			high = mid - 1;
		}
	}
	if (key > vec[mid]) {
		return mid + 1;
	}
	else {
		return mid;
	}
}

bool Tree::Node::is_leaf() {
	// provera da li je neki cvor list
	int n = ptrs.size();
	for (int i = 0; i < n; i++) {
		if (ptrs[i] != nullptr) {
			return false;
		}
	}
	return true;
}

Tree::Tree(int deg) {
	if (deg == 0) {
		m = 0;
		max_keys_root = 0;
		min_keys = 0;
		root = nullptr;
	}
	else {
		m = deg;
		max_keys_root = (int)(2 * floor((2 * m - 2) / 3));
		min_keys = (int)(ceil((2. * m - 1) / 3.) - 1);
		root = new Node(max_keys_root + 1);
	}
}

Tree::~Tree() {
	delete_tree();
}

Tree::Tree(const Tree& t) {
	copy_tree(t);
}

Tree::Tree(Tree&& t) {
	move_tree(t);
}

Tree& Tree::operator=(const Tree& t) {
	if (this != &t) {
		delete_tree();
		copy_tree(t);
	}
	return *this;
}

Tree& Tree::operator=(Tree&& t) {
	if (this != &t) {
		delete_tree();
		move_tree(t);
	}
	return *this;
}

void Tree::delete_tree() {
	// level-order, kad se dodaju sva deca cvora on se obrise

	if (root == nullptr) {
		return;
	}
	queue <Node*> Q;
	Node* curr = root;
	Q.push(curr);
	while (!Q.empty()) {
		curr = Q.front(); Q.pop();
		for (unsigned int i = 0; i < curr->ptrs.size(); i++) {
			if (curr->ptrs[i] != nullptr) {
				Q.push(curr->ptrs[i]);
			}
		}
		delete curr;
	}
	root = nullptr;
}

void Tree::copy_tree(const Tree& t) {
	// kopiranje stabla; pomocna f-ja za konstruktor kopije i kopirajuci operator dodele
	// stablo t se obilazi po level orderu i na taj nacin se formira kopija

	m = t.m;
	max_keys_root = t.max_keys_root;
	min_keys = t.min_keys;
	root = new Node(max_keys_root + 1);

	queue <Node*> Q, Q_copy;
	Node* curr = t.root, * curr_copy = root;
	Q.push(curr); Q_copy.push(curr_copy);

	while (!Q.empty()) {
		curr = Q.front(); Q.pop();
		curr_copy = Q_copy.front(); Q_copy.pop();
		curr_copy->keys = curr->keys;

		for (unsigned int i = 0; i < curr->ptrs.size(); i++) {
			if (curr->ptrs[i] != nullptr) {
				Q.push(curr->ptrs[i]);

				Node* tmp = new Node(m);
				curr_copy->ptrs[i] = tmp;
				Q_copy.push(tmp);
			}
			else {
				curr_copy->ptrs.push_back(nullptr);
			}
		}
	}
}

void Tree::move_tree(Tree& t) {
	m = t.m;
	max_keys_root = t.max_keys_root;
	min_keys = t.min_keys;
	root = t.root;
	t.root = nullptr;
}

Tree::Node* Tree::get_root() {
	return root;
}

bool Tree::search(string key) {
	int idx;
	Node* curr = root;
	while (curr != nullptr) {
		idx = binary_search(curr->keys, key);
		if (idx == -1) {
			return false;
		}
		else {
			if (idx < (int)curr->keys.size() && curr->keys[idx] == key) {
				return true;
			}
			else {
				curr = curr->ptrs[idx];
			}
		}
	}
	return false;
}

int Tree::Node::count_ptrs() {
	// pomocna f-ja koja broji validne pokazivace (oni koji nisu nullptr)

	int cnt = 0;
	for (unsigned int i = 0; i < ptrs.size(); i++) {
		if (ptrs[i] != nullptr) {
			cnt++;
		}
		else {
			return cnt;
		}
	}
	return cnt;
}

void Tree::redistribute(Node* left, Node* parent, int idx_p, Node* right, string key) {
	// f-ja za prelvanje kljuceva

	// u tmp se smestaju svi kljucevi u sortirani poredak
	vector <string> tmp;
	for (unsigned int i = 0; i < left->keys.size(); i++) {
		tmp.push_back(left->keys[i]);
	}
	tmp.push_back(parent->keys[idx_p]);
	for (unsigned int i = 0; i < right->keys.size(); i++) {
		tmp.push_back(right->keys[i]);
	}
	right->keys.clear();
	left->keys.clear();
	tmp.insert(tmp.begin() + binary_search(tmp, key), key);

	// iz tmp-a se rasporedjuju kljucevi tako da leva polovina ide u left, sredisnji u roditelja
	// i desna polovina u right
	int mid = (int) floor(tmp.size() / 2.);
	for (int i = 0; i < (int)tmp.size(); i++) {
		if (i < mid) {
			left->keys.push_back(tmp[i]);
		}
		else if (i == mid) {
			parent->keys[idx_p] = tmp[i];
		}
		else if (i > mid) {
			right->keys.push_back(tmp[i]);
		}
	}

	// ako se ne radi prelivanje u listu, moraju i deca da se preraspodele
	if (!right->is_leaf()) {

		// smestaju se sva deca u tmp_ptrs
		vector <Node*> tmp_ptrs;
		for (int i = 0; i < left->count_ptrs(); i++) {
			tmp_ptrs.push_back(left->ptrs[i]);
		}
		for (int i = 0; i < right->count_ptrs(); i++) {
			tmp_ptrs.push_back(right->ptrs[i]);
		}
		left->ptrs.clear(); right->ptrs.clear();

		// prvo se redom popunjavaju deca left cvora dok nema za jedan vise dece od kljuceva
		// zatim se na isti nacin popunjava right cvor
		unsigned int i = 0;
		while (left->ptrs.size() < left->keys.size() + 1) {
			left->ptrs.push_back(tmp_ptrs[i++]);
		}
		while (right->ptrs.size() < right->keys.size() + 1) {
			right->ptrs.push_back(tmp_ptrs[i++]);
		}

		// prazna mesta se popunjavaju nullptr
		while ( (int) left->ptrs.size() < m) {
			left->ptrs.push_back(nullptr);
		}
		while ( (int) right->ptrs.size() < m) {
			right->ptrs.push_back(nullptr);
		}
	}
}

string Tree::split(Node* left, Node* mid, Node* parent, int idx_p, Node* right, string key) {
	// f-ja za prelamanje 2 cvora u 3
	
	// u tmp se smestaju kljucevi iz left, mid i right kao i 2 razdvojna kljuca u sortiranom poretku
	vector <string> tmp;
	for (unsigned int i = 0; i < left->keys.size(); i++) {
		tmp.push_back(left->keys[i]);
	}
	tmp.push_back(parent->keys[idx_p]);
	for (unsigned int i = 0; i < mid->keys.size(); i++) {
		tmp.push_back(mid->keys[i]);
	}
	left->keys.clear();
	mid->keys.clear();
	right->keys.clear();
	tmp.insert(tmp.begin() + binary_search(tmp, key), key);

	// cnt1 - broj kljuceva koji ide u left
	// cnt2 - broj kljuceva koji ide u mid
	// cnt3 - broj kljuceva koji ide u right
	// first - indeks prvog razdvojnog kljuca
	// second - indeks drugog razdvojnog kljuca
	int cnt1 = (int)floor((2. * m - 2) / 3.), cnt2 = (int)floor((2. * m - 1) / 3.);
	int cnt3 = (int)floor(2. * m / 3.), first = cnt1, second = cnt1 + cnt2 + 1;

	// raspodela kljuceva u cvorove left, mid i right kao i menjanje prvog razdvojnog kljuca
	for (int i = 0; i < cnt1; i++) {
		left->keys.push_back(tmp[i]);
	}
	parent->keys[idx_p] = tmp[first];
	for (int i = cnt1 + 1; i < second; i++) {
		mid->keys.push_back(tmp[i]);
	}
	for (unsigned int i = second + 1; i < tmp.size(); i++) {
		right->keys.push_back(tmp[i]);
	}

	// ako cvorovi nisu listovi, vrsi se preraspodela dece
	if (!mid->is_leaf()) {

		// sva deca se smestaju u tmp_ptrs
		vector <Node*> tmp_ptrs;
		for (int i = 0; i < left->count_ptrs(); i++) {
			tmp_ptrs.push_back(left->ptrs[i]);
		}
		for (int i = 0; i < mid->count_ptrs(); i++) {
			tmp_ptrs.push_back(mid->ptrs[i]);
		}
		left->ptrs.clear();
		mid->ptrs.clear();
		right->ptrs.clear();

		//broj dece za jedan veci od broja kljuceva
		cnt1++;
		cnt2++;

		// raspodela dece
		for (int i = 0; i < cnt1; i++) {
			left->ptrs.push_back(tmp_ptrs[i]);
		}
		for (int i = cnt1; i < cnt1 + cnt2; i++) {
			mid->ptrs.push_back(tmp_ptrs[i]);
		}
		for (unsigned int i = cnt2 + cnt1; i < tmp_ptrs.size(); i++) {
			right->ptrs.push_back(tmp_ptrs[i]);
		}

		// popunjavanje dece praznim pokazivacima
		while ( (int) left->ptrs.size() < m) {
			left->ptrs.push_back(nullptr);
		}
		while ( (int) mid->ptrs.size() < m) {
			mid->ptrs.push_back(nullptr);
		}
		while ( (int) right->ptrs.size() < m) {
			right->ptrs.push_back(nullptr);
		}
	}

	// pokusaj dodavanja drugog razdelnog kljuca
	// ako je cvor vec popunjen, vraca se taj kljuc koji ne moze da se doda da bi se 
	// dodavanje propagiralo na visi nivo
	// u suprotnom se samo doda u roditelja
	if (parent == root) {
		if ( (int) parent->keys.size() < max_keys_root) {
			parent->keys.insert(parent->keys.begin() + idx_p + 1, tmp[second]);
			parent->ptrs.insert(parent->ptrs.begin() + idx_p + 2, right);
			parent->ptrs.pop_back();
			return "";
		}
		else {
			return tmp[second];
		}
	}
	else {
		if ( (int) parent->keys.size() < m - 1) {
			parent->keys.insert(parent->keys.begin() + idx_p + 1, tmp[second]);
			parent->ptrs.insert(parent->ptrs.begin() + idx_p + 2, right);
			parent->ptrs.pop_back();
			return "";
		}
		else {
			return tmp[second];
		}
	}
}

bool Tree::insert(string key) {
	int idx;
	Node* curr = root;
	stack < pair <Node*, int>> S; // poseceni cvorovi pre curr;
	while (curr != nullptr) {
		idx = binary_search(curr->keys, key);
		
		// kada je koren prazan
		if (idx == -1) {
			curr->keys.push_back(key);
			return true;
		}

		// kljuc postoji u stablu
		else if (idx < (int) curr->keys.size() && curr->keys[idx] == key) {
			return false;
		}
		else {

			// ako je list, radi se umetanje, u suprotnom se silazi dalje kroz stablo
			if (curr->is_leaf()) {

				// odvaja se slucaj za koren=list zbog toga sto moze da ima vise kljuceva, i drugacije se razdvaja
				if (curr == root) {
					if (curr->keys.size() != max_keys_root) {
						curr->keys.insert(curr->keys.begin() + idx, key);
					}
					else {
						curr->keys.insert(curr->keys.begin() + idx, key);
						Node* left = new Node(m), * right = new Node(m);
						int mid = (int)ceil(max_keys_root / 2);
						for (int i = 0; i <= max_keys_root; i++) {
							if (i < mid) {
								left->keys.push_back(curr->keys[i]);
							}
							else if (i > mid) {
								right->keys.push_back(curr->keys[i]);
							}
						}
						curr->keys.erase(curr->keys.begin() + mid + 1, curr->keys.end());
						curr->keys.erase(curr->keys.begin(), curr->keys.begin() + mid);
						curr->ptrs[0] = left;
						curr->ptrs[1] = right;
					}
				}
				else {
					
					// ako ima dovoljno mesta samo se umece
					if (curr->keys.size() != m - 1) {
						curr->keys.insert(curr->keys.begin() + idx, key);
					}
					else {
						// k - pokusava se umetanje stringa k, na pocetku je on key, kad se propagira ka gore
						//			on uzima vrednost tog kljuca koji se propagira (povratna vrednost f-je split)
						string k = key;

						// parent - roditelj
						// idx_p - indeks pokazivaca na curr
						pair <Node*, int> p = S.top(); S.pop();
						Node* parent = p.first; int idx_p = p.second;

						// desni/levi brat sa kojima se proba prelivanje/odvajanje
						Node *bro_right = nullptr, * bro_left = nullptr;

						while (true) {
							// trazenje levog i desnog brata
							if (idx_p + 1 < (int)parent->ptrs.size() && parent->ptrs[idx_p + 1] != nullptr) {
								bro_right = parent->ptrs[idx_p + 1];
							}
							else if (idx_p - 1 >= 0 && parent->ptrs[idx_p - 1] != nullptr) {
								bro_left = parent->ptrs[idx_p - 1];
							}

							// pokusaj prelivanja
							if (bro_right != nullptr && (int)parent->ptrs[idx_p + 1]->keys.size() < m - 1) {
								redistribute(curr, parent, idx_p, bro_right, k);
								break;
							}
							else if (bro_left != nullptr && (int)parent->ptrs[idx_p - 1]->keys.size() < m - 1) {
								redistribute(bro_left, parent, idx_p - 1, curr, k);
								break;
							}

							// ako ne uspe prelivanje, radi se odvajanje prvo sa desnim pa sa levim
							else {
								Node* right = new Node(m);
								if (bro_right != nullptr) {
									k = split(curr, bro_right, parent, idx_p, right, k);
								}
								else if (bro_left != nullptr) {
									k = split(bro_left, curr, parent, idx_p - 1, right, k);
								}

								// ako je povratna vrednost f-je split "", znaci da je roditelj imao dovoljno mesta
								if (k == "") {
									break;
								}

								// odvaja se slucaj kada je koren roditelj jer vaze drugacija pravila
								else if (parent == root) {

									// ako postoji desni brat, splitovali smo sa njim
									// pa je deo niza pokazivaca curr, bro_right, right
									if (bro_right != nullptr) {
										parent->ptrs.insert(parent->ptrs.begin() + idx_p + 2, right);
									}

									// u suprotnom smo splitovali sa levim
									// pa je deo niza bro_left, curr, right
									else if (bro_left != nullptr) {
										parent->ptrs.insert(parent->ptrs.begin() + idx_p + 1, right);
									}

									// raspodela kljuceva na left i right; kljuc na poziciji mid ostaje u korenu
									parent->keys.insert(parent->keys.begin() + binary_search(parent->keys, k), k);
									Node* left = new Node(m), * right = new Node(m);
									int mid = (int)ceil(max_keys_root / 2);
									for (int i = 0; i <= max_keys_root; i++) {
										if (i < mid) {
											left->keys.push_back(parent->keys[i]);
										}
										else if (i > mid) {
											right->keys.push_back(parent->keys[i]);
										}
									}

									// raspodela pokazivaca iz korena u nove cvorove left i right
									left->ptrs.clear(); right->ptrs.clear();
									for (int i = 0; i <= max_keys_root + 1; i++) {
										if (i <= mid) {
											left->ptrs.push_back(parent->ptrs[i]);
										}
										else {
											right->ptrs.push_back(parent->ptrs[i]);
										}
									}

									// popunjavanje praznih pokazivaca sa nullptr
									while ( (int) left->ptrs.size() < m) {
										left->ptrs.push_back(nullptr);
									}
									while ( (int) right->ptrs.size() < m) {
										right->ptrs.push_back(nullptr);
									}

									// ostavljanje samo sredisnjeg kljuca u korenu, azuriranje pokazivaca
									parent->keys.erase(parent->keys.begin() + mid + 1, parent->keys.end());
									parent->keys.erase(parent->keys.begin(), parent->keys.begin() + mid);
									parent->ptrs[0] = left;
									parent->ptrs[1] = right;
									for (int i = 2; i <= max_keys_root + 1; i++) {
										parent->ptrs[i] = nullptr;
									}
									parent->ptrs.pop_back();
									break;
								}
								else {

									// propagira se umetanje
									p = S.top(); S.pop();

									// ako postoji desni brat, splitovali smo sa njim
									// pa je deo niza pokazivaca curr, bro_right, right
									if (bro_right != nullptr) {
										parent->ptrs.insert(parent->ptrs.begin() + idx_p + 2, right);
									}

									// u suprotnom smo splitovali sa levim
									// pa je deo niza bro_left, curr, right
									else if (bro_left != nullptr) {
										parent->ptrs.insert(parent->ptrs.begin() + idx_p + 1, right);
									}

									// azuriranje stanja za umetanje u sledeci visi nivo
									curr = parent;
									parent = p.first;
									bro_right = nullptr;
									bro_left = nullptr;
									idx_p = p.second;
								}
							}
						}
					}
				}
				return true; 
			}
			else {
				S.push(make_pair(curr, idx));
				curr = curr->ptrs[idx];
			}
		}
	}
	return false;
}

ostream& Tree::Node::print_keys(ostream& os) {
	int n = keys.size();
	for (int i = 0; i < n; i++) {
		os << " " << keys[i] << " ";
		if (i != n - 1) {
			os << "|";
		}
	}
	return os;
}

ostream& operator<<(ostream& os, const Tree& t) {
	// level order, crtice izmedju brace

	// red cvorovi za ispis
	queue <Tree::Node*> Q;

	// red roditelja i indeksa do trenutnog cvora kako bi se proverilo da li ima desnog brata za ispis crtica
	queue <pair<Tree::Node*, int>> Q_parent; int idx_p;

	// prev sluzi za proveru da li su ispisani svi cvorovi
	Tree::Node* curr = t.root, * prev;

	// koren nema roditelja
	Tree::Node* parent = nullptr;


	Q.push(curr);
	Q.push(nullptr);
	Q_parent.push(make_pair(parent, 0));
	Q_parent.push(make_pair(nullptr, 0));

	while (!Q.empty()) {
		prev = curr;
		curr = Q.front(); Q.pop();
		pair <Tree::Node*, int> p = Q_parent.front();  Q_parent.pop();
		parent =p.first;
		idx_p = p.second;

		// nov nivo ili kraj ispisa ako je i prev == nullptr
		if (curr == nullptr) {
			if (prev == curr) {
				break;
			}
			cout << endl << endl;
			Q.push(nullptr);
			Q_parent.push(make_pair(nullptr, 0));
		}

		// ispis cvora
		else {
			curr->print_keys(os);

			// provera da li curr ima desnog brata
			if (parent != nullptr && idx_p + 1 < (int)parent->ptrs.size() && parent->ptrs[idx_p + 1] != nullptr) {
				os << "---";
			}
			else {
				os << "   ";
			}

			// dodavanje dece i roditelja sa indeksom
			for (unsigned int i = 0; i < curr->ptrs.size(); i++) {
				if (curr->ptrs[i] != nullptr) {
					Q_parent.push(make_pair(curr, i));
					Q.push(curr->ptrs[i]);
				}
			}
		}
	}
	return os;
}

void Tree::inorder_rec(Node * root) {
	if (root != nullptr) {
		Node* p = root;
		for (unsigned int i = 0; i < p->keys.size(); i++) {
			inorder_rec(p->ptrs[i]);
			cout << p->keys[i] << ", ";
		}
		inorder_rec(p->ptrs[p->keys.size()]);
	}
}

Tree::Node* Tree::swap_with_successor(Node* curr, int idx, stack <pair <Node*, int>>& S) {
	// zamena kljuca sa sledbenikom ako treba da se obrise ali nije u listu
	// stavljaju se na stek cvorovi kroz koje se prolazi, ako se propagira brisanje

	S.push(make_pair(curr, idx));
	Node* succ = curr->ptrs[idx];
	while (!succ->is_leaf()) {
		S.push(make_pair(succ, 0));
		succ = succ->ptrs[0];
	}
	swap(succ->keys[0], curr->keys[idx - 1]);
	return succ;
}

void Tree::lend(Node* dst, Node* src, Node * parent, int idx, string oc) {
	// f-ja pozajmice iz src u dst
	// oc == "br" znaci da se pozajmljuje od desnog brata
	// oc == "bl" znaci da se pozajmljuje od levog brata

	if (oc == "br") {

		// prebacivanje kljuceva
		dst->keys.push_back(parent->keys[idx]);
		parent->keys[idx] = src->keys[0];
		src->keys.erase(src->keys.begin());

		// ako se pozajmica vrsila izmedju listova, nema potrebe za azuriranjem dece
		if (!src->is_leaf()) {
			dst->ptrs.insert(dst->ptrs.begin() + dst->count_ptrs(), src->ptrs[0]);
			src->ptrs.erase(src->ptrs.begin());
			dst->ptrs.pop_back();
			src->ptrs.push_back(nullptr);
		}
	}
	else if (oc == "bl") {

		// prebacivanje kljuceva
		dst->keys.insert(dst->keys.begin(), parent->keys[idx - 1]);
		parent->keys[idx - 1] = src->keys[src->keys.size() - 1];
		src->keys.pop_back();

		// ako se pozajmica vrsila izmedju listova, nema potrebe za azuriranjem dece
		if (!src->is_leaf()) {
			dst->ptrs.insert(dst->ptrs.begin(), src->ptrs[src->count_ptrs() - 1]);
			src->ptrs.erase(src->ptrs.begin() + src->count_ptrs() - 1);
			dst->ptrs.pop_back();
			src->ptrs.push_back(nullptr);
		}
	}
}

void Tree::merge(Node* left, Node* mid, Node* right, Node *parent, int idx) {
	// spajanje 3 cvora u 2

	// stavljanje svih kljuceva u tmp zbog raspodele
	vector <string> tmp;
	for (unsigned int i = 0; i < left->keys.size(); i++) {
		tmp.push_back(left->keys[i]);
	}
	tmp.push_back(parent->keys[idx - 1]);
	for (unsigned int i = 0; i < mid->keys.size(); i++) {
		tmp.push_back(mid->keys[i]);
	}
	tmp.push_back(parent->keys[idx]);
	parent->keys.erase(parent->keys.begin() + idx);
	for (unsigned int i = 0; i < right->keys.size(); i++) {
		tmp.push_back(right->keys[i]);
	}
	left->keys.clear();
	mid->keys.clear();
	right->keys.clear();

	// raspodela kljuceva u left i right
	unsigned int mid_idx = (int)floor(tmp.size() / 2.);
	for (unsigned int i = 0; i < tmp.size(); i++) {
		if (i < mid_idx) {
			left->keys.push_back(tmp[i]);
		}
		else if (i == mid_idx) {
			parent->keys[idx - 1] = tmp[i];
		}
		else {
			right->keys.push_back(tmp[i]);
		}
	}

	// ako nisu listovi, azuriranje dece
	if (!mid->is_leaf()) {

		// first_null - indeks prvog praznog pokaziva;
		//			umesto praznih se stavljaju pokazivaci iz mida dok se ne popune (num_keys + 1 == num_ptrs)
		// i - indeks pokazivaca koji se dodaje
		unsigned int i = 0, cnt_ptrs = left->count_ptrs(), first_null = left->count_ptrs();
		while (cnt_ptrs < left->keys.size() + 1) {
			left->ptrs[first_null + i - 1] = mid->ptrs[i++];
			cnt_ptrs++;
		}

		cnt_ptrs = mid->count_ptrs();
		// j - indeks gde se dodaje pokazivac u right
		int j = 0;
		while (i < cnt_ptrs) {
			right->ptrs.insert(right->ptrs.begin() + j++, mid->ptrs[i++]);
			right->ptrs.pop_back();
		}
	}

	// brisanje pokazivaca na mid i cvora mid
	parent->ptrs.erase(parent->ptrs.begin() + idx);
	parent->ptrs.push_back(nullptr);
	delete mid;
}

void Tree::merge_two(Node* left, Node* right, Node* parent, int idx) {
	// stablo reda 3, spaja 2 cvora u 1

	// stavljanje svih kljuceva u tmp zbog raspodele
	vector <string> tmp;
	for (unsigned int i = 0; i < left->keys.size(); i++) {
		tmp.push_back(left->keys[i]);
	}
	tmp.push_back(parent->keys[idx]);
	parent->keys.erase(parent->keys.begin() + idx);
	for (unsigned int i = 0; i < right->keys.size(); i++) {
		tmp.push_back(right->keys[i]);
	}
	left->keys.clear();
	right->keys.clear();

	// svi kljucevi se stavljaju u left
	for (unsigned int i = 0; i < tmp.size(); i++) {
		left->keys.push_back(tmp[i]);
	}

	// ako nisu listovi, prebacivanje svih pokazivaca u left
	if (!left->is_leaf()) {

		// svi pokazivaci se upisuju u tmp_ptrs
		vector<Node*> tmp_ptrs;
		for (int i = 0; i < left->count_ptrs(); i++) {
			tmp_ptrs.push_back(left->ptrs[i]);
		}
		for (int i = 0; i < right->count_ptrs(); i++) {
			tmp_ptrs.push_back(right->ptrs[i]);
		}

		left->ptrs.clear();
		right->ptrs.clear();
		for (unsigned int i = 0; i < tmp_ptrs.size(); i++) {
			left->ptrs.push_back(tmp_ptrs[i]);
		}
	}

	// brisanje pokazivaca na right i cvora right
	parent->ptrs.erase(parent->ptrs.begin() + idx + 1);
	parent->ptrs.push_back(nullptr);
	delete right;
}

bool Tree::remove(string key) {
	Node* curr = root;
	int idx;
	stack <pair <Node*, int>> S;  // poseceni cvorovi pre curr
	while (curr != nullptr) {
		idx = binary_search(curr->keys, key);

		// ako je prazan koren
		if (idx == -1) {
			return false;
		}

		// nadjen kljuc
		else if (idx < (int) curr->keys.size() && curr->keys[idx] == key){

			// ako nije u listu, zameni se sledbenikom
			if (!curr->is_leaf()) {
				curr = swap_with_successor(curr, idx + 1, S);
				idx = 0;
			}

			// koren je list
			if (curr == root) {
				curr->keys.erase(curr->keys.begin() + idx);
				return true;
			}

			// brisanje 
			else {

				// brisanje iz lista, ako je nije ostalo dovoljno kljuceva -> operacije pozajmice i spajanja
				curr->keys.erase(curr->keys.begin() + idx);
				if ( (int) curr->keys.size() < min_keys) {
					pair <Node*, int> p = S.top(); S.pop();
					// idx_p - indeks pokazivaca na curr;
					int idx_p = p.second;

					// bro_right - desni brat, bro_left - levi brat
					// second_bro_right - drugi desni brat, second_bro_left - drugi levi brat
					Node* parent = p.first, * bro_right = nullptr, * bro_left = nullptr;
					Node* second_bro_right = nullptr, * second_bro_left = nullptr;

					while (true) {

						// trazenje da li postoje desni/levi brat i drugi desni/levi brat
						if (idx_p + 1 < (int) parent->ptrs.size() && parent->ptrs[idx_p + 1] != nullptr) {
							bro_right = parent->ptrs[idx_p + 1];
						}
						if (idx_p - 1 >= 0 && parent->ptrs[idx_p - 1] != nullptr) {
							bro_left = parent->ptrs[idx_p - 1];
						}
						if (idx_p + 2 < (int) parent->ptrs.size() && parent->ptrs[idx_p + 2] != nullptr) {
							second_bro_right = parent->ptrs[idx_p + 2];
						}
						if (idx_p - 2 >= 0 && parent->ptrs[idx_p - 2] != nullptr) {
							second_bro_left = parent->ptrs[idx_p - 2];
						}

						// pozajmice, prvo se pokusava sa desnim, pa sa levim
						if (bro_right != nullptr && (int) bro_right->keys.size() > min_keys) {
							lend(curr, bro_right, parent, idx_p, "br");
							break;
						}
						else if (bro_left != nullptr && (int) bro_left->keys.size() > min_keys) {
							lend(curr, bro_left, parent, idx_p, "bl");
							break;
						}

						// ako ne moze pozajmica sa prvom bracom, onda dupla pozajmica
						// iz second_bro_right/second_bro_left u bro_right/bro_left,
						// pa iz bro_right/bro_left u curr
						else if (second_bro_right != nullptr && (int) second_bro_right->keys.size() > min_keys) {
							lend(bro_right, second_bro_right, parent, idx_p + 1, "br");
							lend(curr, bro_right, parent, idx_p, "br");
							break;
						}
						else if (second_bro_left != nullptr && (int) second_bro_left->keys.size() > min_keys) {
							lend(bro_left, second_bro_left, parent, idx_p - 1, "bl");
							lend(curr, bro_left, parent, idx_p, "bl");
							break;
						}

						// spajanje
						else {

							// ako je roditelj koren, minimalan broj kljuceva je 2, pa se zbog toga odvaja
							if (parent == root) {
								if (parent->keys.size() > 1) {

									// ako je stablo reda 3, spajaju se 2 cvora u 1
									if (m == 3) {
										if (bro_right != nullptr) {
											merge_two(curr, bro_right, parent, idx_p);
										}
										else if (bro_left != nullptr) {
											merge_two(bro_left, curr, parent, idx_p - 1);
										}
									}
									else {

										// prvo se pokusava sa desnim i levim bratom
										// pa onda sa desnim i drugim desnim
										// i na kraju sa levim i drugim levim
										if (bro_right != nullptr && bro_left != nullptr) {
											merge(bro_left, curr, bro_right, parent, idx_p);
										}
										else if (bro_right != nullptr && second_bro_right != nullptr) {
											merge(curr, bro_right, second_bro_right, parent, idx_p + 1);
										}
										else if (bro_left != nullptr && second_bro_left != nullptr) {
											merge(second_bro_left, bro_left, curr, parent, idx_p - 1);
										}
									}
									break;
								}

								// ako koren nema dovoljno kljuceva za spajanje, 3 cvora se spajaju u koren
								else {

									// skupljanje svih kljuceva u tmp i stavljanje u koren
									Node* left = parent->ptrs[0], * right = parent->ptrs[1];
									vector<string> tmp;
									for (unsigned int i = 0; i < left->keys.size(); i++) {
										tmp.push_back(left->keys[i]);
									}
									tmp.push_back(parent->keys[0]);
									for (unsigned int i = 0; i < right->keys.size(); i++) {
										tmp.push_back(right->keys[i]);
									}
									parent->keys = tmp;

									// azuriranje dece korena na decu left-a i right-a
									parent->ptrs.clear();
									for (int i = 0; i < left->count_ptrs(); i++) {
										parent->ptrs.push_back(left->ptrs[i]);
									}
									for (int i = 0; i < right->count_ptrs(); i++) {
										parent->ptrs.push_back(right->ptrs[i]);
									}
									while ( (int) parent->ptrs.size() < max_keys_root + 1) {
										parent->ptrs.push_back(nullptr);
									}
									delete right; delete left;
									break;
								}
							}
							else {

								// ako je stablo reda 3, spajaju se 2 cvora u 1
								if (m == 3) {
									if (bro_right != nullptr) {
										merge_two(curr, bro_right, parent, idx_p);
									}
									else if (bro_left != nullptr) {
										merge_two(bro_left, curr, parent, idx_p - 1);
									}
								}
								else {
									// prvo se pokusava sa desnim i levim bratom
									// pa onda sa desnim i drugim desnim
									// i na kraju sa levim i drugim levim
									if (bro_right != nullptr && bro_left != nullptr) {
										merge(bro_left, curr, bro_right, parent, idx_p);
									}
									else if (bro_right != nullptr && second_bro_right != nullptr) {
										merge(curr, bro_right, second_bro_right, parent, idx_p + 1);
									}
									else if (bro_left != nullptr && second_bro_left != nullptr) {
										merge(second_bro_left, bro_left, curr, parent, idx_p - 1);
									}
								}

								// ako roditelj ima dovoljno kljuceva zavrsava se brisanje
								if (parent != root && (int) parent->keys.size() >= min_keys) {
									break;
								}

								// propagiranje brisanja na nivo iznad
								p = S.top(); S.pop();
								curr = parent;
								parent = p.first;
								idx_p = p.second;
								bro_right = nullptr; bro_left = nullptr;
								second_bro_right = nullptr; second_bro_left = nullptr;
							}
						}
					}
					return true;
				}
				else {
					return true;
				}
			}
		}
		else {
			
			// ako je trenutni cvor list i nije nadjen kljuc tu, ne postoji u stablu
			if (curr->is_leaf()) {
				return false;
			}
			else {
				S.push(make_pair(curr, idx));
				curr = curr->ptrs[idx];
			}
		}
	}
	return false;
}

int Tree::count_keys(Node* root) {
	// f-ja koja broji kljuceve u stablu sa korenom root koristeci level order
	int cnt = 0;

	if (root == nullptr) {
		return cnt;
	}
	queue <Node*> Q;
	Node* curr = root;
	Q.push(curr);
	while (!Q.empty()) {
		curr = Q.front(); Q.pop();
		cnt += curr->keys.size();
		for (unsigned int i = 0; i < curr->ptrs.size(); i++) {
			if (curr->ptrs[i] != nullptr) {
				Q.push(curr->ptrs[i]);
			}
		}
	}
	return cnt;
}

int Tree::count_smaller_keys(string X) {
	int cnt = 0, idx;
	Node* curr = root;
	while (curr != nullptr) {
		idx = binary_search(curr->keys, X);

		// prazan koren
		if (idx == -1) {
			break;
		}

		// broje se kljucevi u svim podstablima levo od potencijalne pozicije stringa X
		for (int i = 0; i < idx; i++) {
			cnt += count_keys(curr->ptrs[i]) + 1;
		}

		// ako je na idx bas string X, dodaje se broju i njegovo levo podstablo i nema vise kljuceva manjih od X
		if (idx < (int)curr->keys.size() && curr->keys[idx] == X) {
			cnt += count_keys(curr->ptrs[idx]);
			break;
		}
		curr = curr->ptrs[idx];
	}
	return cnt;
}
