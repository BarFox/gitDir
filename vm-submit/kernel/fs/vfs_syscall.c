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
 *  FILE: vfs_syscall.c
 *  AUTH: mcc | jal
 *  DESC:
 *  DATE: Wed Apr  8 02:46:19 1998
 *  $Id: vfs_syscall.c,v 1.10 2014/12/22 16:15:17 william Exp $
 */

#include "kernel.h"
#include "errno.h"
#include "globals.h"
#include "fs/vfs.h"
#include "fs/file.h"
#include "fs/vnode.h"
#include "fs/vfs_syscall.h"
#include "fs/open.h"
#include "fs/fcntl.h"
#include "fs/lseek.h"
#include "mm/kmalloc.h"
#include "util/string.h"
#include "util/printf.h"
#include "fs/stat.h"
#include "util/debug.h"

/* To read a file:
 *      o fget(fd)
 *      o call its virtual read fs_op
 *      o update f_pos
 *      o fput() it
 *      o return the number of bytes read, or an error
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        fd is not a valid file descriptor or is not open for reading.
 *      o EISDIR
 *        fd refers to a directory.
 *
 * In all cases, be sure you do not leak file refcounts by returning before
 * you fput() a file that you fget()'ed.
 */
int
do_read(int fd, void *buf, size_t nbytes)
{
    file_t *file = fget(fd);

    if(file == NULL || ( (file->f_mode & FMODE_READ) == 0)) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }

    if(S_ISDIR(file->f_vnode->vn_mode)) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        fput(file);
        return -EISDIR;
    }
    dbg(DBG_PRINT, "(GRADING2B)\n");
    int bytes = file->f_vnode->vn_ops->read(file->f_vnode, file->f_pos, buf, nbytes);

    file->f_pos += bytes;
    /*fput(file);*/

    return bytes; 
}

/* Very similar to do_read.  Check f_mode to be sure the file is writable.  If
 * f_mode & FMODE_APPEND, do_lseek() to the end of the file, call the write
 * fs_op, and fput the file.  As always, be mindful of refcount leaks.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        fd is not a valid file descriptor or is not open for writing.
 */
int
do_write(int fd, const void *buf, size_t nbytes)
{  
    
    file_t *file = fget(fd);

    if(file == NULL) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }

    if( (file->f_mode & FMODE_WRITE) != 0) { 
        if(file->f_mode & FMODE_APPEND) {
            dbg(DBG_PRINT, "(GRADING2B)\n");
            do_lseek(fd, 0, SEEK_END);
        }

        int bytes = file->f_vnode->vn_ops->write(file->f_vnode, file->f_pos, buf, nbytes);
        file->f_pos += bytes;
        fput(file);

        KASSERT((S_ISCHR(file->f_vnode->vn_mode)) || (S_ISBLK(file->f_vnode->vn_mode)) ||
            ((S_ISREG(file->f_vnode->vn_mode)) && (file->f_pos <= file->f_vnode->vn_len)));
        dbg(DBG_PRINT, "(GRADING1A 3.a)\n");
        return bytes;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        fput(file);
        return -EBADF;
    }
}

/*
 * Zero curproc->p_files[fd], and fput() the file. Return 0 on success
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        fd isn't a valid open file descriptor.
 */
int
do_close(int fd)
{
    if (fd < 0 || fd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }
    if(curproc->p_files[fd] == NULL) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF; 
    }

    dbg(DBG_PRINT, "(GRADING2B)\n");
    file_t *f = curproc->p_files[fd];

   /* fput(f);*/
    curproc->p_files[fd] = NULL;
    return 0; 
}

/* To dup a file:
 *      o fget(fd) to up fd's refcount
 *      o get_empty_fd()
 *      o point the new fd to the same file_t* as the given fd
 *      o return the new file descriptor
 *
 * Don't fput() the fd unless something goes wrong.  Since we are creating
 * another reference to the file_t*, we want to up the refcount.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        fd isn't an open file descriptor.
 *      o EMFILE
 *        The process already has the maximum number of file descriptors open
 *        and tried to open a new one.
 */
int
do_dup(int fd)
{
    if (fd < 0 || fd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }
    if(curproc->p_files[fd] == NULL) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF; 
    }

    file_t *file = fget(fd);
    int index = get_empty_fd(curproc);
    dbg(DBG_PRINT, "(GRADING2B)\n");
    /*
    if(index==-EMFILE){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        fput(file);
        return -EMFILE;
    }
    */
    curproc->p_files[index] = file;
    return index; 
}

