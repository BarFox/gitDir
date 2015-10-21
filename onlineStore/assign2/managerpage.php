<?php
    session_start();
    //destroy session
    if($_POST['operation']=="logout"){
        session_destroy();
        // echo "lol: ".$_SESSION['usertype'];
        header("Location: login.php");
        exit;
    }
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
    mysql_select_db('assignment2',$con);
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
    ?>
<html>
<head>
<title>Manager</title>
<link rel="stylesheet" type="text/css" href="loginstyle.css" />
<script type="text/javascript" src="jsfile.js"></script>
</head>
<body style="background-color:#555555" class="font-common" >
<div class="mainbox opacy" id="box1" style="display:block;<!--background:url(pic/bg1.jpg);-->">
<div style="postion:relative;top:150px;left:100px">
<!--<h3 >BetterAmazon</h3>
<h5 >Please login!</h5>-->
<img src="pic/bunnyfox.jpg">
</div>
<div class="welcome">
<p>Manager <?php echo $un;?>, You are login!</p>
</div>
<div>
<FORM METHOD=POST ACTION="showPlist.php" onsubmit="return validate_formM1(this)">

    <div class="textbox" style="top:15px;">
        <label>Product Price></label>
        <INPUT TYPE="text" name="lowlimit" id="lowlimit" value="">
    </div>

    <div class="textbox" style="top:15px;">
        <label>Product Price<</label>
        <INPUT TYPE="text" name="highlimit" id="highlimit" value="">
    </div>
    <div class="textbox" style="top:15px;">
        <label>Product Name:</label>
        <select id="productname" name="productname" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <?php
                $sql="select * from product";
                $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                if(!$con){
                    die;//
                }
                mysql_select_db('assignment2',$con);
                $res=mysql_query($sql,$con);
                while ($row=mysql_fetch_assoc($res)  ){
                    echo "<option value='".$row['productid']."'>".$row['productname']."</option>";
                   // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                }

            ?>
        </select>

    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Category:</label>
        <select id="category" name="category" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <?php
                $sql="select * from productcategory";
                $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                if(!$con){
                    die;//
                }
                mysql_select_db('assignment2',$con);
                $res=mysql_query($sql,$con);
                while ($row=mysql_fetch_assoc($res)  ){
                    echo "<option value='".$row['productcategoryid']."'>".$row['productcategoryname']."</option>";
                    // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                }
    
            ?>
        </select>
    </div>
    <div class="textbox welcome" style="position:relative;top:15px">
        <BUTTON TYPE="submit" name="show product">Show Product</button>
    </div>
</FORM>

<FORM METHOD=POST ACTION="showElist.php" onsubmit="return validate_formM2(this)">
    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Salary></label>
        <INPUT TYPE="text" name="lowlimit" id="lowlimitS" value="">
    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Salary<</label>
        <INPUT TYPE="text" name="highlimit" id="highlimitS" value="">
    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type:</label>
        <select id="usertype" name="usertype" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <option value="employee">employee</option>
            <option value="admin">admin</option>
            <option value="manager">manager</option>
        </select>
    </div>

    <div class="textbox welcome" style="position:relative;top:15px">
    <BUTTON TYPE="submit" name="show employee" >Show Employee</button>
    </div>
</FORM>
<FORM METHOD=POST ACTION="showSlist.php" onsubmit="return validate_formM3(this)">

    <div class="textbox" style="top:15px;">
    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sale Price></label>
        <INPUT TYPE="text" name="lowlimit" id="lowlimitH" value="" onblur='document.getElementById("mess4").style.display="none"' onfocus='document.getElementById("mess4").style.display="block"'>
    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sale Price<</label>
        <INPUT TYPE="text" name="highlimit" id="highlimitH" value="" onblur='document.getElementById("mess4").style.display="none"' onfocus='document.getElementById("mess4").style.display="block"'>
    </div>
    <div class="textbox" style="top:15px;">
        <label>Product Name:</label>
        <select id="productname" name="productname" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <?php
                $sql="select * from product";
                $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                if(!$con){
                    die;//
                }
                mysql_select_db('assignment2',$con);
                $res=mysql_query($sql,$con);
                while ($row=mysql_fetch_assoc($res)  ){
                    echo "<option value='".$row['productid']."'>".$row['productname']."</option>";
                    // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                }
    
            ?>
    </select>

    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Category:</label>
        <select id="category" name="category" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <?php
                $sql="select * from productcategory";
                $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                if(!$con){
                    die;//
                }
                mysql_select_db('assignment2',$con);
                $res=mysql_query($sql,$con);
                while ($row=mysql_fetch_assoc($res)  ){
                    echo "<option value='".$row['productcategoryid']."'>".$row['productcategoryname']."</option>";
                    // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                }
    
            ?>
        </select>
    </div>
    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start Date:</label>
        <INPUT TYPE="date" name="start" id="start" value="">
    </div>

    <div class="textbox" style="top:15px;">
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End Date:</label>
        <INPUT TYPE="date" name="end" id="end" value="">
    </div>

    <div id="mess4" style="display:none;position:relative;top:20px;left:0px;line-height:20px;z-index:9999;font-size:12px;width:400px;">
        <span style="font-size:12px; color:red" >
            <img src="pic/alert.png" style="position:relative;top:5px">
                Product in Special Sales is 70% of the original price.
        </span>
    </div>


    <div class="textbox welcome" style="position:relative;top:15px">
    <BUTTON TYPE="submit" name="show category" >Show Special Sales</button>
    </div>
</FORM>


    <br>

    <FORM METHOD=POST ACTION="managerpage.php">
        <div class="textbox welcome">
            <input type="hidden" name="operation" value="logout">
            <BUTTON TYPE="submit" name="logout" >Logout</button>
        </div>
    </FORM>



</div>
</div>
</body>
</html>
