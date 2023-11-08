#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>

const volatile char shellcode[] = {
	0xb7,0xa4,0x43,0x03,0x9b,0x84,0x94,0x97,
	0xb2,0x04,0x93,0x84,0x74,0x7b,0xb2,0x04,
	0x93,0x84,0xb4,0x34,0xb6,0x04,0x93,0x84,
	0xf4,0x22,0x23,0x38,0x91,0xfe,0x23,0x3c,
	0x01,0xfe,0x13,0x05,0x01,0xff,0x93,0x25,
	0xf0,0xff,0x13,0x26,0xf0,0xff,0x93,0x08,
	0xd0,0x0d,0x93,0x06,0x30,0x07,0x23,0x0e,
	0xd1,0xfe,0x93,0x06,0xe1,0xff,0x67,0x80,
	0xe6,0xff };

int main(int argc, char* argv[]) {
	const char stack_overflow[32] = { 0 };
	volatile char* heap_overflow[10];
	int32_t integer_overflow = 0;
	unsigned int count = 0;

	setvbuf(stdin,  NULL, _IONBF, 0);
	setvbuf(stdout, NULL, _IONBF, 0);
	setvbuf(stderr, NULL, _IONBF, 0);

	printf("Pretty riscy programming!\n");
	printf("libc-leak: %p\n", &puts);
	printf("stack overflow?\n");
	gets(stack_overflow);
	printf("format-string attack?\n");
	printf(stack_overflow);

	printf("\nheap attack?\n");
	while (true) {
		printf("1) malloc\n2) free\n*) exit\n");
		scanf("%ld", &integer_overflow);
		switch(integer_overflow) {
		case 1:
		scanf("%ld", &integer_overflow);
		if ((heap_overflow[count] = malloc(integer_overflow)) == NULL) {
			goto fail;
		}
		read(stdin, heap_overflow[count], integer_overflow);
		count++;
		break;
		case 2:
		scanf("%ld", &integer_overflow);
		printf("%d: %p\n", integer_overflow, heap_overflow[integer_overflow]);
		free(heap_overflow[integer_overflow]);
		break;
		default:
		goto fail;
		}
	}
	fail:
	printf("ROP unwind?\n");
	return EXIT_FAILURE;
}
