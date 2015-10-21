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

<?php
    $sql3="select * from orderhis where customerid='".$id."' and orderid='".$_GET['orderid']."'";
    $res3=mysql_query($sql3,$con);
    while($row3 = mysql_fetch_assoc($res3))
    {
        echo '<div style="height:auto;width:800px;border:1px solid #edeff4;border-radius:3px;">';
        //  echo ' <div style="height:250px;width:800px;border:1px solid #edeff4;border-radius:3px;">';
        echo '<div style="height:40px;width:790px;padding:5px;background-color:#f3f3f3">';
        echo '<div style="float:left;width:200px;height:45px;font-size:12px;letter-spacing:0px;line-height:30%">';
        echo '<p>ORDER PLACED<br></p>';
        echo "<p>".$row3['orderdate']."</p>";
        echo '</div>';
        echo '<div style="float:left;width:270px;height:45px;font-size:12px;letter-spacing:0px;line-height:30%">';
        echo '<p >SHIPPING ADDRESS<br></p>';
        echo "<p >".$row3['customeraddress']."</p>";
        echo '</div>';
        echo '<div style="float:left;width:200px;height:45px;font-size:12px;letter-spacing:0px;line-height:30%">';
        echo '<p >CREADIT CARD #<br></p>';
        echo "<p >".$row3['creditcard']."</p>";
        echo '</div>';
        echo '<div style="float:right;width:100px;height:45px;font-size:12px;letter-spacing:0px;line-height:30%">';
        echo '<p >ORDER #<br></p>';
        echo "<p >#".$row3['orderid']."</p>";
        echo ' </div>';
        echo '</div>';
        
        $sql4="select * from orderitems where orderid='".$row3['orderid']."'";
        $res4=mysql_query($sql4,$con);
        while($row4 = mysql_fetch_assoc($res4))
        {
            $sql5="select * from product where productid='".$row4['productid']."'";
            $res5=mysql_query($sql5,$con);
            $row5 = mysql_fetch_assoc($res5);
            echo '<div style="height:150px; width:760px;padding:20px">';
            echo '<div style="float:left;display:block;width:100px;height:120px;">';
            echo '<div style="float:left;width:100px;height:120px;"> <img src=';
            echo "'".$row5['productimage']."'";
            echo 'height="120" width="100"></div>';
            echo '</div>';
            echo '<div style="float:left;display:block;width:450px;height:auto;margin:0 0 0 30px;font-size:16px">';
            echo "<p>".$row5['productname']."</p>";
            echo ' </div>';
            echo '<div style="float:left;display:block;width:130px;height:80px;margin:0 0 0 30px;font-size:16px;padding:0px">';
            echo "<p style='margin:20px 0 20px 40px'>QUANTITY:".$row4['productquantity']."</p>";
            echo '<form method=GET action="showP.php">';
            echo '<input type="hidden" id="productid" name="productid" value=';
            echo "'".$row4['productid']."'>";
            echo '<BUTTON class="cartbutton" TYPE="submit" name="submit" style="color:#fff;border:1px solid black;width:100px;float:right">Buy It Again</button>';
            echo ' </div>';
            echo '<div style="float:left;display:block;width:450px;height:auto;margin:0 0 0 30px;font-size:16px">';
            echo ' <p style="color:red">';
            echo "$".$row4['productprice']."</p>";
            echo '</div>';
            
            echo '</div>';
            echo '<div style="float:left;background-color:#edeff4;height:1px;width:800px;margin:0px 0 0 0"></div>';
            
        }
        echo ' </div>';
        echo "<br>";
        echo "<br>";
    }
    ?>

        </div>
        <div class="box3" style="border:1px solid white">

        </div>
    </body>
</html>