/* Same as do_dup, but insted of using get_empty_fd() to get the new fd,
 * they give it to us in 'nfd'.  If nfd is in use (and not the same as ofd)
 * do_close() it first.  Then return the new file descriptor.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        ofd isn't an open file descriptor, or nfd is out of the allowed
 *        range for file descriptors.
 */
int
do_dup2(int ofd, int nfd)
{
    if (ofd < 0 || ofd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }

    if(curproc->p_files[ofd] == NULL) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF; 
    }

    if(nfd < 0 || nfd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }

    file_t *file = fget(ofd); 
    if(curproc->p_files[nfd] != NULL){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        do_close(nfd);
    }

    dbg(DBG_PRINT, "(GRADING2B)\n");  
    curproc->p_files[nfd] = file; 
    return nfd; 
}

/*
 * This routine creates a special file of the type specified by 'mode' at
 * the location specified by 'path'. 'mode' should be one of S_IFCHR or
 * S_IFBLK (you might note that mknod(2) normally allows one to create
 * regular files as well-- for simplicity this is not the case in Weenix).
 * 'devid', as you might expect, is the device identifier of the device
 * that the new special file should represent.
 *
 * You might use a combination of dir_namev, lookup, and the fs-specific
 * mknod (that is, the containing directory's 'mknod' vnode operation).
 * Return the result of the fs-specific mknod, or an error.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EINVAL x 
 *        mode requested creation of something other than a device special
 *        file.
 *      o EEXIST x 
 *        path already exists.
 *      o ENOENT
 *        A directory component in path does not exist.
 *      o ENOTDIR x 
 *        A component used as a directory in path is not, in fact, a directory.
 *      o ENAMETOOLONG x 
 *        A component of path was too long.
 */
int
do_mknod(const char *path, int mode, unsigned devid)
{
    vnode_t *res_vnode = NULL;
    const char *name = NULL;
    size_t namelen; 
    vnode_t *base = NULL; 
    vnode_t *original_dir;

    if(path[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = vfs_root_vn;
    }
    /*else{
        original_dir = curproc->p_cwd;
    }*/
    /*
    if(strlen(path) > NAME_LEN) {
        return -ENAMETOOLONG; 
    }*/
    /*
    if(!S_ISCHR(mode) && !S_ISBLK(mode)) {
        return -EINVAL;
    }*/
    
    int dir = dir_namev(path, &namelen, &name, base, &res_vnode);
    /*if(dir==-ENOENT){
        return -ENOENT;
    }

    if(dir == -ENOTDIR) {
        return -ENOTDIR;
    }*/
    vnode_t *res = NULL;
    int look = lookup(res_vnode, name, namelen, &res);
    /*if(look >= 0) {
        if(res_vnode != original_dir){
            vput(res_vnode);     
        }
        vput(res);
        return -EEXIST;
    }*/
    KASSERT(NULL != res_vnode->vn_ops->mknod);
    dbg(DBG_PRINT, "(GRADING2A 3.b)\n");
    
    dir = res_vnode->vn_ops->mknod(res_vnode, name, namelen, mode, devid);
    if(res_vnode != original_dir){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        vput(res_vnode);     
    }
    return dir;
}

/* Use dir_namev() to find the vnode of the dir we want to make the new
 * directory in.  Then use lookup() to make sure it doesn't already exist.
 * Finally call the dir's mkdir vn_ops. Return what it returns.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EEXIST x 
 *        path already exists.
 *      o ENOENT <--- 
 *        A directory component in path does not exist.
 *      o ENOTDIR x
 *        A component used as a directory in path is not, in fact, a directory.
 *      o ENAMETOOLONG x
 *        A component of path was too long.
 */
int
do_mkdir(const char *path)
{
    vnode_t *res_vnode = NULL;
    const char *name = NULL;
    size_t namelen; 
    vnode_t *base = NULL; 
    vnode_t *original_dir;

    if(path[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = vfs_root_vn;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = curproc->p_cwd;
    }

    int len = strlen(path);
    if(len > NAME_LEN) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -ENAMETOOLONG; 
    }

    int dir = dir_namev(path, &namelen, &name, base, &res_vnode);
    if(dir==-ENOENT){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -ENOENT;
    }
    
    if(dir == -ENOTDIR) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -ENOTDIR;
    }
    vnode_t *res = NULL;
    int look = lookup(res_vnode, name, namelen, &res);
    if(look >= 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(res_vnode != original_dir){
             dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(res_vnode);     
        }
        vput(res);
        return -EEXIST;
    }
    KASSERT(NULL != res_vnode->vn_ops->mkdir);
    dbg(DBG_PRINT, "(GRADING2A 3.c)\n");

    dir = res_vnode->vn_ops->mkdir(res_vnode, name, namelen);
    if(res_vnode != original_dir){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        vput(res_vnode);     
    }   
    return dir;
}

