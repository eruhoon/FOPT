CC=gcc

default: main 

foptmain.o:
	$(CC) -c foptmain.c -g

syscall-names.h: /usr/include/sys/syscall.h syscall-reporter.mk
    echo "static const char *syscall_names[] = {" > $@ ;\
    echo "#include <sys/syscall.h>" | cpp -dM | grep '^#define __NR_' | \
        LC_ALL=C sed -r -n -e 's/^\#define[ \t]+__NR_([a-z0-9_]+)[ \t]+([0-9]+)(.*)/ [\2] = "\1",/p' >> $@ ;\
    echo "};" >> $@

syscall-reporter.o: syscall-reporter.c syscall-names.h

main: exception_syscall.o syscall-reporter.o foptmain.o
	$(CC) exception_syscall.o syscall-reporter.o foptmain.o -o fopt_tester -ldl -lm

clean:	
	rm *.o
	rm fopt_tester
