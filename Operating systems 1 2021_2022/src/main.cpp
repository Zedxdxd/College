#include "../h/RISCV.hpp"
#include "../h/syscall_cpp.hpp"
#include "../h/_console.hpp"
#include "../h/_thread.hpp"
#include "../h/Thread.hpp"
#include "../h/Semaphore.hpp"
#include "../h/PeriodicThread.hpp"

void main(){

    // inicijalizacija kernela
    MemoryAllocator::init();
    RISCV::write_stvec(interruptvec);
    _console::init();

    // dozvoljavanje spoljasnjih prekida
    /*uint64 sstatus = RISCV::read_sstatus();
    sstatus |= 2;
    RISCV::write_sstatus(sstatus);*/

    // jedinstveno pokretanje njihovih testova
    thread_t thr;
    thread_create(&thr, nullptr, nullptr);
    _thread::running = thr;
    thread_t putc_thr;
    thread_create(&putc_thr, _console::putc_thread, nullptr);

    asm volatile("xor a0, a0, a0");
    asm volatile("ecall");
    thread_create(&Scheduler::idle, RISCV::idle_function, nullptr);
    thread_t user_thread;
    thread_create(&user_thread, RISCV::wrapper_userMain, nullptr);

    while (!RISCV::user_main_finished){
        thread_dispatch();
    }

    RISCV::kernel_finished = true;

    // vracanje u sistemski rezim
    asm volatile("xor a0, a0, a0");
    asm volatile("ecall");

    // ovaj ovde dispatch sluzi da se pogase ostale sistemske niti jer je kernel zavrsio svoj rad
    thread_dispatch();

    RISCV::threads.delete_all_threads();
    _thread::nullify_sleeping_list();

    // osiguranje da se sve ispisalo na konzolu
    //time_sleep(5);



}
