Documentation for Kernel Assignment 1
=====================================

+-------------+
| BUILD & RUN |
+-------------+

Input: "make" for BUILD
Input: "./weenix" for RUN

+-----------------+
| SKIP (Optional) |
+-----------------+


+---------+
| GRADING |
+---------+

(A.1) In main/kmain.c:
    (a) In bootstrap(): 3 out of 3 pts
    (b) In initproc_create(): 3 out of 3 pts

(A.2) In proc/proc.c:
    (a) In proc_create(): 4 out of 4 pts
    (b) In proc_cleanup(): 5 out of 5 pts
    (c) In do_waitpid(): 8 out of 8 pts

(A.3) In proc/kthread.c:
    (a) In kthread_create(): 2 out of 2 pts
    (b) In kthread_cancel(): 1 out of 1 pt
    (c) In kthread_exit(): 3 out of 3 pts

(A.4) In proc/sched.c:
    (a) In sched_wakeup_on(): 1 out of 1 pt
    (b) In sched_make_runnable(): 1 out of 1 pt

(A.5) In proc/kmutex.c:
    (a) In kmutex_lock(): 1 out of 1 pt
    (b) In kmutex_lock_cancellable(): 1 out of 1 pt
    (c) In kmutex_unlock(): 2 out of 2 pts

(B) Kshell : 20 out of 20 pts
    Comments: none

(C.1) waitpid any test, etc. (4 out of 4 pts)
(C.2) Context switch test (1 out of 1 pt)
(C.3) wake me test, etc. (2 out of 2 pts)
(C.4) wake me uncancellable test, etc. (2 out of 2 pts)
(C.5) cancel me test, etc. (4 out of 4 pts)
(C.6) reparenting test, etc. (2 out of 2 pts)
(C.7) show race test, etc. (3 out of 3 pts)
(C.8) kill child procs test (2 out of 2 pts)
(C.9) proc kill all test (2 out of 2 pts)

(D.1) sunghan_test(): producer/consumer test (9 out of 9 pts)
(D.2) sunghan_deadlock_test(): deadlock test (4 out of 4 pts)

(E) Additional self-checks: (10 out of 10 pts)
    Comments: By running all the tests in the above sections, all code paths are exercised.

Missing required section(s) in README file (procs-README.txt): (-0)
Submitted binary file : (-0)
Submitted extra (unmodified) file : (-0)
Wrong file location in submission : (-0)
Use dbg_print(...) instead of dbg(DBG_PRINT, ...) : (-0)
Not properly indentify which dbg() printout is for which item in the grading guidelines : (-0)
Cannot compile : (-0)
Compiler warnings : (-0)
"make clean" : (-0)
Useless KASSERT : (-0)
Insufficient/Confusing dbg : (-0)
Kernel panic : (-0)
Cannot halt kernel cleanly : (-0)

+------+
| BUGS |
+------+

All bugs found has been corrected.

+---------------------------+
| CONTRIBUTION FROM MEMBERS |
+---------------------------+

equal-shared

+------------------+
| OTHER (Optional) |
+------------------+

