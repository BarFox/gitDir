<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ShowO extends CI_Controller {

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
       //     echo "got".$row0['username'];
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
        
        //get search option
       // $productid=$this->uri->segment(3);
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        
        //get order
        $orderid=inp($this->uri->segment(3));
        if(preg_match("/[^0-9]/",$orderid,$par1)){
            session_destroy();
            header('Location:' . site_url('Login') ) ;
            exit;
        }

       // $sql3="select * from orderhis where customerid='".$id."' and orderid='".$orderid."'";
       // $res3=mysql_query($sql3);
        $this->load->model("showo_model");
        $res3=$this->showo_model->getorderhis($id,$orderid);
        $data['res3']=$res3;
        
        //show orderitems
        $this->load->model("showo_model");
        $res4=$this->showo_model->getorderitems($orderid);
        $data['res4']=$res4;
        //output
        $this->load->view('showO.php',$data);
        
    }
   
}
    
?>
