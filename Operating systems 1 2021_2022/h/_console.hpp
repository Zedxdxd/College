#ifndef __console_hpp_
#define __console_hpp_

#include "_sem.hpp"

class _console{
    static const int BUFFER_SIZE = 512;
    static char output_buffer[BUFFER_SIZE]; // bafer za putc, izlazni bafer
    static sem_t output_buffer_space_available, output_buffer_item_available;
    static int output_buffer_head, output_buffer_tail;

    static char input_buffer[BUFFER_SIZE]; // bafer za getc, ulazni bafer
    static sem_t input_buffer_item_available;
    static int input_buffer_head, input_buffer_tail;
    static int input_num_chars; // koliko karaktera se trenutno nalazi u baferu, da bi se znalo da je pun kako bi se ignorisalo

public:
    static void init();

    static void putc_thread(void* arg);

    static void _putc(char c);

    static void load_char();

    static char _getc();

    static void print_int(int xx, int base = 10, int sgn = 0);
    static void print_string(char const *string);
};

#endif //__console_hpp_
