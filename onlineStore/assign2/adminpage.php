<?php
    session_start();
    //destroy session
    function test_input($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
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
   if($ut!='admin'){
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
        <title>Admin</title>
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
         <FORM METHOD=POST ACTION="addchangeE.php">
            <div class="textbox welcome" >
                <input type="hidden" name="operation" value="add">
                <BUTTON TYPE="submit" name="Add Employee">Add Employee</button>
            </div>
         </FORM>

        <FORM METHOD=POST ACTION="changedeleteE.php">
            <div class="textbox welcome" >
                <input type="hidden" name="operation" value="change">
                <BUTTON TYPE="submit" name="Change Employee" >Change Employee</button>
            </div>
        </FORM>
        <FORM METHOD=POST ACTION="changedeleteE.php">
            <div class="textbox welcome">
                <input type="hidden" name="operation" value="delete">
                <BUTTON TYPE="submit" name="Delete Employee" >Delete Employee</button>
            </div>
        </FORM>

        <br>

        <FORM METHOD=POST ACTION="adminpage.php">
            <div class="textbox welcome">
                <input type="hidden" name="operation" value="logout">
                <BUTTON TYPE="submit" name="logout" >Logout</button>
            </div>
        </FORM>


    </div>
</div>
</body>
</html>
