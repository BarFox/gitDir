#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/time.h>
#include <pthread.h>
#include <unistd.h>
#include <signal.h>
#include "my402list.c"
//#include "warmup2.h"
#define lambda_arg 2
#define mu_arg 0.35
#define r_arg 4
#define B_arg 10
#define P_arg 3
#define n_arg 20//change it !!
struct argstruct
{
    //arrival time
    double lambda;
    //service time
    double mu;
    //token arrival time
    double r;
    //token bucket size
    int B;
    //token requirement of each packet
    int P;
    //packet num
    int n;
};
struct Packet//need to handle
{
    //interarrival time
    double inter_t;
    //token requirement
    int token;
    //service time
    double service_t;
    //packet num
    int packetnum;
    //timestamp
    //struct timeval timestamp[6];
	double timestamp[7];
};


void *packet(void *);
void *token(void *);
void *sever1(void *);
void *sever2(void *);
void *monitor();
//general facilities
My402List Q1,Q2;
My402List arrival,Q1s,Q2s,S1s,S2s;//record all served packet
int pdrop=0;
int preceive=0;
int tdrop=0;
int treceive=0;

int fin_sign=0;//if got a fin_sign server will terminate it self
int openfile=0;//if file opened it should be closed
int all_packet=0;//when all packet has been generated , change this sign to be 1 and combine with the next sign, tell server it could terminate at convinient time
int last_packet=0;//record the number of last packet that has not been dropped, when server finish servering this packet, it could quit
//int num_p;
//bucket
int bucket=0;//change
int inputmode=0;//D or T mode
FILE *fp;//tracefile
//for syn
pthread_mutex_t m;
pthread_cond_t queue;
pthread_t packet_thr, token_thr, sever1_thr, sever2_thr, monitor_thr;
sigset_t set;
//pthread_cond_t serverQ=PTHREAD_MUTEX_INITIALIZER;

struct timeval tv_begin;

//function define
double time_p();

int main(int argc, char *argv[])
{
    //first handle the input argment;
    struct argstruct arg={lambda_arg, mu_arg, r_arg,B_arg,P_arg,n_arg};
	int argcnum;    
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-mu",10)==0){
			arg.mu=atof(argv[argcnum+1]);	
		}
	}
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-lambda",10)==0){
			arg.lambda=atof(argv[argcnum+1]);	
		}
	}
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-r",10)==0){
			arg.r=atof(argv[argcnum+1]);	
		}
	}
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-B",10)==0){
			arg.B=atoi(argv[argcnum+1]);	
		}
	}
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-P",10)==0){
			arg.P=atoi(argv[argcnum+1]);	
		}
	}
	for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-n",10)==0){
			//printf("%d\n",atoi(argv[argcnum+1]));
			arg.n=atoi(argv[argcnum+1]);	
			//arg.n=3;
		}
	}
        for(argcnum=1;argcnum<argc;argcnum++){
		if(strncmp(argv[argcnum],"-t",10)==0){
			//arg.P=atoi(argv[argcnum+1]);	
			openfile=1;
			if ((fp=fopen(argv[argcnum+1],"r"))==NULL)//open file
			{
			perror(argv[argcnum+1]);
			return 1;
			}
			char transf1[1024];
			fgets(transf1,1024,fp);
			arg.n=atoi(transf1);
			inputmode=1;//set to T mode
			//printf("fileopen\n");

		}
	}
   
    
    //general facilities initiate
    //My402List Q1,Q2;
    if (!My402ListInit(&Q1)){
        perror("Q1 initiation failed");//return?
    };
    if (!My402ListInit(&Q2)){
        perror("Q2 initiation failed");
    };
    //general facilities
    if (!My402ListInit(&Q1s)){
        perror("initiation failed");//return?
    };
    if (!My402ListInit(&Q2s)){
        perror("initiation failed");//return?
    };
    if (!My402ListInit(&S1s)){
        perror("initiation failed");//return?
    };
    if (!My402ListInit(&S2s)){
        perror("initiation failed");//return?
    };
    if (!My402ListInit(&arrival)){
        perror("initiation failed");//return?
    };
    
    gettimeofday(&tv_begin,NULL);//get the begin time
    printf("00000000.000ms: emulation begins\n");
    //add mask for ctrl+c
	sigemptyset(&set);
	sigaddset(&set,SIGINT);
	sigprocmask(SIG_BLOCK,&set,0);

    //thread create
    pthread_create(&packet_thr,0,packet,&arg);
    pthread_create(&token_thr,0,token,&arg);
    pthread_create(&sever1_thr,0,sever1,&arg);
    pthread_create(&sever2_thr,0,sever2,&arg);//error handle
	pthread_create(&monitor_thr,0,monitor,0);//error handle
    
    //thread join
    pthread_join(packet_thr,0);
    pthread_join(token_thr,0);
    pthread_join(sever1_thr,0);
    pthread_join(sever2_thr,0);//didnot handle if fail
	pthread_join(monitor_thr,0);//didnot handle if fail
	double time_end=time_p();//the time end ex
	printf("%012.03fms: emulation ends\n",time_end);//need to handle never print
	if(openfile==1){	
		fclose(fp);
	}//close file// need a condition
	My402ListUnlinkAll(&Q1);
	My402ListUnlinkAll(&Q2);
    
    //statistic
    
    
    double Ppdrop=pdrop/(preceive+pdrop);
    double Ptdrop=tdrop/(treceive+tdrop);
    
    printf("\nStatistics:\n");
    printf("average packet inter-arrival time = \n");
    printf("average packet service time = \n");
    printf("\n");
    printf("average number of packets in Q1 = \n");
    printf("average number of packets in Q2 = \n");
    printf("average number of packets in S1 = \n");
    printf("average number of packets in S2 = \n");
    printf("\n");
    printf("average time a packet spent in system = \n");
    printf("standard deviation for time  spent in system = \n");
    printf("\n");
    printf("token drop probability = %.6g\n",Ptdrop);
    printf("packet drop probability = \n");
    return 0;
}

