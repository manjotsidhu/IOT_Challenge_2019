/*
 * ASWM.c
 *
 * Created: 08-01-2019 22:01:45
 * Author : Manjot Singh Sidhu
 */ 
#define F_CPU 16000000UL

#include <avr/io.h>
#include <util/delay.h>

#include "uart.h"

void main() {
	uart_init();
	_delay_ms(100);
	uart_string("AT+SAPBR=3,1,\"Contype\",\"GPRS\"\r\n");
	_delay_ms(300);

	uart_string("AT+SAPBR=3,1,\"APN\",\"internet\"\r\n");
	_delay_ms(300);

	uart_string("AT+SAPBR=1,1\r\n");
	_delay_ms(300);
	
	uart_string("AT+SAPBR=2,1\r\n");
	_delay_ms(300);
	
	uart_string("AT+HTTPINITr\n");
	_delay_ms(300);
	
	uart_string("AT+HTTPPARA = \"CID\",1\r\n");
	_delay_ms(300);
	
	uart_string("AT+HTTPPARA=\"URL\",\"api.\r\n");
	_delay_ms(300);
}