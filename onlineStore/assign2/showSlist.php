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
    $start=inp($_POST['start']);
    $end=inp($_POST['end']);
    
    echo "asd ".$category." dasd";
    //delete is handled in the same page
    if(strlen($lowlimit)==0){
        $lowlimit=0;
    }else{
        $lowlimit=$lowlimit/0.7;
        echo $lowlimit;
    }
    if($productid=='undefined'){
        $productid="%";
    }else{
        $highlimit=$highlimit/0.7;
        echo $highlimit;
    }
    if(strlen($highlimit)==0){
        $highlimit=99999999;//this is not a good way
    }
    if($category=='undefined'){
        $category="%";
    }
    if(strlen($start)==0){
        $start="'%'";
    }else{
        $start="unix_timestamp('".$start."')";
    }
    if(strlen($end)==0){
        $end="'%'";
    }
    else{
        $end="unix_timestamp('".$end."')";
    }
    $sql="select * from specialsales,product where specialsales.productid=product.productid and product.productprice > ".$lowlimit." and product.productid like '".$productid."' and product.productprice< ".$highlimit." and product.productcategoryid like '".$category."' and specialsales.startdate like ".$start." and specialsales.enddate like ".$end."";
    //echo "seccessful: ".$sql;
?>

<html>
<head>
<title>show product</title>
<link rel="stylesheet" type="text/css" href="loginstyle.css" />
<script type="text/javascript" src="jsfile.js"></script>
</head>
<body style="background-color:#555555" class="font-common">
<div class="showbox opacy" id="box1" style="display:block;<!--background:url(pic/bg1.jpg);-->">
<div style="postion:relative;top:150px;left:100px">
<!--<h3 >BetterAmazon</h3>
<h5 >Please login!</h5>-->
<img src="pic/bunnyfox.jpg">
</div>
<div class="welcome">
<p>Employee <?php echo $un;?>, You are login!</p>

</div>
<div>
<FORM METHOD=POST ACTION="showS.php">

<?php
    
    
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment2',$con);
    $res=mysql_query($sql,$con);
    date_default_timezone_set("UTC");
    // for the first one it is checked
    if($row = mysql_fetch_assoc($res)){
        echo '<div class="textbox welcome" >';
        
        echo '<input checked="true" type="radio" name="';
        echo "product";
        echo '" value="';
        echo $row['productid'];
        echo '"  />';
        
        echo "Product: ".$row['productname']." &nbsp;&nbsp;Sale Price: $".$row['productprice']*0.7." &nbsp;&nbsp;&nbsp;Start Date: ".date('Y-m-d',$row['startdate'])."     &nbsp;&nbsp;&nbsp;End Date: ".date('Y-m-d',$row['enddate']);
        
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
        
        echo "Product: ".$row['productname']." &nbsp;&nbsp;Sale Price: $".$row['productprice']*0.7." &nbsp;&nbsp;&nbsp;Start Date: ".date('Y-m-d',$row['startdate'])."     &nbsp;&nbsp;&nbsp;End Date: ".date('Y-m-d',$row['enddate']);
        
        echo "</div>";
    }
    

    // echo "success";
    ?>
    
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
