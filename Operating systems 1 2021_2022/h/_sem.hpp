#ifndef __sem_hpp_
#define __sem_hpp_

#include "_thread.hpp"
#include "Scheduler.hpp"

class _thread;
using thread_t = _thread*;

class _sem{
    int value;

    // indikator da li je semafor zatvoren. Ako se neka nit odblokira prilikom zatvaranja semafora, vratice se u njegov
    // wait, pa mora da vrati gresku
    bool closed;

    // glava i rep liste niti koje cekaju na semaforu
    thread_t blocked_head, blocked_tail;

    // identifikator u nizu pregradaka
    int id;

    // blokira tekucu nit
    void block();

    // odblokira prvu nit iz liste
    void unblock();

public:
    int wait();
    int signal();
    void close();
    _sem(unsigned int val = 1);

    bool is_finished();

    int get_id();


    // preklopljeni new i delete kako bi se sa new direktno pozvao MemoryAllocator
    void* operator new(size_t size){
        _sem* ptr = (_sem*)MemoryAllocator::alloc(bytes_to_blocks(size));
        return ptr;
    }

    void operator delete(void* ptr){
        MemoryAllocator::dealloc(ptr);
    }
};

using sem_t = _sem*;

#endif //__sem_hpp_
