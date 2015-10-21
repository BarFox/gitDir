<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class accountinfo_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function updatecustomer($username,$password0,$customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$id0){
            $sql4="update customer set username=?,password=password(?),customername=?,customeraddress=?,creditcard=?,securitycode=?,expirationdate=? where customerid=?";
            $res4= $this->db->query($sql4, array($username,$password0,$customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$id0));
        //    $res4=$res4->result_array();
        //    $res4=mysql_query($sql4);
            return $res4;
        }
        
        public function showcustomer($id0){
            $sql3="select * from customer where customerid=?";
            $res3=  $this->db->query($sql3, array($id0));
            $res3=$res3->result_array();
          //  $res3=mysql_query($sql3);
            return $res3;
        }
    }
    
?>