<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class login_model extends CI_Model{
        function __construct()
        {
            parent::__construct();
        }
        public function getprofile($username,$password){
            //var $mysql;
            // var $sql;
            //  echo $username;
            //  echo $password;
            $sql = "SELECT * FROM customer WHERE username='".$username."'";
            // echo $sql;
            $res=mysql_query($sql);
            //   $row = mysql_fetch_assoc($res);
            return $res;
            // $res = $this->db->query($sql);
            //  echo "got".$res->result();
            //  return $res->result();
            //  $query = $this->db->from('users')->where(array('username' => $username, 'password' => $password))->get();
            //   echo "we have find ".$query->row_array();
            //  return $query->row_array();
        }
        public function validateprofile($un){
            $sql0="select * from customer where username=?";
            $res0=  $this->db->query($sql0, array( $un));
            $res0=$res0->result_array();
           // $res0=mysql_query($sql0);
            //echo $sql0;
         //   echo got.$res0['username'];
            return $res0;
        }
      
    }
    
        
?>