/* Use dir_namev() to find the vnode of the directory containing the dir to be
 * removed. Then call the containing dir's rmdir v_op.  The rmdir v_op will
 * return an error if the dir to be removed does not exist or is not empty, so
 * you don't need to worry about that here. Return the value of the v_op,
 * or an error.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EINVAL x 
 *        path has "." as its final component.
 *      o ENOTEMPTY x
 *        path has ".." as its final component.
 *      o ENOENT 
 *        A directory component in path does not exist.
 *      o ENOTDIR
 *        A component used as a directory in path is not, in fact, a directory.
 *      o ENAMETOOLONG x 
 *        A component of path was too long.
 */
int
do_rmdir(const char *path)
{
    vnode_t *res_vnode = NULL;
    const char *name = NULL;
    size_t namelen; 
    vnode_t *base = NULL; 
    vnode_t *original_dir;


    if(path[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2C 1)\n");
        original_dir = vfs_root_vn;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = curproc->p_cwd;
    }

    /*if(strlen(path) > NAME_LEN) {
        return -ENAMETOOLONG; 
    }*/

    int dir = dir_namev(path, &namelen, &name, base, &res_vnode); /* increases refcount */

    if(dir <0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return dir;
    }

    if(strncmp(name, "..", 2) == 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(res_vnode != original_dir){
            dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(res_vnode);     
        }
        return -ENOTEMPTY;
    }

    if(strncmp(name, ".", 1) ==0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(res_vnode != original_dir){
            dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(res_vnode);     
        }
        return -EINVAL;
    }

    vnode_t *res = NULL;
    int look = lookup(res_vnode, name, namelen, &res);
    if(look < 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        /*if(res_vnode != original_dir){
            vput(res_vnode);     
        }*/   
        return -ENOENT;
    }

    if(!S_ISDIR(res->vn_mode)){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        /*if(res_vnode != original_dir){
            vput(res_vnode);     
        }*/  
        vput(res);
        return -ENOTDIR;
    }


    KASSERT(NULL != res_vnode->vn_ops->rmdir);
    dbg(DBG_PRINT, "(GRADING2A 3.d)\n");
    dir = res_vnode->vn_ops->rmdir(res_vnode, name, namelen);
    /*if(res_vnode != original_dir){
        vput(res_vnode);     
    }*/
    vput(res);
    return dir;
}

/*
 * Same as do_rmdir, but for files.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EISDIR
 *        path refers to a directory.
 *      o ENOENT
 *        A component in path does not exist.
 *      o ENOTDIR
 *        A component used as a directory in path is not, in fact, a directory.
 *      o ENAMETOOLONG x
 *        A component of path was too long.
 */
int
do_unlink(const char *path)
{
    vnode_t *res_vnode = NULL;
    const char *name = NULL;
    size_t namelen; 
    vnode_t *base = NULL; 
    vnode_t *original_dir;

    if(path[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2C 1)\n");
        original_dir = vfs_root_vn;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = curproc->p_cwd;
    }

    /*if(strlen(path) > NAME_LEN) { 
        return -ENAMETOOLONG; 
    }*/
        
    int dir = dir_namev(path, &namelen, &name, base, &res_vnode); 
    /*if(dir==-ENOENT){ 
        return -ENOENT;
    }*/
    /*if(dir == -ENOTDIR) {  
        return -ENOTDIR;
    }*/

    vnode_t *res = NULL;
    int look = lookup(res_vnode, name, namelen, &res);
    if(look < 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(res_vnode != original_dir){
            dbg(DBG_PRINT, "(GRADING2C 2)\n");
            vput(res_vnode);     
        }  
        return -ENOENT;
    }

    if(S_ISDIR(res->vn_mode)){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        /*if(res_vnode != original_dir){
            vput(res_vnode);     
        }*/
        vput(res);
        return -EISDIR;
    }
        
    KASSERT(NULL != res_vnode->vn_ops->unlink);
    dbg(DBG_PRINT, "(GRADING2A 3.e)\n");
    dir = res_vnode->vn_ops->unlink(res_vnode, name, namelen);
    if(res_vnode != original_dir){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        vput(res_vnode);     
    }               
    vput(res);
    return dir;
}

