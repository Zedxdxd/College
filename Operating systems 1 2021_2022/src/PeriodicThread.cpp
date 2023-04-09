#include "../h/PeriodicThread.hpp"


PeriodicThread::PeriodicThread(time_t period) : Thread() {
    this->period = period;
}

void PeriodicThread::run() {
    while(1){
        periodicActivation();
        time_sleep(period);
    }
}

PeriodicThread::~PeriodicThread() {
    Thread::finish_thread(this);
}