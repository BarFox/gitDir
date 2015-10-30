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

#include "util/string.h"
#include "util/debug.h"

#include "mm/mmobj.h"
#include "mm/pframe.h"
#include "mm/mm.h"
#include "mm/page.h"
#include "mm/slab.h"
#include "mm/tlb.h"

#include "vm/vmmap.h"
#include "vm/shadow.h"
#include "vm/shadowd.h"

#define SHADOW_SINGLETON_THRESHOLD 5

int shadow_count = 0; /* for debugging/verification purposes */
#ifdef __SHADOWD__
/*
 * number of shadow objects with a single parent, that is another shadow
 * object in the shadow objects tree(singletons)
 */
static int shadow_singleton_count = 0;
#endif

static slab_allocator_t *shadow_allocator;

static void shadow_ref(mmobj_t *o);
static void shadow_put(mmobj_t *o);
static int  shadow_lookuppage(mmobj_t *o, uint32_t pagenum, int forwrite, pframe_t **pf);
static int  shadow_fillpage(mmobj_t *o, pframe_t *pf);
static int  shadow_dirtypage(mmobj_t *o, pframe_t *pf);
static int  shadow_cleanpage(mmobj_t *o, pframe_t *pf);

static mmobj_ops_t shadow_mmobj_ops = {
        .ref = shadow_ref,
        .put = shadow_put,
        .lookuppage = shadow_lookuppage,
        .fillpage  = shadow_fillpage,
        .dirtypage = shadow_dirtypage,
        .cleanpage = shadow_cleanpage
};

/*
 * This function is called at boot time to initialize the
 * shadow page sub system. Currently it only initializes the
 * shadow_allocator object.
 */
void
shadow_init()
{
        shadow_allocator = slab_allocator_create("shadow", sizeof(mmobj_t));
        
        KASSERT(shadow_allocator);
        dbg(DBG_PRINT, "(GRADING3A 6.a)\n");

        /*NOT_YET_IMPLEMENTED("VM: shadow_init");*/
}

/*
 * You'll want to use the shadow_allocator to allocate the mmobj to
 * return, then then initialize it. Take a look in mm/mmobj.h for
 * macros which can be of use here. Make sure your initial
 * reference count is correct.
 */
mmobj_t *
shadow_create()
{
        dbg(DBG_PRINT, "(GRADING3D 2)\n");
        
        mmobj_t *shadow = (mmobj_t*)slab_obj_alloc(shadow_allocator);
    /*    
        if(shadow == NULL) {
            KASSERT(0);
            return NULL;
        }*/

        mmobj_init(shadow ,&shadow_mmobj_ops);   
        shadow->mmo_un.mmo_bottom_obj = NULL;     
        shadow->mmo_refcount++;

        return shadow; 

        /*NOT_YET_IMPLEMENTED("VM: shadow_create");
        return NULL;*/
}

/* Implementation of mmobj entry points: */

/*
 * Increment the reference count on the object.
 */
static void
shadow_ref(mmobj_t *o)
{
        KASSERT(o && (0 < o->mmo_refcount) && (&shadow_mmobj_ops == o->mmo_ops));
       /* NOT_YET_IMPLEMENTED("VM: shadow_ref");*/
        dbg(DBG_PRINT, "(GRADING3A 6.b)\n");
        o->mmo_refcount ++;
        return;
}

/*
 * Decrement the reference count on the object. If, however, the
 * reference count on the object reaches the number of resident
 * pages of the object, we can conclude that the object is no
 * longer in use and, since it is a shadow object, it will never
 * be used again. You should unpin and uncache all of the object's
 * pages and then free the object itself.
 */
static void
shadow_put(mmobj_t *o)
{
      /*  NOT_YET_IMPLEMENTED("VM: shadow_put");*/
    KASSERT(o && (0 < o->mmo_refcount) && (&shadow_mmobj_ops == o->mmo_ops));
    dbg(DBG_PRINT, "(GRADING3A 6.c)\n");

    pframe_t* pf;
    list_t *link;                                    
    static int count = 0;
    
/*
    if(o->mmo_refcount == o->mmo_nrespages-1){   
        link = o->mmo_respages.l_next;
        KASSERT(0);                                                                                   
        while(link != &o->mmo_respages) {    
            KASSERT(0);                             
            pf = list_item(link, pframe_t, pf_olink);                                 
            if(pframe_is_pinned(pf)){
                KASSERT(0);
                pframe_unpin(pf);
            }
            count++;
            while(pframe_is_busy(pf)){
                KASSERT(0);
                sched_sleep_on(&pf->pf_waitq);
            }
            pframe_free(pf); 
            count--;    
            link=o->mmo_respages.l_next;                                 
        }                                                       

        if(count==0){
            KASSERT(0);
            o->mmo_ops->put(o);
            slab_obj_free(shadow_allocator, o); 
        }
    }*/
    o->mmo_refcount--;

}

