import serial
import sys
from time import sleep

while True:
	ser = serial.Serial(
	    port='/dev/ttyACM0',\
	    baudrate=9600,\
	    parity=serial.PARITY_NONE,\
	    stopbits=serial.STOPBITS_ONE,\
	    bytesize=serial.EIGHTBITS,\
	        timeout=0)

	#this will store the line
	line = 0
	ser.write(sys.argv[1])
	sleep(1.3)
	line = ser.readline()
	f = open('tmp/currentValue.txt','w')
	f.write(line)
	f.close()
	ser.close()
