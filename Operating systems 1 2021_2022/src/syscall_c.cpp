#include "../lib/hw.h"
#include "../h/syscall_c.hpp"
#include "../h/RISCV.hpp"


void system_call(sys_call_args* args){

    // spremanje argumenata sistemskih poziva
    uint64 reg = args->arg1;
    RISCV::write_a1(reg);
    reg = args->arg2;
    RISCV::write_a2(reg);
    reg = args->arg3;
    RISCV::write_a3(reg);
    reg = args->arg4;
    RISCV::write_a4(reg);

    // cuvanje vrednosti arguments(a0) na stek
    asm volatile("addi sp, sp, -8");
    asm volatile("sd a0, 0 * 8(sp)");

    // upisivanje koda operacije
    reg = args->code;
    RISCV::write_a0(reg);

    RISCV::ecall();

    // povratna vrednost sistemskog poziva
    //asm volatile("addi %0, t6, 0" : "=r"(reg));
    //asm volatile("ld %0, -1 * 8(sp)" : "=r"(reg));
    asm volatile("addi %0, a0, 0" : "=r"(reg));

    // vracanje vrednosti arguments(a0) sa steka
    asm volatile("ld a0, 0 * 8(sp)");
    asm volatile("addi sp, sp, 8");

    // upisivanje povratne vrednosti
    args->ret_val = (long)reg;
}


void* mem_alloc(size_t sz){
    // sz - broj bajtova koji treba zauzeti

    sys_call_args arguments;

    // pretvaranje sz u broj blokova
    sz = (sz + sizeof(size_t)) / MEM_BLOCK_SIZE + ((sz + sizeof(size_t)) % MEM_BLOCK_SIZE == 0 ? 0 : 1);

    arguments.code = mem_alloc_code;
    arguments.arg1 = sz;
    system_call(&arguments);

    return (void*)arguments.ret_val;
}


int mem_free(void* ptr){
    // ptr - pokazivac na memoriju koju treba osloboditi

    sys_call_args arguments;

    arguments.code = mem_free_code;
    arguments.arg1 = (uint64)ptr;
    system_call(&arguments);

    return arguments.ret_val;
}


int thread_create (thread_t* handle, void(*start_routine)(void*), void* arg) {
    // handle - pokazivac na nit koja se kreira
    // start_routine - pokazivac na funkciju koju ce ta nit izvrsavati
    // arg - argumetni funkcije start_routine

    sys_call_args arguments;

    // cuvaju se promenljive na steku jer su upisane u a0-a4,a to je potrebno za sistemski poziv mem_alloc
    asm volatile("addi sp, sp, -24");
    asm volatile("sd %0, 0 * 8(sp)" :: "r"(handle));
    asm volatile("sd %0, 1 * 8(sp)" :: "r"(start_routine));
    asm volatile("sd %0, 2 * 8(sp)" :: "r"(arg));

    // alociranje prostora za stek
    uint64* stack_space = nullptr;
    if (start_routine != nullptr){
        stack_space = (uint64*) mem_alloc(DEFAULT_STACK_SIZE);
    }
    arguments.arg4 = (uint64)stack_space;

    // vracanje promenljivih sa steka
    asm volatile("ld %0, 0 * 8(sp)" : "=r"(handle));
    asm volatile("ld %0, 1 * 8(sp)" : "=r"(start_routine));
    asm volatile("ld %0, 2 * 8(sp)" : "=r"(arg));
    asm volatile("addi sp, sp, 24");

    arguments.code = thread_create_code;
    arguments.arg1 = (uint64)handle;
    arguments.arg2 = (uint64)start_routine;
    arguments.arg3 = (uint64)arg;
    system_call(&arguments);

    return arguments.ret_val;
}


int thread_exit(){

    sys_call_args arguments;

    arguments.code = thread_exit_code;
    system_call(&arguments);

    return arguments.ret_val;
}


void thread_dispatch(){

    sys_call_args arguments;

    // kod poziva thread_dispatch
    arguments.code = thread_dispatch_code;

    system_call(&arguments);
}

int thread_create_no_start (thread_t* handle, void(*start_routine)(void*), void* arg){
    // handle - pokazivac na nit koja se kreira
    // start_routine - pokazivac na funkciju koju ce ta nit izvrsavati
    // arg - argumetni funkcije start_routine

    sys_call_args arguments;

    // cuvaju se promenljive na steku jer su upisane u a0-a4,a to je potrebno za sistemski poziv mem_alloc
    asm volatile("addi sp, sp, -24");
    asm volatile("sd %0, 0 * 8(sp)" :: "r"(handle));
    asm volatile("sd %0, 1 * 8(sp)" :: "r"(start_routine));
    asm volatile("sd %0, 2 * 8(sp)" :: "r"(arg));

    // alociranje prostora za stek
    uint64* stack_space = nullptr;
    if (start_routine != nullptr){
        stack_space = (uint64*) mem_alloc(DEFAULT_STACK_SIZE);
    }
    arguments.arg4 = (uint64)stack_space;

    asm volatile("ld %0, 0 * 8(sp)" : "=r"(handle));
    asm volatile("ld %0, 1 * 8(sp)" : "=r"(start_routine));
    asm volatile("ld %0, 2 * 8(sp)" : "=r"(arg));
    asm volatile("addi sp, sp, 24");

    arguments.code = thread_create_no_start_code;
    arguments.arg1 = (uint64)handle;
    arguments.arg2 = (uint64)start_routine;
    arguments.arg3 = (uint64)arg;
    system_call(&arguments);

    return arguments.ret_val;
}


int thread_start(thread_t handle){
    // handle - rucka ciji se rad zapocinje

    sys_call_args arguments;

    arguments.code = thread_start_code;
    arguments.arg1 = (uint64)handle;
    system_call(&arguments);

    return arguments.ret_val;
}


int sem_open(sem_t* handle, unsigned init){
    // handle - pokazivac na semafor koji se kreira
    // init - pocetna vrednost semafora

    sys_call_args arguments;

    arguments.code = sem_open_code;
    arguments.arg1 = (uint64)handle;
    arguments.arg2 = (uint64)init;
    system_call(&arguments);

    return arguments.ret_val;
}


int sem_close(sem_t handle){
    // handle - rucka semafora koji se zatvara

    sys_call_args arguments;

    arguments.code = sem_close_code;
    arguments.arg1 = (uint64)handle;
    system_call(&arguments);

    return arguments.ret_val;
}


int sem_wait(sem_t handle){
    // handle - rucka semafora na kome se izvrsava operacija wait

    sys_call_args arguments;

    arguments.code = sem_wait_code;
    arguments.arg1 = (uint64)handle;
    system_call(&arguments);

    return arguments.ret_val;
}


int sem_signal(sem_t handle){
    // handle - rucka semafora na kome se izvrsava operacija signal

    sys_call_args arguments;

    arguments.code = sem_signal_code;
    arguments.arg1 = (uint64)handle;
    system_call(&arguments);

    return arguments.ret_val;
}


int time_sleep(time_t time){
    // time - broj odrezaka tajmera na koji se uspavljuje tekuca nit

    sys_call_args arguments;

    arguments.code = time_sleep_code;
    arguments.arg1 = (uint64)time;
    system_call(&arguments);

    return arguments.ret_val;
}


char getc(){
    sys_call_args arguments;

    arguments.code = getc_code;
    system_call(&arguments);

    return arguments.ret_val;
}


void putc(char c){
    // c - karakter koji ce se ispisati na konzolu

    sys_call_args arguments;

    arguments.code = putc_code;
    arguments.arg1 = (uint64)c;

    system_call(&arguments);
}

