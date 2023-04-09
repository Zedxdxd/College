# klasa za cvor liste
class ListNode:
    def __init__(self, num):
        self.info = num
        self.next = None
        self.prev = None


# klasa za listu
class List:
    def __init__(self):
        self.start_node = None    # pocetni cvor

    def insert_el(self, num):
        new_node = ListNode(num)    # kreiranje novog cvora

        # ako je lista prazna, kreiranje pocetnog cvora
        if self.start_node is None:
            new_node.prev = new_node
            new_node.next = new_node
            self.start_node = new_node

        # ako lista nije prazna
        else:
            # postavljanje pokazivaca p i q na pocetni cvor, q nam sluzi kao provera da li je dostignut kraj liste
            p = self.start_node
            q = self.start_node

            # ako ostane 0, znaci da element treba da se upise na pocetak jer nijednom se nije izvrsila petlja
            counter = 0

            # promenljiva koja se koristi da ne bi bilo duplih elemenata u listi
            in_list = False

            while p.info <= num:
                counter += 1
                if p.info == num:
                    in_list = True
                    break
                counter += 1
                p = p.next
                if p == q:
                    break

            # menjanje pocetnog cvora na novi alocirani
            if counter == 0:
                r = q.prev
                r.next = new_node
                new_node.prev = r
                p.prev = new_node
                new_node.next = p
                self.start_node = new_node

            # umetanje cvora, r je pomocni pokazivac prethodnika cvora p
            elif not in_list:
                r = p.prev
                r.next = new_node
                new_node.prev = r
                new_node.next = p
                p.prev = new_node

    def delete_el(self, num):

        # ako je skup prazan
        if self.start_node is None:
            print("Skup je prazan.")

        else:
            # postavljanje p i q na pocetni cvor, q je provera da li smo se vratili na pocetak
            p = self.start_node
            q = self.start_node

            found = True   # Promenljiva za oznacavanje da li je element nadjen
            while p.info < num:
                p = p.next
                if p == q:
                    found = False
                    break

            if not found or p.info > num:
                print("Taj element se ne nalazi u skupu.")

            else:
                # ovaj if se izvrsava kada je element nadjen na pocetku
                if p == q:
                    self.start_node = self.start_node.next
                    r = p.prev
                    r.next = self.start_node
                    # ako skup ima samo jedan element
                    if p == q == r:
                        self.start_node = None
                        del q
                        del r
                    del p

                else:
                    r = p.next
                    q = p.prev
                    r.prev = q
                    q.next = r
                    del p

    def check_if_elem_in_list(self, num):
        # ako je skup prazan
        if self.start_node is None:
            return 0

        else:
            p = self.start_node
            q = self.start_node
            while p.info < num:
                p = p.next

                # ako smo stigli do kraja skupa i i dalje je p.info < num, znaci da nema elementa u skupu
                if p == q:
                    return False

            # izlazi se iz while petlje samo ako je p == q ili p >= num
            # ovde se obradjuje slucaj p >= num
            if p.info == num:
                return True
            else:
                return False

    def cardinality(self):
        # ako je lista prazna kardinalnost je 0
        if self.start_node is None:
            return 0

        else:
            p = self.start_node
            q = self.start_node
            # brojac elemenata
            counter = 0
            while True:
                p = p.next
                counter += 1

                # ovaj uslov oznacava kraj skupa
                if p == q:
                    return counter

    def list_print(self):
        if self.start_node is None:
            print("Skup je prazan")
        else:
            p = self.start_node
            q = self.start_node
            while True:
                print(p.info, end=" ")
                p = p.next
                if p == q:
                    break

    def add_at_end(self, num):
        """
        metoda koja dodaje element sa vrednoscu num na kraj liste, pogodna za operacije sa dva skupa
        kad uvek dodajemo na kraj nove liste jer su efikasnije operacije
        """
        new_node = ListNode(num)

        # ako je lista prazna, tada uvezujemo new_node kao pocetni cvor
        if self.start_node is None:
            self.start_node = new_node
            new_node.next = new_node
            new_node.prev = new_node

        else:
            r = self.start_node.prev
            r.next = new_node
            new_node.prev = r
            new_node.next = self.start_node
            self.start_node.prev = new_node


