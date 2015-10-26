/*
 * This program can automatically run osmocom cell_log and extracts useful information
 * 
 * usage: gcc osmocom_auto.c -o osmocom_auto 
 *        ./osmocom_auto num_of_scan
 * num_of_scan is the number of scans we want to do. It is set to 10 by default.
 * Each scan will take approximately 5 minutes.
 *
 * written by Liu Kexin 19/10/2015
 */



#include <stdio.h>
#include <signal.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <stdlib.h>
#include <string.h>
int findNextInt(char* str);


int main(int argc, char** argv){
	int i = 0, j = 0, duration = (argv[1]==NULL?10:atoi(argv[1]));
	setvbuf (stdout, NULL, _IONBF, 0);
	printf("starting program\n");
	printf("The program will run for %i iterations.\n",duration);
	while(i++<duration){
		int pid = fork();
		if (!pid){

			char str[20]="./";
			sprintf(str, "%d", i);
			strcat(str, ".txt");
			int fileDis = open(str,O_WRONLY|O_CREAT,0666);
			dup2(fileDis,1);
			dup2(fileDis,2);

			char* command[] = {"./src/host/layer23/src/misc/cell_log",NULL};	
			execv(command[0],command);

			exit(0);
		}
		else {
			sleep(300);
			kill(pid, SIGKILL);
			sleep(1);

			FILE *fp;
			FILE *outfile;

			char inFileName[64];
			sprintf(inFileName,"%d",i);
			strcat(inFileName,".txt");
			char outFileName[64] = "output";
			strcat(outFileName,inFileName);
			fp = fopen(inFileName,"r");
			outfile = fopen(outFileName,"w");

			//newly added in---------------------
			char ARFCNOutFileName[64] = "./ARFCN/a";
			strcat(ARFCNOutFileName,inFileName);
			
			FILE * ARFCNOutFile;
			ARFCNOutFile = fopen(ARFCNOutFileName,"w");
			//newly added in---------------------

			char buff[256];
			int sigStren[1024] = {0};
			int visited[1024] = {0};

			while(fgets(buff,sizeof(buff),fp)){
				char* loc = strstr(buff,"Cell:");

				if (loc){
					char* temp = strstr(loc,"ARFCN");
					int next = findNextInt(temp);
					if (!visited[next]){
						visited[next] = 1;
						fprintf(outfile,"%sSignal Strength: %i\n\n",temp,sigStren[next]);
						printf("%sSignal Strength: %i\n\n",temp,sigStren[next]);
						//newly added in-------------------
						fprintf(ARFCNOutFile,"%i\n",next);
						//newly added in-------------------
					}
				}
				else{
					char* temp = strstr(buff,"ARFCN");
					if (temp==NULL) continue;
					int next = findNextInt(temp);
					if (next==-32768) continue;
					char* temp2 = strstr(buff,"rxlev");
					if (temp2==NULL) continue;
					int next2 = findNextInt(temp2);
					if (next2==-32768) continue;
					sigStren[next] = next2;
				}
			}
			
			fclose(fp);
			fclose(outfile);
			fclose(ARFCNOutFile);			

			printf("iteration %i finished\n",i);	

		}
	}
}

int findNextInt(char* str){
	while (str[0]!=' '&&str[0] != '='){
		if (str[0]=='\0') return -32768;
		str++;
	}
	while (str[0] == ' '||str[0] == '='){
		str++;
	}

	int index = 0;
	while (str[index]!=' '&&str[index]!=','){
		index++;
	}

	char value[128];
	strcpy(value,str);

	value[index] = '\0';
	return atoi(value);
}
