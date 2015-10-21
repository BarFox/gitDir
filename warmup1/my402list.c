#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/time.h>


#include "cs402.h"
#include "my402list.h"
//free!!!!!
//all if ()need to modify


int My402ListLength(My402List *list)//modify from the function "Traverse"
{
	int i=0;
	My402ListElem *elem=NULL;
	for(elem=My402ListFirst(list);elem!=NULL;elem=My402ListNext(list,elem))
	{
		i++;
	}
	return i;// or I could directly return num_memebers, these will be the same result
	//return ((*list).num_members);
}

int My402ListEmpty(My402List *list)//just check does the anchor.next point to anchor itself?
{
/*	int state=My402ListLength(list);
	if(state==0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}*/
if (((*list).anchor.next)==&((*list).anchor))
return TRUE;
else 
{return FALSE;}
}

My402ListElem *My402ListFirst(My402List *list)//return the next elem of anchor
{
	//if(((*list).anchor.next)=&((*list).anchor))
	if(My402ListEmpty(list)==TRUE)
	{
		return NULL;
	}
	else
	return ((*list).anchor.next);
}

My402ListElem *My402ListLast(My402List *list)//return the pre elem of anchor
{
	//My402ListElem *p=&((*list).anchor);
	//if(((*list).anchor.next)=&((*list).anchor))
	if(My402ListEmpty(list)==TRUE)	
	{
		return NULL;
	}
	else
	/*{
		while(p!=((*list).anchor))
		{
			p=(*list).anchor.next;
		}
		return 
	}*/
	{
		return ((*list).anchor.prev);
	}
}



int My402ListAppend(My402List *list, void *obj)
{//when using,just use (char *) element; 
	//My402ListElem *p;
	if (My402ListEmpty(list)==TRUE)
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));
		if (p)
		{
			(*p).obj=obj;//is this OK?
			(*list).anchor.next=p;
			(*list).anchor.prev=p;
			(*p).prev=&((*list).anchor);
			(*p).next=&((*list).anchor);
			((*list).num_members)++;//add 1
			return TRUE;
		}
		else 
		{return FALSE;}	
	}
	else
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));
		if (p)
		{
			(*p).obj=obj;
			My402ListElem *last=My402ListLast(list);
			(*last).next=p;
			(*p).prev=last;
			(*p).next=&((*list).anchor);
			(*list).anchor.prev=p;
			((*list).num_members)++;
			return TRUE;
		}
		else
		{return FALSE;}
	}
}

int My402ListPrepend(My402List *list, void *obj)
{//when using,just use (char *) element; 
	//My402ListElem *p;
	if (My402ListEmpty(list)==TRUE)
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));
		if (p)
		{
			(*p).obj=obj;//is this OK?
			(*list).anchor.next=p;
			(*list).anchor.prev=p;
			(*p).next=&((*list).anchor);
			(*p).prev=&((*list).anchor);
			(*list).num_members++;
			return TRUE;
		}
		else 
		{return FALSE;}	
	}
	else
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));
		if (p)
		{
			(*p).obj=obj;
			My402ListElem *first=My402ListFirst(list);
			(*first).prev=p;
			(*p).next=first;
			(*p).prev=&((*list).anchor);
			(*list).anchor.next=p;
			(*list).num_members++;
			return TRUE;
		}
		else
		{return FALSE;}
	}
}

void My402ListUnlink (My402List *list,My402ListElem *elem)//why list??!! if empty or last or first?
{
	(*((*elem).next)).prev=(*elem).prev;
	(*((*elem).prev)).next=(*elem).next;
	free(elem);//free need to handle: if fail to free?
	((*list).num_members)--;
}

void My402ListUnlinkAll (My402List *list)// free all elem but not anchor
{
if(My402ListEmpty(list)==FALSE)
{
	My402ListElem *elem_1=(*list).anchor.next;
	My402ListElem *elem_2;
	while(elem_1!=&((*list).anchor))
	{
		elem_2=(*elem_1).next;
		free(elem_1);//free need to handle
		elem_1=elem_2;
	}
	(*list).anchor.next=&((*list).anchor);
	(*list).anchor.prev=&((*list).anchor);
	(*list).num_members=0;
}
}

My402ListElem *My402ListFind (My402List *list,void *obj)// find the obj
{
	My402ListElem *elem=(*list).anchor.next;
	while(elem!=&((*list).anchor))
	{
		if(elem->obj==obj)// simply compare pointer
		{
			return elem;
		}
		elem=(*elem).next;
	}
	return NULL;
}

int My402ListInit(My402List *list)//
{
	//if (list=(My402List*)malloc(sizeof(My402List)))
	{//should free andress space when list is not empty!!!??
		((*list).num_members)=0;//the length of link list
		((*list).anchor.obj)=NULL;//anchor spot's obj point to nothing
		//((*list).anchor.next)=&((*list).anchor);
		(list->anchor).next=&(list->anchor);
		((*list).anchor.prev)=&((*list).anchor);
		((*list).num_members)=0;
		return TRUE;
	}
	//else
	//	return FALSE;
}



My402ListElem *My402ListNext(My402List *list,My402ListElem *p)
{//the use of list
	//if(((*p).next)=&((*list).anchor))
	//if(My402ListEmpty(list)==TRUE)
	if (((*p).next)==&((*list).anchor))	
	{
		return NULL;
	}
	else
	{
		return ((*p).next);
	}
}

My402ListElem *My402ListPrev(My402List *list,My402ListElem *p)
{//the use of list
	//if(((*p).prev)=&((*list).anchor))
	//if(My402ListEmpty(list)==TRUE)
	if (((*p).prev)==&((*list).anchor))
	{
		return NULL;
	}
	else
	{
		return ((*p).prev);
	}
}




int My402ListInsertBefore(My402List *list, void *obj, My402ListElem *elem)
{
	if (elem==NULL)
	{
        int ret;
		ret=My402ListPrepend(list,obj);
		return ret;//return value are depended on Append
	}
	else
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));
		if(p)
		{
		//	My402ListElem *p;
			(*p).obj=obj;
			/*My402ListElem *belem=(*elem).prev;
			(*belem).next=p;
			(*p).prev=belem;
			(*p).next=elem;
			(*elem).prev=p;*/
			(elem->prev)->next=p;
			p->prev=elem->prev;
			p->next=elem;
			elem->prev=p;
			((*list).num_members)++;
			return TRUE;//unlink the list and insert one
		}
		else
		{
			return FALSE;
		}
	}
}

int My402ListInsertAfter(My402List *list, void *obj, My402ListElem *elem)
{
	if (elem==NULL)
	{
        int ret;
		ret=My402ListAppend(list,obj);
		return ret;//return value are depended on Append
	}
	else
	{
		My402ListElem *p=(My402ListElem*)malloc(sizeof(My402ListElem));// no matter how many elems are in the list, we could use the same function, even if the list is empty
		if(p)// if can not malloc
		{
			(*p).obj=obj;
			/*My402ListElem *aelem=(*elem).next;
			(*aelem).prev=p;
			(*p).prev=elem;
			(*p).next=aelem;
			(*elem).next=p;*/
			elem->next->prev=p;
			p->prev=elem;
			p->next=elem->next;
			elem->next=p;
			((*list).num_members)++;
			return TRUE;//unlink the list and insert one
		}
		else
		{
			return FALSE;
		}
	}
}


/*
void main()
{
	
}*/
