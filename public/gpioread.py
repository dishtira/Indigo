import sys
import RPi.GPIO as GPIO
from time import sleep

while True:
	line = 0
	GPIO.setmode(GPIO.BCM)
	GPIO.setup(17,GPIO.OUT)
	line = GPIO.input(17)
	f = open('tmp/currentGPIO.txt','w')
	f.write(line)
	f.close()
	ser.close()
