<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class orders_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function showorders($id){
            $sql3="select *, p.productid as pid from orders as o left join product as p on o.productid=p.productid left join specialsales as s on o.productid=s.productid where o.customerid='".$id."'";
            $res3=mysql_query($sql3);
            return $res3;
        }
        
        public function deleteitems($id,$deleteitems){
            $sql1="delete from orders where customerid='".$id."' and productid='".$deleteitems."'";
            $res1=mysql_query($sql1);
            return $res1;
        }
        public function deleteall($id){
            $sql1="delete from orders where customerid='".$id."'";
            $res1=mysql_query($sql1);
            return $res1;
        }
    }
    
?>