<?php  
    $un=$_POST['username'];
    $pw=$_POST['password'];
    $errmsg="";
    //if empty
    
    if( strlen($un)==0 ){
        $errmsg='Invalid login';
    }
    if( strlen($pw)==0 ){
        $errmsg='Invalid login';
    }
    
    if(strlen($un)==0 && strlen($pw)==0){
        $errmsg="";
    }
    
    //if un&pw are all not empty
    
    $usertype="";
    if(strlen($un)>0 && strlen($pw)>0){
        $sql="select usertype from users where username='".$un."' and password='".$pw."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment3',$con);
        $res=mysql_query($sql,$con);
        if(! ($row=mysql_fetch_assoc($res) ) ){
            $errmsg='Invalid login';
        }
        else{
            $usertype=$row['usertype'];
        }
    }
    
    //print page
    
    if(strlen($errmsg)>0){
        require 'prelogin.html';
        
        //echo strlen($un);
        //echo strlen($pw);
        echo "<p style='color:red'>".$errmsg."</p>";
        //echo "<p style='color:red'>the:".$sql."</p>";
        // echo "<p style='color:red'>the:".$con."</p>";
        //echo "<p style='color:red'>the:".$res."</p>";
        //echo "<p style='color:red'>".$row."</p>";
        require 'postlogin.html';
    }
    else if(!$res){
        require 'prelogin.html';
        
        require 'postlogin.html';
    }
    else{
        
        
        session_start();//default timeout is 20min
        //$session.Timeout=30;
        $_SESSION['username']=$un;
        $_SESSION['usertype']=$usertype;//when to destroy? need a log out? how to set the timeout?
        
       
        //go to the right page
        if($usertype=='employee'){
             //echo "successful:".$usertype;
             require 'employeepage.php';
        }
        else if($usertype=='manager'){
             //echo "successful:".$usertype;
             require 'managerpage.php';
        }
        else if($usertype=='admin'){
             //echo "successful:".$usertype;
             require 'adminpage.php';
        }
        else{
            echo "who are you!";//this type of operator not exist
        }
    }
     
   
?>
    