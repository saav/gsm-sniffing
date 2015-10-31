#include <stdio.h>
#include <signal.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <stdlib.h>
#include <string.h>


int main(int argc, char** argv){
	int count = 0;

	int i = fork();

	if (i){
		execl("./osmocom_auto","./osmocom_auto",argv[1],NULL);
		exit(0);
	}

	else{
		printf("in else\n");
		printf("%d\n",atoi(argv[1]));
		while (count++<atoi(argv[1])){
			sleep(301);
			char path[32] = "./ARFCN/a";
			char countstr[20] = "";
			sprintf(countstr,"%d",count);
			
			strcat(path,countstr);
			strcat(path,".txt");

			printf("path is %s\n",path);

			int j = fork();
			if (j) execl("./sdr_script","./sdr_script",path,NULL);
		}
	}

	return 0;
}
