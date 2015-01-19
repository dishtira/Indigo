#!/bin/bash
i=10
while [ $i -ge 10 ]
do
	a=$(gpio read 0)
	b=$(gpio read 1)
	c=$(gpio read 2)
	d=$(gpio read 3)
	echo [\"$a\",\"$b\",\"$c\",\"$d\"] > /var/www/public/tmp/currentGPIO.txt
	sleep 1
done
