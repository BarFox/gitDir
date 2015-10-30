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

#include "types.h"
#include "globals.h"
#include "errno.h"

#include "util/debug.h"
#include "util/string.h"

#include "proc/proc.h"
#include "proc/kthread.h"

#include "mm/mm.h"
#include "mm/mman.h"
#include "mm/page.h"
#include "mm/pframe.h"
#include "mm/mmobj.h"
#include "mm/pagetable.h"
#include "mm/tlb.h"

#include "fs/file.h"
#include "fs/vnode.h"

#include "vm/shadow.h"
#include "vm/vmmap.h"

#include "api/exec.h"

#include "main/interrupt.h"

/* Pushes the appropriate things onto the kernel stack of a newly forked thread
 * so that it can begin execution in userland_entry.
 * regs: registers the new thread should have on execution
 * kstack: location of the new thread's kernel stack
 * Returns the new stack pointer on success. */
static uint32_t
fork_setup_stack(const regs_t *regs, void *kstack)
{
        /* Pointer argument and dummy return address, and userland dummy return
         * address */
        uint32_t esp = ((uint32_t) kstack) + DEFAULT_STACK_SIZE - (sizeof(regs_t) + 12);
        *(void **)(esp + 4) = (void *)(esp + 8); /* Set the argument to point to location of struct on stack */
        memcpy((void *)(esp + 8), regs, sizeof(regs_t)); /* Copy over struct */
        return esp;
}


/*
 * The implementation of fork(2). Once this works,
 * you're practically home free. This is what the
 * entirety of Weenix has been leading up to.
 * Go forth and conquer.
 */
int
do_fork(struct regs *regs)
{
    KASSERT(regs != NULL);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");
    KASSERT(curproc != NULL);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");
    KASSERT(curproc->p_state == PROC_RUNNING);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");

/*------Allocate memory for process, address space and thread------*/

    proc_t *newproc;
    newproc=proc_create("child_proc");
  /*  if(newproc==NULL){
        KASSERT(0);
        return -ENOMEM;
    }*/

    newproc->p_vmmap = vmmap_clone(curproc->p_vmmap);
    KASSERT(newproc->p_vmmap);

    kthread_t *newthr;


    KASSERT(newproc->p_state == PROC_RUNNING);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");

/*-------------Config vmmap for both child process-----------------*/

    vmarea_t *parent_vma;
    vmarea_t *child_vma;


    mmobj_t *parent_shadow;
    mmobj_t *child_shadow;

    mmobj_t *temp_obj;

    list_iterate_begin(&curproc->p_vmmap->vmm_list, parent_vma, vmarea_t, vma_plink){
        dbg(DBG_PRINT, "(GRADING3B 1)\n");
        child_vma = vmmap_lookup(newproc->p_vmmap, parent_vma->vma_start);

        if(parent_vma->vma_flags & MAP_SHARED){
           dbg(DBG_PRINT, "(GRADING3D 3)\n");
            child_vma->vma_obj = parent_vma->vma_obj;
            parent_vma->vma_obj->mmo_ops->ref(parent_vma->vma_obj);
            list_insert_tail(&child_vma->vma_obj->mmo_un.mmo_vmas, &child_vma->vma_olink); 

        }else if(parent_vma->vma_flags & MAP_PRIVATE){
            dbg(DBG_PRINT, "(GRADING3B 1)\n");
            parent_shadow = shadow_create();
            child_shadow = shadow_create();

            KASSERT(parent_shadow);
            KASSERT(child_shadow);

            parent_vma->vma_obj->mmo_ops->ref(parent_vma->vma_obj);
            if(parent_vma->vma_obj->mmo_shadowed){
                dbg(DBG_PRINT, "(GRADING3B 1)\n");
                parent_shadow->mmo_un.mmo_bottom_obj = parent_vma->vma_obj->mmo_un.mmo_bottom_obj;
                child_shadow->mmo_un.mmo_bottom_obj = parent_vma->vma_obj->mmo_un.mmo_bottom_obj;

                parent_shadow->mmo_shadowed = parent_vma->vma_obj;
                child_shadow->mmo_shadowed = parent_vma->vma_obj;

                parent_vma->vma_obj = parent_shadow;
                child_vma->vma_obj = child_shadow;
               
            }
            /*else{
                KASSERT(0);
                parent_shadow->mmo_un.mmo_bottom_obj = parent_vma->vma_obj;
                child_shadow->mmo_un.mmo_bottom_obj = parent_vma->vma_obj;

                parent_shadow->mmo_shadowed = parent_vma->vma_obj;
                child_shadow->mmo_shadowed = parent_vma->vma_obj;

                parent_vma->vma_obj = parent_shadow;
                child_vma->vma_obj = child_shadow;
            }*/

            list_insert_tail(&parent_vma->vma_obj->mmo_un.mmo_bottom_obj->mmo_un.mmo_vmas, &child_vma->vma_olink);
        } /*else{
            KASSERT(0);
            return -EPERM;
        }*/

    }list_iterate_end();

    pt_unmap_range(curproc->p_pagedir, USER_MEM_LOW, USER_MEM_HIGH);

    tlb_flush_all();

/*----------------Config child process------------------*/
    newthr = kthread_clone(curthr);
    KASSERT(newthr);

    list_insert_tail(&newproc->p_threads, &newthr->kt_plink);

    int f_num;
    for(f_num = 0; f_num < NFILES; f_num++){
        if(curproc->p_files[f_num]!=NULL){
            dbg(DBG_PRINT, "(GRADING3B 1)\n");
            newproc->p_files[f_num] = curproc->p_files[f_num];
            /*fref(curproc->p_files[f_num]);*/
        }else{
            dbg(DBG_PRINT, "(GRADING3B 1)\n");
            newproc->p_files[f_num] = NULL;
        }
    }

    if(newproc->p_cwd){
        dbg(DBG_PRINT, "(GRADING3B 1)\n");
        vput(newproc->p_cwd);
    }

    newproc->p_cwd = curproc->p_cwd;
    vref(curproc->p_cwd);

    newproc->p_brk = curproc->p_brk;
    newproc->p_start_brk = curproc->p_start_brk;

/*--------------Config thread of child process------------------*/
    

    KASSERT(newproc->p_pagedir != NULL);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");
    newthr->kt_ctx.c_pdptr = newproc->p_pagedir;

    newthr->kt_ctx.c_eip = (uint32_t)userland_entry;

    regs->r_eax = 0;

    KASSERT(newthr->kt_kstack != NULL);
    dbg(DBG_PRINT, "(GRADING3A 7.a)\n");
    newthr->kt_ctx.c_esp = fork_setup_stack(regs, newthr->kt_kstack);

    newthr->kt_ctx.c_kstack = (uintptr_t)newthr->kt_kstack;
    newthr->kt_ctx.c_kstacksz = DEFAULT_STACK_SIZE;

    newthr->kt_proc = newproc;

    tlb_flush_all();

    sched_make_runnable(newthr);

    return newproc->p_pid;

    /*NOT_YET_IMPLEMENTED("VM: do_fork");
    return 0;*/
}
