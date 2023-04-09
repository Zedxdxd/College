#ifndef _Semaphore_hpp_
#define _Semaphore_hpp_
#include "_sem.hpp"

class Semaphore {
private:
    sem_t myHandle;

public:
    Semaphore (unsigned init = 1);
    virtual ~Semaphore ();
    int wait ();
    int signal ();
};

#endif //_Semaphore_hpp_