void *packet(void *arg)
{
    struct argstruct *arg_p=(struct argstruct*) arg;
    double time_back=0;
    //generate packet
    int i;
   // long int inter_t;
    double time_e2=time_p();//the first packet handle
	sigprocmask(SIG_BLOCK,&set,0);
	//pthread_setcanceltype(PTHREAD_CANCEL_DEFFRRED);
	
    for(i=1;i<=arg_p->n;i++)
    {
        pthread_setcancelstate(PTHREAD_CANCEL_DISABLE,0);
	
        struct Packet *p=(struct Packet *)malloc(sizeof(struct Packet));
        //handle packet
	if(inputmode==0)
      	{
		double time_e1=time_p();//the time end ex
        pthread_setcancelstate(PTHREAD_CANCEL_ENABLE,0);
        	if ((time_e1-time_e2)<(1/arg_p->lambda)*1000) {
           		 usleep(((1/arg_p->lambda)*1000-(time_e1-time_e2))*1000);//is this OK?
        	}
        	time_e2=time_p();//the time begin ex
        	//get time
		
		p->packetnum=i;
        	p->token=arg_p->P;
       
        	//p->inter_t=inter_t;
        	p->service_t=1/arg_p->mu;
  	}
	else if(inputmode==1)// need to handle later!!!
	{
		
		char transf[1024];
		fgets(transf,1024,fp);
		//printf("%s\n",transf);
		char intert[10];
		char tokenum[10];
		char servert[10];
		int tra;
		int set=0;
		for(tra=0;transf[tra]!=' '&&transf[tra]!='\t';tra++){
			intert[set]=transf[tra];
			set++;		
		}
		intert[set]='\0';
		//set=0;
		while(transf[tra]==' '||transf[tra]=='\t'){
		tra++;		
		}
		//token[0]=transf[tra];
		set=0;
		for(;transf[tra]!=' '&&transf[tra]!='\t';tra++){
			tokenum[set]=transf[tra];
			set++;		
		}
		tokenum[set]='\0';
		while(transf[tra]==' '||transf[tra]=='\t'){
		tra++;		
		}
		set=0;
		for(;transf[tra]!=' '&&transf[tra]!='\t'&&transf[tra]!='\0';tra++){
			servert[set]=transf[tra];
			set++;		
		}
		servert[set]='\0';
		p->packetnum=i;
        	p->token=atoi(tokenum);
       
        	p->inter_t=atof(intert)/1000;
        	p->service_t=atof(servert)/1000;//
		//printf("%d %f %f\n",p->token,p->inter_t,p->service_t);

		double time_e1=time_p();//the time end ex
        pthread_setcancelstate(PTHREAD_CANCEL_ENABLE,0);
        	if ((time_e1-time_e2)<p->inter_t) {
           		 usleep(((p->inter_t)*1000-(time_e1-time_e2))*1000);//is this OK?
        	}
       		time_e2=time_p();//the time begin ex
        	//get time
	}
	
	//usleep(10);
	pthread_setcancelstate(PTHREAD_CANCEL_DISABLE,0);
        pthread_mutex_lock(&m);//
	if (i==arg_p->n)
	{
		all_packet=1;
        
	}// if all packet arrived, set all_packet to 1
        
        double time_1=time_p();//the time p arrive
        
        if (p->token>arg_p->B) {//if more than B just thro out the packet
    		printf("%012.03f ms: p%d arrives, needs %d tokens, inter-arrival time = %.3fms, dropped\n",time_1,(*p).packetnum,(*p).token,time_1-time_back);
            (void)My402ListPrepend(&arrival, p);
            pdrop++;//drop+1
            pthread_cond_broadcast(&queue);//if this is the last packet it need to wake up server
            pthread_mutex_unlock(&m);//
		continue;
		
        }
	printf("%012.03f ms: p%d arrives, needs %d tokens, inter-arrival time = %.3fms\n",time_1,(*p).packetnum,(*p).token,time_1-time_back);
        (void)My402ListPrepend(&arrival, p);
        preceive++;
        (*p).timestamp[6]=time_1;
	last_packet=(*p).packetnum;//always set last_packet= the undropped packet
     	time_back=time_1;//make a copy
        if (My402ListEmpty(&Q1)) {
            	//printf("Q1 is empty\n");
            	if (p->token<=bucket) {
                double time_2=time_p();//the time enter Q1
		(*p).timestamp[0]=time_2;//record time
                printf("%012.03f ms: p%d enters Q1\n",time_2,(*p).packetnum);
                (void)My402ListPrepend(&Q1, p);
                bucket=bucket-((*p).token);//reduce Bucket
                My402ListUnlink(&Q1,My402ListLast(&Q1));
        
                double time_3=time_p();//the time get out Q1
		(*p).timestamp[1]=time_3;//record time
                printf("%012.03f ms:(packet) p%d leaves Q1, time in Q1 = %.3fms, token bucket now has %d token\n",time_3,(*p).packetnum,(*p).timestamp[1]-(*p).timestamp[0],bucket);
                (void)My402ListPrepend(&Q2, p);
                double time_4=time_p();//the time enter Q2
		(*p).timestamp[2]=time_4;//record time
                printf("%012.03f ms:(packet) p%d enters Q2\n",time_4,(*p).packetnum);
                
           	 }
           	 else
            	{
                double time_5=time_p();//the time enter Q1
		(*p).timestamp[0]=time_5;//record time
                printf("%012.03f ms: p%d enters Q1\n",time_5,(*p).packetnum);
                (void)My402ListPrepend(&Q1, p);
           	 }
        }
        else
        {
		double time_6=time_p();//the time enter Q1
		(*p).timestamp[0]=time_6;//record time
                printf("%012.03f ms: p%d enters Q1\n",time_6,(*p).packetnum);
            	(void)My402ListPrepend(&Q1, p);
        }
        pthread_cond_broadcast(&queue);
        pthread_mutex_unlock(&m);//need to handle
       // usleep((1/arg_p->lambda)*1000000);
        //printf("%f\n",arg_p->lambda);
        double time_0=time_1;
        //tv1=tv2;
	pthread_setcancelstate(PTHREAD_CANCEL_ENABLE,0);
	//printf("the end of packet\n");
	usleep(10);
    }
    printf("the end of packet\n");
    return (void*)0;
}