/* To link:
 *      o open_namev(from)
 *      o dir_namev(to)
 *      o call the destination dir's (to) link vn_ops.
 *      o return the result of link, or an error
 *
 * Remember to vput the vnodes returned from open_namev and dir_namev.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EEXIST x
 *        to already exists.
 *      o ENOENT
 *        A directory component in from x or to does not exist.
 *      o ENOTDIR 
 *        A component used as a directory in from x or to is not, in fact, a
 *        directory.
 *      o ENAMETOOLONG x
 *        A component of from or to was too long.
 *      o EISDIR 
 *        from is a directory.
 */
int
do_link(const char *from, const char *to)
{
        vnode_t *res_vnode_f = NULL;
        vnode_t *res_vnode_d = NULL;
        size_t namelen_d; 
        const char *name_d = NULL;
        int flag_f = O_RDWR;
        vnode_t *base_f = NULL; 
        vnode_t *base_d = NULL;
        
        if(strlen(from) > NAME_LEN) {
            return -ENAMETOOLONG; 
        }
        if(strlen(to) > NAME_LEN) {
            return -ENAMETOOLONG; 
        }

        int dir_f = open_namev(from, flag_f, &res_vnode_f, base_f); 
        if(dir_f==-ENOENT){
            return -ENOENT;
        }
    
        if(dir_f == -ENOTDIR) {
            return -ENOTDIR;
        }
        if(S_ISDIR(res_vnode_f->vn_mode)){
            return -EISDIR;
        }

        int dir_d = dir_namev(to, &namelen_d, &name_d, base_d, &res_vnode_d); 
        if(dir_d==-ENOENT){
            return -ENOENT;
        }
    
        if(dir_d == -ENOTDIR) {
            return -ENOTDIR;
        }

        vnode_t *res_d = NULL;
        int look_d = lookup(res_vnode_d, name_d, namelen_d, &res_d);

        if(look_d >= 0) {
            vput(res_d);
            return -EEXIST;
        }

        
        return res_vnode_d->vn_ops->link(res_vnode_f,res_vnode_d, name_d, namelen_d);

       /* NOT_YET_IMPLEMENTED("VFS: do_link");
        return -1;*/
}

/*      o link newname to oldname
 *      o unlink oldname
 *      o return the value of unlink, or an error
 *
 * Note that this does not provide the same behavior as the
 * Linux system call (if unlink fails then two links to the
 * file could exist).
 */
int
do_rename(const char *oldname, const char *newname)
{
        /*int check=do_link(oldname,newname);
        if(check<0){
            return check;
        }
        return do_unlink(oldname);*/
        NOT_YET_IMPLEMENTED("VFS: do_rename");
        return -1;
}

/* Make the named directory the current process's cwd (current working
 * directory).  Don't forget to down the refcount to the old cwd (vput()) and
 * up the refcount to the new cwd (open_namev() or vget()). Return 0 on
 * success.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o ENOENT
 *        path does not exist.
 *      o ENAMETOOLONG x
 *        A component of path was too long.
 *      o ENOTDIR x
 *        A component of path is not a directory.
 */
int
do_chdir(const char *path)
{
    vnode_t *res_vnode = NULL;
    int flag = O_RDWR;
    vnode_t *base = NULL; 
    /*if(strlen(path) > NAME_LEN) {
        return -ENAMETOOLONG; 
    }*/
    dbg(DBG_PRINT, "(GRADING2B)\n");
    int dir = open_namev(path, flag, &res_vnode, base); 
    if(dir < 0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return dir;
    }

    if(S_ISDIR(res_vnode->vn_mode)==0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        vput(res_vnode);
        return -ENOTDIR;
    }
    /*
    if((strncmp(path, "..", 2) == 0) && (res_vnode == NULL)) {
        return 0; 
    }*/
    vput(curproc->p_cwd);
    curproc->p_cwd = res_vnode;

    return 0;
}

/* Call the readdir fs_op on the given fd, filling in the given dirent_t*.
 * If the readdir fs_op is successful, it will return a positive value which
 * is the number of bytes copied to the dirent_t.  You need to increment the
 * file_t's f_pos by this amount.  As always, be aware of refcounts, check
 * the return value of the fget and the virtual function, and be sure the
 * virtual function exists (is not null) before calling it.????
 *
 * Return either 0 or sizeof(dirent_t), or -errno.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        Invalid file descriptor fd.
 *      o ENOTDIR
 *        File descriptor does not refer to a directory.
 */
int
do_getdent(int fd, struct dirent *dirp)
{   
    if (fd < 0 || fd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }

    if(curproc->p_files[fd] == NULL) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF; 
    }

    file_t *fil = fget(fd);
    if(S_ISDIR(fil->f_vnode->vn_mode)==0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        fput(fil);
        return -ENOTDIR;
    }

    KASSERT(NULL != fil->f_vnode->vn_ops->readdir);
    int offset = fil->f_vnode->vn_ops->readdir(fil->f_vnode,fil->f_pos,dirp);

    do_lseek(fd,offset,SEEK_CUR);
    fput(fil);
    if(offset==0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return 0;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return sizeof(*dirp); 
    }
}

