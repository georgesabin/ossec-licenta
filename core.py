#!/usr/bin/python3

import sys
from threading import Timer
from subprocess import call
#from subprocess import Popen
import subprocess

print ('Number of arguments:', len(sys.argv), 'arguments.')
print ('Argument List:', str(sys.argv))

def linuxComand():
    Timer(5.0, linuxComand).start()
    call('git --version', shell=True)
#linuxComand()
#call('echo "test" | /var/ossec/bin/ossec-logtest', shell=True)

command = '/var/ossec/bin/ossec-logtest'
echo = subprocess.Popen(['echo', 'test'], stdout=subprocess.PIPE)
cmdLog = subprocess.Popen(['sudo', command], stdin=echo.stdout, stdout=subprocess.PIPE)
