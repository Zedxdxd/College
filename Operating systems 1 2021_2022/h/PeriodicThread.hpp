#ifndef _PeriodicThread_hpp_
#define _PeriodicThread_hpp_

#include "Thread.hpp"

class PeriodicThread : public Thread {
private:
    time_t period;
protected:
    PeriodicThread (time_t period);
    virtual void periodicActivation () {}
    void run() override;
public:
    ~PeriodicThread() override;
};

#endif // _PeriodicThread_hpp_
