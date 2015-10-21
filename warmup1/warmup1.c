#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/time.h>

#include <sys/types.h>
#include <time.h>
#include<ctype.h>//

#include "cs402.h"
#include "my402list.h"
//#include "my402list.c"
#define MAXLENGTH 1026
//#define MAXLINE 20 //at most store 20 line of transfer action

struct Transact// struct to store obj
{
	char symbol;//='0';
	char date[50];
	char amount[50];
	char descrip[50];//use strncpy so no problem
};

char * My402ListItoa(int i)// when i is too big the whole process could still run while the output is wrong
{// but the number will not be too big since refine in the input
	static char list[15];
	char *p;
	p=list;
	int n=0;
	int m=0;
	while (i!=0)
	{	
		list[n]=i%10+'0';
		i=i/10;
		n++;
	}
	list[n]='\0';
	for (;m<=(n-1)/2;m++)
	{
		int temp;
		temp=list[m];
		list[m]=list[n-1-m];
		list[n-1-m]=temp;
	}
	return 	p;
}

void BubbleForward(My402List *pList, My402ListElem **pp_elem1, My402ListElem **pp_elem2)// modify from listtest.c
    /* (*pp_elem1) must be closer to First() than (*pp_elem2) */
{
    My402ListElem *elem1=(*pp_elem1), *elem2=(*pp_elem2);
    void *obj1=elem1->obj, *obj2=elem2->obj;
    My402ListElem *elem1prev=My402ListPrev(pList, elem1);
/*  My402ListElem *elem1next=My402ListNext(pList, elem1); */
/*  My402ListElem *elem2prev=My402ListPrev(pList, elem2); */
    My402ListElem *elem2next=My402ListNext(pList, elem2);

    My402ListUnlink(pList, elem1);
    My402ListUnlink(pList, elem2);
    if (elem1prev == NULL) {
        (void)My402ListPrepend(pList, obj2);
        *pp_elem1 = My402ListFirst(pList);
    } else {
        (void)My402ListInsertAfter(pList, obj2, elem1prev);
        *pp_elem1 = My402ListNext(pList, elem1prev);
    }
    if (elem2next == NULL) {
        (void)My402ListAppend(pList, obj1);
        *pp_elem2 = My402ListLast(pList);
    } else {
        (void)My402ListInsertBefore(pList, obj1, elem2next);
        *pp_elem2 = My402ListPrev(pList, elem2next);
    }
}

void BubbleSortForwardList(My402List *pList, int num_items)// modify from listtest.c
{
    My402ListElem *elem=NULL;
    int i=0;

  /*  if (My402ListLength(pList) != num_items) {
        fprintf(stderr, "List length is not %1d in BubbleSortForwardList().\n", num_items);
        exit(1);
    }*/
    for (i=0; i < num_items; i++) {
        int j=0, something_swapped=FALSE;
        My402ListElem *next_elem=NULL;

        for (elem=My402ListFirst(pList), j=0; j < num_items-i-1; elem=next_elem, j++) {
           // int cur_val=(int)(elem->obj), next_val=0;
		
		char cur_var[50],next_var[50];
		strncpy(cur_var,((*(struct Transact *)(elem->obj)).date),sizeof(cur_var));
           	next_elem=My402ListNext(pList, elem);
        //    next_val = (int)(next_elem->obj);
		strncpy(next_var,((*(struct Transact *)(next_elem->obj)).date),sizeof(next_var));
//            if (cur_val > next_val)
		if (strcmp(cur_var,next_var)>0)
	    {
                BubbleForward(pList, &elem, &next_elem);
                something_swapped = TRUE;
            }
        }
        if (!something_swapped) break;
    }
}

