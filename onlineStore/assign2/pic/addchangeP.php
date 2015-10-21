<?php
    session_start();
    $ut=$_SESSION['usertype'];
    $un=$_SESSION['username'];
    //echo "<p>username:".$ut." | usertype:".$un."</p>";
    //echo "employee";
    //1.check usertype, if wrong go to loginpage.
    //2.go to mysql and check username?
    /*
    if($ut!='employee'){
        //require 'login.php';
        //  die;//?
        header("Location: login.php");
        //  echo $ut." ".$un;
        exit;
    }
    // echo $ut;
     */
    //handle add or change
    
  //  echo "1. ".$_POST['productname'];
  //  echo "2. ".$_POST['operation'];
  //  echo "3. ".$_POST['product'];
    if(strlen($_POST['productname'])!=0 && $_POST['operation']=="add"){
        $sql1="insert into product (productcategoryid,productname,productdesc,productprice) VALUES ('".$_POST['productcategoryid']."','".$_POST['productname']."','".$_POST['productdesc']."','".$_POST['productprice']."')";
        $con1=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con1){
            die;//
        }
        mysql_select_db('assignment2',$con1);
        $res1=mysql_query($sql1,$con1);
        //after add one dowe need to go to oringinal page?
        header("Location: employeepage.php");
        exit;
    }else if(strlen($_POST['productname'])!=0 && $_POST['operation']=="change"){
        //change php
    //    if(){
            $sql2="update product set productcategoryid='".$_POST['productcategoryid']."',productname='".$_POST['productname']."',productdesc='".$_POST['productdesc']."',productprice='".$_POST['productprice']."' where productid='".$_POST['product']."'";
        //    $sql2="update product set productprice='".$_POST['productprice']."' where productid='".$_POST['product']."'";
        
        
            $con2=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
            if(!$con2){
                die;//
            }
            mysql_select_db('assignment2',$con2);
            $res2=mysql_query($sql2,$con2);
            header("Location: employeepage.php");
            exit;
      //  }
        
       // echo "here is the productid: ".$_POST['product'];
    }
    //set the default value of change
    if($_POST['operation']=="change"){
        $value1="";
        $value2="";
        $value3="";
        $value4="";
        $sql="select * from product where productid='".$_POST['product']."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment2',$con);
        $res=mysql_query($sql,$con);

        if($row = mysql_fetch_assoc($res))
        {
            $value1=$row['productcategoryid'];
            $value2=$row['productname'];
            $value3=$row['productdesc'];
            $value4=$row['productprice'];
        }
    }
    
    echo $value2."     ";
    echo $value3;
    
    ?>
<html>
    <head>
        <title>add/change</title>
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
            <FORM METHOD=POST ACTION="addchangeP.php" onsubmit="return validate_form(this)">
                <div class="textbox" style="top:15px;">
                    <label>category id:</label>
                    <INPUT TYPE="text" name="productcategoryid" value=<?php echo $value1; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;product:</label>
                    <INPUT TYPE="text" name="productname" id="productname" value=<?php echo $value2; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>description:</label>
                    <INPUT TYPE="text" name="productdesc" value=<?php echo $value3; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;price:</label>
                    <INPUT TYPE="text" name="productprice" value=<?php echo $value4; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <?php
                    echo '<INPUT TYPE="hidden" name="operation" value="';
                    /*if(){
                        echo "add";
                    }else{
                        echo "change";
                    }
                    */
                    echo $_POST['operation'];
                    echo '">';
               
                    if($_POST['operation']=="change"){
                        echo '<INPUT TYPE="hidden" name="product" value="';
                        echo $_POST['product'];
                        echo '">';
        
                    }
                
                    ?>
                </div>
                <div class="textbox welcome" style="position:relative;top:20px">
                <BUTTON TYPE="submit" name="Product"><?php echo $_POST['operation'] ?> Product</button>
                </div>
            </FORM>
            <FORM METHOD=POST ACTION="employeepage.php">
                <div class="textbox welcome" style="position:relative;top:20px">
                    <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
                </div>
            </FORM>
        </div>
    </div>
    </body>
</html>
