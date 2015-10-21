<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ShowP extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database('default');
    }
    
    
    public function index(){
        session_start();

        $id=$_SESSION['customerid'];
        $un=$_SESSION['username'];
        $pw=$_SESSION['password'];
        $t=$_SESSION['accesstime'];
        $data['id']=$id;
        $data['un']=$un;
        $data['pw']=$pw;
        $data['t']=$t;
        $nowtime=time();
      //  echo igothere;
        if($nowtime-$t>1440){
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
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        //get the input
        //$productid=inp($_GET['productid']);
        $productid=inp($this->uri->segment(3));
        if(preg_match("/[^0-9]/",$productid,$par1)){
            session_destroy();
            header('Location:' . site_url('Login') ) ;
            exit;
        }
        $value1="";
        $value2="";
        $value3="";
        $value4="";
        $value5="";
        $value6="";
        $value7="";
        $this->load->model("showP_model");
        $productid_res=$this->showP_model->getproductid($productid);
        $data['productid_res']=$productid_res;
        $data['productid']=$productid;
        
        
        //
        if(inp($this->uri->segment(4))=='addtocart'){
            // echo "got here?";
            // echo "productid: ".$productid;
            // echo "customerid: ".$id;
           // $sql5="select * from orders where productid='".$productid."' and customerid='".$id."'";
           // $res5=mysql_query($sql5);
            
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

        //$productid=$this->uri->segment(3);
        // echo "here ".$productid;
        // $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        // if(!$con){
        //    die;//
        // }
        // mysql_select_db('assignment3',$con);
        //$sql5="select * from specialsales";
        //$res5=mysql_query($sql5);
        $this->load->model("showP_model");
        $res5=$this->showP_model->sale1();
        $countnum=0;
        while($row5 = mysql_fetch_assoc($res5))
        {
            //    echo $row5['productid']."' '";
            //in the same category
            //echo "here ".$row5['productid'];
            $this->load->model("showP_model");
            $res10=$this->showP_model->sale2($row5['productid']);
            $row10 = mysql_fetch_assoc($res10);
            
            $this->load->model("showP_model");
            $res11=$this->showP_model->sale3($productid);
            
           // $sql11="select * from product where productid='".$productid."'";
           // $res11=mysql_query($sql11);
            $row11 = mysql_fetch_assoc($res11);
            if($row10['productcategoryid']!=$row11['productcategoryid']){
                // echo "F".$row5['productid'];
                continue;
            }
            //not the same product
            if($row5['productid']==$productid){
                //      echo "S".$row5['productid'];
                continue;
            }
            //during special sales
            date_default_timezone_set("UTC");
            // $nowtime=date("Y-m-d");
            $nowyear=date("Y");
            $nowmonth=date("m");
            $nowday=date("d");
            $nowstr=$nowyear.$nowmonth.$nowday;
            //echo $nowstr;
            $startstr=substr($row5['startdate'],0,4).substr($row5['startdate'],5,2).substr($row5['startdate'],8,2);
            $endstr=substr($row5['enddate'],0,4).substr($row5['enddate'],5,2).substr($row5['enddate'],8,2);
            
            if( !($startstr<=$nowstr &&$endstr>=$nowstr) ){
                //     echo "T".$row5['productid'];
                continue;
            }
            //$sql6="select * from product where productid='".$row5['productid']."'";
            //$res6=mysql_query($sql6);
            $this->load->model("showP_model");
            $saledetails_res=$this->showP_model->getproductid($row5['productid']);
            $data['saledetails']=$saledetails_res;
            $data['saleproduct']=$row5['productid'];
            $countnum=$countnum+1;
            if($countnum==4){
                break;
            }

        }
        //output
        $this->load->view('showP.php',$data);
        
    }
    
   // public function gotosignup(){
        //echo "LOL";
   //     $this->load->view('signup.php');
   // }

}
    
?>
