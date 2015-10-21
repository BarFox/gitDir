<?php
    session_start();
    if($_POST['operation']=="logout"){
        session_destroy();
        header("Location: login.php");
        exit;
    }//timeout
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

    //before should be session
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

    function inp($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
    //handle checkout
    if($_POST['succeed']=='succeed'){
        //update customer!! insert into orderitems &orderhis, delete from orders!!!!!
        $sql4="update customer set username='".inp($_POST['username'])."',customername='".inp($_POST['customername'])."',customeraddress='".inp($_POST['customeraddress'])."',creditcard='".inp($_POST['creditcard'])."',securitycode='".inp($_POST['securitycode'])."',expirationdate='".inp($_POST['expirationdate'])."' where customerid='".inp($id)."'";
        $res4=mysql_query($sql4,$con);
        //insert into orderhis
        date_default_timezone_set("UTC");
        $nowtime=date("Y-m-d h:i:sa");
        $sql6="insert into orderhis (orderdate,customeraddress,creditcard,customerid) values ('".$nowtime."','".inp($_POST['customeraddress'])."','".inp($_POST['creditcard'])."','".inp($id)."')";
        $res6=mysql_query($sql6,$con);
        //get orderid
        $sql7="SELECT LAST_INSERT_ID()";
        $res7=mysql_query($sql7,$con);
        $row7 = mysql_fetch_assoc($res7);
        $lastid=$row7['LAST_INSERT_ID()'];
        //echo $row7['LAST_INSERT_ID()'];
        //echo $id;
        //get all orders need to be placed
        $sql8="select * from orders where customerid='".$id."'";
        $res8=mysql_query($sql8,$con);
        while($row8 = mysql_fetch_assoc($res8))
        {
            //$row8['productid'] $row8['quantity']
            //need to handle for specialsales
            $sql9="select * from product where productid='".$row8['productid']."'";
            $res9=mysql_query($sql9,$con);
            $row9 = mysql_fetch_assoc($res9);
            
            //specialsales
            $sql18="select * from specialsales where productid='".$row8['productid']."'";
            $res18=mysql_query($sql18,$con);
            
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

            
            $sql10="insert into orderitems (orderid,productid,productquantity,productprice) values ('".$lastid."','".$row8['productid']."','".$row8['quantity']."','".$value4."')";
            $res10=mysql_query($sql10,$con);
        }
        $sql11="delete from orders where customerid='".$id."'";
        $res11=mysql_query($sql11,$con);
        //add some php validate
       // header("Location: search.php");
       // exit;
    }

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CS571 Assignment 3</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script type="text/javascript" src="jsfile.js"></script>
    </head>
<!--
    <script language="JavaScript">
    var titletxt="The world’s largest online video retail store!";
    var pos=0;
    function movetitle()
    {
        document.form1.titlefield.value=titletxt.substring(0,pos);
        if(pos++!=titletxt.length)
        {
            setTimeout("movetitle()",150);
        }
    }
    </script>
-->
    <body class="font-common" onload="movetitle()">
        <div class="box1" style="background-color:#232f3d">

            <div style="display:inline-block;height:80px;width:140px;margin:0 40px 0 40px;">
                <img src="pic/bunnyfox.png" height="80" width="140">
            </div>

            <div style="display:inline-block;color:#888888;height:40px;width:500px;position:relative;bottom:10px">
                <div style="width: 750px; height: 40px;position:relative;bottom:20px">
<form METHOD=POST action="search.php">
<div style="width: auto; height: 40px; float: left; display:inline;">
<select id="productcategoryid" name="productcategoryid" style="font-size:10px;color:#444444;height:30px;width:150px;background-color:white">
<option value="%">All Product</option>
<?php
    $sql2="select * from productcategory";
    $res2=mysql_query($sql2,$con);
    while($row2 = mysql_fetch_assoc($res2))
    {
        echo '<option value="';
        echo $row2['productcategoryid'].'">';
        echo $row2['productcategoryname'].'</option>';
    }
    ?>
</select>
</div>

<div style="width: auto; height: auto; float: left; display: inline">

<input class="input" id="productid" name="productid" type="text" size="40px" style="border:1px; height:30px;  font-size:20px"/>

</div>

<div style="width: auto; height: auto; float: left; display: inline">
<button id="searchbutton" class="searchbutton" style="width: 100px; height: 30px;border-radius:3px;"  >Search</button>
</div>
</form>
                </div>
            </div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<FORM METHOD=POST ACTION="ordersucc.php">
<input type="hidden" name="operation" value="logout">
<button class="rightbutton">Logout</button>
</FORM>
</div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action="orders.php">
<button class="rightbutton">Cart</button>
</form>
</div>

<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action="orderhis.php">
<button class="rightbutton">Order History</button>
</form>
</div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action="accountinfo.php">
<button class="rightbutton" ><span style="color:#888888">Hello <?php echo $un;?></br></span>
<strong>Your Account</strong></button>
</form>
</div>


        </div>
<div class="box2" style="border:1px solid white">

        </div>
        <div class="box3" style="border:1px solid white">
            <span style="font-size:40px;"><center>Your order has been successfully placed.</center></span>
        </div>
    </body>
</html>
