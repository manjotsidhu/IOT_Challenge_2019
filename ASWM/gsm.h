/*
 * SIM900A GSM Module Library
 *
 * Created: 08-01-2019 22:01:45
 * Author : Manjot Singh Sidhu
 */
#define F_CPU 16000000UL

#include <avr/io.h>
#include <avr/interrupt.h>
#include <util/delay.h>

#include "uart.h"

void gsm_init() {
	uart_init();
	
	_delay_ms(100);
	uart_string("AT\r\n");
	_delay_ms(300);
	
	uart_string("AT+CGATT=1\r\n"); // Attach to GPRS
	_delay_ms(2000);
	uart_string("AT+SAPBR=1,1\r\n"); // Open a GPRS context
	_delay_ms(2000);
	uart_string("AT+SAPBR=2,1\r\n");  // To query the GPRS context
}

void gsm_message(int id, int j, int i) {
	_delay_ms(100);
	
	unsigned char buf[50];
	snprintf(buf, 50, "AT+CMGS=\"8074857565\"\r\n");
	uart_string(buf);
	_delay_ms(1000);
	
	uart_string("Garbage Dump ");
	uart_num(id);
	uart_string(" contains ");
	switch(j) {
		case 1 : uart_string("Dry Waste"); break;
		case 2 : uart_string("Wet Waste"); break;
		case 3 : uart_string("Mixed Waste"); break;
		case 4 : uart_string("Medical Waste"); break;
	}
	
	if (i) {	
		uart_string(" more than 85%, please collect ASAP.");
	} else {
		uart_string(" more than 90%, please collect urgently.");
	}
	uart_char(0x1a);
	_delay_ms(5000);
}

void gsm_call() {
	_delay_ms(100);
	
	unsigned char buffer[50];
	snprintf(buffer, 50, "ATD8074857565;\r\n");
	uart_string(buffer);
	_delay_ms(30000);
	uart_string("ATH\r\n");
}

void gsm_send(int i, char data[]) {
	uart_string("AT+HTTPINIT\r\n");                  // Initialize HTTP
	_delay_ms(1000);
	if (i) {
		uart_string("AT+HTTPPARA=\"URL\",\"http://logicamel.com/update.php?u="); // Send PARA command
	} else {
		uart_string("AT+HTTPPARA=\"URL\",\"http://logicamel.com/update.php?aif="); // Send PARA command
	}
	_delay_ms(50);
	uart_string(data);   // Add temp to the url
	_delay_ms(50);
	uart_string("\"\r\n");   // close url
	_delay_ms(2000);
	uart_string("AT+HTTPPARA=\"CID\",1\r\n");    // End the PARA
	_delay_ms(2000);
	uart_string("AT+HTTPACTION=0\r\n");
	_delay_ms(3000);
	uart_string("AT+HTTPTERM\r\n");
	_delay_ms(3000);
}