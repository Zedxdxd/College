import random


# klasa za cvor liste
class ListNode:
    def __init__(self, name, surname, index, program, year):
        self.name = name
        self.surname = surname
        self.index = index
        self.program = program
        self.year = year
        self.next = None
        self.prev = None


# klasa za listu
class List:
    def __init__(self):
        self.start_node = None

    def insert_el(self, name, surname, index, program, year):
        new_node = ListNode(name, surname, index, program, year)  # kreiranje novog cvora

        # ako je lista prazna, kreiranje novog cvora
        if self.start_node is None:
            new_node.next = new_node
            new_node.prev = new_node
            self.start_node = new_node

        # ako lista nije prazna, nov cvor se postavlja na kraj liste
        else:
            p = self.start_node.prev  # postavljanje pokazivaca p na poslednji cvor
            p.next = new_node
            new_node.prev = p
            self.start_node.prev = new_node
            new_node.next = self.start_node

    def return_el(self, pos):
        # ako je lista prazna, ne vraca se element
        if self.start_node is None:
            return None

        # iterira se redom kroz listu dok brojac ne dodje do pozicije pos i onda se taj cvor vraca
        else:
            p = self.start_node
            for i in range(pos + 1):
                if i == pos:
                    return p
                p = p.next

    def delete_el(self, pos):
        """
        brisanje elementa sa zadate pozicije argumentom pos
        """

        # ako je lista prazna
        if self.start_node is None:
            return None

        else:
            p = self.return_el(pos)
            # q je pokazivac na prosli cvor, r je na sledeci od cvora na koji pokazuje pokazivac p
            q = p.prev
            r = p.next
            q.next = r
            r.prev = q

            # ako je samo jedan element u listi
            if p == q == r:
                self.start_node = None
                del q
                del r

            # ako se brise prvi element
            elif pos == 0:
                self.start_node = r

            del p

    def check_if_index_in_list(self, index):
        # ako je lista prazna, ne postoji cvor sa prosledjenim indeksom
        if self.start_node is None:
            return False

        else:
            p = self.start_node

            # p_check sluzi kao provera, ako se p vrati na vrednost p_check znaci da indeks nije nadjen
            p_check = self.start_node

            # provera da li je indeks na pocetnom cvoru
            if p.index == index:
                return True

            else:
                while True:
                    p = p.next
                    if p.index == index:
                        return True
                    elif p == p_check:
                        return False


# klasa za red
class Queue:
    def __init__(self):
        self.rear = None

    def insert_el(self, name, surname, index, program, year):
        new_node = ListNode(name, surname, index, program, year)  # kreiranje novog cvora

        # ako je red prazan, postavlja se pokazivac rear na new_node
        if self.rear is None:
            self.rear = new_node
            self.rear.next = new_node
            self.rear.prev = new_node

        else:
            p = self.rear.next  # pokazivac p ukazuje na front reda
            self.rear.next = new_node
            p.prev = new_node
            new_node.next = p
            new_node.prev = self.rear
            self.rear = new_node  # postavljanje pokazivaca rear na new_noce jer rear ukazuje na poslednji unet element

    def delete_el(self):
        # ako je red prazan, nema sta da se brise
        if self.rear is None:
            pass

        # ako postoje neki clanovi, brise se onaj cvor na koji ukazuje pokazivac front
        else:
            p = self.rear.next  # p je front reda
            r = p.next  # r je pokazivac na element koji ce postati front posle brisanja

            # ako su p i r jednaki, znaci da je u redu samo jedan element, pa posle brisanja red vise ne postoji
            if p == r:
                self.rear = None
                del p
                del r

            else:
                self.rear.next = r
                r.prev = self.rear
                del p

    def is_empty(self):
        if self.rear is None:
            return True
        else:
            return False

    def is_full(self, cap):
        # ako je red prazan
        if self.rear is None:
            return False

        else:
            p = self.rear
            counter = 0  # brojac clanova
            while True:
                p = p.next
                if p != self.rear:
                    counter += 1
                if counter == cap:
                    return True

    def return_el(self):
        # metoda koja vraca element ukazan pokazivacem front (rear.next)

        if self.rear is None:
            pass
        else:
            return self.rear.next


