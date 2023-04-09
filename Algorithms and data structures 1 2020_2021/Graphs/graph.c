#define _CRT_SECURE_NO_DEPRECATE
#include <stdio.h>
#include <stdlib.h>
#include <limits.h>


typedef struct bfs_maja {
	int parent; // prethodnik u obilasku
	int info; // o kom cvoru je rec
	int distance; // udaljenost od pocetnog cvora
}bfs_maja;

typedef struct node_maja {
	bfs_maja info;
	struct node_maja *next;
}Node_maja;

typedef struct queue_maja {
	Node_maja *front; 
	Node_maja *rear;
}Queue_maja;

typedef struct bfs_sanja {
	int parent; // prethodnik u obilasku
	int info; // o kom cvoru je rec
	int distance; // udaljenost od pocetnog cvora (broj poteza)
	int move; // da li se igrac od cvora pomera za jedan ili dva poteza
}bfs_sanja;

typedef struct node_sanja {
	bfs_sanja info;
	struct node_sanja* next;
}Node_sanja;

typedef struct queue_sanja {
	Node_sanja* front;
	Node_sanja* rear;
}Queue_sanja;


void create_graph(char ***pgraph, int n, int **V) {
	*pgraph = calloc(n, sizeof(char*));
	if (!*pgraph) {
		printf("Neuspela alokacija.\n");
		return;
	}

	*V = calloc(n, sizeof(int));
	if (!*V) {
		printf("Neuspela alokacija.\n");
		return;
	}

	for (int i = 0; i < n; i++) {
		(*pgraph)[i] = calloc(n, sizeof(char));
		if (!(*pgraph)[i]) {
			printf("Neuspela alokacija.\n");
			return;
		}

		(*V)[i] = i; // upis indeksa kao pocetnih cvorova grafa
	}
}


void print_graph(char** graph, int n, int *V) {
	printf("     ");

	for (int i = 0; i < n; i++) {
		printf("%2d ", V[i]);
	}

	printf("\n\n");

	for (int i = 0; i < n; i++) {
		printf("%2d   ", V[i]);
		for (int j = 0; j < n; j++) {
			printf("%2d ", graph[i][j]);
		}
		putchar('\n');
	}
}


void add_vertex(char*** pgraph, int* n, int* V, int info) {
	int i, *V_check;

	for (i = 0; i < *n; i++) {
		if (V[i] == info) {
			printf("Taj cvor vec postoji.\n");
			return;
		}
	}

	// dodavanje u niz cvorova novi cvor
	V_check = realloc(V, (*n + 1) * sizeof(int));
	if (!V_check) {
		printf("Neuspela alokacija. Dodavanje cvora neuspesno.\n");
		return;
	}
	V = V_check;
	(*n)++;
	V[*n - 1] = info;

	// dodavanje vrste 
	char** graph_check;
	graph_check = realloc(*pgraph, *n * sizeof(char*));
	if (!graph_check) {
		printf("Neuspela alokacija. Dodavanje cvora neuspesno.\n");
		return;
	}
	*pgraph = graph_check;

	// inicijalizacija dodate vrste i kolone nulama
	char* new_row = calloc(*n, sizeof(char));
	if (!new_row) {
		printf("Neuspela alokacija. Dodavanje cvora neuspesno.\n");
		return;
	}
	(*pgraph)[*n - 1] = new_row;

	for (i = 0; i < *n - 1; i++) {
		char* row_check;

		row_check = realloc((*pgraph)[i], (*n) * sizeof(char));
		if (!row_check) {
			printf("Neuspela alokacija. Dodavanje cvora neuspesno.\n");
			return;
		}
		(*pgraph)[i] = row_check;

		(*pgraph)[i][*n - 1] = 0;
	}
}


int find_vertex(int n, int* V, int info) {
	int i;
	for (i = 0; i < n; i++) {
		if (V[i] == info) {
			return i;
		}
	}
	return -1;
}


