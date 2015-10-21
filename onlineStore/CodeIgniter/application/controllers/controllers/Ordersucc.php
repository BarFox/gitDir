<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordersucc extends CI_Controller {

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
        
        //get search option
        $productid=$this->uri->segment(3);
        $this->load->model("search_model");
        $search_res=$this->search_model->getcategory();
        $data['search_res']=$search_res;
        
        //handle checkout
        if($_POST['succeed']=='succeed'){
            //update customer!! insert into orderitems &orderhis, delete from orders!!!!!
            
            $this->load->model("ordersucc_model");
            $res4=$this->ordersucc_model->updatecustomer(inp($_POST['username']),inp($_POST['customername']),inp($_POST['customeraddress']),inp($_POST['creditcard']),inp($_POST['securitycode']),inp($_POST['expirationdate']),inp($id));
            //insert into orderhis
            date_default_timezone_set("UTC");
            $nowtime=date("Y-m-d h:i:sa");
            $this->load->model("ordersucc_model");
            $res6=$this->ordersucc_model->insertorderhis($nowtime,inp($_POST['customeraddress']),inp($_POST['creditcard']),inp($id));
            //get orderid
            $this->load->model("ordersucc_model");
            $res7=$this->ordersucc_model->getlastid();
            $row7 = mysql_fetch_assoc($res7);
            $lastid=$row7['LAST_INSERT_ID()'];
            //echo $row7['LAST_INSERT_ID()'];
            //echo $id;
            //get all orders need to be placed
            $this->load->model("ordersucc_model");
            $res8=$this->ordersucc_model->getorders($id);
            while($row8 = mysql_fetch_assoc($res8))
            {
                //$row8['productid'] $row8['quantity']
                //need to handle for specialsales
                $this->load->model("ordersucc_model");
                $res9=$this->ordersucc_model->getproduct($row8['productid']);
                $row9 = mysql_fetch_assoc($res9);
                
                //specialsales
                $this->load->model("ordersucc_model");
                $res18=$this->ordersucc_model->getspecialsales($row8['productid']);
                
                
                if( ($row18 = mysql_fetch_assoc($res18)) ){
                    
                    date_default_timezone_set("UTC");
                    // $nowtime=date("Y-m-d");
                    $nowyear=date("Y");
                    $nowmonth=date("m");
                    $nowday=date("d");
                    $nowstr=$nowyear.$nowmonth.$nowday;
                    //echo $nowstr;
                    $startstr=substr($row18['startdate'],0,4).substr($row18['startdate'],5,2).substr($row18['startdate'],8,2);
                    $endstr=substr($row18['enddate'],0,4).substr($row18['enddate'],5,2).substr($row18['enddate'],8,2);
                    
                    if( $startstr<=$nowstr &&$endstr>=$nowstr){
                        //echo specialsales
                        $value4=0.7*$row9['productprice'];
                        
                        
                    }
                    else{
                        //if date not OK just normal price
                        $value4=$row9['productprice'];
                    }
                }
                else{
                    
                    //no special sale exist, just normal price
                    $value4=$row9['productprice'];
                }
                
                $this->load->model("ordersucc_model");
                $res10=$this->ordersucc_model->insertitems($lastid,$row8['productid'],$row8['quantity'],$value4);
                
            }
            $this->load->model("ordersucc_model");
            $res11=$this->ordersucc_model->deleteorders($id);
         //   $sql11="delete from orders where customerid='".$id."'";
          //  $res11=mysql_query($sql11);
            //add some php validate
            // header("Location: search.php");
            // exit;
        }

        
        
        //output
        $this->load->view('ordersucc.php',$data);
        
    }
   
}
    
?>
