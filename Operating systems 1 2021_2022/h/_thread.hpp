#ifndef __thread_hpp_
#define __thread_hpp_

#include "../lib/hw.h"
#include "MemoryAllocator.hpp"
#include "RISCV.hpp"
#include "_sem.hpp"

class _thread{
    // telo koje nit izvrsava
    using Body = void(*)(void*);
    Body body;
    void* args; // argumenti tela niti

    // kontekst izvrsavanja
    struct Context{
        uint64 sp;
        uint64 ra;
        uint64 sepc;
        uint64 sstatus;

        // indikator da li je nit prvi put dosla na izvrsavanje, jer ako jeste onda ne treba
        // da se skidaju registri sa steka, nego se odmah iskace iz prekidne rutine
        uint64 first_context_switch;
    };
    Context context;


    // stack je alocirano mesto za stek, dealocira se pri izlasku iz niti
    uint64* stack;

    // indikator da li je nit zavrsena
    bool finished;

    // indikator da li je blokirana na nekom semaforu (znaci da ne treba da se uzme iz scheduler-a kao spremna
    // takodje da li je uspavana
    bool blocked;

    // indikator da li se nit izvrsava u privilegovanom ili korisnickom rezimu (zbog prelaska u odgovarajuci rezim prilikom promene konteksta)
    bool privileged;

    // indikator da li je nit zapocela svoje izvrsavanje (koristi se kako ne bi mogla dva puta da se zapocne nit)
    bool started;

    // za ulancavanje u Scheduler, na semaforu kad se blokira ili kad se uspava
    _thread* next;

    // lista uspavanih niti
    static _thread* sleeping_threads_head;

    // za svaku nit koliko treba da spava kad se uspava
    time_t sleeping_time;

    // identifikator u nizu pregradaka
    int id;

    // lista niti koje cekaju da se ova nit zavrsi
    _thread* waiting_list_head;

    // zamena konteksta (cuva se trenutni kontekst i postavlja se novi kontekst)
    static void context_switch(Context* old_context, Context* new_thr);

    // prijateljske klase i funkcije, olaksavaju operacije
    friend class Scheduler;
    friend void wrapper();
    friend class Thread;
    friend class _sem;

public:
    _thread(Body body, void* args, void* stack_space);

    // provera da li je nit zavrsena
    bool is_finished() const;

    // postavljanje zavrsenosti
    void set_finished(bool f);

    // postavljanje da je nit blokirana
    // da se ne bi stavila u Scheduler
    void set_blocked(bool b);

    void set_privileged(bool p);

    bool is_privileged();

    int get_id();

    // tekuca nit
    static _thread* running;

    // tekuci broj vremenskih odrezaka
    static time_t curr_time_slices;

    // maksimalni broj vremenskih odrezaka pre promene konteksta
    const static time_t max_time_slices = DEFAULT_TIME_SLICE;

    // zamena konteksta
    static void yield();

    // predavanje procesora drugoj (ili istoj) niti
    static void dispatch();

    // unistavanje tekuce niti
    static int exit();

    // startovanje niti
    int start();

    // uspavljuje nit
    static void sleep(time_t time);

    // azurira listu jer je prosao jedan vremenski odrezak
    static void update_sleeping_list();

    // postavlja head liste uspavanih niti na nullptr, koristi se na kraju kernela da bi se izbacile niti koje spavaju
    static void nullify_sleeping_list();

    // preklopljeni new i delete kako bi se sa new direktno pozvao MemoryAllocator
    void* operator new(size_t size){
        return MemoryAllocator::alloc(bytes_to_blocks(size));
    }

    void operator delete(void* ptr){
        MemoryAllocator::dealloc(ptr);
    }

};

using thread_t = _thread*;

#endif //__thread_hpp_