void delete_vertex(char*** pgraph, int* n, int* V, int info) {
	int i, j, idx_info;

	idx_info = find_vertex(*n, V, info);
	if (idx_info == -1) {
		printf("Ne postoji takav cvor.\n");
		return;
	}

	for (i = idx_info; i < *n - 1; i++) {
		V[i] = V[i + 1];
	}
	int* V_check;
	V_check = realloc(V, (*n + 1) * sizeof(int));
	if (!V_check) {
		printf("Neuspela alokacija. Brisanje cvora neuspesno.\n");
		return;
	}
	V = V_check;

	for (i = 0; i < *n; i++) {
		for (j = idx_info; j < *n - 1; j++) {
			(*pgraph)[i][j] = (*pgraph)[i][j + 1];
		}
		char* row_check = NULL;
		row_check = realloc((*pgraph)[i], (*n - 1) * sizeof(char));
		if (!row_check) {
			printf("Neuspela alokacija. Brisanje cvora neuspesno.\n");
			return;
		}
		(*pgraph)[i] = row_check;
	}
	for (j = 0; j < *n - 1; j++) {
		for (i = idx_info; i < *n - 1; i++) {
			(*pgraph)[i][j] = (*pgraph)[i + 1][j];
		}
	}
	free((*pgraph)[*n - 1]);
	(*n)--;
}


void add_edge(char*** pgraph, int n, int *V, int v0, int v1) {
	int idx0, idx1;

	// nalazenje zadatih cvorova u grafu
	idx0 = find_vertex(n, V, v0);
	idx1 = find_vertex(n, V, v1);
	if (idx0 == -1 && idx1 == -1) {
		printf("Ne postoje ni prvi ni drugi cvor u grafu.\n");
		return;
	}
	if (idx0 == -1) {
		printf("Ne postoji prvi cvor u grafu.\n");
		return;
	}
	if (idx1 == -1) {
		printf("Ne postoji drugi cvor u grafu.\n");
		return;
	}

	// dodavanje grane
	if ((*pgraph)[idx0][idx1]) {
		printf("Vec postoji grana izmedju zadata dva cvora.\n");
	}
	else {
		(*pgraph)[idx0][idx1] = 1;
	}
}


void delete_edge(char*** pgraph, int n, int *V, int v0, int v1) {
	int idx0, idx1;
	
	// nalazenje zadatih cvora u grafu
	idx0 = find_vertex(n, V, v0);
	idx1 = find_vertex(n, V, v1);
	if (idx0 == -1 && idx1 == -1) {
		printf("Ne postoje ni prvi ni drugi cvor u grafu.\n");
		return;
	}
	if (idx0 == -1) {
		printf("Ne postoji prvi cvor u grafu.\n");
		return;
	}
	if (idx1 == -1) {
		printf("Ne postoji drugi cvor u grafu.\n");
		return;
	}

	// brisanje grane
	if (!(*pgraph)[idx0][idx1]) {
		printf("Izmedju dva zadata cvora ne postoji grana.\n");
	}
	else {
		(*pgraph)[idx0][idx1] = 0;
	}
}


void free_graph(char*** pgraph, int n, int* V) {
	int i;
	if (!n) {
		return;
	}
	for (i = 0; i < n; i++) {
		free((*pgraph)[i]);
	}
	free(*pgraph);
	free(V);
}


//red za Maju
int is_empty(Queue_maja* Q) {
	if (!Q->front) {
		return 1;
	}
	else {
		return 0;
	}
}


Queue_maja* insert_el(Queue_maja* Q, bfs_maja data) {
	Node_maja* curr = malloc(sizeof(Node_maja));
	if (!curr) {
		printf("Neuspesno");
		return NULL;
	}
	curr->info = data;
	curr->next = NULL;
	if (is_empty(Q)) {
		Q->front = curr;
		Q->rear = curr;
	}
	else {
		Q->rear->next = curr;
		Q->rear = curr;
	}
	return Q;
}


Queue_maja* delete_el(Queue_maja* Q) {
	if (!is_empty(Q)) {
		Node_maja* curr = Q->front;
		Q->front = curr->next;
		if (!Q->front) {
			Q->rear = NULL;
		}
		free(curr);
	}
	return Q;
}


//red za Sanju
int is_empty_sanja(Queue_sanja* Q) {
	if (!Q->front) {
		return 1;
	}
	else {
		return 0;
	}
}


Queue_sanja* insert_el_sanja(Queue_sanja* Q, bfs_sanja data) {
	Node_sanja* curr = malloc(sizeof(Node_sanja));
	if (!curr) {
		printf("Neuspesno");
		return NULL;
	}
	curr->info = data;
	curr->next = NULL;
	if (is_empty_sanja(Q)) {
		Q->front = curr;
		Q->rear = curr;
	}
	else {
		Q->rear->next = curr;
		Q->rear = curr;
	}
	return Q;
}


Queue_sanja* delete_el_sanja(Queue_sanja* Q) {
	if (!is_empty_sanja(Q)) {
		Node_sanja* curr = Q->front;
		Q->front = curr->next;
		if (!Q->front) {
			Q->rear = NULL;
		}
		free(curr);
	}
	return Q;
}


