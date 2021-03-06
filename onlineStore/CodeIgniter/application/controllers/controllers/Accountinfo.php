<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accountinfo extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }
    
    
    public function index(){
        session_start();

        $id0=$_SESSION['customerid'];
        $un0=$_SESSION['username'];
        $pw0=$_SESSION['password'];
        $t0=$_SESSION['accesstime'];
        $data['id']=$id0;
        $data['un']=$un0;
        $data['pw']=$pw0;
        $data['t0']=$t0;
        $nowtime=time();
        
        if($nowtime-$t0>1440){
            session_destroy();
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        
        
        $this->load->model("login_model");
        $res0=$this->login_model->validateprofile($un0);
        if(!($row0 = mysql_fetch_assoc($res0))){
           // echo lll;
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        
        function inp($data){
            $data=trim($data);
            $data=stripslashes($data);
            $data=htmlspecialchars($data);
            return $data;
        }
        $errormsg="";
        if($_POST['succeed']=='succeed'){
            $this->load->model("accountinfo_model");
            $res0=$this->accountinfo_model->updatecustomer(inp($_POST['username']),inp($_POST['password0']),inp($_POST['customername']),inp($_POST['customeraddress']),inp($_POST['creditcard']),inp($_POST['securitycode']),inp($_POST['expirationdate']),$id0);
            $errormsg="Your infomation has been successfully saved.";
        }
        $data['errormsg']=$errormsg;
        
        $this->load->model("accountinfo_model");
        $res3=$this->accountinfo_model->showcustomer($id0);
        $data['res3']=$res3;
        
        //get search option
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        
      
        //output
        $this->load->view('accountinfo.php',$data);
        
    }
    
}
    
?>
