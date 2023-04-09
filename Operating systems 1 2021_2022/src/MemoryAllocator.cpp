
#include "../h/MemoryAllocator.hpp"

size_t bytes_to_blocks(size_t sz){
    return (sz + sizeof(size_t)) / MEM_BLOCK_SIZE + ((sz + sizeof(size_t)) % MEM_BLOCK_SIZE == 0 ? 0 : 1);
}

MemoryAllocator::MemSegment* MemoryAllocator::free_mem_head = (MemoryAllocator::MemSegment*)HEAP_START_ADDR;
bool MemoryAllocator::initialized = false;

// inicijalizuje listu slobodnih segmenata, treba da se pozove na pocetku
void MemoryAllocator::init(){
    if (!initialized) {
        // inkremetira se HEAP_START_ADDR za 1 kako se ne bi pri poravnanju desilo da je free_mem_head tacno na pocetku heapa
        // dodaje se MEM_BLOCK_SIZE - 1 kako bi se poravnalo na visu adresu
        // & ~(MEM_BLOCK_SIZE - 1) - postavljanje 0 na kraju adrese, tako je poravnato na MEM_BLOCK_SIZE
        // oduzima se sizeof(size_t) zbog nacina na koji funkcionise alokacija, gde free_mem_head ukazuje na velicinu
        free_mem_head = (MemSegment*)((uint64)(((char*)HEAP_START_ADDR + 1) + MEM_BLOCK_SIZE - 1) & ~(MEM_BLOCK_SIZE - 1));
        free_mem_head = (MemSegment*)((char*)free_mem_head - sizeof(size_t));

        // diff je broj bajtova koji moze da se alocira
        size_t diff = (size_t) HEAP_END_ADDR - (size_t) free_mem_head;
        free_mem_head->size = diff / MEM_BLOCK_SIZE; // dobijanje koliko je blokova slobodno
        initialized = true;
    }
}

// koristi FirstFit
void* MemoryAllocator::alloc(size_t sz) {

    // jednostruko ulancana lista

    // ret_ptr je povratna vrednost
    MemSegment* curr = free_mem_head, *prev = nullptr, *ret_ptr = nullptr;

    while (curr){
        // slobodan segment curr ima dovoljno memorije za alokaciju
        if (curr->size >= sz) {

            ret_ptr = curr;

            // ako velicina slobodnog segmenta odgovara velicini koja treba da se zauzme
            // treba da se "izbaci" taj clan iz liste
            if (curr->size == sz){
                curr = ret_ptr->next;
            }

            // ako je velicina slobodnog segmenta veca od velicine koja treba da se zauzme
            // onda se "pravi" nov clan u listi
            else {
                curr = (MemSegment * )((size_t) curr + sz * MEM_BLOCK_SIZE);
                curr->next = ret_ptr->next;
                curr->size = ret_ptr->size - sz;
            }

            // uvezivanje u listu
            if (prev) {
                prev->next = curr;
            }
            else {
                free_mem_head = curr;
            }

            // upis velicine zauzetog bloka zbog kasnije dealokacije
            ret_ptr->size = sz;
            break;
        }
        prev = curr;
        curr = curr->next;
    }

    // nije nadjena slobodna memorija
    if (!ret_ptr){
        return nullptr;
    }

    // zauzeta je memorija, vraca se pokazivac pomeren za sizeof(size_t) i na taj nacin
    // se cuva koliko zauzima zauzeti segment zbog dealokacije posle
    else{
        return (void*)((size_t)ret_ptr + sizeof(size_t));
    }
}

int MemoryAllocator::dealloc(void* ptr){
    if (!ptr){
        return 0;
    }
    size_t size_block = *(size_t*)((size_t)ptr - sizeof(size_t)); // velicina alociranog bloka
    if (size_block == 0) {
        return -1;
    }

    MemSegment* curr = free_mem_head;// *prev = nullptr;

    // ako je free_mem_head == nullptr (nije ostalo vise slobodne memorije nakon poslednje alokacije)
    if (!curr){
        free_mem_head = (MemSegment*)((size_t)ptr - sizeof(size_t));
        free_mem_head->next = nullptr;
        return 0;
    }

    // alocirani deo se nalazi pre pocetka liste slobodnih fragmenata
    if ((size_t)ptr < (size_t)curr){

        // glava liste je sada to sto se oslobadja
        free_mem_head = (MemSegment*)((size_t)ptr - sizeof(size_t));

        // spajanje sa sledecim slobodnim segmentom
        if ((size_t)free_mem_head + size_block * MEM_BLOCK_SIZE == (size_t)curr){
            free_mem_head->next = curr->next;
            free_mem_head->size = size_block + curr->size;
        }

        // uvezivanje u listu (nemoguce spajanje)
        else if ((size_t)free_mem_head + size_block * MEM_BLOCK_SIZE < (size_t)curr) {
            free_mem_head->next = curr;
            free_mem_head->size = size_block;
        }
        else {
            return -1;
        }
        return 0;
    }

    // pretraga da li se ptr nalazi izmedju neka dva slobodna segmenta
    MemSegment *prev = curr;
    curr = curr->next;
    while (curr){

        // nadjen ptr izmedju dva segmenta
        if ((size_t)ptr < (size_t)(curr) && (size_t)ptr > (size_t)prev){

            // spajanje ptr sa segmentom pre i posle
            if ((size_t)prev + prev->size * MEM_BLOCK_SIZE == (size_t)ptr - sizeof(size_t) &&
                    (size_t)ptr - sizeof(size_t) + size_block * MEM_BLOCK_SIZE == (size_t)curr){

                prev->size += curr->size + size_block;
                prev->next = curr->next;
            }

            // spajanje ptr sa segmentom pre
            else if ((size_t)prev + prev->size * MEM_BLOCK_SIZE == (size_t)ptr - sizeof(size_t)){
                prev->size += size_block;
            }

            // spajanje ptr sa segmentom posle
            else if ((size_t)ptr - sizeof(size_t) + size_block * MEM_BLOCK_SIZE == (size_t)curr){
                ptr = (void*)((size_t)ptr - sizeof(size_t));
                ((MemSegment*)ptr)->size = size_block + curr->size;
                ((MemSegment*)ptr)->next = curr->next;
                prev->next = (MemSegment*)ptr;
            }

            // nema spajanja
            else {
                ptr = (void*)((size_t)ptr - sizeof(size_t));
                prev->next = (MemSegment*)ptr;
                ((MemSegment*)ptr)->next = curr;
            }
            return 0;
        }
        prev = curr;
        curr = curr->next;
    }

    // alocirani deo se nalazi posle kraja liste slobodnih fragmenata
    if ((size_t)ptr - sizeof(size_t) > (size_t)prev){

        // spajanje sa slobodnim segmentom pre
        if ((size_t)prev + prev->size * MEM_BLOCK_SIZE == (size_t)prev - sizeof(size_t)){
            prev->size += size_block;
        }

        // nema spajanja
        else if ((size_t)prev + prev->size * MEM_BLOCK_SIZE > (size_t)prev - sizeof(size_t)){
            ptr = (void*)((size_t)ptr - sizeof(size_t));
            prev->next = (MemSegment*)ptr;
            ((MemSegment*)ptr)->next = nullptr;
        }
        else {
            return -1;
        }
        return 0;
    }

    return -1;
}

