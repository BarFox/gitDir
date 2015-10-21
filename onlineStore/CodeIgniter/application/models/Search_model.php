<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class search_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        public function getcategory(){
            $sql = "SELECT * FROM productcategory";
          //  $res=mysql_query($sql);
            $res=$this->db->query($sql);
             $res=$res->result_array();
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
         //   $sql1="select *, p.productid as pid from product as p left join specialsales as s on p.productid=s.productid where p.productname like '".$productid."' and p.productcategoryid like '".$productcategoryid."'";
            $sql1="select *, p.productid as pid from product as p left join specialsales as s on p.productid=s.productid where p.productname like ? and p.productcategoryid like ?";
          $res1=  $this->db->query($sql1, array( $productid, $productcategoryid));
          //  echo $sql1;
          //  $res1=mysql_query($sql1);
            //$row1=$res1->row_array();
           // echo $row1['productname'];
            $res1=$res1->result_array();
          //  foreach ($res1 as $row)
          //  {
           //     echo $row['productname'];
                
          //  }
            
            return $res1;
    
        }
    }
    
?>