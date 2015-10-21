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
    
  //  echo " 1: ".$_POST['productname'];
   // echo " 2: ".$_POST['operation'];
   // echo " 3: ".$_POST['product'];
   // echo " 4: ".$_POST['start'];
   // echo " 5: ".$_POST['end'];
    
    if(strlen(inp($_POST['productname']))!=0 && $_POST['operation']=="add"){
        
        $sql1="insert into specialsales (productid,startdate,enddate) VALUES ('".inp($_POST['productname'])."','".inp($_POST['start'])."','".inp($_POST['end'])."')";
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
            $sql2="update specialsales set productid='".inp($_POST['productname'])."', startdate='".inp($_POST['start'])."',enddate='".inp($_POST['end'])."' where specialsalesid='".inp($_POST['product'])."'";
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
       
        $sql="select * from specialsales where specialsalesid='".inp($_POST['product'])."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment3',$con);
        $res=mysql_query($sql,$con);

        if($row = mysql_fetch_assoc($res))
        {
          //  date_default_timezone_set("UTC");
            $value1=$row['productid'];
           // $sql10="SELECT FROM_UNIXTIME(".$row['startdate'].", '%Y-%m-%d')";
           // $res10=mysql_query($sql10,$con);
           // $row10 = mysql_fetch_assoc($res10);
            $value2=$row['startdate'];
            $value3=$row['enddate'];
        }
    }
    
   // echo $value2."   gjjhgh  ";
   // echo $value3;
    
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
            <FORM METHOD=POST ACTION="addchangeS.php" onsubmit="return validate_form3(this)">



                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;Product:</label>
                    <select id="productname" name="productname" class="selectbox" style="display:inline;">
                        <option value="undefined">Select One</option>
                        <?php
                            $sql="select * from product";
                            $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                            if(!$con){
                                die;//
                            }
                            mysql_select_db('assignment3',$con);
                            $res=mysql_query($sql,$con);
                            while ($row=mysql_fetch_assoc($res)  ){
                                if($row['productid']==$value1){
                                    echo "<option selected value='".$row['productid']."'>".$row['productname']."</option>";
                                }else{
                                    echo "<option value='".$row['productid']."'>".$row['productname']."</option>";
                                }
                       
                            }
    
                        ?>
                    </select>

                </div>
                <div class="textbox" style="top:15px;">
                    <label>Start Date:</label>
                    <input  type="date" name="start" id="start" value=<?php echo "'".$value2."'"; ?> onblur='document.getElementById("mess3").style.display="none"' onfocus='document.getElementById("mess3").style.display="block"' />
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;End Date:</label>
                    <input  type="date" name="end" id="end" value=<?php echo "'".$value3."'"; ?> onblur='document.getElementById("mess3").style.display="none"' onfocus='document.getElementById("mess3").style.display="block"' />
                </div>

                <div class="textbox" style="top:20px;">
                    <span style="font-size:12px; color:red;visibility:hidden"  id="endvalid">
                        <img src="pic/alert.png" style="position:relative;top:5px">
                        The Special Sales's end date should be after the Start Date.
                    </span>

                </div>

                <div id="mess3" style="display:none;position:relative;top:20px;left:0px;line-height:20px;z-index:9999;font-size:12px;width:400px;">

                    <span style="font-size:12px; color:red" >
                        <img src="pic/alert.png" style="position:relative;top:5px">
                            Date should be as the following format:"YYYY-MM-DD". And the last date employees can add is 2015-12-31.
                    </span>


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
                    <BUTTON TYPE="submit" name="oper"><?php echo $_POST['operation'] ?> Special Sales</button>
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
