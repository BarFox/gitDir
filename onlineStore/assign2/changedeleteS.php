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
    if($ut!='employee'){
        //require 'login.php';
        //  die;//?
        header("Location: login.php");
        //  echo $ut." ".$un;
        exit;
    }
    // echo $ut;
    
    
    $oper=$_POST['operation'];
    
    if($oper=="change"){
        $url="addchangeS.php";
    }else{
        $url="changedeleteS.php";
    }
    
    //delete is handled in the same page
    
    $ssid=$_POST['product'];
    echo "success: ". $oper." ". $url." ".$ssid;
    
    if(strlen($ssid)!=0){
        $sql0="delete from specialsales where specialsalesid='".$ssid."'";
        $con0=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
        if(!$con0){
            die;//
        }
        mysql_select_db('assignment2',$con0);
        $res0=mysql_query($sql0,$con0);
        echo "res0:".$res0;

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
                <p>Employee <?php echo $un;?>, You are login!</p>

            </div>
            <div>
                <FORM METHOD=POST ACTION="<?php echo $url; ?>">

                    <?php
                        
                        $sql="select * from specialsales,product where specialsales.productid=product.productid";
                        
                        $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                        if(!$con){
                            die;//
                        }
                        mysql_select_db('assignment2',$con);
                        $res=mysql_query($sql,$con);
                       // echo "success".$res;
                        // for the first one it is checked
                        if($row = mysql_fetch_assoc($res)){
                            echo '<div class="textbox welcome" >';
                            
                            echo '<input checked="true" type="radio" name="';
                            echo "product";
                            echo '" value="';
                            echo $row['specialsalesid'];
                            echo '"  />';
                            
                            echo "Product: ".$row['productname'];
                            
                            echo "</div>";
                            
                        }
                       // echo "seccess:".$row['productid'];
                        while($row = mysql_fetch_assoc($res))
                        {
                            echo '<div class="textbox welcome" >';
                            
                            echo '<input type="radio" name="';
                            echo "product";
                            echo '" value="';
                            echo $row['specialsalesid'];
                            echo '"  />';
                            
                            echo "Product: ".$row['productname'];
                            
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
                        <BUTTON TYPE="submit" ><?php echo $oper; ?> Special Sales</button>
                    </div>
                </FORM>
                <FORM METHOD=POST ACTION="employeepage.php">
                    <div class="textbox welcome">
                        <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
                    </div>
                </FORM>
            </div>
        </div>
    </body>
</html>
