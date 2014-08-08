#include "exception_syscall.h"

static int install_syscall_filter(void)
{
	struct sock_filter filter[] = {
		/* Validate architecture. */
		VALIDATE_ARCHITECTURE,
		/* Grab the system call number. */
		EXAMINE_SYSCALL,
		/* List allowed syscalls. */
		ALLOW_SYSCALL(rt_sigreturn),
#ifdef __NR_sigreturn
		ALLOW_SYSCALL(sigreturn),
#endif
		ALLOW_SYSCALL(exit_group),
		ALLOW_SYSCALL(exit),
		ALLOW_SYSCALL(read),
		ALLOW_SYSCALL(write),
		/* Add more syscalls here. */
		ALLOW_SYSCALL(mprotect),
		ALLOW_SYSCALL(alarm),
		ALLOW_SYSCALL(ioctl),
		ALLOW_SYSCALL(time),
		ALLOW_SYSCALL(lseek),
		ALLOW_SYSCALL(writev),
		ALLOW_SYSCALL(gettid),
		ALLOW_SYSCALL(tgkill),
		ALLOW_SYSCALL(fstat),
		ALLOW_SYSCALL(close),
		ALLOW_SYSCALL(open),
		ALLOW_SYSCALL(mmap),
		ALLOW_SYSCALL(munmap),
		ALLOW_SYSCALL(brk),
		ALLOW_SYSCALL(times),
		ALLOW_SYSCALL(alarm),
		ALLOW_SYSCALL(rt_sigprocmask),
		ALLOW_SYSCALL(rt_sigaction),
		ALLOW_SYSCALL(nanosleep),
		KILL_PROCESS,
	};
	struct sock_fprog prog = {
		.len = (unsigned short)(sizeof(filter)/sizeof(filter[0])),
		.filter = filter,
	};

	if (prctl(PR_SET_NO_NEW_PRIVS, 1, 0, 0, 0)) {
		perror("prctl(NO_NEW_PRIVS)");
		goto failed;
	}
	if (prctl(PR_SET_SECCOMP, SECCOMP_MODE_FILTER, &prog)) {
		perror("prctl(SECCOMP)");
		goto failed;
	}
	return 0;

failed:
	if (errno == EINVAL)
		fprintf(stderr, "SECCOMP_FILTER is not available. :(\n");
	return 1;
}

int install_exception_syscall()
{
	if( install_syscall_reporter() )	return 0;
	if( install_syscall_filter() )		return 0;

	return 1;
}