def union(list1, list2):
    p = list1.start_node
    p_check = list1.start_node  # provera da li je dostignut kraj skupa 1
    q = list2.start_node
    q_check = list2.start_node  # provera da li je dostignut kraj skupa 2

    # kada je jedan skup prazan, unija drugog skupa i tog skupa je taj drugi skup
    if list1.start_node is None:
        return list2
    elif list2.start_node is None:
        return list1

    # unija se racuna tako sto redom uporedjujemo elemente, manji dodajemo na kraj new_list i azuriramo pokazivac
    # ako su jednaki, dodajemo jedan u new_list, i azuriramo oba pokazivaca
    # kada se stigne do kraja jednog skupa, svi preostali clanovi iz drugog se upisuju u new_list
    else:
        new_list = List()  # rezultat
        while True:
            if p.info < q.info:
                new_list.add_at_end(p.info)
                p = p.next
                if p == p_check:
                    while True:
                        new_list.add_at_end(q.info)
                        q = q.next
                        if q == q_check:
                            return new_list

            elif q.info < p.info:
                new_list.add_at_end(q.info)
                q = q.next
                if q == q_check:
                    while True:
                        new_list.add_at_end(p.info)
                        p = p.next
                        if p == p_check:
                            return new_list

            else:
                new_list.add_at_end(q.info)
                q = q.next
                p = p.next
                if p != p_check or q != q_check:
                    if p == p_check:
                        while True:
                            new_list.add_at_end(q.info)
                            q = q.next
                            if q == q_check:
                                return new_list

                    elif q == q_check:
                        while True:
                            new_list.add_at_end(p.info)
                            p = p.next
                            if p == p_check:
                                return new_list

                else:
                    return new_list


def intersection(list1, list2):
    p = list1.start_node
    p_check = list1.start_node  # provera da li je dostignut kraj skupa 1
    q = list2.start_node
    q_check = list2.start_node  # provera da li je dostignut kraj skupa 2

    new_list = List()  # rezultat

    # ako je bilo koji od skupova prazan i presek je prazan
    if list1.start_node is None or list2.start_node is None:
        return new_list

    # presek se racuna tako sto redom uporedjujemo elemente, ako su jednaki dodajemo u new_list
    # ako nisu onda menjamo odgovarajuce pokazivace, kada se dodje do kraja jedne liste vraca se new_list
    while True:
        if p.info < q.info:
            p = p.next
            if p == p_check:
                return new_list

        elif q.info < p.info:
            q = q.next
            if q == q_check:
                return new_list

        else:
            new_list.add_at_end(q.info)
            p = p.next
            q = q.next
            if p == p_check or q == q_check:
                return new_list


def difference(list1, list2):
    p = list1.start_node
    p_check = list1.start_node  # provera da li je dostignut kraj skupa 1
    q = list2.start_node
    q_check = list2.start_node  # provera da li je dostignut kraj skupa 2

    new_list = List()  # rezultat

    # ako je drugi skup prazan, razlika je ceo prvi skup
    if list2.cardinality() == 0:
        return list1

    # ako je prvi skup prazan, razlika je prazan skup
    elif list1.cardinality() == 0:
        return new_list

    # idemo kroz prvi skup, proveravamo da li je elemnt u drugom skupu, ako nije dodamo ga u rezultat
    else:
        while True:
            if p.info < q.info:
                new_list.add_at_end(p.info)
                p = p.next
                if p == p_check:
                    return new_list
            elif q.info < p.info:
                q = q.next
                if q == q_check:
                    while True:
                        new_list.add_at_end(p.info)
                        p = p.next
                        if p == p_check:
                            return new_list
            else:
                p = p.next
                q = q.next
                if p == p_check:
                    return new_list
                elif q == q_check:
                    while True:
                        new_list.add_at_end(p.info)
                        p = p.next
                        if p == p_check:
                            return new_list


# mesto gde se cuvaju skupovi
lists = ["", "", "", "", ""]

# broj sacuvanih skupova
num_of_lists = 0

