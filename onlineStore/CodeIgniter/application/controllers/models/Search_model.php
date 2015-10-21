<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class search_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        public function getcategory(){
            $sql = "SELECT * FROM productcategory";
            $res=mysql_query($sql);
            return $res;
        }
        public function getproduct($productcategoryid,$productid){
            if($productid==null||$productid==""){
                $productid="%";
            }
            else{
                $productid="%".$productid."%";
            }
            if($productcategoryid==null||$productcategoryid==""){
                $productcategoryid="%";
            }
            $value1="";
            $value2="";
            $value3="";
            $value4="";
            $value5="";
            $sql1="select *, p.productid as pid from product as p left join specialsales as s on p.productid=s.productid where p.productname like '".$productid."' and p.productcategoryid like '".$productcategoryid."'";
          //  echo $sql1;
            $res1=mysql_query($sql1);
            return $res1;
    
        }
    }
    
?>