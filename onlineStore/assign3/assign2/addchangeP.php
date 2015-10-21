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
    
    //IF(!){
        
   // }
    
    
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
    if(strlen(inp($_POST['productname']))!=0 && $_POST['operation']=="add"){
        move_uploaded_file($_FILES["file"]["tmp_name"], "../upload/" . $_FILES["file"]["name"]);
        $sql1="insert into product (productcategoryid,productname,productdesc,productprice,productimage) VALUES ('".inp($_POST['productcategoryid'])."','".inp($_POST['productname'])."','".inp($_POST['productdesc'])."','".inp($_POST['productprice'])."','upload/".$_FILES["file"]["name"]."')";
        $con1=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con1){
            die;//
        }
        mysql_select_db('assignment3',$con1);
        $res1=mysql_query($sql1,$con1);
        //echo "successful: ".$_POST['productcategoryid'];
        //after add one dowe need to go to oringinal page?
        header("Location: employeepage.php");
        exit;
    }else if(strlen(inp($_POST['productname']))!=0 && $_POST['operation']=="change"){
        //change php
    //    if(){
        $imagelink=$_POST['imagelink'];
        if($_FILES["file"]["name"]!=""&&$_FILES["file"]["name"]!=null){
            $imagelink="upload/".$_FILES["file"]["name"]."";
        }
      //  echo $imagelink;
            move_uploaded_file($_FILES["file"]["tmp_name"], "../upload/" . $_FILES["file"]["name"]);
            $sql2="update product set productcategoryid='".inp($_POST['productcategoryid'])."',productname='".inp($_POST['productname'])."',productdesc='".inp($_POST['productdesc'])."',productprice='".inp($_POST['productprice'])."',productimage='". $imagelink."' where productid='".inp($_POST['product'])."'";
     //   echo $sql2;
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
        $sql="select * from product where productid='".inp($_POST['product'])."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment3',$con);
        $res=mysql_query($sql,$con);

        if($row = mysql_fetch_assoc($res))
        {
            $value1=$row['productcategoryid'];
            $value2=$row['productname'];
            $value3=$row['productdesc'];
            $value4=$row['productprice'];
        }
    }
    
   // echo $value2."   gjjhgh  ";
   // echo $value3;
  //  echo $row['productimage'];
    $piclink=$row['productimage'];
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
            <FORM METHOD=POST ACTION="addchangeP.php" enctype="multipart/form-data" onsubmit="return validate_form(this)">
                <!--
                <div class="textbox" style="top:15px;">
                    <label>category id:</label>
                    <INPUT TYPE="text" name="productcategoryid" value=<?php echo "'".$value1."'"; ?>>
                </div>
                -->
                <div class="textbox" style="top:15px;">
                    <img src=<?php echo "'../".$row['productimage']."'"; ?> height="120" width="100">
                </div>
                <div class="textbox" style="top:15px;">
                    <label>Picture:</label>
                    <input type="file" name="file" id="file" />
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;Category:</label>
                    <select id="productcategoryid" name="productcategoryid" class="selectbox" style="display:inline;">
                        <option value="undefined">Select One</option>
                        <?php
                            $sql="select * from productcategory";
                            $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                            if(!$con){
                                die;//
                            }
                            mysql_select_db('assignment3',$con);
                            $res=mysql_query($sql,$con);
                            while ($row=mysql_fetch_assoc($res)  ){
                                if($row['productcategoryid']==$value1){
                                    echo "<option selected value='".$row['productcategoryid']."'>".$row['productcategoryname']."</option>";
                                    
                                }else{
                                    echo "<option value='".$row['productcategoryid']."'>".$row['productcategoryname']."</option>";
                                //    echo "categoryid ".$row['productcategoryid'];
                                }
                                
                                // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                            }
    
                        ?>
                    </select>
                </div>



                <div class="textbox" style="top:15px;">
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;product:</label>
                    <INPUT TYPE="text" id="productname" name="productname" id="productname" value=<?php echo "'".$value2."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>description:</label>
                    <INPUT TYPE="text" id="productdesc" name="productdesc" value=<?php echo "'".$value3."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;price:</label>
                    <INPUT TYPE="text" id="productprice" name="productprice" value=<?php echo "'".$value4."'"; ?> onblur='document.getElementById("mess5").style.display="none"' onfocus='document.getElementById("mess5").style.display="block"'>
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

                <div id="mess5" style="display:none;position:relative;top:20px;left:0px;line-height:20px;z-index:9999;font-size:12px;width:400px;">

                    <span style="font-size:12px; color:red" >
                        <img src="pic/alert.png" style="position:relative;top:5px">
                            Price should be a number!
                    </span>


                </div>
                <?php
             //   if($_POST['operation']=='change'){
                   echo '<input type="hidden" id="impagelink" name="imagelink" value=';;
                    echo "'".$piclink."'>";
                 //   echo "dwqeqwdw".$piclink;
             //   }
                ?>
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
