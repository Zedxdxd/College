.global _ZN7_thread14context_switchEPNS_7ContextES1_
.type _ZN7_thread14context_switchEPNS_7ContextES1_, @function

_ZN7_thread14context_switchEPNS_7ContextES1_:
    # a0 = adresa old_context
    # a1 = adresa new_context

    sd sp, 0 * 8(a0)
    sd ra, 1 * 8(a0)
    csrr t0, sepc
    sd t0, 2 * 8(a0)
    csrr t0, sstatus
    sd t0, 3 * 8(a0)

    ld sp, 0 * 8(a1)
    ld ra, 1 * 8(a1)
    ld t0, 2 * 8(a1)
    csrw sepc, t0
    ld t0, 3 * 8(a1)
    csrw sstatus, t0

    ld t0, 4 * 8(a1)
    bnez t0, return_from_interrupt
    ret

    return_from_interrupt:
    xor t0, t0, t0
    sd t0, 4 * 8 (a1)
    sret