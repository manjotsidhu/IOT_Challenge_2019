/*
 * Ultrasonic sensor (HC-SR04) library
 * supports 4 ultrasonic sensors at the same time
 * using ICP and 3 INT registers
 *
 * Created: 08-01-2019 22:01:45
 * Author : Manjot Singh Sidhu
 */
#define F_CPU 16000000UL
 
#include <avr/io.h>
#include <avr/interrupt.h>
#include <util/delay.h>
#include <string.h>
#include <stdlib.h>

#define  TRIG1	PA0
#define	 TRIG2  PA1
#define  TRIG3  PA2
#define  TRIG4  PA3
 
int TimerOverflow = 0;

static volatile int pulse2 = 0;
static volatile int pulse3 = 0;
static volatile int pulse4 = 0;
static volatile int i2 = 0;
static volatile int i3 = 0;
static volatile int i4 = 0;

// For Ultrasonic sensor 1
ISR(TIMER1_OVF_vect)
{
	TimerOverflow++;	/* Increment Timer Overflow count */
}

// For Ultrasonic sensor 2
ISR(INT0_vect)
{
	if (i2==1)
	{
		TCCR1B=0;
		pulse2=TCNT1;
		TCNT1=0;
		i2=0;
	}
	if (i2==0)
	{
		TCCR1B|=(1<<CS10);
		i2=1;
	}
}

// For Ultrasonic sensor 3
ISR(INT1_vect)
{
	if (i3==1)
	{
		TCCR1B=0;
		pulse3=TCNT1;
		TCNT1=0;
		i3=0;
	}
	if (i3==0)
	{
		TCCR1B|=(1<<CS10);
		i3=1;
	}
}

// For Ultrasonic sensor 4
ISR(INT2_vect)
{
	if (i4==1)
	{
		TCCR1B=0;
		pulse4=TCNT1;
		TCNT1=0;
		i4=0;
	}
	if (i4==0)
	{
		TCCR1B|=(1<<CS10);
		i4=1;
	}
}

void us_init() {
	
	TimerOverflow = 0;
			
	DDRA = 0xF;		/* Make trigger pin as output */
	DDRD = 0b00100000;  /* for u2*/
	
	sei();				/* Enable global interrupt */		
	TCCR1A = 0;		/* Set all bit to zero Normal operation */
}

/*
	Ultrasonic Sensor 1
	TRIG - PA0
	ECHO - PD6(ICP1)
*/		
double us1_get() {	
	
	long count;
	TIMSK = (1 << TOIE1);	/* Enable Timer1 overflow interrupts */
		
	/* Give 10us trigger pulse on trig. pin to HC-SR04 */
	PORTA |= (1 << TRIG1);
	_delay_us(10);
	PORTA &= (~(1 << TRIG1));
	
	TCNT1 = 0;	/* Clear Timer counter */
	TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
	TIFR = 1<<ICF1;	/* Clear ICP flag (Input Capture flag) */
	TIFR = 1<<TOV1;	/* Clear Timer Overflow flag */

	/*Calculate width of Echo by Input Capture (ICP) */
	
	while ((TIFR & (1 << ICF1)) == 0);/* Wait for rising edge */
	TCNT1 = 0;	/* Clear Timer counter */
	TCCR1B = 0x01;	/* Capture on falling edge, No prescaler */
	TIFR = 1<<ICF1;	/* Clear ICP flag (Input Capture flag) */
	TIFR = 1<<TOV1;	/* Clear Timer Overflow flag */
	TimerOverflow = 0;/* Clear Timer overflow count */

	while ((TIFR & (1 << ICF1)) == 0);/* Wait for falling edge */
	count = ICR1 + (65535 * TimerOverflow);	/* Take count */
	/* 8MHz Timer freq, sound speed =343 m/s */
	double u1 = (double)count / 466.47; 

	// 12MHz Timer freq, sound speed =343 m/s 
	//u1 = (double)count / 932.94;
	
	_delay_ms(10);
	
	return u1;
	
}

/*
	Ultrasonic Sensor 2
	TRIG - PA1
	ECHO - PD2(INT0)
*/
int us2_get() {		
		GICR|=(1<<INT0);
		MCUCR|=(1<<ISC00);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		PORTA|=(1<<PINA1);
		_delay_us(10);
		PORTA &=~(1<<PINA1);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		int u2 = pulse2/58;
		
		_delay_ms(10);
		
		return u2;
}
		
/*
	Ultrasonic Sensor 3
	TRIG - PA2
	ECHO - PD3(INT1)
*/
uint32_t us3_get() {		
		GICR|=(1<<INT1);
		MCUCR|=(1<<ISC10);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		PORTA|=(1<<PINA2);
		_delay_us(10);
		PORTA &=~(1<<PINA2);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		uint32_t u3 = pulse3/466.47;
		
		_delay_ms(10);
		
		return u3;
}
		
/*
	Ultrasonic Sensor 4
	TRIG - PA3
	ECHO - PB2(INT2)
*/
uint32_t us4_get() {		
		GICR|=(1<<INT2);
		MCUCSR|=(1<<ISC2);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		PORTA|=(1<<PINA3);
		_delay_us(10);
		PORTA &=~(1<<PINA3);
		
		TCNT1 = 0;	/* Clear Timer counter */
		TCCR1B = 0x41;	/* Capture on rising edge, No prescaler*/
		
		uint32_t u4 = pulse4/466.47;
		
		_delay_ms(10);
		
		return u4;
}
