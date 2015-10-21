<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class showO_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
  
        
        public function getorderhis($id,$orderid){
            $sql3="select * from orderhis where customerid='".$id."' and orderid='".$orderid."'";
            $res3=mysql_query($sql3);
            
            return $res3;
        }
        public function getorderitems($orderid){
            $sql4="select *, o.productprice as pprice, o.productid as pid from orderitems as o left join product as p on p.productid=o.productid where o.orderid=".$orderid."";
            $res4=mysql_query($sql4);
            return $res4;
        }
        
    }
    
?>