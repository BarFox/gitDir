/*
 ** doctor
 */
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <netdb.h>
#define MYPORT1 "41070" // the port 1 users will be connecting to
#define MYPORT2 "42070" // the port 2 users will be connecting to
#define MAXBUFLEN 100

// get sockaddr, IPv4 or IPv6:
void *get_in_addr(struct sockaddr *sa)
{
    if (sa->sa_family == AF_INET) {
        return &(((struct sockaddr_in*)sa)->sin_addr);
    }
    return &(((struct sockaddr_in6*)sa)->sin6_addr);
}

int main(void)
{
    int sockfd;
    struct addrinfo hints, *servinfo, *p;
    int rv;
    int numbytes;
    struct sockaddr_storage their_addr;
    char buf[MAXBUFLEN];
    socklen_t addr_len;
    char s[INET6_ADDRSTRLEN];
    //read doc1.txt
    FILE *file_1;
    char doc[3][2][20];
    int i,j;
    //compare and send
   // struct sockaddr remaddr;
    
    memset(&hints, 0, sizeof hints);
    hints.ai_family = AF_UNSPEC; // set to AF_INET to force IPv4
    hints.ai_socktype=SOCK_DGRAM;
    hints.ai_flags = AI_PASSIVE; // use my IP
    
    //fork() start
    pid_t forkid;
    if((forkid=fork())<0)
    {
        perror("doctor: fork");
    }
    if(forkid==0)//for different progress using different port number
    {
        if ((rv = getaddrinfo(NULL, MYPORT1, &hints, &servinfo)) != 0) {//different
            fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
            return 1;
        }
    }
    else
    {
        if ((rv = getaddrinfo(NULL, MYPORT2, &hints, &servinfo)) != 0) {//different
            fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
            return 1;
        }
    }
    
    // loop through all the results and bind to the first we can
    for(p = servinfo; p != NULL; p = p->ai_next) {
        if ((sockfd = socket(p->ai_family, p->ai_socktype,
                             p->ai_protocol)) == -1) {
            perror("doctor: socket");
            continue;
        }
        if (bind(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
            close(sockfd);
            perror("doctor: bind");
            continue;
        }
        break;
    }
    if (p == NULL) {
        fprintf(stderr, "doctor: failed to bind socket\n");
        return 2;
    }
    freeaddrinfo(servinfo);
    
// IP address and port number
    addr_len = sizeof their_addr;

    char hostname[20]="localhost";
    struct hostent *ipaddress;
    struct sockaddr_in my_addr;
    int addrlen=sizeof(struct sockaddr);
    struct in_addr **addr_list;
    ipaddress=gethostbyname(hostname);//IP address
    addr_list = (struct in_addr **)ipaddress->h_addr_list;
    getsockname(sockfd,(struct sockaddr *)&my_addr, (socklen_t *)&addrlen);//socket
    
    if(forkid==0)//for different progress print different
    {
        printf("Phase 3: Doctor 1 has a static UDP port %d and IP address %s\n",ntohs(my_addr.sin_port), inet_ntoa(*addr_list[0]));//diferent
    }
    else
    {
        printf("Phase 3: Doctor 2 has a static UDP port %d and IP address %s\n",ntohs(my_addr.sin_port), inet_ntoa(*addr_list[0]));//diferent

    }
    
   //read doc.txt
    if(forkid==0)//for different progress read different
    {
        file_1=fopen("doc1.txt","r");//diferent
    }
    else
    {
        file_1=fopen("doc2.txt","r");//diferent
    }
    for(i=0;i<3;i++)
    {
        for(j=0;j<2;j++)
        {
           fscanf(file_1,"%s",doc[i][j]);
        }
    }
    fclose(file_1);
    
   while(1){//to handle 2 patient    //diferent
        if ((numbytes = recvfrom(sockfd, buf, MAXBUFLEN-1 , 0,
                             (struct sockaddr *)&their_addr, &addr_len)) == -1) {
            perror("recvfrom");
            exit(1);
        }
      
        //get patient port number
        struct sockaddr_in sins;
        memcpy(&sins, &their_addr, sizeof(sins));
        if(forkid==0)//for different progress print different
        {
            printf("Phase 3: Doctor 1 receives the request from the patient with port number %d the insurance plan %s \n",  ntohs(sins.sin_port), buf);//diferent
        }
        else
        {
            printf("Phase 3: Doctor 2 receives the request from the patient with port number %d the insurance plan %s \n",  ntohs(sins.sin_port), buf);//diferent
    }
    //compare doc and send
        for(i=0;i<3;i++)
        {
            if(strcmp(doc[i][0],buf)==0)
            {
                sendto(sockfd,doc[i][1],strlen(doc[i][1]),0,(struct sockaddr *)&their_addr, addr_len);
                if(forkid==0)//for different progress print different
                {
                    printf("Phase 3: Doctor 1 has sent estimated price %s$ to patient with port number %d\n", doc[i][1], ntohs(sins.sin_port));//diferent
                }
                else
                {
                    printf("Phase 3: Doctor 2 has sent estimated price %s$ to patient with port number %d\n", doc[i][1], ntohs(sins.sin_port));//diferent
                }
            }
        }
       
   }
    close(sockfd);
    return 0;
}
