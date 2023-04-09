.global interrupt
.global interruptvec
.align 4

interruptvec:
    # oduzima se 264 jer se ostavlja jedna lokacija za povratnu vrednost sistemskih poziva koja ce biti tacno pre svih registara na steku
    # na kraju se dodaje 264 pa se stek vraca u prvobitno stanje i ako je pozvan ecall, tu ce se procitati sa lokacije -1*8(sp)
    # gde ce u stvari tacno biti povratna vrednost sistemskog poziva
    addi sp, sp, -256
    .irp index, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31
    sd x\index, \index * 8 (sp)
    .endr

    call interrupt


    # radi sa gp(x2), t1(x6), t2(x7), s8(x24)?, s10(x26)?, s11(x27)?, t3(x28), t4(x29), t5(x30), t6(x31)
    # cuvam a0 (povratnu vrednost) na mesto odakle se skida registar t6, i posle iz registra t6 procitam tu povratnu vrednost
    # ako se otkomentarise ovo, treba u syscall da se otkomentarise citanje iz t6
    #sd x10, 31 * 8 (sp)


    # cuva se na steku povratna vrednost, tacno ispod lokacije sp sa kojom je udjeno u prekidnu rutinu
    #sd x10, 32 * 8 (sp), ako se otkomentarise ovo treba da se stavi sa sp (-)264, i u syscall da se skida sa sp

    .irp index, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31
    ld x\index, \index * 8 (sp)
    .endr
    addi sp, sp, 256
    sret