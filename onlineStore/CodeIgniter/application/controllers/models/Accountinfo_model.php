<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class accountinfo_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function updatecustomer($username,$password0,$customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$id0){
            $sql4="update customer set username='".$username."',password=password('".$password0."'),customername='".$customername."',customeraddress='".$customeraddress."',creditcard='".$creditcard."',securitycode='".$securitycode."',expirationdate='".$expirationdate."' where customerid='".$id0."'";
            $res4=mysql_query($sql4);
            return $res4;
        }
        
        public function showcustomer($id0){
            $sql3="select * from customer where customerid='".$id0."'";
            $res3=mysql_query($sql3);
            return $res3;
        }
    }
    
?>