<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class confirminfo extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }
    
    
    public function index(){
        session_start();

        $id=$_SESSION['customerid'];
        $un=$_SESSION['username'];
        $pw=$_SESSION['password'];
        $t0=$_SESSION['accesstime'];
        $data['id']=$id;
        $data['un']=$un;
        $data['pw']=$pw;
        $data['t0']=$t0;
        $nowtime=time();
        
        if($nowtime-$t0>1440){
            session_destroy();
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        
        
        $this->load->model("login_model");
        $res0=$this->login_model->validateprofile($un);
        $tmp=0;
        foreach ($res0 as $row0){
         //   echo "got".$row0['username'];
            $tmp=$tmp+1;
        }
        //  if(!($row0 = mysql_fetch_assoc($res0)))
        if($tmp==0)
        {
            //echo 111;
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        
        function inp($data){
            $data=trim($data);
            $data=stripslashes($data);
            $data=htmlspecialchars($data);
            return $data;
        }
       // $errormsg="";
        /*
        if($_POST['succeed']=='succeed'){
            $this->load->model("accountinfo_model");
            $res0=$this->accountinfo_model->updatecustomer(inp($_POST['username']),inp($_POST['password0']),inp($_POST['customername']),inp($_POST['customeraddress']),inp($_POST['creditcard']),inp($_POST['securitycode']),inp($_POST['expirationdate']),$id);
     //       $errormsg="Your infomation has been successfully saved.";
        }
   //     $data['errormsg']=$errormsg;
        */
        $this->load->model("accountinfo_model");
        $res3=$this->accountinfo_model->showcustomer($id);
        $data['res3']=$res3;
        
        //get search option
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        
      
        //output
        $this->load->view('confirminfo.php',$data);
        
    }
    
}
    
?>
