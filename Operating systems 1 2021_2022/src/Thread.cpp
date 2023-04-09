#include "../h/Thread.hpp"
#include "../h/_thread.hpp"
#include "../h/syscall_c.hpp"
#include "../h/Scheduler.hpp"

Thread::Thread(void (*body)(void *), void *arg) {
    thread_create_no_start(&myHandle, body, arg);
    if (body == nullptr){
        _thread::running = myHandle;
    }
}

Thread::~Thread() {
    while (!myHandle->is_finished()){
        _thread::running->blocked = true;
        _thread::running->next = myHandle->waiting_list_head;
        myHandle->waiting_list_head = _thread::running;
        thread_dispatch();
    }
    thread_dispatch();
}

int Thread::start() {
    if (_thread::running == nullptr){
        _thread::running = myHandle;
        return 0;
    }
    else if (myHandle->body == nullptr){
        return 0;
    }
    return thread_start(myHandle);
}

void Thread::dispatch() {
    thread_dispatch();
}

Thread::Thread() {
    thread_create_no_start(&myHandle, Thread_wrapper, this);
    if (!_thread::running){
        _thread::running = myHandle;
    }
}


int Thread::sleep(time_t time) {
    return time_sleep(time);
}

void Thread::finish_thread(Thread *thread) {
    thread->myHandle->set_finished(true);
}


void Thread_wrapper(void* arg){
    Thread* thr = (Thread*) arg;
    thr->run();
}