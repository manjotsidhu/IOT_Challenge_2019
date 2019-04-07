/*
 * ESP8266 Interfacing Library
 * for sending POST requests to a server
 *
 * Created: 10-01-2019 8:54:00
 * Author : Manjot Singh Sidhu 
*/
#include <stdio.h>
#include <avr/io.h>
#include <util/delay.h>

#include "uart.h" // Custom UART Library

void esp_init(char ssid[], char pass[]) {
	uart_init();
	_delay_ms(100);
	uart_string("AT+CWMODE=3\r\n");
	_delay_ms(300);

	char buffer[50];
	sprintf(buffer, "AT+CWJAP=\"%s\",\"%s\"\r\n", ssid, pass);
		
	uart_string(buffer);
	_delay_ms(100);
}

void esp_send(int data) {
	uart_string("AT+CIPSTART=\"TCP\",\"logicamel.com\",80\r\n");
	_delay_ms(1000);

	uart_string("AT+CIPSEND=");
	uart_num(strlen(data)+18);
	uart_string("\r\n");
	_delay_ms(1000);

	uart_string("GET /update.php?u=");
	uart_string(data);
	_delay_ms(600);

	uart_string("AT+CIPCLOSE\r\n");
	_delay_ms(200);
}