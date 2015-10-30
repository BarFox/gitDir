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

#include "kernel.h"
#include "globals.h"
#include "types.h"
#include "errno.h"

#include "util/string.h"
#include "util/printf.h"
#include "util/debug.h"

#include "fs/dirent.h"
#include "fs/fcntl.h"
#include "fs/stat.h"
#include "fs/vfs.h"
#include "fs/vnode.h"

/* This takes a base 'dir', a 'name', its 'len', and a result vnode.
 * Most of the work should be done by the vnode's implementation
 * specific lookup() function, but you may want to special case
 * "." and/or ".." here depnding on your implementation.
 *
 * If dir has no lookup(), return -ENOTDIR.
 *
 * Note: returns with the vnode refcount on *result incremented.
 */
int
lookup(vnode_t *dir, const char *name, size_t len, vnode_t **result)
{     
    /*zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz*/  
    KASSERT(NULL != dir);
    dbg(DBG_PRINT, "(GRADING2A 2.a)\n");
    KASSERT(NULL != name);
    dbg(DBG_PRINT, "(GRADING2A 2.a)\n");
    KASSERT(NULL != result);
    dbg(DBG_PRINT, "(GRADING2A 2.a)\n");

    int rtv;

    if(len == 0){
        *result = vfs_root_vn;
        vref(vfs_root_vn);
        return vfs_root_vn->vn_refcount;
    }
        
    if((dir->vn_ops->lookup)==NULL){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return -ENOTDIR;
    }else{ 
        dbg(DBG_PRINT, "(GRADING2B)\n");      
        rtv=dir->vn_ops->lookup(dir,name,len,result);
        return rtv;
    }
}


/* When successful this function returns data in the following "out"-arguments:
 *  o res_vnode: the vnode of the parent directory of "name"
 *  o name: the `basename' (the element of the pathname)
 *  o namelen: the length of the basename
 *
 * For example: dir_namev("/s5fs/bin/ls", &namelen, &name, NULL,
 * &res_vnode) would put 2 in namelen, "ls" in name, and a pointer to the
 * vnode corresponding to "/s5fs/bin" in res_vnode.
 *
 * The "base" argument defines where we start resolving the path from:
 * A base value of NULL means to use the process's current working directory,
 * curproc->p_cwd.  If pathname[0] == '/', ignore base and start with
 * vfs_root_vn.  dir_namev() should call lookup() to take care of resolving each
 * piece of the pathname.
 *
 * Note: A successful call to this causes vnode refcount on *res_vnode to
 * be incremented.
 */
