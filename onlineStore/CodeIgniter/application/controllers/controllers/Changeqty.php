<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Changeqty extends CI_Controller {
        
        public function __construct(){
            parent::__construct();
            $this->load->database('default');
        }
        
        
public function index(){
    //$q=$this->input->post('q');
    //$p=$this->input->post('p');
    //echo $p;
    
    session_start();
    
    $id=$_SESSION['customerid'];
    $un=$_SESSION['username'];
    $pw=$_SESSION['password'];
    $t0=$_SESSION['accesstime'];
    $nowtime=time();
    //echo $nowtime;
    //echo '<br>';
    //echo $t0;
    if($nowtime-$t0>1440){
        session_destroy();
        header("Location: login.php");
        exit;
    }

    $q=$this->input->post('q');
    $p=$this->input->post('p');
   // $q=$_GET["q"];//quantity
   // $p=$_GET["p"];//product
    $pricetotal=0;
    //$con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    //if(!$con){
      //  die;//
   // }
   // mysql_select_db('assignment3');
    $this->load->model("login_model");
    $res0=$this->login_model->validateprofile($un);
    if(!($row0 = mysql_fetch_assoc($res0))){
        //echo 111;
        header('Location:' . site_url('Login') ) ;
        exit;
    }

    
    //update database
  //  $sql1="update orders set quantity='".$q."' where customerid='".$id."' and productid='".$p."'";
   // $res1=mysql_query($sql1);
    $this->load->model("changeqty_model");
    $res1=$this->changeqty_model->updateorders($q,$id,$p);
  
   // $sql3="select * from orders where customerid='".$id."'";
   // $res3=mysql_query($sql3);
    $this->load->model("changeqty_model");
    $res3=$this->changeqty_model->getorders($id);
    
    $pricetotal=0;
    while($row3 = mysql_fetch_assoc($res3))//$row3['quantity']
    {
        $this->load->model("changeqty_model");
        $res4=$this->changeqty_model->getproduct($row3['productid']);
        $row4 = mysql_fetch_assoc($res4);
        
        $this->load->model("changeqty_model");
        $res8=$this->changeqty_model->getspecialsales($row4['productid']);
        
        if( ($row8 = mysql_fetch_assoc($res8)) ){
            
            date_default_timezone_set("UTC");
            // $nowtime=date("Y-m-d");
            $nowyear=date("Y");
            $nowmonth=date("m");
            $nowday=date("d");
            $nowstr=$nowyear.$nowmonth.$nowday;
            //echo $nowstr;
            $startstr=substr($row8['startdate'],0,4).substr($row8['startdate'],5,2).substr($row8['startdate'],8,2);
            $endstr=substr($row8['enddate'],0,4).substr($row8['enddate'],5,2).substr($row8['enddate'],8,2);
            
            if( $startstr<=$nowstr &&$endstr>=$nowstr){
                //echo specialsales
             //   echo $row4['productprice']*0.7.'</span></p>';
                $pricetotal=$pricetotal+$row4['productprice']*0.7*$row3['quantity'];
                
            }
            else{
                //if date not OK just normal price
             //   echo $row4['productprice'].'</span></p>';
                $pricetotal=$pricetotal+$row4['productprice']*$row3['quantity'];
            }
        }
        else{
            
            //no special sale exist, just normal price
          //  echo $row4['productprice'].'</span></p>';
            $pricetotal=$pricetotal+$row4['productprice']*$row3['quantity'];
        }

    
    }
    echo " $".$pricetotal;
    
}
}
?>