"""Custom topology example

Two directly connected switches plus a host for each switch:

   host --- switch --- switch --- host

Adding the 'topos' dict with a key/value pair to generate our newly defined
topology enables one to pass in '--topo=mytopo' from the command line.
"""

from mininet.topo import Topo

class MyTopo( Topo ):
    "Simple topology example."

    def __init__( self ):
        "Create custom topo."

        # Initialize topology
        Topo.__init__( self )

        # Add hosts and switches
      
        host1 = self.addHost( 'h1', ip="10.0.1.100/24", defaultRoute = "via 10.0.1.1" )
        host2 = self.addHost( 'h2', ip="10.0.2.100/24", defaultRoute = "via 10.0.2.1" )
        host3 = self.addHost( 'h3', ip="10.0.3.100/24", defaultRoute = "via 10.0.3.1" )
        switch = self.addSwitch( 's1' )

      
        #host1 = self.addHost( 'h1' )
        #host3 = self.addHost( 'h3' )
        #host2 = self.addHost( 'h2' )
       

        # Add links
        self.addLink(host1, switch)
        self.addLink(host2, switch)
        self.addLink(host3, switch)




topos = { 'mytopo': ( lambda: MyTopo() ) }
