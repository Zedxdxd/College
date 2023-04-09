#ifndef _syscall_cpp_h_
#define _syscall_cpp_h_

#include "../h/syscall_c.hpp"
#include "../lib/hw.h"
#include "../h/MemoryAllocator.hpp"
#include "../h/RISCV.hpp"
#include "Semaphore.hpp"
#include "Thread.hpp"

void* operator new(size_t sz);

void* operator new[](size_t sz);

void operator delete(void* ptr);

void operator delete[](void* ptr);

class Console {
public:
    static char getc ();
    static void putc (char);
};

#endif //_syscall_cpp_h_