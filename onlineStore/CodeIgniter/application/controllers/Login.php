<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }
    
    
    public function index(){
       
        
        session_start();
        
        if($_POST['operation']=="logout"){
            session_destroy();
            
        }//timeout
        $id0=$_SESSION['customerid'];
        $un0=$_SESSION['username'];
        $pw0=$_SESSION['password'];
        $t0=$_SESSION['accesstime'];
       // echo "username ".$un0;
        function inp($data){
            $data=trim($data);
            $data=stripslashes($data);
            $data=htmlspecialchars($data);
            return $data;
        }

        //get search option
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        //get the input
        $un=inp($_POST['username']);
        $pw=inp($_POST['password']);
    if( preg_match("/[^a-zA-Z0-9]/",$un,$par1) || preg_match("/[^a-zA-Z0-9]/",$pw,$par2)){
            $errmsg='Invalid login';
            $data['errmsg']=$errmsg;
            $this->load->view('prelogin.html',$data);
            $this->load->view('postlogin.html');
           // exit;
    }else{
        $errmsg="";
        
        if( strlen($un)==0 ){
            $errmsg='Invalid login';
        }
        if( strlen($pw)==0 ){
            $errmsg='Invalid login';
        }
        
        if(strlen($un)==0 && strlen($pw)==0){
            $errmsg="";
        }

        if(strlen($un)>0 && strlen($pw)>0){
            $this->load->model("login_model");
            $res=$this->login_model->getprofile($un,$pw);
            if(!($row = mysql_fetch_assoc($res))){
                $errmsg='Invalid login';
            };
           // echo "got ".$row['customerid'];
            // var_dump $profile['userindex'];
        }
        
        if(strlen($errmsg)>0){
           // $data['search_res']=$search_res;
            $data['errmsg']=$errmsg;
            $this->load->view('prelogin.html',$data);
           // echo "<p style='color:red'>".$errmsg."</p>";
            $this->load->view('postlogin.html');
        }
        else if(!$res){
            $data['errmsg']=$errmsg;
          //  $data['search_res']=$search_res;
            $this->load->view('prelogin.html',$data);
            
            $this->load->view('postlogin.html');
        }
        else{
            session_start();//default timeout is 20min
            //$session.Timeout=30;
            $_SESSION['username']=$un;
            $_SESSION['password']=$pw;//when to destroy? need a log out? how to set the timeout?
            $_SESSION['customerid']=$row['customerid'];
            $_SESSION['accesstime']=time();
        
            
            header('Location:' . site_url('Search') ) ;
            
           // $this->load->view('search.php',$data);
        }
    }
    }
    
   // public function gotosignup(){
        //echo "LOL";
   //     $this->load->view('signup.php');
   // }

}
    
?>
