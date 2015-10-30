Documentation for Kernel Assignment 3
=====================================

+-------------+
| BUILD & RUN |
+-------------+

Comments: 
(command):
	Make clean
	Make
	./weenix
	
+-----------------+
| SKIP (Optional) |
+-----------------+

Is there are any tests in the standard test suite that you know that it's not
working and you don't want the grader to run it at all so you won't get extra
deductions, please list them here.  (Of course, if the grader won't run these
tests, you will not get plus points for them.)

	1. Forkbomb doesn't work, don't run it.
	2. Eatmem only works for the 1st time, don't run it twice. 

+---------+
| GRADING |
+---------+

(A.1) In mm/pframe.c:
    (a) In pframe_pin(): 1 out of 1 pt
    (b) In pframe_unpin(): 1 out of 1 pt

(A.2) In vm/mmap.c:
    (a) In do_mmap(): 2 out of 2 pts
    (b) In do_munmap(): 2 out of 2 pts

(A.3) In vm/vmmap.c:
    (a) In vmmap_destroy(): 2 out of 2 pts
    (b) In vmmap_insert(): 2 out of 2 pts
    (c) In vmmap_find_range(): 2 out of 2 pts
    (d) In vmmap_lookup(): 1 out of 1 pt
    (e) In vmmap_is_range_empty(): 1 out of 1 pt
    (f) In vmmap_map(): 7 out of 7 pts

(A.4) In vm/anon.c:
    (a) In anon_init(): 1 out of 1 pt
    (b) In anon_ref(): 1 out of 1 pt
    (c) In anon_put(): 1 out of 1 pt
    (d) In anon_fillpage(): 1 out of 1 pt

(A.5) In fs/vnode.c:
    (a) In special_file_mmap(): 2 out of 2 pts

(A.6) In vm/shadow.c:
    (a) In shadow_init(): 1 out of 1 pt
    (b) In shadow_ref(): 1 out of 1 pt
    (c) In shadow_put(): 1 out of 1 pts
    (d) In shadow_fillpage(): 2 out of 2 pts

(A.7) In proc/fork.c:
    (a) In do_fork(): 6 out of 6 pts

(A.8) In proc/kthread.c:
    (a) In kthread_clone(): 2 out of 2 pts

(B.1) /usr/bin/hello (3 out of 3 pts)
(B.2) /bin/uname -a (3 out of 3 pts)
(B.3) /usr/bin/args ab cde fghi j (3 out of 3 pts)
(B.4) /usr/bin/fork-and-wait (5 out of 5 pts)

(C.1) /usr/bin/segfault (1 out of 1 pt)

(D.2) /usr/bin/vfstest (7 out of 7 pts)
(D.3) /usr/bin/memtest (7 out of 7 pts)
(D.4) /usr/bin/eatmem (3.5 out of 7 pts)
(D.5) /usr/bin/forkbomb (0 out of 7 pts)
(D.6) /usr/bin/stress (7 out of 7 pts)

(E.1) /usr/bin/vfstest (0 out of 1 pt)
(E.2) /usr/bin/memtest (0 out of 1 pt)
(E.3) /usr/bin/eatmem (0 out of 1 pt)
(E.4) /usr/bin/forkbomb (0 out of 1 pt)
(E.5) /usr/bin/stress (0 out of 1 pt)

(F) Self-checks: (10 out of 10 pts)
    Comments: (please provide details, add subsections and/or items as needed)

Missing required section(s) in README file (vm-README.txt): (None)
Submitted binary file : (None)
Submitted extra (unmodified) file : (None)
Wrong file location in submission : (None)
Use dbg_print(...) instead of dbg(DBG_PRINT, ...) : (None)
Not properly indentify which dbg() printout is for which item in the grading guidelines : (None)
Cannot compile : (None)
Compiler warnings : (None)
"make clean" : (Yes)
Useless KASSERT : (None)
Insufficient/Confusing dbg : (None)
Kernel panic : (None)
Cannot halt kernel cleanly : (Sometimes)

+------+
| BUGS |
+------+

Comments: Can't halt cleanly because the refcount in Kernel 2. 

+---------------------------+
| CONTRIBUTION FROM MEMBERS |
+---------------------------+

If not equal-share contribution, please list percentages.

+------------------+
| OTHER (Optional) |
+------------------+

Special DBG setting in Config.mk for certain tests: None
Comments on deviation from spec (you will still lose points, but it's better to let the grader know): None
General comments on design decisions: None 

