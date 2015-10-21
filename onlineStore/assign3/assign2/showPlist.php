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
    
    function inp($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
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
    
    $lowlimit=inp($_POST['lowlimit']);
    $highlimit=inp($_POST['highlimit']);
    $productid=inp($_POST['productname']);
    $category=inp($_POST['category']);
    echo "asd ".$category." dasd";
    //delete is handled in the same page
    if(strlen($lowlimit)==0){
        $lowlimit=0;
    }
    if($productid=='undefined'){
        $productid="%";
    }
    if(strlen($highlimit)==0){
        $highlimit=99999999;//this is not a good way
    }
    if($category=='undefined'){
        $category="%";
    }
    
    $sql="select * from product where productprice > ".$lowlimit." and productid like '".$productid."' and productprice< ".$highlimit." and productcategoryid like '".$category."'";
    echo "seccessful: ".$sql;
?>

<html>
<head>
<title>show product</title>
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
<FORM METHOD=POST ACTION="showP.php">

<?php
    
    
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment3',$con);
    $res=mysql_query($sql,$con);
    // for the first one it is checked
    if($row = mysql_fetch_assoc($res)){
        echo '<div class="textbox welcome" >';
        
        echo '<input checked="true" type="radio" name="';
        echo "product";
        echo '" value="';
        echo $row['productid'];
        echo '"  />';
        
        echo "Product: ".$row['productname'] . "      Price: $" . $row['productprice'];
        
        echo "</div>";
    }
    
    while($row = mysql_fetch_assoc($res))
    {
        echo '<div class="textbox welcome" >';
        
        echo '<input type="radio" name="';
        echo "product";
        echo '" value="';
        echo $row['productid'];
        echo '"  />';
        
        echo "Product: ".$row['productname'] . "      Price: $" . $row['productprice'];
        
        echo "</div>";
    }
    

    // echo "success";
    ?>
    <div class="textbox welcome" >
        <BUTTON TYPE="submit" >Show Product</button>
    </div>
</FORM>
    <FORM METHOD=POST ACTION="managerpage.php">
        <div class="textbox welcome">
            <BUTTON TYPE="submit" name="mainpage" >Main Page</button>
        </div>
    </FORM>
</div>
</div>
</body>
</html>
