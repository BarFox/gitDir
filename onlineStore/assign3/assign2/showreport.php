<?php
    session_start();
    //destroy session
   
    $ut=$_SESSION['usertype'];
    $un=$_SESSION['username'];
    //echo "<p>username:".$ut." | usertype:".$un."</p>";
    //echo "employee";
    //1.check usertype, if wrong go to loginpage.
    //2.go to mysql and check username?
    //username
    $sql0="select * from users where username='".$un."'";
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment3',$con);
    $res0=mysql_query($sql0,$con);
    if(!($row = mysql_fetch_assoc($res0))){
        header("Location: login.php");
        exit;
    }
    
    
    
    //usertype
    if($ut!='manager'){
        //require 'login.php';
        //  die;//?
        header("Location: login.php");
        //  echo $ut." ".$un;
        exit;
    }
    // echo $ut;
    
    //handle report request
    //1. if choose sum topic
    //echo $_POST['searchtopic'];
    if($_POST['searchtopic']=='quantitysold'){
        $sql="select sum(oi.productquantity) as sump ";
    }else if($_POST['searchtopic']=='price'){
        $sql="select round(sum(oi.productprice),2) as sump ";
    }
    //if choose total
    if($_POST['searchtype']!='totalsales'&&$_POST['searchtype']!='specialsales'){
        $sql.=", p.productname, pc.productcategoryname";
    }
    //2. general topic
    $sql.=" from orderitems as oi,orderhis as oh,product as p, productcategory as pc";
    //if only want specialsales product maybe not
    $sql.=" where oh.orderid=oi.orderid and p.productid=oi.productid and p.productcategoryid=pc.productcategoryid";
    //specific category
    if($_POST['categorylist']!=undefined){
        $sql.=" and p.productcategoryid='".$_POST['categorylist']."'";
    }
    
    //if date
    if($_POST['lowlimit']!=""&&$_POST['lowlimit']!=null){
        $sql.=" and unix_timestamp(oh.orderdate)>=unix_timestamp('".$_POST['lowlimit']."')";
    }
    if($_POST['highlimit']!=""&&$_POST['highlimit']!=null){
        $sql.=" and unix_timestamp(oh.orderdate)<=unix_timestamp('".$_POST['highlimit']."')";
    }
    //change group by
    if($_POST['searchtype']=='totalsales'){
        
    }else if($_POST['searchtype']=='productid'){
        $sql.=" group by p.productid";
    }else if($_POST['searchtype']=='productcategoryid'){
        $sql.=" group by pc.productcategoryid";
    }else if($_POST['searchtype']=='specialsales'){
        //nothing but only calculate specialsales
        $sql.=" and p.productid in (";
        $sqltmp="select * from specialsales";
        $restmp=mysql_query($sqltmp,$con);
        $tmp=0;
        while($rowtmp = mysql_fetch_assoc($restmp)){
            date_default_timezone_set("UTC");
            // $nowtime=date("Y-m-d");
            $nowyear=date("Y");
            $nowmonth=date("m");
            $nowday=date("d");
            $nowstr=$nowyear.$nowmonth.$nowday;
            //echo $nowstr;
            $startstr=substr($rowtmp['startdate'],0,4).substr($rowtmp['startdate'],5,2).substr($rowtmp['startdate'],8,2);
            $endstr=substr($rowtmp['enddate'],0,4).substr($rowtmp['enddate'],5,2).substr($rowtmp['enddate'],8,2);
            
            if( $startstr<=$nowstr &&$endstr>=$nowstr){
                if($tmp==1){
                    $sql.= ",";
                }else{
                    $tmp=1;
                }
                $sql.= "'".$rowtmp['productid']."'";
            }
            
            
            
        }
        $sql.=")";
    }
   //order
    if($_POST['sortorder']=='DESC'){
        if($_POST['searchtype']=='productid'){
            $sql.=" order by p.productname DESC";
        }
        if($_POST['searchtype']=='productcategoryid'){
            $sql.=" order by pc.productcategoryname DESC";
        }
    }else if($_POST['sortorder']=='ASC'){
        if($_POST['searchtype']=='productid'){
            $sql.=" order by p.productname ASC";
        }
        if($_POST['searchtype']=='productcategoryid'){
            $sql.=" order by pc.productcategoryname ASC";
        }

    }
    $res=mysql_query($sql,$con);
    echo $sql;
    
    ?>
<html>
<head>
<title>Manager</title>
<link rel="stylesheet" type="text/css" href="loginstyle.css" />
<script type="text/javascript" src="jsfile.js"></script>
</head>
<body style="background-color:#555555;" class="font-common" >
    <div class="mainbox opacy" id="box1" style="display:block;text-align:left;width:800px;<!--background:url(pic/bg1.jpg);-->">
        <div style="postion:relative;top:150px;left:100px;margin:0 0 0 180px">

            <img src="pic/bunnyfox.jpg">
        </div>
        <div class="welcome">
            <p style="margin:0 0 0 210px">Manager <?php echo $un;?>, You are login!</p>
        </div>
        <div style="margin:0 0 0 150px">

        <?php
            if($_POST['searchtype']=='totalsales'||$_POST['searchtype']=='specialsales'){
                $row = mysql_fetch_assoc($res);
                echo $_POST['searchtype'].": ".$row['sump'];
            }else if($_POST['searchtype']=='productid'){
               // echo "---------------------------------------------------------";
                echo "<br>";
                while($row = mysql_fetch_assoc($res)){
                    echo " ".$row['productname']." ";
                    
                    echo " &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  ".$row['sump'] ." ";
                    echo "<br>";
                 //   echo "---------------------------------------------------------";
                    echo "<br>";
                }
            }else if($_POST['searchtype']=='productcategoryid'){
               // echo "---------------------------------------------------------";
                echo "<br>";
                while($row = mysql_fetch_assoc($res)){
                    
                    echo " ".$row['productcategoryname']."  ";
                    echo " &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  ".$row['sump'] ."  ";
                    echo "<br>";
               //     echo "---------------------------------------------------------";
                    echo "<br>";
                }

            }
        ?>
        </div>
        <div>
            <br>
            <FORM METHOD=POST ACTION="orderreport.php">
                <div class="textbox welcome">
                    <BUTTON TYPE="submit" name="mainpage" >Return</button>
                </div>
            </FORM>
        </div>
    </div>
</body>
</html>
