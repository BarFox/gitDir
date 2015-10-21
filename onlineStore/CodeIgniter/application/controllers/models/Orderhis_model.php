<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    
    class orderhis_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        
        
        public function getorderhis($id){
            $sql3="select * from orderhis where customerid='".$id."'";
            $res3=mysql_query($sql3);
            return $res3;
        }
    }
    
?>