int
dir_namev(const char *pathname, size_t *namelen, const char **name,
          vnode_t *base, vnode_t **res_vnode)
{      
    /*zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz*/ 
    KASSERT(NULL != pathname);
    dbg(DBG_PRINT, "(GRADING2A 2.b)\n");
    KASSERT(NULL != namelen);
    dbg(DBG_PRINT, "(GRADING2A 2.b)\n");
    KASSERT(NULL != name);
    dbg(DBG_PRINT, "(GRADING2A 2.b)\n");
    KASSERT(NULL != res_vnode);
    dbg(DBG_PRINT, "(GRADING2A 2.b)\n");


    vnode_t *base_dir;
    vnode_t *original_dir;
    char *token;
    char *path;
    size_t len;
    vnode_t *res;
    char pathl[MAXPATHLEN];
    strncpy(pathl,pathname,strlen(pathname));
    pathl[strlen(pathname)]='\0';
    path=pathl;
    int set=0;
    int look = 0; 
    const char *pnr;
    vnode_t *prevRes = NULL; 
    vnode_t *prevprevRes = NULL;
    int sla;
    original_dir=curproc->p_cwd;
    if(pathname[0]=='/'){    
        base_dir=vfs_root_vn;
        *res_vnode = base_dir; 
        path++;

        if(strlen(path) == 0) {
        	dbg(DBG_PRINT, "(GRADING2B)\n"); 
            *res_vnode = vfs_root_vn;
            *name = "";
            *namelen = 0; 
            return 0;
        }

        token=strtok(path,"/");

        while(token !=NULL){
            len=strlen(token);
            prevprevRes = prevRes;
            prevRes = *res_vnode;

            KASSERT(NULL != *res_vnode);
            dbg(DBG_PRINT, "(GRADING2A 2.b)\n");
            look = lookup(base_dir, token, len, res_vnode);
                
            if(look==-ENOENT){
               	dbg(DBG_PRINT, "(GRADING2B)\n"); 
                *res_vnode = prevRes;
                *name=pathname;
                sla=0;
                while(1){
                    pnr=*name;
                    *name=strchr(*name,'/');

                    if(*name==NULL){	
                		dbg(DBG_PRINT, "(GRADING2B)\n");
                        *name=pnr;
                        break;
                    }else{
                        dbg(DBG_PRINT, "(GRADING2B)\n");
                        while(**name=='/'){	
                			dbg(DBG_PRINT, "(GRADING2B)\n");
                            (*name)++;
                            sla++;
                        }
                        /*
                        if((**name)=='\0'){
                			dbg(DBG_PRINT, "(GRADING2B)\n");
                            *name=pnr;
                            break;
                        }*/
                        sla=0;
                    }
                }
                *namelen = strlen(*name)-sla;

                if((strtok(NULL,"/"))==NULL){
                    dbg(DBG_PRINT, "(GRADING2B)\n"); 
                    /*if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){
                        vput(prevprevRes);
                    }*/
                    return 0; 
                }
            }
                /*
                if(look < 0) {
                	dbg(DBG_PRINT, "(GRADING2B)\n");
                    if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){
                        vput(prevprevRes);
                    }
                    if((prevRes!=original_dir) && (prevRes!=NULL)){
                        vput(prevRes);
                    }
                    return look;
                }
                */
            if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){	
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                /*vput(prevprevRes);*/
            }
              
            *name = token;
            *namelen = strlen(token);        
            base_dir=*res_vnode;
            token = strtok(NULL,"/");
        } 
                    
    }else if(base==NULL){
        base_dir=curproc->p_cwd;
        *res_vnode = base_dir;

        token=strtok(pathl,"/");

        while(token !=NULL){
            len=strlen(token);
            prevprevRes = prevRes;
            prevRes = *res_vnode;

            KASSERT(NULL != *res_vnode);
            dbg(DBG_PRINT, "(GRADING2A 2.b)\n");
            look = lookup(base_dir, token, len, res_vnode);
                
            if(look==-ENOENT) {
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                *res_vnode = prevRes;
                *name=pathname;
                while(1){
                    pnr=*name;
                    *name=strchr(*name,'/');

                    if(*name==NULL){
             			dbg(DBG_PRINT, "(GRADING2B)\n");
                        *name=pnr;
                        break;
                    }else{
            			dbg(DBG_PRINT, "(GRADING2B)\n");
                        (*name)++;
                    }
                }

                *namelen = len;

                if((strtok(NULL,"/"))==NULL){  	
            		dbg(DBG_PRINT, "(GRADING2B)\n");
                    if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){
             			dbg(DBG_PRINT, "(GRADING2B)\n");
                        vput(prevprevRes);
                    }
                    return 0; 
                }
            }
                
            if(look < 0) {
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){
            		dbg(DBG_PRINT, "(GRADING2B)\n");
                    vput(prevprevRes);
                }

                if((prevRes!=original_dir) && (prevRes!=NULL)){
            		dbg(DBG_PRINT, "(GRADING2B)\n");
                    vput(prevRes);
                }
                return look;
            }

            if((prevprevRes!=original_dir) && (prevprevRes!=NULL)){
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                vput(prevprevRes);
            }

            *name = token;
            *namelen = strlen(token);

            base_dir=*res_vnode;
            token = strtok(NULL,"/");
            if(token !=NULL){
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                if(*res_vnode == original_dir){
            		dbg(DBG_PRINT, "(GRADING2B)\n");
                    vput(*res_vnode);
                }
            }
        } 
    }
    vput(*res_vnode);
    *res_vnode=prevRes;

    *name=pathname;
    sla=0;
    while(1){
        pnr=*name;
        *name=strchr(*name,'/');
        if(*name==NULL){ 	
            dbg(DBG_PRINT, "(GRADING2B)\n");
            *name=pnr;
            break;
        }else{
            while(**name=='/'){        	
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                (*name)++;
                sla++;
            }

            if((**name)=='\0'){
            	dbg(DBG_PRINT, "(GRADING2B)\n");
                *name=pnr;
                break;
            }else{
                dbg(DBG_PRINT, "(GRADING2B)\n");
                sla=0;
            }
        }
    }
    *namelen = strlen(*name)-sla;

    return 0;
}

