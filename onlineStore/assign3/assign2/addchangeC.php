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
    mysql_select_db('assignment3',$con);
    $res0=mysql_query($sql0,$con);
    if(!($row = mysql_fetch_assoc($res0))){
        header("Location: login.php");
        exit;
    }
    
    
    
    //usertype
    if($ut!='employee'){
        //require 'login.php';
        //  die;//?
        header("Location: login.php");
        //  echo $ut." ".$un;
        exit;
    }
    // echo $ut;
    
    //handle add or change
    function inp($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
    
  //  echo "1. ".$_POST['productname'];
  //  echo "2. ".$_POST['operation'];
  //  echo "3. ".$_POST['product'];
    if(strlen(inp($_POST['productcategoryname']))!=0 && $_POST['operation']=="add"){
        $sql1="insert into productcategory (productcategoryname,productcategorydesc) VALUES ('".inp($_POST['productcategoryname'])."','".inp($_POST['productcategorydesc'])."')";
        $con1=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con1){
            die;//
        }
        mysql_select_db('assignment3',$con1);
        $res1=mysql_query($sql1,$con1);
        //after add one dowe need to go to oringinal page?
        header("Location: employeepage.php");
        exit;
    }else if(strlen(inp($_POST['productcategoryname']))!=0 && $_POST['operation']=="change"){
        //change php
    //    if(){
            $sql2="update productcategory set productcategoryname='".inp($_POST['productcategoryname'])."',productcategorydesc='".inp($_POST['productcategorydesc'])."' where productcategoryid='".inp($_POST['productcategory'])."'";
        //    $sql2="update product set productprice='".$_POST['productprice']."' where productid='".$_POST['product']."'";
        
        
            $con2=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
            if(!$con2){
                die;//
            }
            mysql_select_db('assignment3',$con2);
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
        $sql="select * from productcategory where productcategoryid='".inp($_POST['productcategory'])."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment3',$con);
        $res=mysql_query($sql,$con);

        if($row = mysql_fetch_assoc($res))
        {
            
            $value2=$row['productcategoryname'];
            $value3=$row['productcategorydesc'];
            
        }
    }
    
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
            <FORM METHOD=POST ACTION="addchangeC.php" onsubmit="return validate_form1(this)">

                <div class="textbox" style="top:15px;">
                <label>&nbsp;&nbsp;&nbsp;&nbsp;category:</label>
                    <INPUT TYPE="text" name="productcategoryname" id="productcategoryname" value=<?php echo "'".$value2."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>description:</label>
                    <INPUT TYPE="text" name="productcategorydesc" id="productcategorydesc" value=<?php echo "'".$value3."'"; ?>>
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
                        echo '<INPUT TYPE="hidden" name="productcategory" value="';
                        echo $_POST['productcategory'];
                        echo '">';
        
                    }
                
                    ?>
                </div>
                <div class="textbox welcome" style="position:relative;top:20px">
                <BUTTON TYPE="submit" name="Productcategory"><?php echo $_POST['operation'] ?> Category</button>
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
