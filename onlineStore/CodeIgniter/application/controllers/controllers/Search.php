<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

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
        $data['id0']=$id0;
        $data['un0']=$un0;
        $data['pw0']=$pw0;
        $data['t0']=$t0;
        $nowtime=time();
       // echo $un0;
       // echo $t0;
        if($nowtime-$t0>1440){
            session_destroy();
            
         //   echo $nowtime." | ";
         //   $tmp=$nowtime-$t0;
         //   echo $tmp;
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        
        
        $this->load->model("login_model");
        $res0=$this->login_model->validateprofile($un0);
        if(!($row0 = mysql_fetch_assoc($res0))){
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
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        //get the input
        $productcategoryid=inp($_POST['productcategoryid']);
        $productid=inp($_POST['productid']);
        $this->load->model("search_model");
        $product_res=$this->search_model->getproduct($productcategoryid,$productid);
        $data['product_res']=$product_res;
        $data['productid_res']=$productid;//the search option
        //save the search option
      //  $searchopt=
        
        //echo $product_res;
        //output
        $this->load->view('search.php',$data);
        
    }
    
   // public function gotosignup(){
        //echo "LOL";
   //     $this->load->view('signup.php');
   // }

}
    
?>
