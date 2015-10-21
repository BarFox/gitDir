Documentation for Warmup Assignment 1
=====================================

+-------+
| BUILD |
+-------+

Comments: For creating the executable for my assignment (both listtest and warmup1), just type in ¡°make¡±.

+------+
| SKIP |
+------+

Is there are any tests in the standard test suite that you know that it's not
working and you don't want the grader to run it at all so you won't get extra
deductions, please list them here.  (Of course, if the grader won't run these
tests, you will not get plus points for them.)

+---------+
| GRADING |
+---------+

(A) Doubly-linked Circular List : 40 out of 40 pts 

(B.1) Sort (file) : 30 out of 30 pts
(B.2) Sort (stdin) : 30 out of 30 pts

Missing required section(s) in README file : (Comments: none)
Cannot compile : (Comments: none)
Compiler warnings : (Comments: none)
"make clean" : (Comments: OK)
Segmentation faults : (Comments: none)
Separate compilation : (Comments: yes)
Malformed input : (Comments: OK)
Too slow : (Comments: none)
Bad commandline : (Comments: OK)
Bad behavior for random input : (Comments: none)
Did not use My402List and My402ListElem to implement "sort" in (B) : (Comments: of course yes)

+------+
| BUGS |
+------+

Comments: All bugs that were found have been corrected. 

+-------+
| OTHER |
+-------+

Comments on design decisions: 
(1) For My402ListLength: I could simply return ((*list).num_members) or traverse through the whole list and return length. I achieved both
(2) when input file is from stdin, after finishing all input lines, just type ¡°enter¡± again to finish the input process and go on.
Comments on deviation from spec: none