/* This returns in res_vnode the vnode requested by the other parameters.
 * It makes use of dir_namev and lookup to find the specified vnode (if it
 * exists).  flag is right out of the parameters to open(2); see
 * <weenix/fcntl.h>.  If the O_CREAT flag is specified, and the file does
 * not exist call create() in the parent directory vnode.
 *
 * Note: Increments vnode refcount on *res_vnode.
 */
int
open_namev(const char *pathname, int flag, vnode_t **res_vnode, vnode_t *base)
{
    /*zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz*/
    const char *name = NULL; 
    size_t namelen; 
    vnode_t *parent;
    vnode_t *original_dir;

    if(pathname[0]=='/'){
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = vfs_root_vn;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        original_dir = curproc->p_cwd;
    }

    int dir = dir_namev(pathname, &namelen, &name, base, &parent); 
    if(dir < 0) {
        dbg(DBG_PRINT, "(GRADING2B)\n");
        return dir;
    }

    int look = lookup(parent, name, namelen, res_vnode);
    if(look == -ENOENT){
        dbg(DBG_PRINT, "(GRADING2A 2.c)\n");
        if((flag & O_CREAT)==O_CREAT){
            KASSERT(NULL != (parent)->vn_ops->create);
            dbg(DBG_PRINT, "(GRADING2A 2.c)\n");
            
            look = parent->vn_ops->create(parent, name, namelen, res_vnode);
        }

        if(parent != original_dir){
            dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(parent);
        }
        return look;
    }else{
        dbg(DBG_PRINT, "(GRADING2B)\n");
        if(parent != original_dir){
            dbg(DBG_PRINT, "(GRADING2B)\n");
            vput(parent);
        }
        return look; 
    }
}

#ifdef __GETCWD__
/* Finds the name of 'entry' in the directory 'dir'. The name is writen
 * to the given buffer. On success 0 is returned. If 'dir' does not
 * contain 'entry' then -ENOENT is returned. If the given buffer cannot
 * hold the result then it is filled with as many characters as possible
 * and a null terminator, -ERANGE is returned.
 *
 * Files can be uniquely identified within a file system by their
 * inode numbers. */
int
lookup_name(vnode_t *dir, vnode_t *entry, char *buf, size_t size)
{
        NOT_YET_IMPLEMENTED("GETCWD: lookup_name");
        return -ENOENT;
}


/* Used to find the absolute path of the directory 'dir'. Since
 * directories cannot have more than one link there is always
 * a unique solution. The path is writen to the given buffer.
 * On success 0 is returned. On error this function returns a
 * negative error code. See the man page for getcwd(3) for
 * possible errors. Even if an error code is returned the buffer
 * will be filled with a valid string which has some partial
 * information about the wanted path. */
ssize_t
lookup_dirpath(vnode_t *dir, char *buf, size_t osize)
{
        NOT_YET_IMPLEMENTED("GETCWD: lookup_dirpath");

        return -ENOENT;
}
#endif /* __GETCWD__ */