while True:
    print("1. Ucitaj nov prazan skup;\n"
          "2. Dodaj clanove u skup;\n"
          "3. Obrisi clan iz skupa;\n"
          "4. Ispisi zadati skup;\n"
          "5. Proveri da li uneti element priapada skupu;\n"
          "6. Odredi kardinalnost skupa;\n"
          "7. Odredi uniju dva skupa;\n"
          "8. Odredi presek dva skupa;\n"
          "9. Odredi razliku dva skupa;\n"
          "10. Obrisi skup;\n"
          "0. Prekini program.\n")

    # ovom try naredbom se proverava da li je unet broj ceo
    try:
        n = int(input())  # broj naredbe
    except ValueError:
        print("Unos nije korektan. Unesite ceo broj jednak nekom od rednih brojeva naredbi.\n")
        continue

    if n not in [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]:
        print("Ne postoji naredba sa takvim brojem. Unesite ceo broj jednak nekom od renih brojeva naredbi")

    # Ucitavanje novog praznog skupa
    elif n == 1:
        if num_of_lists < 5:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a-1] != "":
                    print("Na ovom mestu skup vec postoji. "
                          "Unesite 1 ako zelite da na tom mestu ipak upisete prazan skup. "
                          "Za povratak na pocetni meni unesite bilo koji znak.")

                    try:
                        entry = int(input())
                    except ValueError:
                        print("")
                        continue

                    if entry == 1:
                        new_list = List()
                        lists.pop(a-1)
                        lists.insert(a-1, new_list)
                else:
                    num_of_lists += 1
                    new_list = List()
                    lists.pop(a-1)
                    lists.insert(a-1, new_list)
                    print("Skup je ucitan.")
            else:
                print("Redni broj skupa moze biti samo od 1 do 5.")
        else:
            print("Sva mesta su popunjena, izbrisite jedan skup.")

    # Dodavanje clanova u skup
    elif n == 2:
        if num_of_lists == 0:
            print("Nema ucitanih skupova.")
        else:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a - 1] == "":
                    print("Na tom mestu nema unetog skupa.")
                else:
                    print("Unesite cele brojeve koje zelite da dodate u skup odvojene razmakom:")

                    try:
                        nums = [int(i) for i in input().split()]
                    except ValueError:
                        print("Unos nije korektan, clanovi skupa mogu biti samo celi brojevi.\n")
                        print("\nUnesite bilo koji znak za nastavak.")
                        con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                        # unese nesto da bi se petlja opet izvrsila
                        continue

                    for num in nums:
                        lists[a-1].insert_el(num)
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    # Brisanje clana iz skupa
    elif n == 3:
        if num_of_lists == 0:
            print("Nema ucitanih skupova.")
        else:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a - 1] == "":
                    print("Na ovom mestu ne postoji skup.")
                else:
                    print("Unesite element koji zelite da izbrisete:")

                    try:
                        elem = int(input())
                    except ValueError:
                        print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                        print("\nUnesite bilo koji znak za nastavak.")
                        con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                        # unese nesto da bi se petlja opet izvrsila
                        continue

                    lists[a-1].delete_el(elem)
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    # Ispis zadatog skupa
    elif n == 4:
        if num_of_lists == 0:
            print("Nema ucitanih skupova.")
        else:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a-1] == "":
                    print("Na ovom mestu ne postoji skup.")
                else:
                    lists[a-1].list_print()
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    # Provera pripadnosti
    elif n == 5:
        if num_of_lists == 0:
            print("Nema ucitanih skupova.")
        else:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a - 1] == "":
                    print("Na ovom mestu ne postoji skup.")
                else:
                    print("Unesite element")

                    try:
                        elem = int(input())
                    except ValueError:
                        print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                        continue

                    if lists[a-1].check_if_elem_in_list(elem) == 0:
                        print("Skup je prazan")

                    elif lists[a-1].check_if_elem_in_list(elem):
                        print("Pripada.")

                    else:
                        print("Ne pripada.")
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    # Odredjivanje kardinalnosti
    elif n == 6:
        if num_of_lists == 0:
            print("Nema ucitanih skupova.")
        else:
            print("Unesite redni broj skupa:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a - 1] == "":
                    print("Na ovom mestu ne postoji skup.")
                else:
                    print("Kardinalnost je", lists[a-1].cardinality())
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    # Unija
    elif n == 7:
        if num_of_lists < 2:
            print("Nema dovoljno skupova u memoriji za racunanje unije. Za ovu operaciju je potrebno dva skupa, "
                  "a u memoriji ih je {}".format(num_of_lists))
        else:
            if num_of_lists < 5:
                print("Unesite redni broj jednog skupa:")

                try:
                    a = int(input())  # redni broj skupa 1
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                print("Unesite redni broj drugog skupa:")

                try:
                    b = int(input())  # redni broj skupa 2
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                if 0 < a < 6 and 0 < b < 6:
                    if lists[a - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(a))

                    if lists[b - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(b))

                    else:
                        new_list = union(lists[a - 1], lists[b - 1])

                        # cuvanje dobijenog skupa unijom na prvo slobodno mesto
                        num_of_lists += 1
                        for i in range(len(lists)):
                            if lists[i] == "":
                                index_empty = i
                                break
                        lists.pop(index_empty)
                        lists.insert(index_empty, new_list)
                else:
                    print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")
            else:
                print("Sva mesta su popunjena, izbrisite jedan skup.")

    # Presek
    elif n == 8:
        if num_of_lists < 2:
            print("Nema dovoljno skupova u memoriji za racunanje preseka. Za ovu operaciju je potrebno dva skupa, "
                  "a u memoriji ih je {}".format(num_of_lists))
        else:
            if num_of_lists < 5:
                print("Unesite redni broj jednog skupa:")

                try:
                    a = int(input())  # redni broj skupa 1
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                print("Unesite redni broj drugog skupa:")

                try:
                    b = int(input())  # redni broj skupa 2
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                if 0 < a < 6 and 0 < b < 6:
                    if lists[a - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(a))

                    if lists[b - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(b))

                    else:
                        new_list = intersection(lists[a - 1], lists[b - 1])
                        num_of_lists += 1
                        for i in range(len(lists)):
                            if lists[i] == "":
                                index_empty = i
                                break
                        lists.pop(index_empty)
                        lists.insert(index_empty, new_list)
                else:
                    print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")
            else:
                print("Sva mesta su popunjena, izbrisite jedan skup.")

    # Razlika
    elif n == 9:
        if num_of_lists < 2:
            print("Nema dovoljno skupova u memoriji za racunanje razlike. Za ovu operaciju je potrebno dva skupa, "
                  "a u memoriji ih je {}".format(num_of_lists))
        else:
            if num_of_lists < 5:
                print("Unesite redni broj prvog skupa:")

                try:
                    a = int(input())  # redni broj skupa 1
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                print("Unesite redni broj drugog skupa:")

                try:
                    b = int(input())  # redni broj skupa 2
                except ValueError:
                    print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                    print("\nUnesite bilo koji znak za nastavak.")
                    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                    # unese nesto da bi se petlja opet izvrsila
                    continue

                if 0 < a < 6 and 0 < b < 6:
                    if lists[a - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(a))

                    if lists[b - 1] == "":
                        print("Ne postoji skup sa rednim brojem {}.".format(b))

                    else:
                        new_list = difference(lists[a - 1], lists[b - 1])
                        num_of_lists += 1
                        for i in range(len(lists)):
                            if lists[i] == "":
                                index_empty = i
                                break
                        lists.pop(index_empty)
                        lists.insert(index_empty, new_list)
                else:
                    print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")
            else:
                print("Sva mesta su popunjena, izbrisite jedan skup.")

    # Brisanje skupa
    elif n == 10:
        if num_of_lists == 0:
            print("Nema skupova za brisanje.")
        else:
            print("Unesite redni broj skupa za brisanje:")

            try:
                a = int(input())  # redni broj skupa
            except ValueError:
                print("Unos nije korektan. Redni broj skupa moze biti samo ceo broj.\n")
                print("\nUnesite bilo koji znak za nastavak.")
                con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik
                # unese nesto da bi se petlja opet izvrsila
                continue

            if 0 < a < 6:
                if lists[a - 1] != "":
                    lists[a - 1].start_node = None
                    del lists[a - 1]
                    lists.insert(a - 1, "")
                    num_of_lists -= 1
                else:
                    print("Na ovom mestu ne postoji skup.")
            else:
                print("Redni broj skupa moze biti samo ceo broj od 1 do 5.")

    elif n == 0:
        break

    print("\nUnesite bilo koji znak za nastavak.")
    con = input()  # pomocna promenljiva pomocu koje se ceka da korisnik unese nesto da bi se petlja opet izvrsila
    print("")
