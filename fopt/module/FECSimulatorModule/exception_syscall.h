#ifndef __EXCEPTION_SYSCALL_H__
#define	__EXCEPTION_SYSCALL_H__

	
	#include "config.h"
	#include "seccomp-bpf.h"
	#include "syscall-reporter.h"

	
	static int install_syscall_filter(void);
	int install_exception_syscall();
#endif
