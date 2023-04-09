import copy


# klasa za cvor stabla
class TreeNode:
    def __init__(self, square, nums):
        self.square = square  # stanje kvadrata upisano u cvor
        self.sons = []  # lista sa cvorovima koji su sinovi odgovarajuceg cvora
        self.possible = True
        self.nums = nums  # ovde se cuva ostatak vrednosti koje nisu upisane u magicni kvadrat

        # korisno za postorder, da se oznaci da li se cvor vec jednom pojavio na steku
        self.visited = False

        # korisno za postorder, da se oznaci da je cvor obradjen i onda se pri povratku nece staviti na stek
        self.processed = False

    def add_sons(self, S):
        """
        uvezuje sinove na zadati cvor tako sto ih ubacuje u self.sons, ne ubacuje one od kojih dalje nije moguce
        formirati magicne kvadrate
        :param S: karakteristican zbir popunjenog kvadrata (argument f-je check_if_possible_after koja se ovde koristi)
        :return:
        """
        for i in range(len(self.nums)):
            square1 = copy.deepcopy(self.square)  # pravljenje kopije i preko nje se dalje popunjava kvadrat

            # pomocna kopija pomocu koje se proverava da li je moguce dodati tekuci element self.nums[i]
            # u kvadrat i dobiti kvadrat od koga je dalje moguce formirati magicni
            square2 = copy.deepcopy(square1)

            if check_if_possible_after(square2, self.nums[i], S):
                insert_first_empty(square1, self.nums[i])  # unos broja u kvadrat

                # kreiranje liste nums1 u kojoj ce se nalaziti brojevi za unos osim broja koji je unet u kvadrat
                nums1 = []
                for j in range(len(self.nums)):
                    if j != i:
                        nums1.append(self.nums[j])

                # kreiranje novog cvora i uvezivanje
                node = TreeNode(square1, nums1)
                self.sons.append(node)


# klasa za cvor jednostruko ulancane liste (ulancana lista se koristi za implementaciju reda i steka)
class ListNode:
    def __init__(self, info):
        self.next = None
        self.info = info


# klasa za red
class Queue:
    def __init__(self):
        self.front = None
        self.rear = None

    def insert_el(self, info):
        # dodavanje elementa u red
        p = ListNode(info)
        if not self.front or not self.rear:
            self.front = p
            self.rear = p
        else:
            self.rear.next = p
            self.rear = p

    def delete_el(self):
        # brisanje elementa iz reda
        if self.front == self.rear:
            p = self.front
            p.next = None
            del p
            self.front = None
            self.rear = None
        else:
            p = self.front
            self.front = p.next
            p.next = None
            del p

    def empty(self):
        # provera da li je red prazan
        if not self.front or not self.rear:
            return True
        else:
            return False


# klasa za stek
class Stack:
    def __init__(self):
        self.top = None

    def push(self, info):
        p = ListNode(info)  # alokacija cvora

        # ako je stek prazan
        if not self.top:
            self.top = p

        # ako postoje elementi u steku
        else:
            p.next = self.top
            self.top = p

    def pop(self):
        p = self.top
        x = p.info  # sadrzaj koji se vraca sa vrha steka

        # ako je u steku samo jedan element
        if not self.top.next:
            self.top = None
            del p
            return x

        # ako su u steku vise elemenata
        else:
            self.top = p.next
            p.next = None
            del p
            return x

    def empty(self):
        if not self.top:
            return True
        else:
            return False