int find_min_dist_maja(bfs_maja* visited, int len, int vertex) {
	// trazenje najmanjeg puta od pocetnog do ciljnog cvora iz vektora visited; ne dozvoljava se broj poteza 0
	int min_dist = INT_MAX, i;
	for (i = 0; i < len; i++) {
		if (visited[i].info == vertex) {
			if (visited[i].info < min_dist) {
				min_dist = visited[i].distance;
			}
		}
	}
	if (min_dist == INT_MAX) {
		return -1;
	}
	else {
		return min_dist;
	}
}


int find_min_dist_sanja(bfs_sanja* visited, int len, int vertex) {
	// trazenje najmanjeg puta od pocetnog do ciljnog cvora iz vektora visited
	int min_dist = INT_MAX, i;
	for (i = 0; i < len; i++) {
		if (visited[i].info == vertex) {
			if (visited[i].distance < min_dist) {
				min_dist = visited[i].distance;
			}
		}
	}
	if (min_dist == INT_MAX) {
		return -1;
	}
	else {
		return min_dist;
	}
}


bfs_maja* find_min_maja_paths(char*** pgraph, int n, int* V, int idx_start, int idx_end, int* min_dist, int* len) {
	// modifikovani BFS, vraca vektor visited iz koga se nalaze putevi
	Queue_maja* Q = malloc(sizeof(Queue_maja));
	if (!Q) {
		printf("Neuspela alokacija memorije.\n");
		return NULL;
	}
	Q->front = NULL;
	Q->rear = NULL;

	int curr_idx, i, j, found;
	bfs_maja curr, * visited = malloc(n * n * sizeof(bfs_maja));
	*len = 0;  // duzina vektora visited
	if (!visited) {
		printf("Neuspela alokacija memorije.\n");
		return NULL;
	}

	curr.distance = 0;
	curr.info = V[idx_start];
	curr.parent = -1;
	Q = insert_el(Q, curr);
	while (!is_empty(Q)) {
		// u visited se dodaju samo cvorovi koji su jedinstveni i po prethodniku i po sadrzaju
		// da bi se ocuvali svi najkraci putevi od pocetnog do ciljnog cvora
		curr = Q->front->info;
		Q = delete_el(Q);
		found = 0;
		for (i = 0; i < *len; i++) {
			if (visited[i].info == curr.info && visited[i].parent == curr.parent) {
				found = 1;
				break;
			}
		}

		// ako je tekuci cvor jedinstven, dodaju se svi njegovi sledbenici u red
		// ako nije jedinstven, znaci da je ovaj deo vec uradjen
		if (!found) {
			curr_idx = find_vertex(n, V, curr.info);
			visited[(*len)++] = curr;
			for (j = 0; j < n; j++) {
				if ((*pgraph)[curr_idx][j]) {
					bfs_maja new_el;
					new_el.distance = curr.distance + 1;
					new_el.info = V[j];
					new_el.parent = curr.info;
					Q = insert_el(Q, new_el);
				}
			}
		}
	}
	free(Q);
	*min_dist = find_min_dist_maja(visited, *len, V[idx_end]);
	return visited;
}


void print_one_maja(int *V, int idx_end, bfs_maja* visited, int len, int min_dist) {
	// ispis jednog Majinog puta

	// trazenje cvora sa zadatom min_dist - pocetak puta
	int i = 0, min_idx = -1;
	for (i = 0; i < len; i++) {
		if (visited[i].info == V[idx_end] && visited[i].distance == min_dist) {
			min_idx = i;
			break;
		}
	}
	if (min_idx == -1) {
		printf("Maja ne moze stici do cilja.");
	}

	// u vektoru path se cuva poredak cvorova od krajnjeg do pocetnog
	else {
		printf("Maja: ");
		int* path = malloc((min_dist + 1) * sizeof(int));
		if (!path) {
			printf("Neuspela alokacija memorije.\n");
			return;
		}
		int path_len = 0;
		bfs_maja curr = visited[min_idx];
		int curr_idx = min_idx;
		while (curr.parent != -1) {
			path[path_len++] = curr.info;
			for (i = 0; i < len; i++) {
				if (visited[i].info == curr.parent && visited[i].distance == curr.distance - 1) {
					curr_idx = i;
					break;
				}
			}
			curr = visited[curr_idx];
		}
		path[path_len] = visited[curr_idx].info;
		for (i = path_len; i >= 0; i--) {
			printf("%d", path[i]);
			if (i) {
				printf("->");
			}
		}
		free(path);
	}
}