void *token(void *arg)// how to stop token?
{
    struct argstruct *arg_p=(struct argstruct*) arg;
    struct timeval tv1,tv2;
  //  long int inter_t;
   // gettimeofday(&tv1,NULL);
    int toc=0;
	double time_e2=time_p();
	sigprocmask(SIG_BLOCK,&set,0);
	//pthread_setcanceltype(PTHREAD_CANCEL_DEFFRRED);
	
    while (1) {//how to end?
	pthread_setcancelstate(PTHREAD_CANCEL_DISABLE,0);        
	toc++;
	double time_e1=time_p();
	pthread_setcancelstate(PTHREAD_CANCEL_ENABLE,0);
	if((time_e1-time_e2)<(1/arg_p->r)*1000000){
        	usleep(((1/arg_p->r)*1000-(time_e1-time_e2))*1000);//is this OK?
	}   
	time_e2=time_p();   
	
	usleep(10);
	pthread_setcancelstate(PTHREAD_CANCEL_DISABLE,0);  
	pthread_mutex_lock(&m);//
       /* while (My402ListEmpty(&Q2)) {//when to stop?
            pthread_cond_wait(&queue,&m);
        }*/
        //get time
        if((all_packet==1)&&(My402ListEmpty(&Q1)))
        {
            printf("token quit\n");
            pthread_mutex_unlock(&m);//need to handle
            return (void*) 0;
        }
        if (bucket<arg_p->B) {
            	bucket++;
		double time_5=time_p();//the time token arrive
        	printf("%012.03f ms: token t%d arrives, token bucket now has %d token\n",time_5,toc,bucket);
            treceive++;
        }//bucket++
	else{
		double time_5=time_p();//the time token arrive
		printf("%012.03f ms: token t%d arrives, dropped\n",time_5,toc);
        tdrop++;
	}
       
	
        //handle Q1
        while(1){//check if true to handle as many packet as possible
        if (My402ListEmpty(&Q1)) {//2 conditions to judge
            break;
        }
        struct Packet *tp=(struct Packet*)(My402ListLast(&Q1)->obj);
        //(*tp).timestamp[2].tv_sec
        if ((*tp).token<=bucket) {
            bucket=bucket-((*tp).token);
            My402ListUnlink(&Q1,My402ListLast(&Q1));//
                        //gettimeofday(&tv2,NULL);
            //(*tp).timestamp[2]=tv2;
            double time_1=time_p();//the time get out Q1
		(*tp).timestamp[1]=time_1;//record time
            printf("%012.03f ms:(t) p%d leaves Q1, time in Q1 = %.3f ms, token bucket now has %d token\n",time_1,(*tp).packetnum,(*tp).timestamp[1]-(*tp).timestamp[0],bucket);
            (void)My402ListPrepend(&Q2, tp);//the consequence is differ

            //gettimeofday(&tv2,NULL);
            //(*tp).timestamp[1]=tv2;
            double time_2=time_p();//the time enter Q2
		(*tp).timestamp[2]=time_2;//record time
            printf("%012.03f ms:(t) p%d enters Q2\n",time_2,(*tp).packetnum);
        }
        else
            {
                break;//if not enough token break
            }
        //send signal to server wake up
            
        }
        pthread_cond_broadcast(&queue);// ask server to start serve
        pthread_mutex_unlock(&m);//need to handle
	pthread_setcancelstate(PTHREAD_CANCEL_ENABLE,0);
	//printf("the end of token\n");
	usleep(10);// the only way i can think
    }
    
        return (void*) 0;
}

