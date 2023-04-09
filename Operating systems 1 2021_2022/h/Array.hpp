#ifndef _Array_hpp_
#define _Array_hpp_

#include "syscall_c.hpp"

// pravi se staticki niz pregradaka za semafore i niti
template <typename T> class Array {
private:
    static const int ARRAY_SIZE = 512;
    T arr[ARRAY_SIZE];
    // free pokazuje na prvi slobodan clan
    // cnt_items kaze koliko nenultih clanova ima u nizu
    int free, cnt_items;

public:
    Array(){
        for (int i = 0; i < ARRAY_SIZE; i++){
            arr[i] = nullptr;
        }
        free = 0;
        cnt_items = 0;
    }

    T& operator[](int i){
        return arr[i];
    }

    // dodaje na slobodno mesto i vraca gde je dodao (to je id te klase)
    int add(T t){
        if (cnt_items == ARRAY_SIZE){
            return -1;
        }
        arr[free] = t;
        int old_free = free;
        for (int i = 0; i < cnt_items; i++){
            if (!arr[free]){
                break;
            }
            free = (free + 1)%ARRAY_SIZE;
        }
        cnt_items++;
        return old_free;
    }


    void delete_all_threads() {
        for (int i = 3; i < ARRAY_SIZE; i++){
            delete arr[i];
            arr[i] = nullptr;
        }
    }
};


#endif //_Array_hpp_
