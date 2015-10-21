<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }
    
    public function index()
	{
        function inp($data){
            $data=trim($data);
            $data=stripslashes($data);
            $data=htmlspecialchars($data);
            return $data;
        }
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
       // echo "here ".$search_res;
        if($_POST['succeed']=='succeed'){
            
            $this->load->model("signup_model");
            $res=$this->signup_model->insertcustomer(inp($_POST['customername']),inp($_POST['customeraddress']),inp($_POST['creditcard']),inp($_POST['securitycode']),inp($_POST['expirationdate']),inp($_POST['username']),inp($_POST['password0']));
            //
            $this->load->model("signup_model");
            $res7=$this->signup_model->getlastid();
            $row7 = mysql_fetch_assoc($res7);
            $lastid=$row7['LAST_INSERT_ID()'];
         //   echo $lastid;
            //build session
            $_SESSION['username']=inp($_POST['username']);
            $_SESSION['password']=inp($_POST['password0']);//when to destroy? need a log out? how to set the timeout?
            $_SESSION['customerid']=$lastid;
            $_SESSION['accesstime']=time();
            
            
            //echo $_SESSION['username'];
            //echo $_SESSION['password'];
            //echo $_SESSION['customerid'];
            //echo $_SESSION['accesstime'];
            //add some php validate
         //   header("Location: search.php");
         
            //jump to that page
          //  $this->load->view('search.php',$data);
            header('Location:' . site_url('Search') ) ;
        }
        else{
            
        //    $row2 = mysql_fetch_assoc($search_res);
         //   echo "got ".$row2['productcategoryid'];
            $this->load->view('signup.php',$data);
        }

	//	$this->load->view('welcome_message');
	}
}
