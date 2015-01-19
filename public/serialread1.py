import serial
import sys
from time import sleep

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
sleep(0.3)
line = ser.readline()
print(line)
ser.close()