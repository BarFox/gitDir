<?php
    session_start();
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
    //handle add or change
 
        $value1="";
        $value2="";
        $value3="";
        $value4="";
        $value5="";
        $value6="";
        $sql1="select * from users,employees where users.userindex=employees.userindex and users.userindex=".$_POST['userindex']."";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment2',$con);
        $res1=mysql_query($sql1,$con);

        if($row1 = mysql_fetch_assoc($res1))
        {
            $value1=$row1['username'];
            $value2=$row1['password'];
            $value3=$row1['usertype'];
            $value4=$row1['employeefname'];
            $value5=$row1['employeelname'];
            $value6=$row1['age'];
            $value7=$row1['salary'];
        }
    /*
        $sql2="select * from productcategory where productcategoryid='".$value1."'";
        $res2=mysql_query($sql2,$con);
    
        if($row2 = mysql_fetch_assoc($res2)){
            $value5=$row2['productcategorydesc'];
            $value6=$row2['productcategoryname'];
        }
     */
    //echo $value2."   gjjhgh  ";
    //echo $value3;
    //echo $sql1;
    
    ?>
<html>
    <head>
        <title>show employees</title>
        <link rel="stylesheet" type="text/css" href="loginstyle.css" />
        <script type="text/javascript" src="jsfile.js"></script>
    </head>
    <body style="background-color:#555555" class="font-common">
    <div class="mainbox opacy" id="box1" style="display:block;<!--background:url(pic/bg1.jpg);-->">
        <div style="postion:relative;top:150px;left:100px">
        <!--<h3 >BetterAmazon</h3>
        <h5 >Please login!</h5>-->
        <img src="pic/bunnyfox.jpg">
        </div>
        <div class="welcome">
            <p>Employee <?php echo $un;?>, You are login!</p>
        </div>
        <div>
            <FORM>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;username:</label>
                    <INPUT TYPE="text" name="productname" id="productname" value=<?php echo "'".$value1."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                <label>&nbsp;&nbsp;password:</label>
                    <INPUT TYPE="text" name="productname" id="productname" value=<?php echo "'".$value2."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;usertype:</label>
                    <INPUT TYPE="text" name="productdesc" value=<?php echo "'".$value3."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>first name:</label>
                    <INPUT TYPE="text" name="productprice" value=<?php echo "'".$value4."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>last name:</label>
                    <INPUT TYPE="text" name="productcategoryid" value=<?php echo "'".$value5."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;age:</label>
                    <INPUT TYPE="text" name="productcategoryid" value=<?php echo "'".$value6."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;salary:</label>
                    <INPUT TYPE="text" name="productcategoryid" value=<?php echo "'".$value7."'"; ?>>
                </div>
            </FORM>
            <FORM METHOD=POST ACTION="managerpage.php">
                <div class="textbox welcome" style="position:relative;top:20px">
                    <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
                </div>
            </FORM>
        </div>
    </div>
    </body>
</html>
