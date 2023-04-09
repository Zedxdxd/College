#ifndef _Thread_hpp_
#define _Thread_hpp_

#include "../lib/hw.h"
#include "../h/_thread.hpp"
#include "syscall_c.hpp"

class Thread {
private:
    friend void Thread_wrapper(void* arg);
    thread_t myHandle;

protected:
    Thread ();
    virtual void run () {}
    static void finish_thread(Thread* thread);

public:
    Thread (void (*body)(void*), void* arg);
    virtual ~Thread ();
    int start ();
    static void dispatch ();
    static int sleep (time_t time);
};

void Thread_wrapper(void* arg);

#endif //_Thread_hpp_