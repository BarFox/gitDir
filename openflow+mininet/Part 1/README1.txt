PART 1:

1)of_tutorial.py

This program is written according to the “Create a Learning Switch” tutorial.
It runs the act_like_switch method, which will handle the packet and switch them accordingly.  This program incorporates Flow Mods.
	
How to run: 
	./pox.py log.level --DEBUG misc.of_tutorial
	sudo mn --topo single,3 --mac --switch ovsk --controller remote

Router Exercise: 

2) mytopo.py

This program creates a custom topology of the network. It consists of 3 hosts (h1, h2, h3) connected to a switch (s1) and controlled by a remote controller.  The 3 hosts are on 3 networks connected to the router interfaces: 10.0.1.1, 10.0.3.1, 10.0.2.1. The switches are connected to the switch with 3 links. 

Running the network topology file: mytopo.py
	save this file in directory: /mininet
	sudo mn --custom mytopo.py --topo mytopo --mac --controller remote --switch ovsk

3) router_part1.py

This program acts as the POX controller to control the network in order for it to act as a Layer 3 switch. 

How to run: 
	save this .py file into the misc folder (/mininet/pox/pox/misc)
	cd pox (cd into the pox directory) 
	./pox.py log.level --DEBUG --mac misc.router_part1 misc.full_payload 