void PrintTestList(My402List *pList, int num_items)// to print all lines
{
	static  long int balance=0;
    My402ListElem *elem=NULL;
	//char time[50]; 

        for (elem=My402ListFirst(pList); elem != NULL; elem=My402ListNext(pList, elem)) {
        //int ival=(int)(elem->obj);
	char date[16];
	char buf[26];
	//printf("%s\n",((*(struct Transact *)(elem->obj)).date));
	//modify the time
	time_t datetime=(time_t)atoi(((*(struct Transact *)(elem->obj)).date));
	strncpy(buf,ctime(&datetime),sizeof(buf));
	date[0]=buf[0];	
	date[1]=buf[1];
	date[2]=buf[2];
	date[3]=buf[3];
	date[4]=buf[4];
	date[5]=buf[5];
	date[6]=buf[6];
	date[7]=buf[7];
	date[8]=buf[8];
	date[9]=buf[9];
	date[10]=buf[10];
	date[11]=buf[20];
	date[12]=buf[21];
	date[13]=buf[22];
	date[14]=buf[23];
	date[15]='\0';// too silly way... I could simply use a pointer..next time
	
    //modify the descrip
	char desc[26];
	int i=0;
	for(;i<=24;i++) 	
	{desc[i]=' ';}
	i=0;
	while((((*(struct Transact *)(elem->obj)).descrip[i])!='\0') && (i<=23))//this number?ok
	{
		desc[i]=((*(struct Transact *)(elem->obj)).descrip[i]);
		i++;
	}
    desc[24]=' ';
	desc[25]='\0';

	//modify amount
	char amount[16];
	for(i=0;i<=14;i++) 	
	{amount[i]=' ';}
	amount[15]='\0';
		int n=12;
	i=strlen((*(struct Transact *)(elem->obj)).amount);
	//printf("%d\n",i);
	//printf("the number is !!!!!!!%s\n",(*(struct Transact *)(elem->obj)).amount);
	while(i>0 && n>=0)
	{
		amount[n]=(*(struct Transact *)(elem->obj)).amount[i-1];
		n--;
		i--;
	}

	

	//modify the balance
	char amountcopy[16];
	char *balancecopy;	
	char balanceprint[16]={' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ','\0'};
	strncpy(amountcopy,(*(struct Transact *)(elem->obj)).amount,sizeof(amountcopy));
	char *p=strchr(amountcopy,'.');
	*p=*(p+1);
	*(p+1)=*(p+2);
	*(p+2)='\0';
	long int transfer=atoi(amountcopy);	
	if((*(struct Transact *)(elem->obj)).symbol=='-')
	{transfer=(-1)*transfer;}
	//printf("transfer is %ld\n",transfer);
	balance=balance+transfer;
	if(balance<0)
	{
		balancecopy=My402ListItoa((-1)*balance);
		
	}
	else
	{balancecopy=My402ListItoa(balance);}
	n=strlen(balancecopy);
	balancecopy[n+1]='\0';
	balancecopy[n]=balancecopy[n-1];
	balancecopy[n-1]=balancecopy[n-2];
	balancecopy[n-2]='.';
	i=13;	
	while(i>0 && n>=0)///
	{
		balanceprint[i]=balancecopy[n];
		n--;
		i--;
	}
	
	//printf("balance is %ld\n",balance);
	//add ',' and convert to "?????"
	if(transfer>=100000||transfer<=-100000)// add ','
	{
		amount[0]=amount[1];
		amount[1]=amount[2];
		amount[2]=amount[3];
		amount[3]=amount[4];
		amount[4]=amount[5];
		amount[5]=amount[6];
		amount[6]=',';
	}
	if(balance>=100000||balance<=-100000)
	{
		balanceprint[0]=balanceprint[1];
		balanceprint[1]=balanceprint[2];
		balanceprint[2]=balanceprint[3];
		balanceprint[3]=balanceprint[4];
		balanceprint[4]=balanceprint[5];
		balanceprint[5]=balanceprint[6];
		balanceprint[6]=balanceprint[7];
		balanceprint[7]=',';
	}
        if(((*(struct Transact *)(elem->obj)).symbol)=='-') // add '()'
        {
            amount[0]='(';
            amount[13]=')';
        }
        if(balance<0)
        {
            balanceprint[14]=')';
            balanceprint[1]='(';
        }
	
	if (amount[2]!=' ')// for too big number
	{
		amount[13]='?';
		amount[12]='?';
		amount[11]='.';
		amount[10]='?';
		amount[9]='?';
		amount[8]='?';
		amount[7]='.';
		amount[6]='?';
		amount[5]='?';
		amount[4]='?';
		amount[3]='.';
		amount[2]='?';
		amount[1]=' ';
		amount[0]=' ';
	}	

	if(balance>=1000000000)//in this condition number still could not be too big since i just use long int
	{
		balanceprint[14]='?';
		balanceprint[13]='?';
		balanceprint[12]='.';
		balanceprint[11]='?';
		balanceprint[10]='?';
		balanceprint[9]='?';
		balanceprint[8]='.';
		balanceprint[7]='?';
		balanceprint[6]='?';
		balanceprint[5]='?';
		balanceprint[4]='.';
		balanceprint[3]='?';
		balanceprint[2]=' ';
		balanceprint[1]=' ';
		balanceprint[1]=' ';
	}
	printf("| %s | %s| %s|%s |\n",date,desc,amount,balanceprint);//
	
    }
    //fprintf(stdout, "\n");
}



