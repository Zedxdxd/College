#include "../h/_sem.hpp"
#include "../h/_console.hpp"
#include "../h/RISCV.hpp"

_sem::_sem(unsigned int val) {
    value = val;
    blocked_head = nullptr;
    blocked_tail = nullptr;
    closed = false;
    this->id = RISCV::semaphores.add(this);
}

int _sem::wait() {
    if (closed || id < 0){
        return -1;
    }
    if (--value < 0) {
        block();
    }
    if (closed){
        return -1;
    }
    return 0;
}

int _sem::signal() {
    if (closed || id < 0) {
        return -1;
    }
    if (++value <= 0) {
        unblock();
    }
    return 0;
}

void _sem::block() {
    if (!blocked_head){
        blocked_head = _thread::running;
    }
    else {
        blocked_tail->next = _thread::running;
    }
    blocked_tail = _thread::running;
    blocked_tail->next = nullptr;
    blocked_tail->set_blocked(true);
    _thread::yield();
}

void _sem::unblock() {
    // ako se poziva unblock, sigurno se neka nit vec blokirala na semaforu pa nema provere da li je blocked_head == nullptr
    thread_t cur = blocked_head;
    blocked_head = blocked_head->next;
    if (!blocked_head){
        blocked_tail = nullptr;
    }
    cur->set_blocked(false);
    cur->next = nullptr;
    Scheduler::put(cur);
}

void _sem::close() {
    while (blocked_head){
        thread_t cur = blocked_head;
        blocked_head = blocked_head->next;
        cur->next = nullptr;
        cur->set_blocked(false);
        Scheduler::put(cur);
    }
    blocked_tail = nullptr;
    closed = true;
}

bool _sem::is_finished() {
    return closed;
}

int _sem::get_id() {
    return id;
}