/* This function looks up the given page in this shadow object. The
 * forwrite argument is true if the page is being looked up for
 * writing, false if it is being looked up for reading. This function
 * must handle all do-not-copy-on-not-write magic (i.e. when forwrite
 * is false find the first shadow object in the chain which has the
 * given page resident). copy-on-write magic (necessary when forwrite
 * is true) is handled in shadow_fillpage, not here. It is important to
 * use iteration rather than recursion here as a recursive implementation
 * can overflow the kernel stack when looking down a long shadow chain */
 static int
 shadow_lookuppage(mmobj_t *o, uint32_t pagenum, int forwrite, pframe_t **pf)
 {
    dbg(DBG_PRINT, "(GRADING3B 2)\n");

    if(forwrite == 0) { /* looking up for reading -- find the first first shadow object in chain*/
        dbg(DBG_PRINT, "(GRADING3B 2)\n");
        mmobj_t *new_o = o;
        mmobj_t *bottom = mmobj_bottom_obj(o); 

        pframe_t *pf_new;
        while(new_o != bottom) {
            pf_new= pframe_get_resident(new_o, pagenum);
            if(pf_new != NULL) {
                dbg(DBG_PRINT, "(GRADING3D 2)\n");
              /*  while(pframe_is_busy(pf_new)){
                    KASSERT(0);
                    sched_sleep_on(&pf_new->pf_waitq);
                }*/
                *pf = pf_new;
                return 0; 
            }
            new_o = new_o->mmo_shadowed;
        }
        int ret = pframe_lookup(bottom, pagenum, forwrite, pf);
                        /*int ret = pframe_get(bottom, pagenum, pf);*/
        return ret;

    } else { /*looking up for writing */ 
        dbg(DBG_PRINT, "(GRADING3B 2)\n");
        int ret = pframe_get(o, pagenum, pf);
        return ret;
          /*  return shadow_fillpage(o, *pf);*/
    }

     /*   NOT_YET_IMPLEMENTED("VM: shadow_lookuppage");
        return 0;*/
    return 0;
}

/* As per the specification in mmobj.h, fill the page frame starting
 * at address pf->pf_addr with the contents of the page identified by
 * pf->pf_obj and pf->pf_pagenum. This function handles all
 * copy-on-write magic (i.e. if there is a shadow object which has
 * data for the pf->pf_pagenum-th page then we should take that data,
 * if no such shadow object exists we need to follow the chain of
 * shadow objects all the way to the bottom object and take the data
 * for the pf->pf_pagenum-th page from the last object in the chain).
 * It is important to use iteration rather than recursion here as a 
 * recursive implementation can overflow the kernel stack when 
 * looking down a long shadow chain */
static int
shadow_fillpage(mmobj_t *o, pframe_t *pf)
{

        KASSERT(pframe_is_busy(pf));
        KASSERT(!pframe_is_pinned(pf));
        dbg(DBG_PRINT, "(GRADING3A 6.d)\n");
        pframe_t *pf_new;
        int ret = shadow_lookuppage(o->mmo_shadowed, pf->pf_pagenum, 0, &pf_new);
        if(ret<0){
            dbg(DBG_PRINT, "(GRADING3D 3)\n");
            return ret;
        }
        pframe_dirty(pf_new);
/*
        mmobj_t *new_o = o;
        mmobj_t *bottom = mmobj_bottom_obj(o);
        pframe_t *pf_new;  

        while(new_o != bottom) {
            pf_new = pframe_get_resident(o->mmo_shadowed, pf->pf_pagenum);
            if(pf_new != NULL) {
                while(pframe_is_busy(pf_new)){
                        sched_sleep_on(&pf_new->pf_waitq);
                }
                break;
            }
            new_o = new_o->mmo_shadowed;
        }
*/
        /* get the bottom resident */
       /* if(pf_new == NULL) { 
           pf_new = pframe_get_resident(bottom, pf->pf_pagenum);
        } else {
            int ret = pframe_get(bottom, pf->pf_pagenum, &pf_new);
        }*/
            /*
        if(pf_new == NULL) {
            int ret = pframe_get(bottom, pf->pf_pagenum, &pf_new);
        }*/

       /* KASSERT(pf_new && "DIDNT FIND A PFRAME SHADOW FILLPAGE");*/
        memcpy(pf->pf_addr, pf_new->pf_addr, PAGE_SIZE);
        pframe_pin(pf); 

      /*  NOT_YET_IMPLEMENTED("VM: shadow_fillpage");*/
        return 0;
}

/* These next two functions are not difficult. */

static int
shadow_dirtypage(mmobj_t *o, pframe_t *pf)
{
       /* dbg(DBG_PRINT, "(GRADING3__ __.___)\n");
        NOT_YET_IMPLEMENTED("VM: shadow_dirtypage");
        return -1;*/
    dbg(DBG_PRINT, "(GRADING3B 2)\n");

 /*   if(!pframe_is_pinned(pf)){
        KASSERT(0);
        pframe_pin(pf);
    }*/
    return 0;
}

static int
shadow_cleanpage(mmobj_t *o, pframe_t *pf)
{
      /*  dbg(DBG_PRINT, "(GRADING3__ __.___)\n");
        NOT_YET_IMPLEMENTED("VM: shadow_cleanpage");
        return -1;*/
    dbg(DBG_PRINT, "(GRADING3___ ___.___)\n");
    
 /*   if(pframe_is_pinned(pf)){
        KASSERT(0);
        pframe_unpin(pf);
    }*/
    return 0;
}
