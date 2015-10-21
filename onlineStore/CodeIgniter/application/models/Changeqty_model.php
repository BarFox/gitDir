<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class Changeqty_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        public function updateorders($q,$id,$p){
            $sql1="update orders set quantity='".$q."' where customerid='".$id."' and productid='".$p."'";
            $res1=mysql_query($sql1);
            return $res1;
        }
        public function getorders($id){
            $sql3="select * from orders where customerid='".$id."'";
            $res3=mysql_query($sql3);
            return $res3;
        }
        
        public function getproduct($productid){
            $sql4="select * from product where productid='".$productid."'";
            $res4=mysql_query($sql4);//$row4['productprice'] $row4['productname'] $row4['productimage']
            return $res4;

        }
        public function getspecialsales($productid){
            $sql8="select * from specialsales where productid='".$productid."'";
            $res8=mysql_query($sql8);
            return $res8;
        }
        
    }
    
?>