void *sever1(void *arg)// how to stop server?
{
    int last_serve=0;
    struct argstruct *arg_p=(struct argstruct*) arg;
    struct timeval tv1,tv2;
	struct Packet *tp;
  // long int inter_t;
    
    //usleep((1/arg_p->mu)*1000000);//is this OK?
    //gettimeofday(&tv1,NULL);
    //printf("%ld,%d\n",tv1.tv_sec,tv1.tv_usec);
	sigprocmask(SIG_BLOCK,&set,0);
    while(1){
        pthread_mutex_lock(&m);//
       
	if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server1 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	break;
	}//when to quit
        while (My402ListEmpty(&Q2)) {//when to stop?
            pthread_cond_wait(&queue,&m);
            if((last_serve>=last_packet) && (all_packet==1))
            {
                printf("inside server1 time to quit\n");
                fin_sign=1;//set end sign to be 1
                //pthread_cancel(packet_thr);
                //pthread_cancel(token_thr);
                //pthread_cancel(sever1_thr);
                //pthread_cancel(sever2_thr);
                pthread_cancel(monitor_thr);
                pthread_cond_broadcast(&queue);// ask server to start serve
                pthread_mutex_unlock(&m);//need to handle
                return (void*) 0;
                
            }

			if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server1 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	return (void*) 0;
	}
        }
	if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server1 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	break;
	}//when to quit
        while (!My402ListEmpty(&Q2)) {
                tp=(struct Packet*)(My402ListLast(&Q2)->obj);
                My402ListUnlink(&Q2,My402ListLast(&Q2));//
                //gettimeofday(&tv1,NULL);
           // char time_s[50];
            double time_1=time_p();//the time get out Q2
		(*tp).timestamp[3]=time_1;//record time
            printf("%012.03f ms: (S1)p%d leaves Q2, time in Q2 is %.3fms\n",time_1,(*tp).packetnum,(*tp).timestamp[3]-(*tp).timestamp[2]);
            //printf("packet leaves Q2,\n");
            last_serve=(*tp).packetnum;//record the packet last served
            double time_2=time_p();//the time enter S1
		(*tp).timestamp[4]=time_2;//record time
            printf("%012.03f ms: p%d begins service at S1, requesting %.0fms of service \n",time_2,(*tp).packetnum,(*tp).service_t*1000);
            pthread_mutex_unlock(&m);//need to handle
                usleep((*tp).service_t*1000000);
             pthread_mutex_lock(&m);
            double time_3=time_p();//the time leave S1
		(*tp).timestamp[5]=time_3;//record time
            printf("%012.03f ms: p%d departs from S1, service time = %.3fms,time in system = %.3fms\n",time_3,(*tp).packetnum,(*tp).timestamp[5]-(*tp).timestamp[4],(*tp).timestamp[5]-(*tp).timestamp[0]);  
        }
	
	//quit
	if(((tp->packetnum)>=last_packet) && (all_packet==1))
	{
		
		fin_sign=1;//set end sign to be 1
		//pthread_cancel(packet_thr);
		//pthread_cancel(token_thr);
		//pthread_cancel(sever1_thr);
		//pthread_cancel(sever2_thr);
		pthread_cancel(monitor_thr);
		pthread_cond_broadcast(&queue);// ask server to start serve
		pthread_mutex_unlock(&m);//need to handle
		break;
		
	}
	pthread_mutex_unlock(&m);//need to handle
	
    }
	printf("server1 quits\n");
    return (void*) 0;
}