bfs_sanja* find_min_sanja_paths(char*** pgraph, int n, int* V, int idx_start, int idx_end, int* min_dist, int* len) {
	// Izmenjen BFS, u vektor visited je povratna vrednost f-je. U njemu se cuvaju cvorovi koji su jedinstveni
	// po prethodniku i sadrzaju. Ako se od tekuceg cvora Sanja pomera za jedno mesto, tada je prethodnik
	// tekuceg cvora na udaljenosti 2

	Queue_sanja *Q = malloc(sizeof(Queue_sanja));
	if (!Q) {
		printf("Neuspela alokacija memorije.\n");
		return NULL;
	}
	Q->front = NULL;
	Q->rear = NULL;

	bfs_sanja curr, *visited = malloc(n * n * sizeof(bfs_sanja));
	if (!visited) {
		printf("Neuspela alokacija memorije.\n");
		return NULL;
	}

	int curr_idx, i, j, found;
	*len = 0; // duzina vektora visited

	curr.parent = -1;
	curr.info = V[idx_start];
	curr.distance = 0;
	curr.move = 1;
	Q = insert_el_sanja(Q, curr);
	while (!is_empty_sanja(Q)) {
		curr = Q->front->info;
		Q = delete_el_sanja(Q);
		found = 0;

		// provera da li je tekuci cvor vec bio (da li je jedinstven)
		for (i = 0; i < *len; i++) {
			if (visited[i].info == curr.info && visited[i].parent == curr.parent) {
				found = 1;
				break;
			}
		}

		// ako nije jedinstven, dodaje se u vektor i proverava se broj koraka od njega
		if (!found) {
			curr_idx = find_vertex(n, V, curr.info);
			visited[(*len)++] = curr;

			// ako je broj koraka 1, u red se dodaju svi cvorovi koji su mu susedi, i njima se postavlja
			// broj koraka na 2
			if (curr.move == 1) {
				for (j = 0; j < n; j++) {
					if ((*pgraph)[curr_idx][j]) {
						bfs_sanja new_el;
						new_el.distance = curr.distance + 1;
						new_el.info = V[j];
						new_el.parent = curr.info;
						new_el.move = 2;
						Q = insert_el_sanja(Q, new_el);
					}
				}
			}

			// ako je broj koraka 2, prvo se trazi cvor koji je tekucem sused, pa se trazi sused
			// tog suseda i takav se dodaje u red. njegov prethodnik ce biti postavljen na tekuci cvor
			else if (curr.move == 2) {
				for (i = 0; i < n; i++) {
					if ((*pgraph)[curr_idx][i]) {
						for (j = 0; j < n; j++) {
							if ((*pgraph)[i][j]) {
								bfs_sanja new_el;
								new_el.distance = curr.distance + 1;
								new_el.info = V[j];
								new_el.parent = curr.info;
								new_el.move = 1;
								Q = insert_el_sanja(Q, new_el);
							}
						}
					}
				}
			}
		}

	}
	free(Q);
	*min_dist = find_min_dist_sanja(visited, *len, V[idx_end]);
	return visited;
}


int find_vertex_between(char*** pgraph, int n, int start_vertex, int end_vertex) {
	int i;
	for (i = 0; i < n; i++) {
		if ((*pgraph)[start_vertex][i]) {
			if ((*pgraph)[i][end_vertex]) {
				return i;
			}
		}
	}
	return INT_MAX;
}


