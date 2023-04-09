#include "../h/RISCV.hpp"
#include "../lib/hw.h"
#include "../h/MemoryAllocator.hpp"
#include "../h/_sem.hpp"
#include "../h/_thread.hpp"
#include "../h/_console.hpp"
#include "../lib/console.h"

Array<thread_t> RISCV::threads = Array<thread_t>();
Array<sem_t> RISCV::semaphores = Array<sem_t>();

extern "C" void interrupt(){
    uint64 scause = RISCV::read_scause();

    // prekid od tajmera
    if (scause == TIMER_INTERRUPT){
        _thread::update_sleeping_list(); // smanjuje vreme nitima koje su uspavane
        _thread::curr_time_slices++;

        // istekao je vremenski odrezak, treba da se promeni kontekst
        if (_thread::curr_time_slices >= _thread::max_time_slices) {
            _thread::curr_time_slices = 0;
            _thread::yield();
        }

        // azuriranje sip registra jer je obradjen prekid od tajmera
        uint64 sip = RISCV::read_sip();
        sip &= ~2;
        RISCV::write_sip(sip);
    }

    // spoljasnji hardverski prekid
    else if (scause == EXTERNAL_HARDWARE_INTERRUPT){

        int val_int = plic_claim();
        if (val_int == 0xa){
            bool keyboard_interrupt = false;
            while (*(char*)CONSOLE_STATUS & CONSOLE_RX_STATUS_BIT){
                _console::load_char();
                keyboard_interrupt = true;
            }
            if (keyboard_interrupt){
                _thread::yield();
            }
            plic_complete(val_int);
        }
    }

    // prekid zbog poziva ecall iz korisnickog rezima
    else if (scause == ECALL_FROM_USER){
        uint64 sys_code, arg1, arg2, arg3, arg4;
        asm volatile("ld %0, 10 * 8(fp)" : "=r"(sys_code));
        asm volatile("ld %0, 11 * 8(fp)" : "=r"(arg1));
        asm volatile("ld %0, 12 * 8(fp)" : "=r"(arg2));
        asm volatile("ld %0, 13 * 8(fp)" : "=r"(arg3));
        asm volatile("ld %0, 14 * 8(fp)" : "=r"(arg4));

        // prelaz na privilegovani rezim
        if (sys_code == switch_privileges){
            uint64 sstatus;
            asm volatile("csrr %0, sstatus" : "=r"(sstatus));
            sstatus |= 1 << 8;
            asm volatile("csrw sstatus, %0" :: "r"(sstatus));
        }

        // mem_alloc
        else if (sys_code == mem_alloc_code){
            MemoryAllocator::alloc(arg1);
        }

        // mem_free
        else if (sys_code == mem_free_code){
            MemoryAllocator::dealloc((void*)arg1);
        }

        // thread_create
        else if (sys_code == thread_create_code){
            *((thread_t*)arg1) = new _thread((void(*)(void*))arg2, (void*)arg3, (void*)arg4);
            if ((*((thread_t*)arg1))->get_id() >= 0) {
                (*((thread_t *) arg1))->start();
            }
        }

        // thread_exit
        else if (sys_code == thread_exit_code){
            _thread::exit();
        }

        // thread_dispatch
        else if (sys_code == thread_dispatch_code){
            _thread::yield();
        }

        // thread_create_no_start
        else if (sys_code == thread_create_no_start_code){
            *((thread_t*)arg1) = new _thread((void(*)(void*))arg2, (void*)arg3, (void*)arg4);
            (*((thread_t*)arg1))->get_id();
        }

        // thread_start
        else if (sys_code == thread_start_code){
            ((thread_t)arg1)->start();
        }

        // sem_open
        else if (sys_code == sem_open_code){
            *((sem_t*)arg1) = new _sem(arg2);
            (*((sem_t*)arg1))->get_id();
        }

        // sem_close
        else if (sys_code == sem_close_code){
            ((sem_t)arg1)->close();
        }

        // sem_wait
        else if (sys_code == sem_wait_code){
            ((sem_t)arg1)->wait();
        }

        // sem_signal
        else if (sys_code == sem_signal_code){
            ((sem_t)arg1)->signal();
        }

        // time_sleep
        else if (sys_code == time_sleep_code){
            _thread::sleep(arg1);
        }

        // getc
        else if (sys_code == getc_code){
            _console::_getc();
        }

        // putc
        else if (sys_code == putc_code){
            _console::_putc(arg1);
        }

        asm volatile("sd a0, 10 * 8(fp)");

        // sepc ima sacuvanu adresu poziva ecall, pa treba da se poveca za 4 da bi se vratilo na sledecu instrukciju
        asm volatile("csrr t0, sepc");
        asm volatile("addi t0, t0, 4");
        asm volatile("csrw sepc, t0");

    }

    // prekid zbog poziva ecall iz privilegovanog rezima
    else if (scause == ECALL_FROM_PRIVILEGED){
        uint64 sys_code, arg1, arg2, arg3, arg4;
        asm volatile("ld %0, 10 * 8(fp)" : "=r"(sys_code));
        asm volatile("ld %0, 11 * 8(fp)" : "=r"(arg1));
        asm volatile("ld %0, 12 * 8(fp)" : "=r"(arg2));
        asm volatile("ld %0, 13 * 8(fp)" : "=r"(arg3));
        asm volatile("ld %0, 14 * 8(fp)" : "=r"(arg4));

        // prebacivanje na korisnicki rezim
        if (sys_code == switch_privileges){
            uint64 sstatus;
            asm volatile("csrr %0, sstatus" : "=r"(sstatus));
            sstatus &= ~(1 << 8);
            asm volatile("csrw sstatus, %0" :: "r"(sstatus));
        }

        // mem_alloc
        else if (sys_code == mem_alloc_code){
            MemoryAllocator::alloc(arg1);
        }

        // mem_free
        else if (sys_code == mem_free_code){
            MemoryAllocator::dealloc((void*)arg1);
        }

        // thread_create
        else if (sys_code == thread_create_code){
            *((thread_t*)arg1) = new _thread((void(*)(void*))arg2, (void*)arg3, (void*)arg4);
            if ((*((thread_t*)arg1))->get_id() >= 0) {
                (*((thread_t *) arg1))->start();
            }
        }

        // thread_exit
        else if (sys_code == thread_exit_code){
            _thread::exit();
        }

        // thread_dispatch
        else if (sys_code == thread_dispatch_code){
            _thread::yield();
        }

        // thread_create_no_start
        else if (sys_code == thread_create_no_start_code){
            *((thread_t*)arg1) = new _thread((void(*)(void*))arg2, (void*)arg3, (void*)arg4);
            (*((thread_t*)arg1))->get_id();
        }

        // thread_start
        else if (sys_code == thread_start_code){
            ((thread_t)arg1)->start();
        }

        // sem_open
        else if (sys_code == sem_open_code){
            *((sem_t*)arg1) = new _sem(arg2);
            (*((sem_t*)arg1))->get_id();
        }

        // sem_close
        else if (sys_code == sem_close_code){
            ((sem_t)arg1)->close();
        }

        // sem_wait
        else if (sys_code == sem_wait_code){
            ((sem_t)arg1)->wait();
        }

        // sem_signal
        else if (sys_code == sem_signal_code){
            ((sem_t)arg1)->signal();
        }

        // time_sleep
        else if (sys_code == time_sleep_code){
            _thread::sleep(arg1);
        }

        // getc
        else if (sys_code == getc_code){
            _console::_getc();
        }

        // putc
        else if (sys_code == putc_code){
            _console::_putc(arg1);
        }

        asm volatile("sd a0, 10 * 8(fp)");

        // sepc ima sacuvanu adresu poziva ecall, pa treba da se poveca za 4 da bi se vratilo na sledecu instrukciju
        asm volatile("csrr t0, sepc");
        asm volatile("addi t0, t0, 4");
        asm volatile("csrw sepc, t0");
    }

    else if (scause == ILLEGAL_INSTRUCTION_INTERRUPT){
        _console::print_string("Illegal instruction: sepc=");
        uint64 sepc;
        asm volatile("csrr %0, sepc" : "=r"(sepc));
        _console::print_int(sepc, 16);
        _console::_putc('\n');
    }

    else if (scause == ILLEGAL_READ_ADDRESS_INTERRUPT){
        _console::print_string("Illegal read address: sepc=");
        uint64 sepc;
        asm volatile("csrr %0, sepc" : "=r"(sepc));
        _console::print_int(sepc, 16);
        _console::_putc('\n');
    }

    else if (scause == ILLEGAL_WRITE_ADDRESS_INTERRUPT){
        _console::print_string("Illegal write address: sepc=");
        uint64 sepc;
        asm volatile("csrr %0, sepc" : "=r"(sepc));
        _console::print_int(sepc, 16);
        _console::_putc('\n');
    }

    else {
        _console::print_string("Unknown cause of interrupt: sepc=");
        uint64 tmp;
        asm volatile("csrr %0, sepc" : "=r"(tmp));
        _console::print_int(tmp, 16);
        _console::print_string(", scause=");
        _console::print_int(RISCV::read_scause());
        _console::_putc('\n');
    }
}

bool RISCV::kernel_finished = false;
bool RISCV::user_main_finished = false;


void RISCV::wrapper_userMain(void* arg) {
    userMain();
    user_main_finished = true;
}

void RISCV::idle_function(void *arg) {
    while(!kernel_finished){
        thread_dispatch();
    }
}