void *sever2(void *arg)
{
      int last_serve=0;
    struct argstruct *arg_p=(struct argstruct*) arg;
    struct timeval tv1,tv2;
	struct Packet *tp;
   sigprocmask(SIG_BLOCK,&set,0);
    while(1){
	
        pthread_mutex_lock(&m);//
	if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server2 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	break;
	}//when to quit
        while (My402ListEmpty(&Q2)) {//when to stop?
		
            pthread_cond_wait(&queue,&m);
            if((last_serve>=last_packet) && (all_packet==1))
            {
                printf("inside server1 time to quit\n");
                fin_sign=1;//set end sign to be 1
                //pthread_cancel(packet_thr);
                //pthread_cancel(token_thr);
                //pthread_cancel(sever1_thr);
                //pthread_cancel(sever2_thr);
                pthread_cancel(monitor_thr);
                pthread_cond_broadcast(&queue);// ask server to start serve
                pthread_mutex_unlock(&m);//need to handle
                return (void*) 0;
                
            }

		if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server2 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	return (void*) 0;
	}
        }
	if (fin_sign==1){
	//pthread_cancel(monitor_thr);
	printf("server2 time to quit\n");
	pthread_mutex_unlock(&m);//need to handle
	break;
	}//when to quit
        while (!My402ListEmpty(&Q2)) {
            tp=(struct Packet*)(My402ListLast(&Q2)->obj);
            My402ListUnlink(&Q2,My402ListLast(&Q2));//
            
            double time_1=time_p();//the time get out Q2
		(*tp).timestamp[3]=time_1;//record time
            printf("%012.03f ms: (S1)p%d leaves Q2, time in Q2 is %.3fms\n",time_1,(*tp).packetnum,(*tp).timestamp[3]-(*tp).timestamp[2]);
            //printf("packet leaves Q2,\n");
            last_serve=(*tp).packetnum;//record the packet last served
            double time_2=time_p();//the time enter S1
		(*tp).timestamp[4]=time_2;//record time
            printf("%012.03f ms: p%d begins service at S2, requesting %.0fms of service\n",time_2,(*tp).packetnum,(*tp).service_t*1000);
            pthread_mutex_unlock(&m);//need to handle
            usleep((*tp).service_t*1000000);
            pthread_mutex_lock(&m);//when Q2 is empty m is lock forever
            double time_3=time_p();//the time leave S1
		(*tp).timestamp[5]=time_3;//record time
            printf("%012.03f ms: p%d departs from S2, service time = %.3fms,time in system = %.3fms\n",time_3,(*tp).packetnum,(*tp).timestamp[5]-(*tp).timestamp[4],(*tp).timestamp[5]-(*tp).timestamp[0]);
//printf("packet is %d all packet is %d\n",tp->packetnum,arg_p->n);
		//printf("the time stamp is %.3f %.3f %.3f %.3f %.3f %.3fms\n",(*tp).timestamp[0],(*tp).timestamp[1],(*tp).timestamp[2],(*tp).timestamp[3],(*tp).timestamp[4],(*tp).timestamp[5]);
            
            // when reach the end of here cancel other threads and end progress
        }
	
