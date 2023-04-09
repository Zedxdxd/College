#ifndef _syscall_c_hpp_
#define _syscall_c_hpp_

#include "MemoryAllocator.hpp"
#include "../lib/hw.h"
#include "_thread.hpp"
#include "_sem.hpp"

class _thread;
using thread_t  = _thread*;
class _sem;
using sem_t = _sem*;

// struktura za poziv sistemskih poziva
struct sys_call_args{
    uint64 code;
    uint64 arg1 = 0, arg2 = 0, arg3 = 0, arg4 = 0;
    long ret_val;
};


void system_call(sys_call_args* args); // ujedinjeno pakovanje argumenata za sistemske pozive i sistemski poziv


void* mem_alloc(size_t sz); // C API za alokaciju
int mem_free(void* ptr);    // C API za dealokaciju

int thread_create (thread_t* handle, void(*start_routine)(void*), void* arg); // C API za kreiranje niti i startuje je
int thread_exit(); // C API za gasenje niti
void thread_dispatch(); // C APi za sinhronu promenu konteksta
int thread_create_no_start (thread_t* handle, void(*start_routine)(void*), void* arg); // C API za kreiranje niti
int thread_start(thread_t handle); // C API za startovanje niti

int sem_open(sem_t* handle, unsigned int init); // C API za kreiranje semafora
int sem_close(sem_t handle); // C API za unistavanje semafora
int sem_wait(sem_t handle); // C API za operaciju cekanja na semaforu handle
int sem_signal(sem_t signal); // C API za operaciju signaliziranja na semaforu handle

int time_sleep(time_t time); // C API za uspavljivanje tekuce niti

char getc(); // C API za dobijanje karaktera sa konzole
void putc(char c); // C API za ispis na konzolu


#endif //_syscall_c_hpp_
