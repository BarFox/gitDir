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
#include "kernel.h"
#include "errno.h"

#include "util/debug.h"

#include "proc/proc.h"

#include "mm/mm.h"
#include "mm/mman.h"
#include "mm/page.h"
#include "mm/mmobj.h"
#include "mm/pframe.h"
#include "mm/pagetable.h"

#include "vm/pagefault.h"
#include "vm/vmmap.h"

/*
 * This gets called by _pt_fault_handler in mm/pagetable.c The
 * calling function has already done a lot of error checking for
 * us. In particular it has checked that we are not page faulting
 * while in kernel mode. Make sure you understand why an
 * unexpected page fault in kernel mode is bad in Weenix. You
 * should probably read the _pt_fault_handler function to get a
 * sense of what it is doing.
 *
 * Before you can do anything you need to find the vmarea that
 * contains the address that was faulted on. Make sure to check
 * the permissions on the area to see if the process has
 * permission to do [cause]. If either of these checks does not
 * pass kill the offending process, setting its exit status to
 * EFAULT (normally we would send the SIGSEGV signal, however
 * Weenix does not support signals).
 *
 * Now it is time to find the correct page (don't forget
 * about shadow objects, especially copy-on-write magic!). Make
 * sure that if the user writes to the page it will be handled
 * correctly.
 *
 * Finally call pt_map to have the new mapping placed into the
 * appropriate page table.
 *
 * @param vaddr the address that was accessed to cause the fault
 *
 * @param cause this is the type of operation on the memory
 *              address which caused the fault, possible values
 *              can be found in pagefault.h
 */
void
handle_pagefault(uintptr_t vaddr, uint32_t cause)
{
	dbg(DBG_PRINT, "(GRADING3B 1)\n");
	vmarea_t *vma_fault;
	pframe_t *page_frame;
	pagedir_t *page_dir;
	uintptr_t paddr;
	uint32_t pagenum;
	uint32_t pdflag; 
	uint32_t ptflag;
	/*int vmaflag;*/
	int errno;

	vma_fault = vmmap_lookup(curproc->p_vmmap, ADDR_TO_PN(vaddr));
	if(vma_fault == NULL) {
		dbg(DBG_PRINT, "(GRADING3D 3)\n");
		proc_kill(curproc, EFAULT);
		return;
	}

	if(!(vma_fault->vma_prot & PROT_READ)){
		dbg(DBG_PRINT, "(GRADING3D 3)\n");
		proc_kill(curproc, EFAULT);
		return;		
	}

	/*vmaflag = vma_fault->vma_flags & MAP_TYPE;
	
	if(vmaflag!=MAP_SHARED && vmaflag!=MAP_PRIVATE){
		KASSERT(0); 
		proc_kill(curproc, EFAULT);
		return;			
	}
	*/
	pagenum = ADDR_TO_PN(vaddr)-vma_fault->vma_start+vma_fault->vma_off;

	if(cause & FAULT_RESERVED){
		/*
		KASSERT(0); 
		proc_kill(curproc, EFAULT);
		return;
		*/				
	}else{
		if(cause & FAULT_WRITE){
			dbg(DBG_PRINT, "(GRADING3B 1)\n");
			if(cause & FAULT_EXEC){
				/*
				KASSERT(0); 
				proc_kill(curproc, EFAULT);
				return;
				*/		
			}else{
				dbg(DBG_PRINT, "(GRADING3B 1)\n");
				if(vma_fault->vma_prot & PROT_WRITE){
					dbg(DBG_PRINT, "(GRADING3B 1)\n");
					errno = pframe_lookup(vma_fault->vma_obj, pagenum, 1, &page_frame);
					if(errno < 0){
						dbg(DBG_PRINT, "(GRADING3D 3)\n");
						proc_kill(curproc, EFAULT);
						return;	
					}else{
						dbg(DBG_PRINT, "(GRADING3B 1)\n");
						pframe_dirty(page_frame);
						page_dir=pt_get();
						paddr=pt_virt_to_phys((uintptr_t)page_frame->pf_addr);
						pdflag=PD_PRESENT|PD_WRITE|PD_USER;
						ptflag=PT_PRESENT|PT_WRITE|PT_USER;
						pt_map(page_dir, (uintptr_t)PAGE_ALIGN_DOWN(vaddr), (uintptr_t)PAGE_ALIGN_DOWN(paddr), pdflag, ptflag);
						return;
					}
				}else{
					dbg(DBG_PRINT, "(GRADING3D 3)\n"); 
					proc_kill(curproc, EFAULT);
					return;						
				}
			}
		}else{
			dbg(DBG_PRINT, "(GRADING3B 1)\n");
			if(cause & FAULT_PRESENT){
				/*
				KASSERT(0); 
				proc_kill(curproc, EFAULT);
				return;
				*/	
			}else{
				dbg(DBG_PRINT, "(GRADING3B 1)\n");
				if(cause & FAULT_EXEC){
					/*
					KASSERT(0); 
					if(vma_fault->vma_prot & PROT_EXEC){
						KASSERT(0); 
						errno = pframe_lookup(vma_fault->vma_obj, pagenum, 0, &page_frame);
						if(errno < 0){
							KASSERT(0); 
							proc_kill(curproc, EFAULT);
							return;	
						}else{
							KASSERT(0); 
							page_dir=pt_get();
							paddr=pt_virt_to_phys((uintptr_t)page_frame->pf_addr);
							pdflag=PD_PRESENT|PD_USER;
							ptflag=PT_PRESENT|PT_USER;
							pt_map(page_dir, (uintptr_t)PAGE_ALIGN_DOWN(vaddr), (uintptr_t)PAGE_ALIGN_DOWN(paddr), pdflag, ptflag);
							return;
						}
					}else{
						KASSERT(0); 
						proc_kill(curproc, EFAULT);
						return;						
					}
					*/
				}else{
					dbg(DBG_PRINT, "(GRADING3B 1)\n");
					errno = pframe_lookup(vma_fault->vma_obj, pagenum, 0, &page_frame);
					if(errno < 0){
						dbg(DBG_PRINT, "(GRADING3D 3)\n");
						proc_kill(curproc, EFAULT);
						return;	
					}else{
						dbg(DBG_PRINT, "(GRADING3B 1)\n");
						page_dir=pt_get();
						paddr=pt_virt_to_phys((uintptr_t)page_frame->pf_addr);
						pdflag=PD_PRESENT|PD_USER;
						ptflag=PT_PRESENT|PT_USER;
						pt_map(page_dir, (uintptr_t)PAGE_ALIGN_DOWN(vaddr), (uintptr_t)PAGE_ALIGN_DOWN(paddr), pdflag, ptflag);
						return;
					}
				}
			}
		}
	}

    /* NOT_YET_IMPLEMENTED("VM: handle_pagefault"); */
}