//quit
	if(((tp->packetnum)>=last_packet) && (all_packet==1))
	{
		
		
		fin_sign=1;
		//pthread_cancel(packet_thr);
		//pthread_cancel(token_thr);
		//pthread_cancel(sever1_thr);
		//pthread_cancel(sever2_thr);
		pthread_cancel(monitor_thr);
		pthread_cond_broadcast(&queue);// ask server to start serve
		pthread_mutex_unlock(&m);//need to handle
		break;
	}
	pthread_mutex_unlock(&m);//need to handle
    }
    printf("server2 quits\n");
    return (void*) 0;
}

void *monitor(){
	int sig;
	while (1){
		sigwait(&set,&sig);// no need for ever thread?????
		pthread_mutex_lock(&m);
		//statistic
		printf("ctrl+c catched\n");
		pthread_cancel(packet_thr);
		pthread_cancel(token_thr);
		//pthread_cancel(sever1_thr);
		//pthread_cancel(sever2_thr);
		fin_sign=1;//is this true?
		pthread_cond_broadcast(&queue);// ask server to start serve
		pthread_mutex_unlock(&m);
		//exit(0);
		break;
			
	}
	printf("monitor quits\n");
	return 0;
}

/*void *statistic(void *arg)
{
    return (void*) 0;
}*/

char * My402ListItoa(int i)// when i is too big the whole process could still run while the output is wrong
{
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


double time_p(){
    //char time[18];
    //char time_l[7];
    struct timeval tv1,tv2;
    gettimeofday(&tv1,NULL);
    tv2.tv_sec=tv1.tv_sec-tv_begin.tv_sec;
    tv2.tv_usec=tv1.tv_usec-tv_begin.tv_usec;
    if(tv2.tv_usec<0)
    {
        tv2.tv_sec--;
        tv2.tv_usec=tv2.tv_usec+1000000;
    }
    double p_int1=(double)tv2.tv_sec;
    double p_int2=(double)tv2.tv_usec;
    double print_int=p_int1*1000+p_int2/1000;
    return print_int;
    //printf("%ld,%d\n",tv2.tv_sec,tv2.tv_usec);
    //printf("%012.03f this is the double \n",print_int);
    //memset(time,0,sizeof(time));
    //strncat(time,My402ListItoa(tv2.tv_sec),10);
    //strncpy(time_l,,10);
    //strncat(time,".",3);
    //strncat(time,My402ListItoa(tv2.tv_usec),10);
    //printf("%s\n",time);
    
}



