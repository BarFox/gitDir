/******************************************************************************/
/* Important Spring 2015 CSCI 402 usage information:                          */
/*                                                                            */
/* This fils is part of CSCI 402 kernel programming assignments at USC.       */
/* Please understand that you are NOT permitted to distribute or publically   */
/*         display a copy of this file (or ANY PART of it) for any reason.    */
/* If anyone (including your prospective employer) asks you to post the code, */
/*         you must inform them that you do NOT have permissions to do so.    */
/* You are also NOT permitted to remove or alter this comment block.          */
/* If this comment block is removed or altered in a submitted file, 20 points */
/*         will be deducted.                                                  */
/******************************************************************************/

/*
 *  FILE: open.c
 *  AUTH: mcc | jal
 *  DESC:
 *  DATE: Mon Apr  6 19:27:49 1998
 */

#include "globals.h"
#include "errno.h"
#include "fs/fcntl.h"
#include "util/string.h"
#include "util/printf.h"
#include "fs/vfs.h"
#include "fs/vnode.h"
#include "fs/file.h"
#include "fs/vfs_syscall.h"
#include "fs/open.h"
#include "fs/stat.h"
#include "util/debug.h"

/* find empty index in p->p_files[] */
int
get_empty_fd(proc_t *p)
{
        int fd;

        for (fd = 0; fd < NFILES; fd++) {
                if (!p->p_files[fd])
                        return fd;
        }

        dbg(DBG_ERROR | DBG_VFS, "ERROR: get_empty_fd: out of file descriptors "
            "for pid %d\n", curproc->p_pid);
        return -EMFILE;
}

/*
 * There a number of steps to opening a file:
 *      1. Get the next empty file descriptor.
 *      2. Call fget to get a fresh file_t.
 *      3. Save the file_t in curproc's file descriptor table.
 *      4. Set file_t->f_mode to OR of FMODE_(READ|WRITE|APPEND) based on
 *         oflags, which can be O_RDONLY, O_WRONLY or O_RDWR, possibly OR'd with
 *         O_APPEND or O_CREAT.
 *      5. Use open_namev() to get the vnode for the file_t.
 *      6. Fill in the fields of the file_t.
 *      7. Return new fd.
 *
 * If anything goes wrong at any point (specifically if the call to open_namev
 * fails), be sure to remove the fd from curproc, fput the file_t and return an
 * error.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EINVAL
 *        oflags is not valid.
 *      o EMFILE
 *        The process already has the maximum number of files open.
 *      o ENOMEM
 *        Insufficient kernel memory was available.
 *      o ENAMETOOLONG
 *        A component of filename was too long.
 *      o ENOENT
 *        O_CREAT is not set and the named file does not exist.  Or, a
 *        directory component in pathname does not exist.
 *      o EISDIR
 *        pathname refers to a directory and the access requested involved
 *        writing (that is, O_WRONLY or O_RDWR is set).
 *      o ENXIO
 *        pathname refers to a device special file and no corresponding device
 *        exists.
 */

int
do_open(const char *filename, int oflags)
{
	int fd;
	int errno=0;
	vnode_t* tarvnode;
	if((oflags & 0x003)>2 || (oflags & 0x003)<0){
		dbg(DBG_PRINT, "(GRADING2B)\n");
		return -EINVAL;
	}

	if(strlen(filename)>NAME_LEN){
		dbg(DBG_PRINT, "(GRADING2B)\n");
		return -ENAMETOOLONG;
	}
	fd=get_empty_fd(curproc);

	/*if(fd<0){
		
		return -EMFILE;
	}*/

	curproc->p_files[fd]=fget(-1);
	/*if(curproc->p_files[fd]==NULL){
		
		return -ENOMEM;
	}*/

	if(oflags & O_APPEND){
		dbg(DBG_PRINT, "(GRADING2B)\n");
		curproc->p_files[fd]->f_mode=FMODE_APPEND;
	}else{
		dbg(DBG_PRINT, "(GRADING2B)\n");
		curproc->p_files[fd]->f_mode=0;
		curproc->p_files[fd]->f_pos=0;
	}

	switch(oflags & 0x003){
		case O_RDONLY:
			dbg(DBG_PRINT, "(GRADING2B)\n");
			curproc->p_files[fd]->f_mode = curproc->p_files[fd]->f_mode | FMODE_READ;
			break;
		case O_WRONLY:
			dbg(DBG_PRINT, "(GRADING2B)\n");
			curproc->p_files[fd]->f_mode = curproc->p_files[fd]->f_mode | FMODE_WRITE;
			break;
		case O_RDWR:
			dbg(DBG_PRINT, "(GRADING2B)\n");
			curproc->p_files[fd]->f_mode = curproc->p_files[fd]->f_mode | FMODE_READ | FMODE_WRITE;
			break;
	}

	errno=open_namev(filename, oflags, &tarvnode, NULL);
	if(errno<0){
		dbg(DBG_PRINT, "(GRADING2B)\n");
		fput(curproc->p_files[fd]);
		return errno;
	}
	if(S_ISDIR(tarvnode->vn_mode)){
		dbg(DBG_PRINT, "(GRADING2B)\n");
		if((oflags & 0x003) != O_RDONLY){
			dbg(DBG_PRINT, "(GRADING2B)\n");
			fput(curproc->p_files[fd]);
			vput(tarvnode);
			return -EISDIR;
		}
	}

	curproc->p_files[fd]->f_vnode = tarvnode;

	return fd;
    /*NOT_YET_IMPLEMENTED("VFS: do_open");
    return -1;*/
}
