# Copyright 2012 James McCauley
#
# This file is part of POX.
#
# POX is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# POX is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with POX.  If not, see <http://www.gnu.org/licenses/>.

"""
This component is for use with the OpenFlow tutorial.
It acts as a simple hub, but can be modified to act like an L2
learning switch.
It's quite similar to the one for NOX.  Credit where credit due. :)
"""

from pox.core import core
import pox.openflow.libopenflow_01 as of
import pox.lib.packet as pck
from pox.lib.addresses import IPAddr, EthAddr

log = core.getLogger()



class Tutorial (object):
  """
  A Tutorial object is created for each switch that connects.
  A Connection object for that switch is passed to the __init__ function.
  """
  def __init__ (self, connection):
    # Keep track of the connection to the switch so that we can
    # send it messages!
    self.connection = connection

    # This binds our PacketIn event listener
    connection.addListeners(self)

    # Use this table to keep track of which ethernet address is on
    # which switch port (keys are MACs, values are ports).
    self.mac_to_port = {}
    
    self.IP_to_port = {}

    self.ARP_cache = {}

    self.int_to_ip = {}

  def resend_packet (self, packet_in, out_port):
    """
    Instructs the switch to resend a packet that it had sent to us.
    "packet_in" is the ofp_packet_in object the switch had sent to the
    controller due to a table-miss.
    """
    msg = of.ofp_packet_out()
    msg.data = packet_in

    # Add an action to send to the specified port
    action = of.ofp_action_output(port = out_port)
    msg.actions.append(action)

    # Send message to switch
    self.connection.send(msg)


  def act_like_hub (self, packet, packet_in):
    """
    Implement hub-like behavior -- send all packets to all ports besides
    the input port.
    """

    # We want to output to all ports -- we do that using the special
    # OFPP_ALL port as the output port.  (We could have also used
    # OFPP_FLOOD.)
    self.resend_packet(packet_in, of.OFPP_ALL)

    # Note that if we didn't get a valid buffer_id, a slightly better
    # implementation would check that we got the full data before
    # sending it (len(packet_in.data) should be == packet_in.total_len)).


   

  def act_like_router (self, event, packet_in):
    """
    Implement router-like behavior 
    """
    packet = event.parsed
    ipPacket = packet.payload
    icmpPacket = packet.payload.payload
    #self.IP_to_port[ipPacket.srcip] = packet_in.in_port
    self.mac_to_port[packet.src] = packet_in.in_port

    self.mac_to_port[EthAddr("00:00:00:00:00:01")] = 1
    self.mac_to_port[EthAddr("00:00:00:00:00:02")] = 2
    self.mac_to_port[EthAddr("00:00:00:00:00:03")] = 3

    self.ARP_cache[IPAddr('10.0.1.100')] = EthAddr("00:00:00:00:00:01")
    self.ARP_cache[IPAddr('10.0.2.100')] = EthAddr("00:00:00:00:00:02")
    self.ARP_cache[IPAddr('10.0.3.100')] = EthAddr("00:00:00:00:00:03")

   # self.rt_table["10.0.1.100/24"] =['10.0.1.100', 's1-eth1', '10.0.1.1', 1]
   # self.rt_table["10.0.2.100/24"] =['10.0.2.100', 's1-eth2', '10.0.2.1', 3]
   # self.rt_table["10.0.3.100/24"] =['10.0.3.100', 's1-eth3', '10.0.3.1', 2]

    self.IP_to_port[IPAddr('10.0.1.1')]=1
    self.IP_to_port[IPAddr('10.0.3.1')]=3
    self.IP_to_port[IPAddr('10.0.2.1')]=2

    
    if packet.type == pck.ethernet.ARP_TYPE: 
      ip_add = ipPacket.protodst
      log.debug("arp packet: %s" % (ip_add))
    else: 
      ip_add = ipPacket.dstip
      log.debug("ip packet: %s" % (ip_add))
          
        
    if ip_add in self.IP_to_port or ip_add in self.ARP_cache: 

    
      if packet.type == pck.ethernet.ARP_TYPE:
        log.debug("Received an ARP packet")
        if ipPacket.opcode == pck.arp.REQUEST:
          log.debug("       ARP request packet with MAC: %s" % (packet.src))
          log.debug("       ARP request packet with iP: %s" % (ipPacket.protosrc))

          pck_ARP = pck.arp()

          pck_ARP.hwtype = ipPacket.hwtype
          pck_ARP.protype = ipPacket.prototype
          pck_ARP.hwlen = ipPacket.hwlen
          pck_ARP.protolen = ipPacket.protolen

          pck_ARP.hwsrc = EthAddr("12:12:12:12:12:12")
          pck_ARP.protosrc = ipPacket.protodst
          pck_ARP.protodst = ipPacket.protosrc
          pck_ARP.hwdst = ipPacket.hwsrc
          pck_ARP.opcode = pck.arp.REPLY

          pck_MAC = pck.ethernet()
          pck_MAC.type = pck.ethernet.ARP_TYPE
          pck_MAC.dst = ipPacket.hwsrc
          pck_MAC.src = EthAddr("12:12:12:12:12:12")
          pck_MAC.payload = pck_ARP

          log.debug("       Sending ARP reply  with MAC: %s" % (pck_MAC.dst))
          log.debug("       Sending ARP reply with IP: %s" % (pck_ARP.protodst))

          msg_to_send = of.ofp_packet_out()
          msg_to_send.data = pck_MAC.pack()
          msg_to_send.actions.append(of.ofp_action_output(port = of.OFPP_IN_PORT))
          msg_to_send.in_port = event.port
          event.connection.send(msg_to_send)


      elif packet.type == pck.ethernet.IP_TYPE:
       
        log.debug("Relaying ipv4 packet")
        
        log.debug("       Received packet from  MAC: %s" % (packet.src))
        log.debug("       Received packet from  IP: %s" % (ipPacket.srcip))
        
        pck_IP = pck.ipv4()
        pck_IP.protocol = ipPacket.protocol
        pck_IP.srcip = ipPacket.srcip
        pck_IP.dstip = ipPacket.dstip
        #pck_IP.srcip = self.int_to_ip[ipPacket.srcip]
        #pck_IP.dstip = self.int_to_ip[ipPacket.dstip]
        pck_IP.payload = packet.payload.payload

        pck_MAC = pck.ethernet()
        pck_MAC.type = pck.ethernet.IP_TYPE
        pck_MAC.dst = self.ARP_cache[ipPacket.dstip]
        pck_MAC.src = EthAddr("12:12:12:12:12:12")
        pck_MAC.payload = pck_IP


      #  if ipPacket.dstip in self.IP_to_port:
          #self.resend_packet(pck_MAC, self.IP_to_port[ipPacket.dstip ])
        log.debug("       Relaying packet to  MAC: %s" % (pck_MAC.dst))
        log.debug("       Relaying packet to  IP: %s" % (pck_IP.dstip))

          
        msg_to_send = of.ofp_packet_out()
        msg_to_send.data = pck_MAC.pack()
        port_out = self.mac_to_port[pck_MAC.dst]
        msg_to_send.actions.append(of.ofp_action_output(port = port_out))
        msg_to_send.in_port = event.port
        event.connection.send(msg_to_send)

        log.debug("       Relaying to Port: %s" % port_out)
    
    else:
      log.debug("Send host unavailable message")
      log.debug("Received packet with IP: %s" % ipPacket.srcip)
      log.debug("MAC: %s" % packet.src)

      log.debug("       RCVD IP  packet with MAC source: %s" % (packet.src))
      log.debug("       IP  packet with iP source: %s" % (ipPacket.srcip))
      log.debug("       IP  packet with MAC dest: %s" % (packet.dst))
      log.debug("       IP  packet with iP dest: %s" % (ipPacket.dstip))

      pck_pay = pck.unreach()
      pck_pay.payload = ipPacket

      pck_ICMP = pck.icmp()
      pck_ICMP.type = pck.TYPE_DEST_UNREACH
      pck_ICMP.code = pck.CODE_UNREACH_HOST
      pck_ICMP.payload = pck_pay
      
      pck_IP = pck.ipv4()
      pck_IP.protocol = pck.ipv4.ICMP_PROTOCOL
      pck_IP.srcip = ipPacket.dstip
      pck_IP.dstip = ipPacket.srcip
      pck_IP.payload = pck_ICMP

      pck_MAC = pck.ethernet()
      pck_MAC.type = pck.ethernet.IP_TYPE
      pck_MAC.dst = packet.src
      pck_MAC.src = packet.dst
      pck_MAC.payload = pck_IP

      msg_to_send = of.ofp_packet_out()
      msg_to_send.data = pck_MAC.pack()
      msg_to_send.actions.append(of.ofp_action_output(port = of.OFPP_IN_PORT))
      msg_to_send.in_port = event.port
      event.connection.send(msg_to_send)
      
    

  def _handle_PacketIn (self, event):
    """
    Handles packet in messages from the switch.
    """

    packet = event.parsed # This is the parsed packet data.
    if not packet.parsed:
      log.warning("Ignoring incomplete packet")
      return

    packet_in = event.ofp # The actual ofp_packet_in message.

    # Comment out the following line and uncomment the one after
    # when starting the exercise.
    self.act_like_router(event, packet_in)
    #self.act_like_switch(packet, packet_in)



def launch ():
  """
  Starts the component
  """
  def start_switch (event):
    log.debug("Controlling %s" % (event.connection,))
    Tutorial(event.connection)
  core.openflow.addListenerByName("ConnectionUp", start_switch)