/*
 * Modify f_pos according to offset and whence.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o EBADF
 *        fd is not an open file descriptor.
 *      o EINVAL
 *        whence is not one of SEEK_SET, SEEK_CUR, SEEK_END; or the resulting
 *        file offset would be negative.
 */
int
do_lseek(int fd, int offset, int whence)
{
    if (fd < 0 || fd >= NFILES){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }
    /*if(curproc->p_files[fd] == NULL) {
        return -EBADF; 
    }*/
    file_t *file = fget(fd);
    if(file == NULL){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EBADF;
    }
    int oldpos = file->f_pos;
    /*if(file == NULL || ( (file->f_mode && FMODE_READ) == 0)) {
        return -EBADF;
    }*/
    if(whence==SEEK_SET){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        file->f_pos=offset;
    }else if(whence==SEEK_CUR){   
        dbg(DBG_PRINT, "(GRADING2B)\n");
        file->f_pos+=offset;
    }else if(whence==SEEK_END){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        file->f_pos=file->f_vnode->vn_len+offset;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        fput(file);
        return -EINVAL;
    }
    if(file->f_pos < 0){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        file->f_pos = oldpos;
        fput(file);
        return -EINVAL;
    }
    fput(file);
    return file->f_pos;
}

/*
 * Find the vnode associated with the path, and call the stat() vnode operation.
 *
 * Error cases you must handle for this function at the VFS level:
 *      o ENOENT
 *        A component of path does not exist.
 *      o ENOTDIR x 
 *        A component of the path prefix of path is not a directory.
 *      o ENAMETOOLONG x
 *        A component of path was too long.
 */
int
do_stat(const char *path, struct stat *buf)
{
    vnode_t *res_vnode = NULL;
    const char *name = NULL;
    size_t namelen; 
    vnode_t *base = NULL; 
    int ret;
    vnode_t *original_dir;

    if(path[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = vfs_root_vn;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = curproc->p_cwd;
    }

    if(strlen(path) == 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -EINVAL;
    }
    
    /*if(strlen(path) > MAXPATHLEN) {
        return -ENAMETOOLONG; 
    }*/

    int dir = dir_namev(path, &namelen, &name, base, &res_vnode);
    /*if(dir == -ENOTDIR) {
        return -ENOTDIR;
    }*/
    /*if(dir == -ENOENT) {
        return -ENOENT;
    }*/

    vnode_t *res2;

    int look =  lookup(res_vnode, name, namelen, &res2);
    if(look == -ENOENT) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(res_vnode != original_dir){
            dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(res_vnode);
        }
        return -ENOENT;
    }
       
    KASSERT(res2->vn_ops->stat);
    dbg(DBG_PRINT, "(GRADING2A 3.f)\n");

    look = res2->vn_ops->stat(res2,buf);
    if(res_vnode != original_dir){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        vput(res_vnode);
    }
    vput(res2);
    return look;
}

#ifdef __MOUNTING__
/*
 * Implementing this function is not required and strongly discouraged unless
 * you are absolutely sure your Weenix is perfect.
 *
 * This is the syscall entry point into vfs for mounting. You will need to
 * create the fs_t struct and populate its fs_dev and fs_type fields before
 * calling vfs's mountfunc(). mountfunc() will use the fields you populated
 * in order to determine which underlying filesystem's mount function should
 * be run, then it will finish setting up the fs_t struct. At this point you
 * have a fully functioning file system, however it is not mounted on the
 * virtual file system, you will need to call vfs_mount to do this.
 *
 * There are lots of things which can go wrong here. Make sure you have good
 * error handling. Remember the fs_dev and fs_type buffers have limited size
 * so you should not write arbitrary length strings to them.
 */
int
do_mount(const char *source, const char *target, const char *type)
{
        NOT_YET_IMPLEMENTED("MOUNTING: do_mount");
        return -EINVAL;
}

/*
 * Implementing this function is not required and strongly discouraged unless
 * you are absolutley sure your Weenix is perfect.
 *
 * This function delegates all of the real work to vfs_umount. You should not worry
 * about freeing the fs_t struct here, that is done in vfs_umount. All this function
 * does is figure out which file system to pass to vfs_umount and do good error
 * checking.
 */
int
do_umount(const char *target)
{
        NOT_YET_IMPLEMENTED("MOUNTING: do_umount");
        return -EINVAL;
}
#endif
