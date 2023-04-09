#include "../h/Scheduler.hpp"


thread_t Scheduler::head = nullptr;
thread_t Scheduler::tail = nullptr;
thread_t Scheduler::idle = nullptr;

void Scheduler::put(thread_t thr) {
    if (thr == idle){
        return;
    }
    if (head){
        tail->next = thr;
    }
    else {
        head = thr;
    }
    tail = thr;
    tail->next = nullptr;
}

thread_t Scheduler::get(){
    if (!head){
        return idle;
    }
    thread_t ret = head;

    head = head->next;
    if (!head){
        tail = nullptr;
    }
    ret->next = nullptr;
    return ret;
}