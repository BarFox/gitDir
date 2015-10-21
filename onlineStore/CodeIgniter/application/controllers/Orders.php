<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

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
          //  echo "got".$row0['username'];
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
        $productid=inp($this->uri->segment(3));
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        
        if(inp($this->uri->segment(4))=='addtocart'){
            
            $this->load->model("showP_model");
            $res5=$this->showP_model->getorders($productid,$id);
            if($row5 = mysql_fetch_assoc($res5))
            {
                $tmp=$row5['quantity']+1;
                $this->load->model("showP_model");
                $res5=$this->showP_model->updateorders($tmp,$productid,$id);
            }else{
                $this->load->model("showP_model");
                $res5=$this->showP_model->insertorders($productid,$id);
            }
        }
        if($_POST['delete']!=''&&$_POST['delete']!=null){
            $this->load->model("orders_model");
            $res1=$this->orders_model->deleteitems($id,$_POST['delete']);
        }
        if($_POST['deleteall']=='deleteall'){
            $this->load->model("orders_model");
            $res1=$this->orders_model->deleteall($id);
        }
        //show product
        $this->load->model("orders_model");
        $res3=$this->orders_model->showorders($id);
        $data['res3']=$res3;
        
        
        //output
        $this->load->view('orders.php',$data);
        
    }
   
}
    
?>
