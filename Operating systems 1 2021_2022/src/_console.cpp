#include "../h/_sem.hpp"
#include "../h/_console.hpp"

char _console::output_buffer[_console::BUFFER_SIZE]; // bafer za putc, izlazni bafer
sem_t _console::output_buffer_space_available = nullptr;
sem_t _console::output_buffer_item_available = nullptr;
int _console::output_buffer_head = 0;
int _console::output_buffer_tail = 0;


char _console::input_buffer[_console::BUFFER_SIZE]; // bafer za putc, izlazni bafer
sem_t _console::input_buffer_item_available = nullptr;
int _console::input_buffer_head = 0;
int _console::input_buffer_tail = 0;
int _console::input_num_chars = 0;


void _console::init() {
    // inicijalizacija prenosa za putc
    sem_open(&output_buffer_item_available, 0);
    sem_open(&output_buffer_space_available, BUFFER_SIZE);

    // inicijalizacija prenosa za getc
    sem_open(&input_buffer_item_available, 0);
}

void _console::putc_thread(void* arg){
    while(!RISCV::kernel_finished || output_buffer_head != output_buffer_tail){
        while (*(char*)CONSOLE_STATUS & CONSOLE_TX_STATUS_BIT){
            if (RISCV::kernel_finished && output_buffer_head == output_buffer_tail){
                break;
            }
            sem_wait(output_buffer_item_available);
            char c = output_buffer[output_buffer_head];
            output_buffer_head = (output_buffer_head + 1) % BUFFER_SIZE;
            *(char*)CONSOLE_TX_DATA = c;
            sem_signal(output_buffer_space_available);
        }
        thread_dispatch();
    }
    time_sleep(5);
}

void _console::_putc(char c){
    output_buffer_space_available->wait();
    output_buffer[output_buffer_tail] = c;
    output_buffer_tail = (output_buffer_tail + 1) % BUFFER_SIZE;
    output_buffer_item_available->signal();
}

void _console::load_char() {
    // ako nema mesta za karaktere u baferu u kome se cuvaju ucitani podaci, onda ignorisi
    if (input_num_chars == BUFFER_SIZE){
        return;
    }
    char c = *(char*)CONSOLE_RX_DATA;

    // menja se '\r' u '\n' kako bi se preslo u novi red, a ne da se vrati na pocetak istog reda
    if (c == '\r'){
        c = '\n';
    }
    input_buffer[input_buffer_tail] = c;
    input_buffer_tail = (input_buffer_tail + 1) % BUFFER_SIZE;
    input_num_chars++;
    input_buffer_item_available->signal();
}

char _console::_getc() {
    input_buffer_item_available->wait();
    char c = input_buffer[input_buffer_head];
    input_buffer_head = (input_buffer_head + 1) % BUFFER_SIZE;
    input_num_chars--;
    return c;
}


void _console::print_int(int xx, int base , int sgn)
{
    char digits[] = "0123456789ABCDEF";
    char buf[16];
    int i, neg;
    uint x;

    neg = 0;
    if(sgn && xx < 0){
        neg = 1;
        x = -xx;
    } else {
        x = xx;
    }

    i = 0;
    do{
        buf[i++] = digits[x % base];
    }while((x /= base) != 0);
    if(neg)
        buf[i++] = '-';

    while(--i >= 0)
        _console::_putc(buf[i]);

}

void _console::print_string(char const *string){
    while (*string != '\0')
    {
        _console::_putc(*string);
        string++;
    }
}