students_list = List()  # lista sa studentima
students_queue = Queue()  # red studenata
num_of_students = 0  # broj studenata koji se upise
step = 0  # korak simulacije

while True:
    print("1. Unos podataka o studentima;\n"
          "2. Zapocni simulaciju;\n"
          "0. Prekini program.")

    try:
        n = int(input())   # broj naredbe
    except ValueError:
        print("Unos nije korektan. Unesite ceo broj jednak nekom od brojeva naredbi.")
        continue

    if n not in [0, 1, 2, 3]:
        print("Ne postoji naredba sa takvim brojem. Unesite ceo broj jednak nekom od renih brojeva naredbi")
        continue

    # unos studenta
    elif n == 1:
        while True:
            print("Unesite ime:")
            name = input()

            print("Unesite prezime:")
            surname = input()

            print("Unesite broj indeksa:")
            index = input()

            print("Unesite studijski program:")
            program = input()

            print("Unesite trenutnu godinu studija:")
            try:
                year = int(input())
            except ValueError:
                print("Unos nije korektan. Unesite ceo broj za godinu studija.")
                continue
            if year > 3:
                print("Osnovne studije traju 4 godina, tako da trenutna godina studija ne moze biti veca od 3.")
                continue

            # posto je indeks studenta jedinstven, provera da li je vec unet student sa istim indeksom
            if not students_list.check_if_index_in_list(index):
                students_list.insert_el(name, surname, index, program, year)
                num_of_students += 1
            else:
                print("Student sa tim brojem indeksa je vec unet.")
                continue

            print("Student je ubacen u listu. "
                  "Ako zelite da upisete jos jednog studenta unesite 1."
                  "Za povratak na pocetni meni unesite bilo koji znak.")
            try:
                entry = int(input())
            except ValueError:
                print("")
                break

            if entry == 1:
                continue
            else:
                break

        print("Trenutni broj studenata u listi je {}.".format(num_of_students))

    # simulacija
    elif n == 2:
        # provera da li je unet bar jedan student
        if num_of_students != 0:

            # promenljiva koja cuva broj upisanih studenata, za potencijalnu proveru da li je red pun
            max_students = num_of_students

            # pakovanje studenata u red za cekanje
            while num_of_students > 0:
                # generisanje pseudoslucajnog broja manjeg od broja studenata
                x = random.randint(0, num_of_students - 1)

                # vracanje podataka o studentu sa pozicije x, brisanje iz liste i upis u red
                student = students_list.return_el(x)
                students_queue.insert_el(student.name, student.surname, student.index, student.program, student.year)
                students_list.delete_el(x)
                num_of_students -= 1
                step += 1

            # upis studenata
            while not students_queue.is_empty():
                x = random.uniform(0, 1)  # generisanje sanse za upis

                # student ne moze da upise godinu, pa se vraca na kraj reda da proba opet
                if 0 <= x <= 0.5:
                    student = students_queue.return_el()
                    students_queue.delete_el()
                    students_queue.insert_el(student.name, student.surname,
                                             student.index, student.program, student.year)
                    step += 1

                # student upisuje godinu, ispisuju se njegovi podaci i brise se iz reda
                elif 0.5 < x < 1:
                    student = students_queue.return_el()
                    print("Ime: {}, prezime: {}, upisana godina: {}"
                          .format(student.name, student.surname, student.year + 1))
                    students_queue.delete_el()
                    step += 1

            print("Broj koraka simulacije je {}".format(step))

        else:
            print("Nijedan student nije unet. Unesite podatke o bar jednom studentu da biste mogli"
                  "da pokrenete simulaciju upisa.")

    # prekid programa
    elif n == 0:
        break
