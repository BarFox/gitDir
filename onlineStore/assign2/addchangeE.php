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
    if($ut!='admin'){
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
    if(strlen(inp($_POST['username']))!=0 && $_POST['operation']=="add"){
        $sql1_1="insert into users (username,password,usertype) VALUES ('".inp($_POST['username'])."','".inp($_POST['password'])."','".inp($_POST['usertype'])."')";
        $con1=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con1){
            die;//
        }
        mysql_select_db('assignment2',$con1);
        $res1_1=mysql_query($sql1_1,$con1);
        $sql1_2="select userindex from users where username='".inp($_POST['username'])."'";
        $res1_2=mysql_query($sql1_2,$con1);
        if($row = mysql_fetch_assoc($res1_2)){
            $newuserindex=$row['userindex'];
        }
        $sql1_3="insert into employees (userindex,employeefname,employeelname,age,salary) VALUES ('".$newuserindex."','".inp($_POST['employeefname'])."','".inp($_POST['employeelname'])."','".inp($_POST['age'])."','".inp($_POST['salary'])."')";
        $res1_3=mysql_query($sql1_3,$con1);
        //after add one dowe need to go to oringinal page?
        header("Location: adminpage.php");
        exit;
    }else if(strlen(inp($_POST['username']))!=0 && $_POST['operation']=="change"){
        //change php
    //    if(){
            $sql2_1="update employees set employeefname='".inp($_POST['employeefname'])."',employeelname='".inp($_POST['employeelname'])."',age='".inp($_POST['age'])."',salary='".inp($_POST['salary'])."' where employeeid='".inp($_POST['employees'])."'";
        
        //    $sql2="update product set productprice='".$_POST['productprice']."' where productid='".$_POST['product']."'";
        
        
            $con2=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
            if(!$con2){
                die;//
            }
            mysql_select_db('assignment2',$con2);
            $res2_1=mysql_query($sql2_1,$con2);
        
            $sql2_2="select userindex from employees where employeeid='".inp($_POST['employees'])."'";
            $res2_2=mysql_query($sql2_2,$con2);
            if($row = mysql_fetch_assoc($res2_2)){
                $coruserindex=$row['userindex'];
            }
            $sql2_3="update users set username='".inp($_POST['username'])."',password='".inp($_POST['password'])."',usertype='".inp($_POST['usertype'])."' where userindex='".$coruserindex."'";
            $res2_3=mysql_query($sql2_3,$con2);
            header("Location: adminpage.php");
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
        $value5="";
        $value6="";
        $value7="";
        $sql3_1="select * from employees where employeeid='".inp($_POST['employees'])."'";
        $con3=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con3){
            die;//
        }
        mysql_select_db('assignment2',$con3);
        $res3_1=mysql_query($sql3_1,$con3);
        if($row = mysql_fetch_assoc($res3_1))
        {
            $index=$row['userindex'];
            $value4=$row['employeefname'];
            $value5=$row['employeelname'];
            $value6=$row['age'];
            $value7=$row['salary'];
        }
        
        $sql3_2="select * from users where userindex='".$index."'";
        $res3_2=mysql_query($sql3_2,$con3);
        if($row = mysql_fetch_assoc($res3_2))
        {
            $value1=$row['username'];
            $value2=$row['password'];
            $value3=$row['usertype'];
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
            <p>Admin <?php echo $un;?>, You are login!</p>
        </div>
        <div>
            <FORM METHOD=POST ACTION="addchangeE.php" onsubmit="return validate_form2(this)">

                <div class="textbox" style="top:15px;">
                    <label>&nbsp;username:</label>
                    <INPUT TYPE="text" name="username" id="username" value=<?php echo "'".$value1."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;password:</label>
                    <INPUT TYPE="text" name="password" id="password" value=<?php echo "'".$value2."'"; ?>>
                </div>
                <!--
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;usertype:</label>
                    <INPUT TYPE="text" name="usertype" id="usertype" value=<?php echo "'".$value3."'"; ?>>
                </div>
                -->
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;User Type:</label>
                    <select id="usertype" name="usertype" class="selectbox" style="display:inline;">
                        <option value="undefined">Select One</option>
                        <?php
                            if($value3=="employee"){
                                echo "<option selected value='employee'>employee</option>";
                                
                            }else{
                                echo "<option value='employee'>employee</option>";
                            }
                            if($value3=="admin"){
                                echo "<option selected value='admin'>admin</option>";
                                
                            }else{
                                echo "<option value='admin'>admin</option>";
                            }
                            if($value3=="manager"){
                                echo "<option selected value='manager'>manager</option>";
                                
                            }else{
                                echo "<option value='manager'>manager</option>";
                            }

                            
                            
                        ?>

                    </select>
                </div>





                <div class="textbox" style="top:15px;">
                    <label>first name:</label>
                    <INPUT TYPE="text" name="employeefname" id="employeefname" value=<?php echo "'".$value4."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>last name:</label>
                    <INPUT TYPE="text" name="employeelname" id="employeelname" value=<?php echo "'".$value5."'"; ?>>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;age:</label>
                    <INPUT TYPE="text" name="age" id="age" value=<?php echo "'".$value6."'"; ?> onblur='document.getElementById("mess6").style.display="none"' onfocus='document.getElementById("mess6").style.display="block"'>
                </div>
                <div class="textbox" style="top:15px;">
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;salary:</label>
                    <INPUT TYPE="text" name="salary" id="salary" value=<?php echo "'".$value7."'"; ?> onblur='document.getElementById("mess7").style.display="none"' onfocus='document.getElementById("mess7").style.display="block"'>
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
               //for change
                    if($_POST['operation']=="change"){
                        echo '<INPUT TYPE="hidden" name="employees" value="';
                        echo $_POST['employees'];
                        echo '">';
        
                    }
                
                    ?>

                </div>


                <div id="mess6" style="display:none;position:relative;top:20px;left:0px;line-height:20px;z-index:9999;font-size:12px;width:400px;">

                    <span style="font-size:12px; color:red" >
                        <img src="pic/alert.png" style="position:relative;top:5px">
                            Age should be a number! an int with no point.
                    </span>


                </div>
                <div id="mess7" style="display:none;position:relative;top:20px;left:0px;line-height:20px;z-index:9999;font-size:12px;width:400px;">

                    <span style="font-size:12px; color:red" >
                        <img src="pic/alert.png" style="position:relative;top:5px">
                            Salary should be a number!
                    </span>


                </div>




                <div class="textbox welcome" style="position:relative;top:20px">
                <BUTTON TYPE="submit" name="employee"><?php echo $_POST['operation'] ?> Employee</button>
                </div>
            </FORM>
            <FORM METHOD=POST ACTION="adminpage.php">
                <div class="textbox welcome" style="position:relative;top:20px">
                    <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
                </div>
            </FORM>
        </div>
    </div>
    </body>
</html>
