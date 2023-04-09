#ifndef _RISCV_HPP_
#define _RISCV_HPP_

#include "../lib/hw.h"
#include "Array.hpp"
#include "_thread.hpp"
#include "_sem.hpp"

class _thread;
using thread_t = _thread*;
class _sem;
using sem_t = _sem*;
void userMain();

// usluzna klasa sa statickim metodama koje ugradjuju asemblerski kod kao i pomocnim promenljivama
class RISCV {
public:
    inline static void write_stvec(void(*ivec)()){
        asm volatile("csrw stvec, %0" :: "r" (ivec));
    }

    inline static uint64 read_scause(){
        uint64 val;
        asm volatile("csrr %0, scause" : "=r"(val));
        return val;
    }

    inline static uint64 read_sip(){
        uint64 val;
        asm volatile("csrr %0, sip" : "=r"(val));
        return val;
    }

    inline static void write_sip(uint64 val){
        asm volatile("csrw sip, %0" :: "r"(val));
    }

    inline static uint64 read_sstatus(){
        uint64 val;
        asm volatile("csrr %0, sstatus" : "=r"(val));
        return val;
    }

    inline static void write_sstatus(uint64 val){
        asm volatile("csrw sstatus, %0" :: "r"(val));
    }

    inline static void write_a0(uint64 val){
        asm volatile("addi a0, %0, 0" :: "r"(val));
    }

    inline static void write_a1(uint64 val){
        asm volatile("addi a1, %0, 0" :: "r"(val));
    }

    inline static void write_a2(uint64 val){
        asm volatile("addi a2, %0, 0" :: "r"(val));
    }

    inline static void write_a3(uint64 val){
        asm volatile("addi a3, %0, 0" :: "r"(val));
    }

    inline static void write_a4(uint64 val){
        asm volatile("addi a4, %0, 0" :: "r"(val));
    }

    inline static void write_a5(uint64 val){
        asm volatile("addi a5, %0, 0" :: "r"(val));
    }

    inline static void write_a6(uint64 val){
        asm volatile("addi a6, %0, 0" :: "r"(val));
    }

    inline static void ecall(){
        asm volatile("ecall");
    }

    inline static uint64 read_a0(){
        uint64 val;
        asm volatile("addi %0, a0, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a1(){
        uint64 val;
        asm volatile("addi %0, a1, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a2(){
        uint64 val;
        asm volatile("addi %0, a2, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a3(){
        uint64 val;
        asm volatile("addi %0, a3, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a4(){
        uint64 val;
        asm volatile("addi %0, a4, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a5(){
        uint64 val;
        asm volatile("addi %0, a5, 0" : "=r" (val));
        return val;
    }

    inline static uint64 read_a6(){
        uint64 val;
        asm volatile("addi %0, a6, 0" : "=r" (val));
        return val;
    }

    static void push_registers();
    static void pop_registers();

    static bool kernel_finished;
    static bool user_main_finished;

    // omotac oko userMain-a za kreiranje niti
    static void wrapper_userMain(void* arg);

    // funkcija za idle nit koja samo vrsi promenu konteksta
    static void idle_function(void* arg);

    static Array<thread_t> threads;
    static Array<sem_t> semaphores;
};

extern "C" void interruptvec();
extern "C" void interrupt();

// konstante za kodove sistemskih poziva
static const uint16 switch_privileges = 0x00;
static const uint16 mem_alloc_code = 0x01;
static const uint16 mem_free_code = 0x02;
static const uint16 thread_create_code = 0x11;
static const uint16 thread_exit_code = 0x12;
static const uint16 thread_dispatch_code = 0x13;
static const uint16 thread_create_no_start_code = 0x14;
static const uint16 thread_start_code = 0x15;
static const uint16 sem_open_code = 0x21;
static const uint16 sem_close_code = 0x22;
static const uint16 sem_wait_code = 0x23;
static const uint16 sem_signal_code = 0x24;
static const uint16 time_sleep_code = 0x31;
static const uint16 getc_code = 0x41;
static const uint16 putc_code = 0x42;

// konstante za razloge skoka u prekidnu rutinu
static const uint64 TIMER_INTERRUPT = 0x8000000000000001;
static const uint64 EXTERNAL_HARDWARE_INTERRUPT = 0x8000000000000009;
static const uint64 ILLEGAL_INSTRUCTION_INTERRUPT = 0x0000000000000002;
static const uint64 ILLEGAL_READ_ADDRESS_INTERRUPT = 0x0000000000000005;
static const uint64 ILLEGAL_WRITE_ADDRESS_INTERRUPT = 0x0000000000000007;
static const uint64 ECALL_FROM_USER = 0x0000000000000008;
static const uint64 ECALL_FROM_PRIVILEGED = 0x0000000000000009;



#endif //_RISCV_HPP_