void print_one_sanja(char*** pgraph, int n, int* V, bfs_sanja* visited, int len, int min_dist, int idx_end) {
	int i, min_idx = -1;

	// trazenje krajnjeg cvora
	for (i = 0; i < len; i++) {
		if (visited[i].distance == min_dist && V[idx_end] == visited[i].info) {
			min_idx = i;
			break;
		}
	}

	// u slucaju da se ne nadje u vektoru visited, znaci da Sanja ne moze stici do cilja
	if (min_idx == -1) {
		printf("Sanja ne moze stici do cilja.");
	}
	else {
		// u path se cuvaju put od pocetnog polja do ciljnog polja u obrnutom poretku
		int* path = malloc(n * n * sizeof(int)), curr_idx = min_idx, len_path = 0;
		if (!path) {
			printf("Neuspela alokacija memorije.\n");
			return;
		}
		
		bfs_sanja curr = visited[min_idx]; // tekuci cvor

		printf("Sanja: ");
		while (curr.parent != -1) {
			path[len_path++] = curr.info;

			// ako tekuci cvor ima move 1, to znaci da se do njega doslo putem duzine 2, pa treba dodati medjucvor u put
			if (curr.move == 1){
				path[len_path++] = find_vertex_between(pgraph, n, curr.parent, curr.info);
				path[len_path++] = -1; // oznaka da se ispisu zagrade da bi se naznacilo da je paran potez
			}

			// trazenje prethodnika na putu
			for (i = 0; i < len; i++) {
				if (visited[i].info == curr.parent && visited[i].distance == curr.distance - 1) {
					curr_idx = i;
					break;
				}
			}
			curr = visited[curr_idx];
		}
		path[len_path] = curr.info;
		for (i = len_path; i >= 0;) {
			if (path[i] == -1) {
				printf("(%d->%d)", path[i - 1], path[i - 2]);
				i -= 3;
				if (i >= 0) {
					printf("->");
				}
			}
			else {
				printf("%d", path[i]);
				if (i--) {
					printf("->");
				}
			}
		}
		free(path);
	}
}


