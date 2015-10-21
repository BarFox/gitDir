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
    
    $oper=$_POST['operation'];
    
    if($oper=="change"){
        $url="addchangeE.php";
    }else{
        $url="changedeleteE.php";
    }
    
    //delete is handled in the same page
    
    $employeeid=$_POST['employees'];
   // echo "success: ". $oper." ". $url." ".$prodid;
    
    if(strlen($employeeid)!=0){
        //select from employees for userindex
        $sql0="select userindex from employees where employeeid='".$employeeid."'";
        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con){
            die;//
        }
        mysql_select_db('assignment2',$con);
        $res0=mysql_query($sql0,$con);
        if($row = mysql_fetch_assoc($res0)){
            $userindex= $row['userindex'];
        }
        //delete from users table
        $sql1="delete from users where userindex='".$userindex."'";
        //delete from employees table
        $sql2="delete from employees where employeeid='".$employeeid."'";
        $res1=mysql_query($sql1,$con);
        $res2=mysql_query($sql2,$con);
        
        

    }
    
    
    ?>
<html>
    <head>
        <title>change/delete</title>
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
                <FORM METHOD=POST ACTION="<?php echo $url; ?>">

                    <?php
                        
                        $sql="select * from employees";
                        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                        if(!$con){
                            die;//
                        }
                        mysql_select_db('assignment2',$con);
                        $res=mysql_query($sql,$con);
                        // for the first one it is checked
                        if($row = mysql_fetch_assoc($res)){
                            echo '<div class="textbox welcome" >';
                            
                            echo '<input checked="true" type="radio" name="';
                            echo "employees";
                            echo '" value="';
                            echo $row['employeeid'];
                            echo '"  />';
                            
                            echo "Name: ".$row['employeefname']." ".$row['employeelname']."      Salary: $" . $row['salary'];
                            
                            echo "</div>";
                        }
                        
                        while($row = mysql_fetch_assoc($res))
                        {
                            echo '<div class="textbox welcome" >';
                            
                            echo '<input type="radio" name="';
                            echo "employees";
                            echo '" value="';
                            echo $row['employeeid'];
                            echo '"  />';
                            
                           echo "Name: ".$row['employeefname']." ".$row['employeelname']."      Salary: $" . $row['salary'];
                            
                            echo "</div>";
                        }
                        
                        if($oper=="delete"){
                            echo '<input type="hidden" name="operation" value="delete">';
                        }else if($oper=="change"){
                            //for change
                            echo '<input type="hidden" name="operation" value="change">';
                            //echo "1: ".$oper;
                        }
                       // echo "success";
                ?>
                    <div class="textbox welcome" >
                        <BUTTON TYPE="submit" ><?php echo $oper; ?> users</button>
                    </div>
                </FORM>
                <FORM METHOD=POST ACTION="adminpage.php">
                    <div class="textbox welcome">
                        <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
                    </div>
                </FORM>
            </div>
        </div>
    </body>
</html>
