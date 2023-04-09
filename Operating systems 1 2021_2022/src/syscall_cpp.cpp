#include "../h/syscall_cpp.hpp"

void* operator new(size_t sz){
    return mem_alloc(sz);
}

void* operator new[](size_t sz){
    return mem_alloc(sz);
}

void operator delete(void* ptr){

    // ne znam sta da radim sa povratnom vrednoscu poziva mem_free
    if (mem_free(ptr) < 0)
        putc('?');
}

void operator delete[](void* ptr){

    // ne znam sta da radim sa povratnom vrednoscu poziva mem_free
    mem_free(ptr);
}

void Console::putc(char c) {
    ::putc(c);
}

char Console::getc() {
    return ::getc();
}