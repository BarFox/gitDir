Part 2: 

1) mytopo.py:

This program creates a custom topology of the network.  It consists of 2 switches and 4 hosts. Hosts h1 and h2 are connected to switch s1.  Hosts h3 and h4 are connected to switch h2. The switches are connected by a link. There are 2 networks.

How to run:
	save this file in directory /mininet
	sudo mn --custom mytopo.py --topo --mac --controller remote --switch ovsk

2) router_part2.py:

This program acts as the POX controller to control the network in order for it to act as a Layer 3 switch. 

How to run:
	save this .py file into the misc folder (/mininet/pox/pox/misc)
	cd pox (cd into the pox directory) 
	./pox.py log.level --DEBUG --mac misc.router_part_2 misc.full_payload 