# klasa za stablo
class Tree:
    def __init__(self):
        self.root = None

    def form(self, square, nums, S):
        """
        formiranje stabla
        :param square: pocetno stanje kvadrata (unosi se u koren stabla)
        :param nums: brojevi koji treba da se unesu u kvadrat
        :param S: karakteristican zbir kvadrata (promenljiva za metodu add_sons)
        :return:
        """
        self.root = TreeNode(square, nums)
        q = Queue()
        q.insert_el(self.root)
        while not q.empty():
            next = q.front
            q.delete_el()
            next.info.add_sons(S)

            # kada se dodaju sinovi u next, posebno se ubacuje svaki u red da bi se i oni obradili na isti nacin
            for son in next.info.sons:
                q.insert_el(son)

    def level_order(self):
        to_print = []  # lista sa svim kvadratima iz tekuceg nivoa
        q = Queue()
        q.insert_el(self.root)
        level = 0

        while not q.empty():
            next = q.front
            # cuva se broj nula jer ono sto razlikuje nivoe je broj nula (praznih mesta) u magicnom kvadratu
            zeroes = count_zeroes(next.info.square)
            to_print.append(next.info.square)
            q.delete_el()

            # dodavanje svih sinova u red
            for son in next.info.sons:
                q.insert_el(son)

            if not q.empty():
                # broj nula sledeceg clana iz reda (ako se razlikuje znaci da treba da se obradjuje sledeci
                # nivo pa se ispisuje trenutni nivo
                new_zeroes = count_zeroes(q.front.info.square)
                if new_zeroes != zeroes:
                    print("Nivo {}:".format(level))
                    level += 1
                    print_level_formatted(to_print)
                    to_print = []
                    print("")

        # na kraju kada red ostane prazan, u to_print ostanu kvadrati iz poslednjeg nivoa pa treba da se ispisu
        print("Nivo {}:".format(level))
        level += 1
        print_level_formatted(to_print)
        print("")
        del q

    def postorder(self):
        magic_squares = []  # lista sa svim magicnim kvadratima po postorder poretku
        next = self.root
        s = Stack()

        # dodavanje svih sinova "najlevljijh" sinova na stek (u obrnutom poretku)
        while next.sons:
            s.push(next)
            for i in range(len(next.sons) - 1):
                s.push(next.sons[-i - 1])
            next = next.sons[0]
        s.push(next)

        # obrada se vrsi dok se stek ne isprazni
        while not s.empty():
            next = s.pop()

            # ako se cvor prvi put nasao na steku, menja mu se visited vrednost
            # i na stek se dodaju svi njegovi sinovi (u obrnutom poretku) koji nisu ispisani
            if not next.visited:
                next.visited = True
                while next.sons:
                    for i in range(len(next.sons) - 1):
                        if not next.sons[-i - 1].processed:
                            s.push(next.sons[-i - 1])
                    next = next.sons[0]
                s.push(next)

            # ako je cvor vec bio jednom na steku, sada mu se vraca vrednost visited na pocetnu
            # i ako nije ispisan i magican je dodaje se u magic_squares
            else:
                next.visited = False
                if not next.processed and check_if_magic(next.square):
                    next.processed = True
                    magic_squares.append(next.square)

        # prolazak po level orderu i vracanje svim cvorovima processed i visited u False
        # da bi mogli opet da se ispisu ako se jos jednom pozove postorder
        q = Queue()
        q.insert_el(self.root)
        while not q.empty():
            next = q.front
            next.info.processed = False
            next.info.visited = False
            q.delete_el()
            for son in next.info.sons:
                q.insert_el(son)
        del q
        del s

        return magic_squares


def input_by_position():
    # unos brojeva preko pozicije u pocetnom stanju kvadrata
    print("Unesite clan u formatu: red kolona vrednost")
    data = [int(i) for i in input().split(" ")]
    return data[0], data[1], data[2]


def count_zeroes(matrix):
    # f-ja koja broji nule u kvadratu (nule oznacavaju prazna mesta)
    zeroes = 0
    for i in range(len(matrix)):
        for j in range(len(matrix[i])):
            if matrix[i][j] == 0:
                zeroes += 1
    return zeroes


def check_if_arithmetic(nums):
    # provera da li je niz nums aritmeticki niz (samo aritmeticki nizovi mogu da se unesu u magicni kvadrat)
    nums.sort()
    difference = nums[1] - nums[0]
    for i in range(1, len(nums) - 1):
        if nums[i + 1] - nums[i] != difference:
            return False
    return True


def check_if_magic(matrix):
    # f-ja koja proverava da li je kvadrat magican

    # provera da li je magicni kvadrat popunjen
    if count_zeroes(matrix) == 0:

        S = sum(matrix[0])  # karakteristican zbir (uzima se suma prve vrste zbog uporedjivanja)
        check_sum = 0  # suma koja se uporedjuje sa S

        # uporedjivanje po vrstama
        for i in range(1, len(matrix)):
            if sum(matrix[i]) != S:
                return False

        # uporedjivanje po kolonama
        for j in range(len(matrix)):
            for i in range(len(matrix)):
                check_sum += matrix[i][j]
            if check_sum != S:
                return False
            else:
                check_sum = 0

        # uporedjivanje po glavnoj dijagonali
        i = 0  # vrsta clana koji posmatramo
        j = 0  # kolona clana koji posmatramo
        while i < len(matrix) or j < len(matrix):
            check_sum += matrix[i][j]
            i += 1
            j += 1
        if check_sum != S:
            return False
        else:
            check_sum = 0

        # uporedjivanje po sporednoj dijagonali
        i = len(matrix) - 1  # vrsta clana koji posmatramo
        j = 0  # kolona clana koji posmatramo
        while i >= 0 or j < len(matrix):
            check_sum += matrix[i][j]
            i -= 1
            j += 1
        if check_sum != S:
            return False
        else:
            return True

    else:
        return False


