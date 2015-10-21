<?php
    session_start();
    $id0=$_SESSION['customerid'];
    $un0=$_SESSION['username'];
    $pw0=$_SESSION['password'];
    $t0=$_SESSION['accesstime'];
    /*
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
     */
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment3',$con);
    $sql0="select * from customer where username='".$un0."'";
    $res0=mysql_query($sql0,$con);
    if(($row0 = mysql_fetch_assoc($res0))){
        header("Location: search.php");
        exit;
    }

    
    
    $un=$_POST['username'];
    $pw=$_POST['password'];
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
        $sql="select * from customer where username='".$un."' and password=password('".$pw."')";
        
        $res=mysql_query($sql,$con);
        if(! ($row=mysql_fetch_assoc($res) ) ){
            $errmsg='Invalid login';
        }
    }
    
    if(strlen($errmsg)>0){
        require 'prelogin.html';
        echo "<p style='color:red'>".$errmsg."</p>";
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
        $_SESSION['password']=$pw;//when to destroy? need a log out? how to set the timeout?
        $_SESSION['customerid']=$row['customerid'];
        $_SESSION['accesstime']=time();

        require 'search.php';
    }

    
?>

