import random as _r
import time

def main():
	print 1000
	_r.seed(time.time()) #Randomnize
	for i in range(1000):
		print _r.randrange(100000,pow(2,30))
		
	
if __name__=='__main__':
	main()
