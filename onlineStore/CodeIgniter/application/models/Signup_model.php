<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class signup_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function insertcustomer($customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$username,$password0){
            $sql="insert into customer (customername,customeraddress,creditcard,securitycode,expirationdate,username,password) values (?,?,?,?,?,?,password(?))";
            $res=$this->db->query($sql, array($customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$username,$password0));
          //   $res=$res->result_array();
          //  $res=mysql_query($sql);
            return $res;
        }
        public function getlastid(){
            $sql7="SELECT LAST_INSERT_ID()";
            $res7=mysql_query($sql7);
            return $res7;
        }
    }
    
?>