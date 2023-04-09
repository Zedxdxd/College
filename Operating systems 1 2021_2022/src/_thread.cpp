#include "../h/_thread.hpp"
#include "../h/RISCV.hpp"
#include "../h/Scheduler.hpp"
#include "../h/syscall_c.hpp"
#include "../h/RISCV.hpp"

thread_t _thread::running = nullptr;
time_t _thread::curr_time_slices = 0;
thread_t _thread::sleeping_threads_head = nullptr;

// cuvanje starog i restauracija novog konteksta
void _thread::yield() {
    // cuvanje starog konteksta
    RISCV::push_registers();

    // dobijanje novog konteksta
    dispatch();

    // restauracija novog konteksta
    RISCV::pop_registers();
}

// dobijanje novog konteksta
void _thread::dispatch() {
    _thread* old_thr = _thread::running;

    if (!old_thr->is_finished() && !old_thr->blocked) {
        Scheduler::put(old_thr);
    }

    _thread::running = Scheduler::get();
    context_switch(&old_thr->context, &running->context);
}

// omotac oko tela niti, potrebno je ovo da bi se postavilo da je nit zavrsila kad zavrsi sa radom
void wrapper(){
    _thread::running->body(_thread::running->args);

    // zavrsava tekucu nit, poziva se sistemski poziv jer je wrapper u "korisnickom" rezimu
    thread_exit();

}

_thread::_thread(Body body, void* args, void* stack_space){
    this->body = body;
    this->finished = false;
    this->blocked = false;
    this->started = false;
    // alocirano mesto za stek
    this->stack = (uint64*)stack_space;
    this->context.sp = this->stack != nullptr ? (uint64)((char*)this->stack + DEFAULT_STACK_SIZE) : 0;
    this->context.ra = (uint64)(wrapper);
    this->context.sepc = (uint64)(wrapper);
    uint64 sstatus = RISCV::read_sstatus();
    if (sstatus & (1<<8)){
        this->privileged = true;
    }
    else {
        this->privileged = false;
    }
    this->context.sstatus = sstatus;
    this->args = args;
    this->next = nullptr;
    this->context.first_context_switch = this->stack != nullptr ? 1 : 0;
    this->sleeping_time = 0;
    this->waiting_list_head = nullptr;
    this->id = RISCV::threads.add(this);
}

// gasi tekucu nit, predaje procesor drugoj niti
int _thread::exit() {
    _thread::running->set_finished(true);
    while(_thread::running->waiting_list_head){
        _thread::running->waiting_list_head->blocked = false;
        Scheduler::put(_thread::running->waiting_list_head);
        _thread::running->waiting_list_head = _thread::running->waiting_list_head->next;
    }
    MemoryAllocator::dealloc(_thread::running->stack);
    RISCV::threads[_thread::running->id] = nullptr;
    _thread::yield();
    return 0;
}

int _thread::start() {
    // nit je vec stavljena u Scheduler, pa je greska
    if (this->started || id < 0){
        return -1;
    }

    this->started = true;
    if (!this->body) {
        return 0;
    }
    Scheduler::put(this);
    return 0;
}

bool _thread::is_finished() const{
    return finished;
}

// postavljanje zavrsenosti
void _thread::set_finished(bool f){
    finished = f;
}

// postavljanje da je nit blokirana
// da se ne bi stavila u Scheduler
void _thread::set_blocked(bool b){
    blocked = b;
}

void _thread::set_privileged(bool p){
    privileged = p;
}

bool _thread::is_privileged(){
    return privileged;
}

int _thread::get_id(){
    return id;
}

void _thread::sleep(time_t time) {
    _thread* cur = sleeping_threads_head, *prev = nullptr;

    // ako je lista prazna, samo se stavi clan
    if (!cur){
        sleeping_threads_head = _thread::running;
        _thread::running->next = nullptr;
        _thread::running->sleeping_time = time;
        _thread::running->blocked = true;
        _thread::yield();
        return;
    }

    // ako je vreme cekanja manje od prvog clana, doda se na pocetak liste i azurira se prvi clan posle njega
    if (time <= cur->sleeping_time){
        _thread* cur = sleeping_threads_head; // ova linija je bespotrebna
        _thread::running->next = sleeping_threads_head;
        sleeping_threads_head = _thread::running;
        _thread::running->sleeping_time = time;
        _thread::running->blocked = true;
        cur->sleeping_time -= time;
        _thread::yield();
        return;
    }

    time -= cur->sleeping_time;
    prev = cur;
    cur = cur->next;


    // ide se kroz listu dok se ne pronadje mesto za tekucu nit u listi
    while(cur){
        if (time <= cur->sleeping_time){
            /*_thread::running->next = cur;
            prev->next = _thread::running;
            _thread::running->sleeping_time = time;
            cur->sleeping_time -= time;
            _thread::running->blocked = true;
            _thread::yield();
            return;*/
            break;
        }
        time -= cur->sleeping_time;
        prev = cur;
        cur = cur->next;
    }

    prev->next = _thread::running;
    //_thread::running->next = nullptr;
    _thread::running->next = cur;
    _thread::running->sleeping_time = time;
    if (cur) cur->sleeping_time -= time;
    _thread::running->blocked = true;
    _thread::yield();

}

void _thread::update_sleeping_list() {
    // ako je prazna lista nema sta da se dekrementira
    if (!_thread::sleeping_threads_head){
        return;
    }

    // za svaki slucaj se proverava da li je vece od nule, da ne predje u MAX_INT (time_t je unsigned)
    if (_thread::sleeping_threads_head->sleeping_time > 0) {
        _thread::sleeping_threads_head->sleeping_time--;
    }

    // dok god se nailazi na clanove liste sa vremenom spavanja 0, oni se ubacuju u Scheduler
    while (_thread::sleeping_threads_head && _thread::sleeping_threads_head->sleeping_time == 0){
        _thread* cur = _thread::sleeping_threads_head;
        _thread::sleeping_threads_head = _thread::sleeping_threads_head->next;
        cur->next = nullptr;
        cur->blocked = false;
        Scheduler::put(cur);
    }
}

void _thread::nullify_sleeping_list() {
    _thread::sleeping_threads_head = nullptr;
}
