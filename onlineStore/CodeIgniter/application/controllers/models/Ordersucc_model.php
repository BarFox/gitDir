<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class ordersucc_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function updatecustomer($username,$customername,$customeraddress,$creditcard,$securitycode,$expirationdate,$id){
            $sql4="update customer set username='".$username."',customername='".$customername."',customeraddress='".$customeraddress."',creditcard='".$creditcard."',securitycode='".$securitycode."',expirationdate='".$expirationdate."' where customerid='".$id."'";
            $res4=mysql_query($sql4);
            return $res4;
        }
        public function insertorderhis($nowtime,$customeraddress,$creditcard,$id){
            $sql6="insert into orderhis (orderdate,customeraddress,creditcard,customerid) values ('".$nowtime."','".$customeraddress."','".$creditcard."','".$id."')";
            $res6=mysql_query($sql6);
            return $res6;
        }
        public function getlastid(){
            $sql7="SELECT LAST_INSERT_ID()";
            $res7=mysql_query($sql7);
            return $res7;
        }
        public function getorders($id){
            $sql8="select * from orders where customerid='".$id."'";
            $res8=mysql_query($sql8);
            return $res8;
        }
        public function getproduct($productid){
            $sql9="select * from product where productid='".$productid."'";
            $res9=mysql_query($sql9);
            return $res9;
        }
        public function getspecialsales($productid){
            $sql18="select * from specialsales where productid='".$productid."'";
            $res18=mysql_query($sql18);
            return $res18;
        }
        public function insertitems($lastid,$productid,$quantity,$value4){
            $sql10="insert into orderitems (orderid,productid,productquantity,productprice) values ('".$lastid."','".$productid."','".$quantity."','".$value4."')";
            $res10=mysql_query($sql10);
            return $res10;
        }
        public function deleteorders($id){
            $sql11="delete from orders where customerid='".$id."'";
            $res11=mysql_query($sql11);
            return $res11;
        }
        
    }
    
?>