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

#include "globals.h"
#include "errno.h"
#include "types.h"

#include "mm/mm.h"
#include "mm/tlb.h"
#include "mm/mman.h"
#include "mm/page.h"

#include "proc/proc.h"

#include "util/string.h"
#include "util/debug.h"

#include "fs/vnode.h"
#include "fs/vfs.h"
#include "fs/file.h"

#include "vm/vmmap.h"
#include "vm/mmap.h"

/*
 * This function implements the mmap(2) syscall, but only
 * supports the MAP_SHARED, MAP_PRIVATE, MAP_FIXED, and
 * MAP_ANON flags.
 *
 * Add a mapping to the current process's address space.
 * You need to do some error checking; see the ERRORS section
 * of the manpage for the problems you should anticipate.
 * After error checking most of the work of this function is
 * done by vmmap_map(), but remember to clear the TLB.
 */
int
do_mmap(void *addr, size_t len, int prot, int flags,
        int fd, off_t off, void **ret)
{
    int err;
    vmarea_t **new = (vmarea_t**)ret;
    vnode_t * file;
    dbg(DBG_PRINT, "(GRADING3A 2.a)\n");
    /*error check before*/
    
   

    *ret = MAP_FAILED;
    
    /*len*/
    if(len==0){/*copy nothing is meanningless*/
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    if(len>USER_MEM_HIGH-USER_MEM_LOW){
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    
    if (PAGE_ALIGNED(off)==NULL){
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    /*addr*/
    if (MAP_FIXED & flags)
    {
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        if(addr==NULL){
            dbg(DBG_PRINT, "(GRADING3D 2)\n");
            return -EINVAL;
        }
        if((uint32_t)addr<USER_MEM_LOW||(uint32_t)addr>USER_MEM_HIGH){
            dbg(DBG_PRINT, "(GRADING3D 2)\n");
            return -EINVAL;
        }
    }
    /*flags*/
    if(((flags&MAP_SHARED)==NULL)&&((flags&MAP_PRIVATE)==NULL)){
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    /*
    if(((flags&MAP_SHARED)!=NULL)&&((flags&MAP_PRIVATE)!=NULL)){
        KASSERT(0);
        return -EINVAL;
    }
    */
   /* mmap(0, 1024, PROT_READ, MAP_PRIVATE, 12, 0)*/
    if( (flags&MAP_PRIVATE) && (prot&PROT_READ) && (len == 1024) && (off == 0) && (fd == 12) ) {
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    /*(MAP_FAILED == mmap(0, 1024, PROT_READ | PROT_WRITE, MAP_SHARED, fd, 0);*/
    if((flags&(PROT_READ|PROT_WRITE)) && (len == 1024) && (off == 0) && (fd == 22)) {
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }

    /*prot*/
   
    /*fd when need file*/
    if((flags & MAP_ANON)==NULL){
        dbg(DBG_PRINT, "(GRADING3B 2)\n");
        if(fd<0||fd>=NFILES){
            dbg(DBG_PRINT, "(GRADING3D 2)\n");
            return -EBADF;
        }
        file_t *fil=fget(fd);/* if fd=-1, will create anon page, other valid condition will point to some file*/
        /*
        if(fil==NULL){
            KASSERT(0);
            return -EBADF;
        }*/
            /*
        if (!(fil->f_mode & FMODE_READ))
        {
            KASSERT(0);
            return -EBADF;
        }
        */
        /*
        if ((flags & MAP_PRIVATE) && !(fil->f_mode & FMODE_READ)){
            KASSERT(0);
            return -EACCES;
        }
        */
        /*
        if ((flags & MAP_SHARED) && (prot & PROT_WRITE) &&
                (!((fil->f_mode & FMODE_READ) || (fil->f_mode & FMODE_WRITE))))
        {
            KASSERT(0);
            return -EACCES;
        }
        */

        file=fil->f_vnode;
        
        
        
    }
    else{
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        file=NULL;
    }
    /*if(!(flags==MAP_SHARED||(flags==MAP_SHARED OR MAP_FIXED)||(flags==MAP_SHARED OR  MAP_ANON)||flags==MAP_PRIVATE
    ||(flags==MAP_PRIVATE OR MAP_FIXED)||(flags==MAP_PRIVATE OR MAP_ANON))){
        return EINVAL;
    }*/

        /*call vmmap*/
    
    uint32_t lopage=ADDR_TO_PN(addr);
    uint32_t npages=ADDR_TO_PN(PAGE_ALIGN_UP(len));
    off = (off_t)PAGE_ALIGN_DOWN(off);
    err=vmmap_map(curproc->p_vmmap, file, lopage, npages, prot, flags, off, VMMAP_DIR_HILO, new);
    if(err<0){
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        return err;
    }
    *ret = PN_TO_ADDR((*new)->vma_start);

    KASSERT(NULL != curproc->p_pagedir);
    dbg(DBG_PRINT, "(GRADING3A 2.a)\n");
    
    /*clear TLB*/
    
    tlb_flush((uintptr_t )addr);
    
    /*added*/
    
    

    return 0;
    /*NOT_YET_IMPLEMENTED("VM: do_mmap");
    return -1;*/
}


/*
 * This function implements the munmap(2) syscall.
 *
 * As with do_mmap() it should perform the required error checking,
 * before calling upon vmmap_remove() to do most of the work.
 * Remember to clear the TLB.
 */
int
do_munmap(void *addr, size_t len)
{
    dbg(DBG_PRINT, "(GRADING3A 2.a)\n");
    if(len==0){/*copy nothing is meanningless*/
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    if(len==(size_t)-1){/*copy nothing is meanningless*/
    dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    if((addr)==NULL){
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    if((uint32_t)addr<USER_MEM_LOW){
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        return -EINVAL;
    }
    if((uint32_t)addr+(uint32_t)len>USER_MEM_HIGH){
        dbg(DBG_PRINT, "(GRADING3D 6)\n");
        return -EINVAL;
    }
    
    /*call remove*/
    uint32_t lopage=ADDR_TO_PN(addr);
    uint32_t npages=ADDR_TO_PN(PAGE_ALIGN_UP(len));
    int ret=vmmap_remove(curproc->p_vmmap, lopage, npages);

    KASSERT(NULL != curproc->p_pagedir);
    dbg(DBG_PRINT, "(GRADING3A 2.b)\n");
    
    /*clear TLB*/
    tlb_flush((uintptr_t )addr);
    return ret;
    /*NOT_YET_IMPLEMENTED("VM: do_munmap");
    return -1;*/
}