int main() {
	int option, n = 0, info, vertex0, vertex1;
	int start_v, end_v, idx_start, idx_end, game = 1;
	char** graph = NULL;
	int* vertices = NULL;

	while (1) {
		printf("1. Kreiraj prazan graf.\n");
		printf("2. Dodaj cvor.\n");
		printf("3. Izbrisi cvor.\n");
		printf("4. Dodaj granu.\n");
		printf("5. Obrisi granu. \n");
		printf("6. Ispisi graf.\n");
		printf("7. Obrisi graf iz memorije.\n");
		printf("8. Zapocni igru.\n");
		printf("0. Zatvori program.\n");

		scanf("%d", &option);
		if (option < 0 || option > 8) {
			printf("Uneta opcija ne postoji.\n");
			continue;
		}
		
		// Kreiranje praznog grafa
		if (option == 1) {
			if (graph) {
				free(graph);
				graph = NULL;
				n = 0;
			}
			printf("Unesite dimenzije pocetnog grafa: \n");
			scanf("%d", &n);
			if (n > 0) {
				create_graph(&graph, n, &vertices);
			}
			else {
				printf("Unete dimenzije nisu validne.");
			}
		}

		// Dodavanje cvora
		else if (option == 2) {
			if (!graph) {
				printf("Graf nije inicijalizovan.\n");
			}
			else {
				printf("Unesite sadrzaj cvora: ");
				scanf("%d", &info);
				add_vertex(&graph, &n, vertices, info);
			}
		}

		// Brisanje cvora
		else if (option == 3) {
			printf("Unesite sadrzaj cvora: ");
			if (graph) {
				scanf("%d", &info);
				if (n == 1) {
					if (vertices[0] == info) {
						free_graph(&graph, n, vertices);
						graph = NULL;
						n = 0;
					}
					else {
						printf("Ne postoji takav cvor.\n");
					}

				}
				else {
					delete_vertex(&graph, &n, vertices, info);
				}
			}
			else {
				printf("Graf nije inicijalizovan.\n");
			}
		}

		// Dodavanje grane
		else if (option == 4) {
			if (!graph) {
				printf("Graf nije inicijalizovan.\n");
			}
			else {
				printf("Unesite od kog cvora ka kom cvoru zelite granu (odvojeni razmakom): ");
				scanf("%d%d", &vertex0, &vertex1);
				add_edge(&graph, n, vertices, vertex0, vertex1);
			}
		}

		// Brisanje grane
		else if (option == 5) {
			if (!graph) {
				printf("Graf nije inicijalizovan.\n");
			}
			else {
				printf("Unesite od kog cvora ka kom cvoru zelite da uklonite granu (odvojeni razmakom): ");
				scanf("%d%d", &vertex0, &vertex1);
				delete_edge(&graph, n, vertices, vertex0, vertex1);
			}
		}

		// Ispis grafa
		else if (option == 6) {
			if (!graph) {
				printf("Graf nije inicijalizovan.\n");
			}
			else {
				print_graph(graph, n, vertices);
			}
		}

		// Brisanje grafa iz memorije
		else if (option == 7) {
			free_graph(&graph, n, vertices);
			graph = NULL;
			n = 0;
		}

		// Igra
		else if (option == 8) {
			if (!graph || n <= 0) {
				printf("Graf nije inicijalizovan, pa igra ne moze poceti.\n");
				continue;
			}
			while (1) {
				printf("Unesite pocetno polje: ");
				scanf("%d", &start_v);
				idx_start = find_vertex(n, vertices, start_v);
				if (start_v == -1) {
					game = 0;
					break;
				}
				if (idx_start == -1) {
					printf("Taj cvor ne pripada grafu. Ako ne zelite da igrate igru, unesite -1.\n");
					continue;
				}
				printf("Uneto je pocetno polje.\n");
				while (1) {
					printf("Unesite ciljno polje: ");
					scanf("%d", &end_v);
					idx_end = find_vertex(n, vertices, end_v);
					if (end_v == -1) {
						game = 0;
						break;
					}
					if (idx_end == -1) {
						printf("Taj cvor ne pripada grafu. Ako ne zelite da igrate igru, unesite -1.\n");
						continue;
					}
					else {
						break;
					}
				}
				break;
			}
			if (!game) {
				continue;
			}
			// 1 5 4 0 1 4 0 3 4 1 2 4 3 2 4 2 4 4 4 0
			// 1 6 4 0 1 4 0 3 4 0 5 4 1 2 4 3 2 4 5 2 4 2 4 4 4 0
			// prvi graf sa pdfa: 1 6 4 5 2 4 2 0 4 2 4 4 4 1 4 1 3 4 3 4 4 3 5
			// majin put 5 2 4 sanjin put: 5 2 (0 1) 3 (5 2) 4  ili  5 2 (4 1) 3 (5 2) 4
			// drugi graf sa pdfa: 1 6 4 2 3 4 3 1 4 1 5 4 5 0 4 0 4 4 4 1 4 4 2
			int min_dist_maja = INT_MAX, min_dist_sanja = INT_MAX, len_maja_visited, len_sanja_visited;
			bfs_maja* maja_visited;
			bfs_sanja *sanja_visited;
			maja_visited = find_min_maja_paths(&graph, n, vertices, idx_start, idx_end, &min_dist_maja, &len_maja_visited);
			sanja_visited = find_min_sanja_paths(&graph, n, vertices, idx_start, idx_end, &min_dist_sanja, &len_sanja_visited);
			while (1) {
				printf("Izaberite ispis:\n");
				printf("1. Odredi pobednika i ispisi koliko koraka.\n");
				printf("2. Jedan put koji vodi Maju i Sanju do cilja.\n");
				printf("3. Svi putevi koji vode Maju i Sanju do cilja.\n");
				printf("0. Kraj igre.\n");
				
				int num;
				scanf("%d", &num);
				if (num < 0 || num > 3) {
					printf("Uneta opcija ne postoji.\n");
					continue;
				}
				if (!maja_visited || !sanja_visited) {
					break;
				}

				if (num == 1) {
					if (min_dist_maja == -1) {
						if (min_dist_sanja == -1) {
							printf("Nema pobednika, nijedna ne moze stici do ciljnog polja.\n");
						}
						else {
							printf("Pobednica je Sanja, sa %d poteza. Maja ne moze doci do cilja.", min_dist_sanja);
						}
					}
					else if (min_dist_sanja == -1) {
						if (min_dist_maja == -1) {
							printf("Nema pobednika, nijedna ne moze stici do ciljnog polja.\n");
						}
						else {
							printf("Pobednica je Maja, sa %d poteza. Sajna ne moze doci do cilja.\n", min_dist_maja);
						}
					}
					else {
						if (min_dist_maja <= min_dist_sanja) {
							// ako je jednak broj koraka, posto je Sanja drugi igrac Maja pobedjuje
							printf("Pobednica je Maja. Maja je igrala %d poteza, a Sanja %d.\n", min_dist_maja, min_dist_sanja);
						}
						else if (min_dist_sanja < min_dist_maja) {
							printf("Pobednica je Sanja. Maja je igrala %d poteza, a Sanja %d.\n", min_dist_maja, min_dist_sanja);
						}
					}
				}
				else if (num == 2) {
					print_one_maja(vertices, idx_end, maja_visited, len_maja_visited, min_dist_maja);
					putchar('\n');
					print_one_sanja(&graph, n, vertices, sanja_visited, len_sanja_visited, min_dist_sanja, idx_end);
					putchar('\n');
				}
				else if (num == 0) {
					free(maja_visited);
					free(sanja_visited);
					break;
				}
			}
		}

		// Kraj programa
		else if (option == 0) {
			if (graph) {
				free_graph(&graph, n, vertices);
			}
			return 0;

		putchar('\n');
		}
	}

	return 0;
}