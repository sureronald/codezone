#!/usr/bin/env python

def main():
	
	T=int(raw_input())
	for i in range(T):
		x=int(raw_input())
		if x%7==0:
			print '#%d: true' % (i+1)
		else:
			print '#%d: false' % (i+1)
		
	
if __name__=='__main__':
	main()
