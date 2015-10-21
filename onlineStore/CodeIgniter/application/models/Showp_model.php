<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class showP_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        public function getproductid($productid){
         
            $sql1="select *, p.productid as pid from product as p left join specialsales as s on p.productid=s.productid where p.productid=".$productid."";
            
            $res1=mysql_query($sql1);
            return $res1;
        }
        
        public function getorders($productid,$id){
            $sql5="select * from orders where productid='".$productid."' and customerid='".$id."'";
            $res5=mysql_query($sql5);
            return $res5;
        }
        
        public function updateorders($tmp,$productid,$id){
            $sql6="update orders set quantity='".$tmp."' where productid='".$productid."' and customerid='".$id."'";
            $res6=mysql_query($sql6);
            return $res6;
        }
        
        public function insertorders($productid,$id){
            $sql4="insert into orders (productid,quantity,customerid) VALUES ('".$productid."','1','".$id."')";
            $res4=mysql_query($sql4);
            return $res4;
        }
        public function sale1(){
            $sql5="select * from specialsales";
            $res5=mysql_query($sql5);
            return $res5;
        }
        public function sale2($productid){
            $sql10="select * from product where productid='".$productid."'";
            $res10=mysql_query($sql10);
            return $res10;
        }
        public function sale3($productid){
            $sql11="select * from product where productid='".$productid."'";
            $res11=mysql_query($sql11);
            return $res11;
        }
        
    }
    
?>