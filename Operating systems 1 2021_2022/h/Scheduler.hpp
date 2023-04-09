#ifndef _Scheduler_hpp_
#define _Scheduler_hpp_

#include "_thread.hpp"

class _thread;
using thread_t = _thread*;

class Scheduler {

    static thread_t head;
    static thread_t tail;
public:
    static void put(thread_t thr);
    static thread_t get();

    static thread_t idle; // nit koja ima prazno telo, samo vrsi dispatch..
};


#endif //_Scheduler_hpp_