def insert_first_empty(matrix, number):
    # umetanje broja na prvo prazno mesto u kvadratu
    for i in range(len(matrix)):
        for j in range(len(matrix[i])):
            if matrix[i][j] == 0:
                matrix[i][j] = number
                return


def check_if_possible_after(matrix, number, S):
    # provera da li je posle umetanja broja number moguce uopste dobiti magicni kvadrat
    # S je karakteristican zbir za odgovarajuc nepopunjen kvadrat

    insert_first_empty(matrix, number)
    return check_if_possible(matrix, S)


def check_if_possible(matrix, S):
    # provera da li je sa zadatim stanjem kvadrata moguce formirati magicni
    # S je karakteristican zbir za odgovarajuc nepopunjen kvadrat

    # prvo se uporedjuje S sa sumom svake vrste
    for i in range(len(matrix)):
        if 0 not in matrix[i]:
            if sum(matrix[i]) != S:
                return False
        else:
            if sum(matrix[i]) >= S:
                return False

    # uporedjivanje po kolonama
    zeroes = 0  # broj nula u koloni
    check_sum = 0  # promenljiva za cuvanje suma kolona
    for j in range(len(matrix)):
        for i in range(len(matrix)):
            if matrix[i][j] != 0:
                check_sum += matrix[i][j]
            else:
                zeroes += 1
                if check_sum >= S:
                    return False
            if i == len(matrix) - 1:
                if not zeroes and S != check_sum:
                    return False
                elif zeroes:
                    if check_sum >= S:
                        return False
                zeroes = 0
                check_sum = 0

    # provera po glavnoj dijagonali
    i = 0  # broj vrste posmatranog broja
    j = 0  # broj kolone posmatranog broja
    check_sum = 0
    zeroes = 0  # broj nadjenih nula u dijagonali

    while i < len(matrix) or j < len(matrix):
        if matrix[i][j] == 0:
            zeroes += 1
            if check_sum >= S:
                return False
        check_sum += matrix[i][j]
        i += 1
        j += 1

    if not zeroes and S != check_sum:
        return False
    elif zeroes:
        if S <= check_sum:
            return False

    # provera po sporednoj dijagonali
    i = len(matrix) - 1  # broj vrste posmatranog broja
    j = 0  # broj kolone posmatranog broja
    check_sum = 0
    zeroes = 0

    while i >= 0 or j < len(matrix):
        if matrix[i][j] == 0:
            zeroes += 1
            if check_sum >= S:
                return False
        check_sum += matrix[i][j]
        i -= 1
        j += 1

    if not zeroes and S != check_sum:
        return False
    elif zeroes:
        if S <= check_sum:
            return False

    return True  # vraca se True ako su sve provere prosle jer to znaci da je moguce napraviti magican kvadrat


def check_if_perfect(matrix, S):
    # f-ja koja proverava da li je magicni kvadrat savrsen
    # S je karakteristican zbir tog magicnog kvadrata

    if check_if_magic(matrix):
        # provera izlomljenih dijagonala paralelnih sa glavnom
        i_start = len(matrix) - 1  # indeks pocetne vrste provere

        for k in range(len(matrix) - 1):
            i = i_start - k
            j = 0  # indeks pocetne kolone za proveru
            check_sum = 0  # suma za proveru
            while j < len(matrix):
                check_sum += matrix[i][j]
                if i == len(matrix) - 1:
                    i = 0
                else:
                    i += 1
                j += 1
            if check_sum != S:
                return False

        j_start = 0  # indeks pocetne kolone provere

        for k in range(len(matrix) - 1):
            j = j_start + k
            i = 0  # indeks vrste
            check_sum = 0  # suma za proveru
            while i < len(matrix):
                check_sum += matrix[i][j]
                if j == 0:
                    j = len(matrix) - 1
                else:
                    j -= 1
                i += 1
            if check_sum != S:
                return False

        return True  # ako su prosle sve provere znaci da je kvadrat savrsen

    else:
        return False