int main(int argc, char *argv[])//need to make sure the format is right!!!!!!
{
	My402List H;
	if (!My402ListInit(&H)){
		perror("My402ListInit failed");	
	};	
	//int n=0;//for all "for"
	//int l; 
	char transf[MAXLENGTH];
	FILE *fp;
	
	if(argc==3)//2 argment
	{//open this file		
		//char *eof;
        if (strncmp("sort",(*(argv+1)),5)!=0) {
            fprintf(stderr, "(Malformed command) Command form: ./warmup1 sort [tfile]\n");
            return FALSE;
        }
        //if(strncmp)
		if ((fp=fopen(*(argv+2),"r"))==NULL)//open file
		{
			perror(*(argv+2));
			return 1;
		}
	}
	else if(argc==2)//1 argment
	{
        if (strncmp("sort",(*(argv+1)),5)!=0) {
            fprintf(stderr, "(Malformed command) Command form: ./warmup1 sort [tfile]\n");
            return FALSE;
        }
		fp=stdin;
        //printf("Please input transaction records:\n");
	}
	
    else// other condition
	{
		fprintf(stderr, "(Malformed command) Command form: ./warmup1 sort [tfile]\n");
		return FALSE;
	}
	
		while((/*eof=*/fgets(transf,MAXLENGTH,fp))!=NULL&&(strlen(transf)!=1))	
		{	
			//printf("%s\n",transf);
            //consider the format
            if (strlen(transf)>1024) {
                fprintf(stderr, "Input file is not in the right format: too long input\n");
                return FALSE;
            }
            if(transf[0]!='+'&&transf[0]!='-')
            {
                fprintf(stderr, "Input file is not in the right format: 1st char should be '+' or '-'\n");
                return FALSE;
            }
            if (transf[1]!='\t') {
                fprintf(stderr, "Input file is not in the right format: data should be parsed by '\t'\n");
                return FALSE;
            }
            int l=2;
            int numcal=1;
            while(transf[l]!='\t') {
                if (!isdigit(transf[l])||numcal>=11) {
                    fprintf(stderr, "Input file is not in the right format: Wrong number in time stamp field\n");
                    return FALSE;
                }
                l++;
                numcal++;
            }
           /* if (transf[l]!='\t') {
                printf("input file is not in the right format\n");
                return FALSE;
            }*/
            l++;
            numcal=1;
            while(transf[l]!='.'){
                if (!isdigit(transf[l])||numcal>=7) {
                    fprintf(stderr, "Input file is not in the right format: Wrong number in amount field\n");
                    return FALSE;
                }
                l++;
                numcal++;
            }
            l++;
            if (!isdigit(transf[l])) {
                fprintf(stderr, "Input file is not in the right format: Wrong number in amount field\n");
                return FALSE;
            }
            l++;
            if (!isdigit(transf[l])) {
                fprintf(stderr, "Input file is not in the right format: Wrong number in amount field\n");
                return FALSE;
            }
            l++;
            if (transf[l]!='\t') {
                if (isdigit(transf[l])) {
                    fprintf(stderr, "Input file is not in the right format: Wrong number in amount field\n");
                }
                else
                {fprintf(stderr, "Input file is not in the right format: data should be parsed by '\t'\n");}
                return FALSE;
            }
            l++;
           /* if (transf[l]==' '||transf[l]=='\0') {
                printf("Input file is not in the right format: a transaction description must not be empty\n");
                return FALSE;
            }*/
            while (transf[l]!='\0') {
                if (transf[l]=='\t') {
                    {fprintf(stderr, "Input file is not in the right format: too many fields\n");}
                    return FALSE;
                }
                l++;
            }// print out error message
            
            
            
            
            
			struct Transact *p=(struct Transact *)malloc(sizeof(struct Transact));//every time redefine a struct
			//int s;
		
			char *start_ptr=transf;
			char *tab_ptr=strchr(start_ptr,'\t');
			if(tab_ptr!=NULL){
				*tab_ptr++='\0';
			}
			(*p).symbol=transf[0];// store 1st elem//maybe wrong!!!
			start_ptr=tab_ptr;
			tab_ptr=strchr(start_ptr,'\t');
			if (tab_ptr!=NULL){
				*tab_ptr++='\0';
			}
			strncpy((*p).date,start_ptr,sizeof((*p).date));
            time_t cur_time=time(0);
            time_t list_time=atoi((*p).date);
            if (list_time>cur_time) {
                fprintf(stderr, "Input file is not in the right format: tansaction time stamp is in the future\n");
                return FALSE;
            }
            
            
			start_ptr=tab_ptr;
			tab_ptr=strchr(start_ptr,'\t');
			if (tab_ptr!=NULL){
				*tab_ptr++='\0';
			}
			strncpy((*p).amount,start_ptr,sizeof((*p).amount));
			
            start_ptr=tab_ptr;
			tab_ptr=strchr(start_ptr,'\n');
			if (tab_ptr!=NULL){
				*tab_ptr++='\0';
			}
            while(*start_ptr==' ')
            {
                start_ptr++;
            }
			strncpy((*p).descrip,start_ptr,sizeof((*p).descrip));
            if (strlen((*p).descrip)==0) {
                fprintf(stderr, "Input file is not in the right format: description field is empty or only consist of ' '\n");
                return FALSE;
            }
						My402ListAppend(&H,p);

		}// read and store
		
		if(argc==3)// for open file condition still need to close
		{
			fclose(fp);
		}		
//compare if identical time stamp



//store them into linklist
	
//sort
//could not be empty
    if (My402ListEmpty(&H)) {
        fprintf(stderr, "Empty input file: a valid file contains at least one transaction\n");
        return FALSE;
    }
//check is there any identical time stamp?
    My402ListElem *elem=NULL;
    My402ListElem *comp=NULL;
    int idenum=0;
    for (elem=My402ListFirst(&H); elem!=NULL; elem=My402ListNext(&H,elem)) {
        idenum=0;
        for (comp=My402ListFirst(&H); comp!=NULL; comp=My402ListNext(&H,comp)) {
            if (!strncmp((*(struct Transact *)(elem->obj)).date,(*(struct Transact *)(comp->obj)).date,11)) {
                idenum++;
            }
        }
        if(idenum>1)
                {
                    fprintf(stderr, "Input file is not in the right format: Identical transaction time stamp\n");
                    return FALSE;
                }

    }
    
    
BubbleSortForwardList(&H,My402ListLength(&H));
//output pay attention to the format string?
printf("+-----------------+--------------------------+----------------+----------------+\n");
printf("|       Date      | Description              |         Amount |        Balance |\n");
printf("+-----------------+--------------------------+----------------+----------------+\n");
PrintTestList(&H, My402ListLength(&H));
//destroy everything
printf("+-----------------+--------------------------+----------------+----------------+\n");
My402ListUnlinkAll(&H);
return TRUE;
}


