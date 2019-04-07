/*
 * Analysis and Managment of Smart Waste
 * ASWM.c
 *
 * Created: 08-01-2019 22:01:45
 * Author : Manjot Singh Sidhu
 */ 
#define F_CPU 16000000UL
#define ID 3
#define LAT 434324.1
#define LONG 42342.2
#define CAP 44
#define PNO 8074857565

#include <avr/io.h>
#include <util/delay.h>

#include "uart.h"
#include "us.h"
#include "esp.h"
#include "gsm.h"

void main() {
	uart_init();
	us_init();

	_delay_ms(10000);  //WiFi and GSM Setup
	uart_string("IOT Challenge 2019: AMSW\n");
	
	//esp_init("IoT","manjot@12");
	gsm_init();
	
	unsigned char t[50];
	snprintf(t, 50, "%u-%u-%u-0-%u", ID, LAT, LONG, CAP);
	gsm_send(0, t);
	_delay_ms(500);
	
	int current1 = 0;
	int isFirst1 = 1; // first or not
	int first1 = 0;
	int temp1 = 0;
	int per1 = 0;
	
	int current2 = 0;
	int isFirst2 = 1; // first or not
	int first2 = 0;
	int temp2 = 0;
	int per2 = 0;
	
	int current3 = 0;
	int isFirst3 = 1; // first or not
	int first3 = 0;
	int temp3 = 0;
	int per3 = 0;
	
	int current4 = 0;
	int isFirst4 = 1; // first or not
	int first4 = 0;
	int temp4 = 0;
	int per4 = 0;
	
	while(1) {
		int i1=1; // iterations
		int t1 = us1_get(); // first temporary value
		int s1=0,l1=0; // s is sum of 10 average ultrasonic values
		
		while(i1 <= 10) { // 10 times it will run
			_delay_ms(200);

			int p = us1_get(); /// us value
			if(p > t1) {
				break;
			} else if (t1-p <= 5) {
				s1+=p; // s = s + p
				l1++; // l is the count of how many times s is added
			} else {
				break;
			}
			i1++;
		}
		
		if(l1 == 10) {
			current1 = s1/10;
			
			if (isFirst1) {
				first1 = current1;
				isFirst1 = 0;
			} else if(abs(temp1-current1) >= 5) {
				temp1 = current1;
				per1 = 100 - (int) (((float) current1/first1)*100);
				
				// Debugging
				uart_string("u1 current:");
				uart_num(current1);
				uart_string(" percentage:");
				uart_num(per1);
				uart_char('\n');
				
				unsigned char buffer[50];
				snprintf(buffer, 50, "%u-1-%u", ID, per1);
				
				//esp_send(buffer);
				gsm_send(1, buffer);
				
				if (per1 >= 90) {
					gsm_message(ID, 1, 0);
					gsm_call();
				} else if (per1 >= 85) {
					gsm_message(ID, 1, 1);
				}	
			}
		}
		
		int i2=1; // iterations
		int t2 = us2_get(); // first temporary value
		int s2=0,l2=0; // s is sum of 10 average ultrasonic values
		
		while(i2 <= 10) { // 10 times it will run
			_delay_ms(200);

			int p = us2_get(); /// us value
			if(p > t2) {
				break;
				} else if (t2-p <= 5) {
				s2+=p; // s = s + p
				l2++; // l is the count of how many times s is added
				} else {
				break;
			}
			i2++;
		}
		
		if(l2 == 10) {
			current2 = s2/10;
			
			if (isFirst2) {
				first2 = current2;
				isFirst2 = 0;
				} else if(abs(temp2-current2) >= 5) {
				temp2 = current2;
				per2 = 100 - (int) (((float) current2/first2)*100);
				
				// Debugging
				uart_string("u2 current:");
				uart_num(current2);
				uart_string(" percentage:");
				uart_num(per2);
				uart_char('\n');
				
				unsigned char buffer[50];
				snprintf(buffer, 50, "%u-2-%u", ID, per2);
				
				//esp_send(buffer);
				gsm_send(1, buffer);
				
				if (per2 >= 90) {
					gsm_message(ID, 2, 0);
					gsm_call();
				} else if (per2 >= 85) {
					gsm_message(ID, 2, 1);
				}
			}
		}
		
		int i3=1; // iterations
		int t3 = us3_get(); // first temporary value
		int s3=0,l3=0; // s is sum of 10 average ultrasonic values
		
		while(i3 <= 10) { // 10 times it will run
			_delay_ms(200);

			int p = us3_get(); /// us value
			if(p > t3) {
				break;
				} else if (t3-p <= 5) {
				s3+=p; // s = s + p
				l3++; // l is the count of how many times s is added
				} else {
				break;
			}
			i3++;
		}
		
		if(l3 == 10) {
			current3 = s3/10;
			
			if (isFirst3) {
				first3 = current3;
				isFirst3 = 0;
				} else if(abs(temp3-current3) >= 5) {
				temp3 = current3;
				per3 = 100 - (int) (((float) current3/first3)*100);
				
				// Debugging
				uart_string("u3 current:");
				uart_num(current3);
				uart_string(" percentage:");
				uart_num(per3);
				uart_char('\n');
				
				unsigned char buffer[50];
				snprintf(buffer, 50, "%u-3-%u", ID, per3);
				
				//esp_send(buffer);
				gsm_send(1, buffer);
				
				if (per3 >= 90) {
					gsm_message(ID, 3, 0);
					gsm_call();
				} else if (per3 >= 85) {
					gsm_message(ID, 3, 1);
				}
			}
		}
		
		int i4=1; // iterations
		int t4 = us4_get(); // first temporary value
		int s4=0,l4=0; // s is sum of 10 average ultrasonic values
		
		while(i4 <= 10) { // 10 times it will run
			_delay_ms(200);

			int p = us4_get(); /// us value
			if(p > t4) {
				break;
				} else if (t4-p <= 5) {
				s4+=p; // s = s + p
				l4++; // l is the count of how many times s is added
				} else {
				break;
			}
			i4++;
		}
		
		if(l4 == 10) {
			current4 = s4/10;
			
			if (isFirst4) {
				first4 = current4;
				isFirst4 = 0;
				} else if(abs(temp4-current4) >= 5) {
				temp4 = current4;
				per4 = 100 - (int) (((float) current4/first4)*100);
				
				// Debugging
				uart_string("u4 current:");
				uart_num(current4);
				uart_string(" percentage:");
				uart_num(per4);
				uart_char('\n');
				
				unsigned char buffer[50];
				snprintf(buffer, "%u-4-%u", ID, per4);
				
				//esp_send(buffer);
				gsm_send(1, buffer);
				
				if (per4 >= 90) {
					gsm_message(ID, 4, 0);
					gsm_call();
				} else if (per4 >= 85) {
					gsm_message(ID, 4, 1);
				}
			}
		}
		
	}
}