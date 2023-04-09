#ifndef _MemoryAllocator_hpp_
#define _MemoryAllocator_hpp_

#include "../lib/hw.h"

size_t bytes_to_blocks(size_t sz);

class MemoryAllocator{
    // jedan clan liste slobodnih segmenata
    struct MemSegment{
        size_t size;   // broj slobodnih blokova
        MemSegment* next = nullptr;
    };
    static bool initialized;
    static MemSegment* free_mem_head;

public:
    static void init();

    //sz je broj blokova koji se alocira; pre poziva alloc mora da se zaokruzi na ceo broj blokova velicine MEM_BLOCK_SIZE
    static void* alloc(size_t sz);
    static int dealloc(void* ptr);
};

#endif //_MemoryAllocator_hpp_
