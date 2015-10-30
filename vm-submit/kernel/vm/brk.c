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
#include "util/debug.h"

#include "mm/mm.h"
#include "mm/page.h"
#include "mm/mman.h"

#include "vm/mmap.h"
#include "vm/vmmap.h"

#include "proc/proc.h"

/*
 * This function implements the brk(2) system call.
 *
 * This routine manages the calling process's "break" -- the ending address
 * of the process's "dynamic" region (often also referred to as the "heap").
 * The current value of a process's break is maintained in the 'p_brk' member
 * of the proc_t structure that represents the process in question.
 *
 * The 'p_brk' and 'p_start_brk' members of a proc_t struct are initialized
 * by the loader. 'p_start_brk' is subsequently never modified; it always
 * holds the initial value of the break. Note that the starting break is
 * not necessarily page aligned!
 *
 * 'p_start_brk' is the lower limit of 'p_brk' (that is, setting the break
 * to any value less than 'p_start_brk' should be disallowed).
 *
 * The upper limit of 'p_brk' is defined by the minimum of (1) the
 * starting address of the next occuring mapping or (2) USER_MEM_HIGH.
 * That is, growth of the process break is limited only in that it cannot
 * overlap with/expand into an existing mapping or beyond the region of
 * the address space allocated for use by userland. (note the presence of
 * the 'vmmap_is_range_empty' function).
 *
 * The dynamic region should always be represented by at most ONE vmarea.
 * Note that vmareas only have page granularity, you will need to take this
 * into account when deciding how to set the mappings if p_brk or p_start_brk
 * is not page aligned.
 *
 * You are guaranteed that the process data/bss region is non-empty.
 * That is, if the starting brk is not page-aligned, its page has
 * read/write permissions.
 *
 * If addr is NULL, you should NOT fail as the man page says. Instead,
 * "return" the current break. We use this to implement sbrk(0) without writing
 * a separate syscall. Look in user/libc/syscall.c if you're curious.
 *
 * Also, despite the statement on the manpage, you MUST support combined use
 * of brk and mmap in the same process.
 *
 * Note that this function "returns" the new break through the "ret" argument.
 * Return 0 on success, -errno on failure.
 */
int
do_brk(void *addr, void **ret)
{
    uint32_t vfn1;
    uint32_t vfn2;
    vmarea_t * cur_vma;
    vmarea_t * pre_vma;
    vmarea_t *vma;
    int i=0;
    dbg(DBG_PRINT, "(GRADING3B 2)\n");
    if(addr==NULL){
        dbg(DBG_PRINT, "(GRADING3B 2)\n");
        *ret=curproc->p_brk;
        return 0;
    }
    if(addr<curproc->p_start_brk){
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        /*ret=NULL;*/
        return -ENOMEM;
    }
    
    vfn2=ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_start_brk));
    pre_vma = vmmap_lookup(curproc->p_vmmap, vfn2);
    /*if(cur_vma==NULL){
        ret=NULL;
        return -EFAULT;
    }*/

   
   
        
    if (ADDR_TO_PN(PAGE_ALIGN_UP(addr)) == ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_brk))) {
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        curproc->p_brk = addr;
        *ret = addr;
        return 0;
    }
    
    /*
    list_iterate_begin(&curproc->p_vmmap->vmm_list, vma, vmarea_t, vma_plink) {
        if(i==1){
            i=2;
            break;
        }
        if(vma==cur_vma){
            i=1;
        }
    } list_iterate_end();
    if(i==2){
        if(ADDR_TO_PN(addr)>=vma->vma_start){
            ret=NULL;
            return -ENOMEM;
        }
    }
    else if(i==1){
        if(ADDR_TO_PN(addr)>USER_MEM_HIGH){
            ret=NULL;
            return -ENOMEM;
        }
    }
    */

    
    if(addr < curproc->p_brk){
        /*delete trash vma*/
        dbg(DBG_PRINT, "(GRADING3D 3)\n");
        
        vmmap_remove(curproc->p_vmmap,ADDR_TO_PN(PAGE_ALIGN_UP(addr)),ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_brk))-ADDR_TO_PN(PAGE_ALIGN_UP(addr)));
       
       /* pre_vma->vma_end=ADDR_TO_PN(PAGE_ALIGN_DOWN(addr));*/
        
    }
    
    
    else if(addr >curproc->p_brk){
        /*create new vma*/
        dbg(DBG_PRINT, "(GRADING3B 2)\n");
        if (vmmap_is_range_empty(curproc->p_vmmap, ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_brk)), ADDR_TO_PN(PAGE_ALIGN_UP(addr))-ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_brk)))==NULL){
            dbg(DBG_PRINT, "(GRADING3D 3)\n");
            return -ENOMEM;
        }
        
        if(pre_vma!=NULL){
            dbg(DBG_PRINT, "(GRADING3B 2)\n");
            pre_vma->vma_end=ADDR_TO_PN(PAGE_ALIGN_UP(addr));
        }
        else {
            dbg(DBG_PRINT, "(GRADING3B 2)\n");
            int rtr=vmmap_map(curproc->p_vmmap, NULL, ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_start_brk)),ADDR_TO_PN(PAGE_ALIGN_UP(addr))-ADDR_TO_PN(PAGE_ALIGN_UP(curproc->p_start_brk)), PROT_READ | PROT_WRITE,  MAP_PRIVATE, 0, VMMAP_DIR_LOHI,NULL);
            /*
            if(rtr<0){
                KASSERT(0);
                return rtr;
            }
            */
            
        }
        
        
        
    }
    
   /* else{
        cur_vma->vma_end=ADDR_TO_PN(PAGE_ALIGN_DOWN(addr));
    }*/
/*  else if(ADDR_TO_PN(addr)+1==ADDR_TO_PN(curproc->p_brk)+1){

    }*/
        
        curproc->p_brk=addr;
        *ret=(curproc->p_brk);
        return 0;
    
    /*ADDR_TO_PN(addr)+1*/
    
       /* NOT_YET_IMPLEMENTED("VM: do_brk");
        return 0;*/
    
}