def print_level_formatted(list_matrix):
    # ispis svih matrica iz jednog nivoa jednu do druge
    i = 0
    while i < len(list_matrix[0]):
        for k in range(len(list_matrix)):
            for number in list_matrix[k][i]:
                print("{:2}".format(number), end=" ")
            print("", end="  ")
        i += 1
        print("")


square = []  # buduca matrica za cuvanje kvadrata
all_values = []  # lista gde se cuvaju unete vrednosti radi provere da li cine aritmeticki niz
values_in_square = []  # brojevi u kvadratu
values_to_enter = []  # brojevi koji se ubacuju u kvadrat
n = 0  # dimenzija kvadrata
tree = Tree()  # prazno stablo
solutions = []  # resenja magicnog kvadrata

while True:
    print("1. Unos pocetnog stanja kvadrata.\n"
          "2. Unos niza brojeva za popunjavanje kvadrata.\n"
          "3. Formiranje magicnih kvadrata.\n"
          "4. Ispis karakteristicnog zbira magicnog kvadrata.\n"
          "5. Ispis stabla po level order-u.\n"
          "6. Ispis resenja magicnog kvadrata po postorder-u.\n"
          "7. Ispis savrsenih magicnih kvadrata od dobijenih resenja.\n"
          "0. Prekid programa.")

    try:
        b = int(input())  # pomocna promenljiva da pokupi koja je opcija izabrana
    except ValueError:
        print("Uneta je nekorektna vrednost. Unesite ceo broj u opsegu [0, 7].")
        continue
    if b < 0 or b > 7:
        print("Uneta je nekorektna vrednost. Unesite ceo broj u opsegu [0, 7].")
        continue

    # Unos pocetnog stanja kvadrata
    if b == 1:
        print("Unesite dimenzije kvadrata:")
        try:
            n = int(input())
        except ValueError:
            print("Uneta je nekorektna vrednost. Unesite ceo broj veci od 0.")
            continue
        if n < 0:
            print("Uneta je nekorektna vrednost. Unesite ceo broj veci od 0.")
            continue
        if n == 2:
            print("Magican kvadrat dimenzije 2 ne postoji.")
            continue

        # inicijalizacija praznih mesta nulama
        square = []
        all_values = []
        values_to_enter = []
        values_in_square = []
        for i in range(n):
            square.append(n * [0])

        print("Ako zelite da unosite brojeve unesite 1, u suprotnom unesite bilo koji znak.")
        a = input()  # pomocna promenljiva koja kupi unos
        if a == "1":
            while True:
                try:
                    row, column, value = input_by_position()
                except ValueError:
                    print("Red, kolona i vrednost moraju biti 3 prirodna broja koja su odvojena jednim razmakom.")
                    print("Ako zelite da unesete druge podatke unesite 1, u suprotnom "
                          "unesite bilo koji znak.")
                    a = input()
                    if a == "1":
                        continue
                    else:
                        break

                if row <= 0 or column <= 0 or value <= 0:
                    print("Red, kolona i vrednost moraju biti prirodni brojevi.")
                    print("Ako zelite da unesete druge podatke unesite 1, u suprotnom "
                          "unesite bilo koji znak.")
                    a = input()
                    if a == "1":
                        continue
                    else:
                        break

                if row > n or column > n:
                    print("Unete koordinate izlaze iz dimenzija kvadrata. "
                          "Ako zelite da unesete druge koordinate unesite 1, u suprotnom "
                          "unesite bilo koji znak.")
                    a = input()
                    if a == "1":
                        continue
                    else:
                        break

                elif square[row - 1][column - 1] != 0:
                    print("Na datoj poziciji je vec unet broj. Ako zelite da ga promenite u unet broj "
                          "unesite 1. Za povratak na pocetni meni unesite bilo koji znak.")
                    a = input()
                    if a == "1":
                        square[row - 1][column - 1] = value

                        # brisanje broja koji se menja
                        all_values.pop()
                        values_in_square.pop()

                        all_values.append(value)
                        values_in_square.append(value)
                        print("Za dodatan unos brojeva unesite 1, u suprotnom unesite bilo koji znak.")
                        a = input()
                        if a == "1":
                            continue
                        else:
                            break
                    else:
                        break

                else:
                    square[row - 1][column - 1] = value
                    all_values.append(value)
                    values_in_square.append(value)
                    print("Za dodatan unos brojeva unesite 1, u suprotnom unesite bilo koji znak.")
                    a = input()
                    if a == "1":
                        continue
                    else:
                        break
        else:
            continue

    # Unos clanova od kojih se formira magicni kvadrat
    elif b == 2:
        if n == 0:
            print("Prvo unesite dimenzije kvadrata da biste mogli da unosite clanove "
                  "za kasnije formiranje magicnog kvadrata.")
            continue
        else:
            print("Unesite niz brojeva odvojenih razmakom.")
            try:
                data = [int(i) for i in input().split(" ")]
            except ValueError:
                print("Uneta je nekorektna vrednost. Brojevi koji se unose u kvadrat mogu biti "
                      "samo prirodni brojevi odvojeni jednim razmakom.")
                continue
            for num in data:
                if num <= 0:
                    print("Uneta je nekorektna vrednost. Brojevi koji se unose u kvadrat mogu biti "
                          "samo prirodni brojevi.")
                    continue

            if len(all_values) + len(data) > n ** 2:
                print("Broj brojeva od kojih ste zeleli da formirate magicni kvadrat je {}, sto je vece od"
                      "broja preostalih mesta, a to je {}.".format(len(data), n ** 2 - len(all_values)))

            elif len(all_values) + len(data) < n ** 2:
                for num in data:
                    all_values.append(num)
                    values_to_enter.append(num)
                print("Vrednosti su sacuvane. Da biste poceli formiranje kvadrata morate uneti "
                      "jos {} brojeva".format(n ** 2 - len(all_values)))

            else:
                for num in data:
                    all_values.append(num)
                    values_to_enter.append(num)
                print("Uneto je dovoljno brojeva za formiranje magicnih kvadrata.")

    # Formiranje stabla
    elif b == 3:
        if n != 0:
            if len(all_values) < n ** 2:
                print("Nema dovoljno unetih brojeva za formiranje magicnih kvadrata.")
                continue

            if n != 1 and not check_if_arithmetic(all_values):
                print("Sa unetim vrednostima se ne moze formirati kvadrat. Ako zelite da unesete nove brojeve, "
                      "unesite 1 da bi se stari izbrisali. "
                      "U suprotnom unesite bilo koji znak za povratak na pocetni meni")
                a = input()
                if a == "1":
                    all_values = []
                    continue
                else:
                    continue
            else:
                S = sum(all_values) / n
                if check_if_possible(square, S):
                    tree = Tree()
                    values_to_enter.sort()
                    tree.form(square, values_to_enter, S)
                else:
                    print("Od unetog kvadrata nije moguce formirati magicni. Unesite drugi kvadrat "
                          "i onda pokusajte da formirate stablo.")
        else:
            print("Morate prvo uneti pocetno stanje kvadrata.")

    # Ispis karakteristicnog zbira kvadrata
    elif b == 4:
        if len(all_values) < n ** 2:
            print("Nije uneto dovoljno brojeva za formiranje magicnog kvadrata, pa se ne moze izracunati"
                  "karakteristican zbir.")
        else:
            print("Karakteristican zbir magicnog kvadrata je {}.".format(int(sum(all_values) / n)))

    # Ispis stabla po level order-u
    elif b == 5:
        if tree.root:
            tree.level_order()

        else:
            print("Stablo nije formirano, prvo formirajte stablo.")

    # Ispis resenja po postorder-u
    elif b == 6:
        if tree.root:
            solutions = tree.postorder()
            print_level_formatted(solutions)

        else:
            print("Stablo nije formirano, prvo formirajte stablo.")

    # Ispis magicnih kvadrata koji su savrseni is formiranog stabla
    elif b == 7:
        if tree.root:
            S = sum(all_values) / n
            perfect = [i for i in tree.postorder() if check_if_perfect(i, S)]
            if perfect:
                print_level_formatted(perfect)
            else:
                print("Medju resenjima nema savrsenih magicnih kvadrata.")
        else:
            print("Stablo nije formirano, prvo formirajte stablo da biste videli koji su magicni kvadrati savrseni.")

    elif b == 0:
        break
