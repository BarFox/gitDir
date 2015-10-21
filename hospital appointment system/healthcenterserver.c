
//healthcenterserver.c

#include<stdio.h>
#include<string.h>
#include<stdlib.h>
#include<unistd.h>
#include<errno.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<netinet/in.h>
#include<netdb.h>
#include<arpa/inet.h>
#include<sys/wait.h>
#include<signal.h>

#define PORT "21070"//the port number of server side
#define BACKLOG 10//the length of waitinglist

//(Beej's book) reap all dead processes
void sigchld_handler(int s)
{
    while(waitpid(-1, NULL, WNOHANG) > 0);
}

// (Beej's book) get sockaddr, IPv4 or IPv6
void *get_in_addr(struct sockaddr *sa)
{
    if (sa->sa_family == AF_INET) {
        return &(((struct sockaddr_in*)sa)->sin_addr);
    }
    return &(((struct sockaddr_in6*)sa)->sin6_addr);
}

int main(void)
{
    int sockfd,new_fd;
    struct addrinfo hints, *servinfo, *p;
    struct sockaddr storage, their_addr;
    socklen_t sin_size;
    struct sigaction sa;
    int yes=1;
    char s[INET6_ADDRSTRLEN];
    int rv;
    memset(&hints,0,sizeof hints);
    hints.ai_family=AF_INET;
    hints.ai_socktype=SOCK_STREAM;
    hints.ai_flags=AI_PASSIVE;//use my IP
//for printing local IP and port number
    //char ipstr[INET6_ADDRSTRLEN]="nunki.usc.edu";//to get the IP address of patient
    char ipstr[INET6_ADDRSTRLEN]="localhost";
    char ipstr_1[INET6_ADDRSTRLEN]="localhost";
    //char ipstr_1[INET6_ADDRSTRLEN]="nunki.usc.edu";//to get the IP address of server itself
    struct hostent *he;
    struct in_addr **addr_list;
    int ii;
//authenticate | receive username and passpord from patient
    char buf[100];
    int numbytes;
//read users.c and store
    FILE *file_1;
    char users[2][2][20];
    int i,j;//nearly every circuit I use this
// read and store avalibilities.txt
    FILE *file_2;
    char availabilities[6][5][20];
//if the received username is in users.txt? compare
    char *usercom[3];
//receive "available" and compare
    char avail[15], availcom[15]="available";
    int availen;
// send avail table(list of available appointments)
    char availtable[200]=" ";//the string used to store the
// receive appoint number the patient chooses
    char appointsen[15];
    int appointnum;
//to store the used appointment; compare
    FILE *file_3;
    int appointres;
//send doctor message corresponding to the appointment number
    char doctormes[15];
//to get the port number and IP address of patient; print
    struct sockaddr_in peer_addr;
    int addrlen=sizeof(struct sockaddr);
    char patient_ip[20];
    
//getaddrinfo()
    if ((rv = getaddrinfo(NULL, PORT, &hints, &servinfo)) != 0) {
        fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
        return 1;
    }
// loop through all the results and bind to the first we can
    for(p = servinfo; p != NULL; p = p->ai_next) {
        if ((sockfd = socket(p->ai_family, p->ai_socktype,
			 p->ai_protocol)) == -1) {
            perror("server: socket");
            continue;
        }
        if (setsockopt(sockfd, SOL_SOCKET, SO_REUSEADDR, &yes,
		   sizeof(int)) == -1) {
            perror("setsockopt");
            exit(1);
        }
        if (bind(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
            close(sockfd);
            perror("server: bind");
            continue;
        }
        break;
    }
    if (p == NULL) {
        fprintf(stderr, "server: failed to bind\n");
        return 2;
    }

//users.c read and store
    file_1=fopen("users.txt","r");
    for(i=0;i<2;i++)
        {
            for(j=0;j<2;j++)
                {
                    fscanf(file_1,"%s",users[i][j]);
                }
        }
        fclose(file_1);
    
// read and store avalibilities.txt
    file_2=fopen("availabilities.txt","r");
    for(i=0;i<6;i++)
    {
        for(j=0;j<5;j++)
        {
            fscanf(file_2,"%s", availabilities[i][j]);
        }
    }
    
//availnum.txt are used to store used appointment, and at the beginning of server, its content should be initialed
    file_3=fopen("availnum.txt","w");
    fprintf(file_3,"%d",9);// let 9 represent the error number
    fclose(file_3);
//listen
    if (listen(sockfd, BACKLOG) == -1) {
        perror("listen");
        exit(1);
    }
    
    sa.sa_handler = sigchld_handler; // reap all dead processes(Beej's book)
    sigemptyset(&sa.sa_mask);
    sa.sa_flags = SA_RESTART;
    if (sigaction(SIGCHLD, &sa, NULL) == -1) {
        perror("sigaction");
        exit(1);
    }
    
// convert the IP to a string and print it(Beej P73)
    printf("Phase 1: The Health Center Server has port number %s and IP address ",PORT);
    he=gethostbyname(ipstr_1);
    addr_list = (struct in_addr **)he->h_addr_list;
    for(ii = 0; addr_list[ii] != NULL; ii++) {
        printf("%s ", inet_ntoa(*addr_list[ii]));
    }//print IP
    printf("\n");

    freeaddrinfo(servinfo);// all done with this structure

    while(1) { // main accept() loop
        sin_size = sizeof their_addr;
        new_fd = accept(sockfd, (struct sockaddr *)&their_addr, &sin_size);
        if (new_fd == -1) {
  //perror("accept");
            continue;
        }

//*******************************************
//listen and if get connected, create child progress
        if (!fork()) { // this is the child process
            close(sockfd); // child doesn't need the listener

//authenticate | receive username and passpord from patient
            if ((numbytes = recv(new_fd, buf, 99, 0)) == -1) {
                perror("recv");
                exit(1);
            }
            buf[numbytes] = '\0';

//if the received username is in users.txt? compare
        usercom[0]=strtok(buf," ");//the message received from patient
        usercom[1]=strtok(NULL," ");
        usercom[2]=strtok(NULL," ");
        printf("Phase 1: The HeaLth Center Server has received request from a patient with username %s and password %s\n", usercom[1], usercom[2]);
        if(((strcmp(usercom[1],users[0][0])) || (strcmp(usercom[2],users[0][1])))==0)
        {
	        if (send(new_fd, "success", 40, 0) == -1)
            perror("send");
            printf("Phase 1: The Health Center Server sends the response success to patient with user name %s\n",usercom[1]);
        }
       
        else if (((strcmp(usercom[1],users[1][0]))|| (strcmp(usercom[2],users[1][1])))==0)
        {
            if (send(new_fd, "success", 40, 0) == -1)
			perror("send");
            printf("Phase 1: The Health Center Server sends the response success to patient with user name %s\n",usercom[1]);
        }
        
        else
        {
            if (send(new_fd, "failure", 40, 0) == -1)
			perror("send");
            printf("Phase 1: The Health Center Server sends the response failure to patient with user name %s\n",usercom[1]);
        }

//phase 2
//receive "available" and compare
//according to the latest describtion, I need to modify this following progress to go on parallelly with the phase 1,and use a "if" to determine go in which one.????????
//compare the string received is "available' or not, if so send list of available appointments, if not, quit
        if ((availen = recv(new_fd, avail, 15, 0)) == -1) {
            perror("recv");
            exit(1);
        }
        if(availen==0){
            close(new_fd);
            exit(0);//if not the same, server will also drop
        }
            avail[availen] = '\0';

// form the table that will be sent; for now I will just consider 2 patient condition
        file_3=fopen("availnum.txt","r");//the already reserved appointment is stored here; read this file to judge whether a certain line has been reserved or not
        fscanf(file_3,"%d", &appointres);
        fclose(file_3);
        for (i=0; i<6; i++) {
            for (j=0; j<=2; j++) {
                if(i!=(appointres-1)){//to judge whether a certain line has been reserved or not, if reserved, do not read into string "availtable"
                strcat(availtable,availabilities[i][j]);
                strcat(availtable," ");
                }
            }
        }
//get patient IP and port number; using getpeername() to get port number , using gethostbyname() get IP address
        if(getpeername(new_fd, (struct sockaddr *)&peer_addr, (socklen_t *)&addrlen)<0)
            exit(0);
            struct sockaddr_in *s=(struct sockaddr_in *)&peer_addr;
            char peer_IP[INET6_ADDRSTRLEN];
            inet_ntop(AF_INET,&s->sin_addr,peer_IP,sizeof peer_IP);
            // he=gethostbyname(ipstr);
       // addr_list = (struct in_addr **)he->h_addr_list;
        
        //get Ip
            char theirIP[INET6_ADDRSTRLEN];
            inet_ntop(their_addr.sa_family, get_in_addr((struct sockaddr *)&their_addr), theirIP, sizeof theirIP);
            
            
        // send the string "availatable"
        if(strcmp(avail,availcom)==0)
        {
            printf("Phase 2: The Health Center Server, receives a request for available time slots from patients with port number %d and IP address %s\n", ntohs(s->sin_port),theirIP);
     /*       for(ii = 0; addr_list[ii] != NULL; ii++) {
                printf("%s ", inet_ntoa(*addr_list[ii]));
            }
            printf("\n");//ii is used again here!!!!
       */

            if (send(new_fd, availtable, 200, 0) == -1)
                perror("send");
        }
        printf("Phase 2: The Health Center Server sends available time slots to patient with username %s\n",usercom[1]);
//receive the appointment number the patient chooses
        if ((recv(new_fd, appointsen, 15, 0)) == -1) {
            perror("recv");
            exit(1);
        }
        strtok(appointsen," ");
        appointnum=atoi(strtok(NULL," "));
        printf("Phase 2: The Health Center Server receives a request for appointment %d from patient with port number %d and username %s\n",appointnum, ntohs(peer_addr.sin_port), usercom[1]);
        
// open availnum.txt and compare if this appointment has been used（since patients could enter the server at any time, maybe the other patient enters the server and alters the researved number after available sent)
        file_3=fopen("availnum.txt","r");
        fscanf(file_3,"%d", &appointres);
        fclose(file_3);
        if (appointres!=appointnum) {//put the appointment number the patient chooses into availnum.txt
            file_3=fopen("availnum.txt","w");
            fprintf(file_3,"%d",appointnum);
            fclose(file_3);
            strcpy(doctormes,availabilities[appointnum-1][3]);
            strcat(doctormes," ");
            strcat(doctormes,availabilities[appointnum-1][4]);
            if (send(new_fd, doctormes, 15, 0) == -1)
                perror("send");//wait patient to terminate the connection
            printf("Phase 2: The Health Center Server confirms the following appointment %d to patient with username %s\n",appointnum, usercom[1]);
        }
        else{
            if (send(new_fd, "notavailable", 15, 0) == -1)
                perror("send");//wait patient to terminate the connection
            printf("Phase 2: The Health Center Server rejects the following appointment %d  to patient with username %s\n",appointnum, usercom[1]);
        }
        
    //end of child progress
            close(new_fd);//could the server side close the socket?????????????
            exit(0);
        }
        close(new_fd); // parent doesn't need this
    }
    return 0;
}


