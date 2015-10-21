<?php
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

    
    $q=$_GET["q"];//quantity
    $p=$_GET["p"];//product
    $pricetotal=0;
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment3',$con);
    $sql0="select * from customer where username='".$un."'";
    $res0=mysql_query($sql0,$con);
    if(!($row0 = mysql_fetch_assoc($res0))){
        header("Location: login.php");
        exit;
    }
    
    //update database
    $sql1="update orders set quantity='".$q."' where customerid='".$id."' and productid='".$p."'";
    $res1=mysql_query($sql1,$con);
    /*
    $sql2="select * from orders where customerid='".$id."'";
    $res2=mysql_query($sql2,$con);
    while($row2 = mysql_fetch_assoc($res2)){
        //$row2['productid'] $row2['quantity']
        $sql2="select * from orders where customerid='".$id."'";
        $res2=mysql_query($sql2,$con);
        $pricetotal=$pricetotal+$row2['productprice']*$row2['quantity'];
    */
    $sql3="select * from orders where customerid='".$id."'";
    $res3=mysql_query($sql3,$con);
    $pricetotal=0;
    while($row3 = mysql_fetch_assoc($res3))//$row3['quantity']
    {
        $sql4="select * from product where productid='".$row3['productid']."'";
        $res4=mysql_query($sql4,$con);//$row4['productprice'] $row4['productname'] $row4['productimage']
        $row4 = mysql_fetch_assoc($res4);
        
        $sql8="select * from specialsales where productid='".$row4['productid']."'";
        $res8=mysql_query($sql8,$con);
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
    echo $pricetotal;
?>