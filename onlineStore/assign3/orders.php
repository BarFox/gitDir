<?php
    session_start();
    if($_POST['operation']=="logout"){
        session_destroy();
        header("Location: login.php");
        exit;
    }//timeout
    $id=$_SESSION['customerid'];
    $un=$_SESSION['username'];
    $pw=$_SESSION['password'];
    $t0=$_SESSION['accesstime'];
    $nowtime=time();
    //echo $nowtime;
    //echo '<br>';
    //echo $t0;
    if($nowtime-$t0>1440){
        session_destroy();
        header("Location: login.php");
        exit;
    }

    function inp($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
    //before here is session
    $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
    if(!$con){
        die;//
    }
    mysql_select_db('assignment3',$con);
    $sql0="select * from customer where username='".$un."'";
    $res0=mysql_query($sql0,$con);
    if(!($row0 = mysql_fetch_assoc($res0))){
        header("Location: login.php");
        exit;
    }
    
    
    $productid=inp($_GET['productid']);
    if(inp($_GET['addtocart'])=='addtocart'){
        // echo "got here?";
        // echo "productid: ".$productid;
        // echo "customerid: ".$id;
        $sql5="select * from orders where productid='".$productid."' and customerid='".$id."'";
        $res5=mysql_query($sql5,$con);
        if($row5 = mysql_fetch_assoc($res5))
        {
            $tmp=$row5['quantity']+1;
            $sql6="update orders set quantity='".$tmp."' where productid='".$productid."' and customerid='".$id."'";
            $res6=mysql_query($sql6,$con);
        }else{
            $sql4="insert into orders (productid,quantity,customerid) VALUES ('".$productid."','1','".$id."')";
            $res4=mysql_query($sql4,$con);
        }
    }

    
    
    if($_POST['delete']!=''&&$_POST['delete']!=null){
        $sql1="delete from orders where customerid='".$id."' and productid='".$_POST['delete']."'";
        $res1=mysql_query($sql1,$con);
    }
    if($_POST['deleteall']=='deleteall'){
        $sql1="delete from orders where customerid='".$id."'";
        $res1=mysql_query($sql1,$con);
    }
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CS571 Assignment 3</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script type="text/javascript" src="jsfile.js"></script>
    </head>
    <script language="JavaScript">
function changeqty(prod){
    var xmlhttp;
    var tmp="quantitytag"+prod;
    var str=document.getElementById("quantitytag"+prod).value;
    //alert(str);
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("pricetag").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","changeqty.php?q="+str+"&p="+prod,true);
    xmlhttp.send();
    
}
    </script>
    <body class="font-common" onload="movetitle()">
        <div class="box1" style="background-color:#232f3d">

            <div style="display:inline-block;height:80px;width:140px;margin:0 40px 0 40px;">
                <img src="pic/bunnyfox.png" height="80" width="140">
            </div>

            <div style="display:inline-block;color:#888888;height:40px;width:500px;position:relative;bottom:10px">
                <div style="width: 750px; height: 40px;position:relative;bottom:20px">
<form METHOD=POST action="search.php">
<div style="width: auto; height: 40px; float: left; display:inline;">
<select id="productcategoryid" name="productcategoryid" style="font-size:10px;color:#444444;height:30px;width:150px;background-color:white">
<option value="%">All Product</option>
<?php
    $sql2="select * from productcategory";
    $res2=mysql_query($sql2,$con);
    while($row2 = mysql_fetch_assoc($res2))
    {
        echo '<option value="';
        echo $row2['productcategoryid'].'">';
        echo $row2['productcategoryname'].'</option>';
    }
    ?>
</select>
</div>

<div style="width: auto; height: auto; float: left; display: inline">

<input class="input" id="productid" name="productid" type="text" size="40px" style="border:1px; height:30px;  font-size:20px"/>

</div>

<div style="width: auto; height: auto; float: left; display: inline">
<button id="searchbutton" class="searchbutton" style="width: 100px; height: 30px;border-radius:3px;"  >Search</button>
</div>
</form>
                </div>
            </div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<FORM METHOD=POST ACTION="orders.php">
<input type="hidden" name="operation" value="logout">
<button class="rightbutton">Logout</button>
</FORM>
</div>


<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action="orderhis.php">
<button class="rightbutton">Order History</button>
</form>
</div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action="accountinfo.php">
<button class="rightbutton" ><span style="color:#888888">Hello <?php echo $un;?></br></span>
<strong>Your Account</strong></button>
</form>
</div>

        </div>
        <div class="box2" style="height:auto;border:1px solid white;">

            <div style="float:left;width:800px;height:auto;display:block;padding:20px;margin:20px 10px 20px 0;padding:0 0 250px 0">
                <p style="font-size:26px;margin:0 auto auto 30px;font-weight:bold">Shopping Cart</p>
                
                
                <div style="float:left;width:490px;height:32px;display:block;position:relative;bottom:7px">
                    
                </div>
                <div style="float:left;width:100px;height:32px;display:block;padding:0;margin:0 10px 0 0;position:relative;bottom:7px;color:#575a5e">
                    <p >Price</p>
                </div>
                <div style="float:right;width:80px;height:32px;display:block;padding:0;margin:0 10px 0 0;position:relative;bottom:7px;color:#575a5e">
                    <p >Quantity</p>
                </div>
                 <div style="float:left;background-color:#edeff4;height:3px;width:800px;margin:0px 0 0 0"></div>
                <!--here is for php-->

                <?php
                   // echo $id;
                    $sql3="select * from orders where customerid='".$id."'";
                    $res3=mysql_query($sql3,$con);
                    $pricetotal=0;
                  //  echo $res3;
                    while($row3 = mysql_fetch_assoc($res3))//$row3['quantity']
                    {
                     //   echo $row3['productid'];
                        $sql4="select * from product where productid='".$row3['productid']."'";
                        $res4=mysql_query($sql4,$con);//$row4['productprice'] $row4['productname'] $row4['productimage']
                        $row4 = mysql_fetch_assoc($res4);
                        
                     //   echo $row4['productname'];
                        echo '<div style="float:left;width:420px;height:auto;display:block;padding:20px;margin:20px 10px 20px 0">';
                        echo '<div style="float:left;width:85px;height:96px;"> <img src=';
                        echo "'".$row4['productimage']."'";
                        echo 'height="96" width="80"></div>';
                        echo '<div style="float:left;width:310px;height:auto;margin:0 0 0 20px">';
                        echo ' <p style="font-size:20px;margin:0 auto auto 0px;font-weight:bold">';
                        echo '<a href="showP.php?productid='.$row4['productid'].'">';
                        echo $row4['productname'];
                        echo '</a></p>';
                        echo '<form method=POST action="orders.php">';//handle delete
                        echo '<div style="margin:30px 0 0 0">';
                        echo '<input type="hidden" name="delete" id="delete" value=';
                        echo "'".$row4['productid']."'>";
                        echo '<BUTTON  TYPE="submit" name="submit" style="color:#fff;border:1px solid black"> Delete</button>';
                        echo '</div>';
                        echo '</form>';
                        echo '</div>';
                        echo ' </div>';
                        echo ' <div style="float:left;width:100px;height:auto;display:block;margin:20px 10px 20px 0;padding:0 0 0 20px">';
                        
                        $sql8="select * from specialsales where productid='".$row4['productid']."'";
                        $res8=mysql_query($sql8,$con);
                        if( ($row8 = mysql_fetch_assoc($res8)) ){
                            
                            date_default_timezone_set("UTC");
                            // $nowtime=date("Y-m-d");
                            $nowyear=date("Y");
                            $nowmonth=date("m");
                            $nowday=date("d");
                            $nowstr=$nowyear.$nowmonth.$nowday;
                            //echo $nowstr;
                            $startstr=substr($row8['startdate'],0,4).substr($row8['startdate'],5,2).substr($row8['startdate'],8,2);
                            $endstr=substr($row8['enddate'],0,4).substr($row8['enddate'],5,2).substr($row8['enddate'],8,2);
                            
                            if( $startstr<=$nowstr &&$endstr>=$nowstr){
                                //echo specialsales
                                echo ' <p style="font-size:18px;bottom:5px;"><strike><span style="color:red"> $';
                                echo $row4['productprice'].'</span></strike></p>';
                                echo ' <p style="font-size:18px;bottom:5px;"><span style="color:red"> $';
                                echo $row4['productprice']*0.7.'</span></p>';
                                $pricetotal=$pricetotal+$row4['productprice']*0.7*$row3['quantity'];
                                
                            }
                            else{
                                //if date not OK just normal price
                                echo ' <p style="font-size:18px;bottom:5px;"><span style="color:red"> $';
                                echo $row4['productprice'].'</span></p>';
                                $pricetotal=$pricetotal+$row4['productprice']*$row3['quantity'];
                            }
                        }
                        else{
                            
                            //no special sale exist, just normal price
                            echo ' <p style="font-size:18px;bottom:5px;"><span style="color:red"> $';
                            echo $row4['productprice'].'</span></p>';
                            $pricetotal=$pricetotal+$row4['productprice']*$row3['quantity'];
                        }

                        
                        
                        
                        echo ' </div>';
                        echo ' <div style="float:right;width:100px;height:auto;display:block;padding:20px;margin:20px 10px 20px 0">';
                        echo ' <select id="quantitytag';
                        echo $row3['productid'].'"  class="selectbox" onchange="changeqty(';
                        echo "'".$row3['productid']."')";
                        echo '" style="">';
                        if($row3['quantity']==1)
                            echo '<option value="1" selected>1</option>';
                        else
                            echo '<option value="1" >1</option>';
                        
                        if($row3['quantity']==2)
                            echo '<option value="2" selected>2</option>';
                        else
                            echo '<option value="2" >2</option>';
                            
                        if($row3['quantity']==3)
                            echo '<option value="3" selected>3</option>';
                        else
                            echo '<option value="3" >3</option>';
                        
                        if($row3['quantity']==4)
                            echo '<option value="4" selected>4</option>';
                        else
                            echo '<option value="4" >4</option>';
                        
                        if($row3['quantity']==5)
                            echo '<option value="5" selected>5</option>';
                        else
                            echo '<option value="5">5</option>';
                        
                        if($row3['quantity']==6)
                            echo '<option value="6" selected>6</option>';
                        else
                            echo '<option value="6">6</option>';
                        
                        if($row3['quantity']==7)
                            echo '<option value="7" selected>7</option>';
                        else
                            echo '<option value="7">7</option>';
                        
                        if($row3['quantity']==8)
                            echo '<option value="8" selected>8</option>';
                        else
                            echo '<option value="8">8</option>';
                        
                        if($row3['quantity']==9)
                            echo '<option value="9" selected>9</option>';
                        else
                            echo '<option value="9">9</option>';
                        
                        if($row3['quantity']>=10)
                            echo '<option value="'.$row3['quantity'].'">10+</option>';
                        else
                            echo '<option value="'.$row3['quantity'].'">10+</option>';
                        
                        echo ' </select>';
                        echo '</div>';
                        echo ' <div style="float:left;background-color:#edeff4;height:3px;width:800px;margin:10px 0 0 0"></div>';
                        echo '';
                        echo '';
                        
                        
                    }

                ?>


                <!--stop here-->


            </div>
            <div style="float:right;width:250px;height:190px;display:block;padding:20px;margin:50px 10px 20px 0;background-color:#f3f3f3;border:1px solid #edeff4;border-radius:3px;">
                <p style="font-size:24px;font-weight:bold">Subtotal:<span style="color:red" id="pricetag"> $<?php echo $pricetotal; ?></span></p>
                <form method=POST action="confirminfo.php">
                    <input type="hidden" id="customerid" name="customerid" value=<?php echo $row3['productid']; ?>>
                    <BUTTON class="cartbutton" TYPE="submit" name="submit" style="color:#fff;border:1px solid black"> Process to Checkout</button>
                </form>
                <form method=POST action="orders.php">
                    <input type="hidden" id="deleteall" name="deleteall" value="deleteall">
                    <BUTTON class="buybutton" TYPE="submit" name="submit" style="color:#fff;border:1px solid black;height:28px;margin:20px 0 0 0;font-size:14px"> Delete All Products</button>
                </form>
            </div>




            <div style="float:right;width:250px;height:190px;display:block;padding:20px;margin:20px 10px 20px 0;border:1px solid white;border-radius:3px;">
        <?php
        $orginalid=inp($_GET['productid']);
         //   echo $orginalid;
        if(inp($_GET['addtocart'])=='addtocart'){
            //find productid
            $tmp=0;
            $sql21="select * from orderitems where productid='".$orginalid."'";
          //  echo $sql21;
            $res21=mysql_query($sql21,$con);
            while($row21 = mysql_fetch_assoc($res21)){
            //    echo $row21['orderid'];
                //for every order check if there are products bought together
                $sql22="select * from orderitems where orderid='".$row21['orderid']."' and productid!='".$orginalid."'";
         //       echo $sql22;
                $res22=mysql_query($sql22,$con);
                
while($row22 = mysql_fetch_assoc($res22)){
                $sql1="select * from product where productid='".$row22['productid']."'";
                $res1=mysql_query($sql1,$con);
                if($row1 = mysql_fetch_assoc($res1))
                {
                    //print
                    $tmp=1;
                    $value1=$row1['productcategoryid'];
                    $value2=$row1['productname'];
                    $value3=$row1['productdesc'];
                    $value4=$row1['productprice'];
                    $value5=$row1['productimage'];
                    echo '<p><strong>People buy this product also buy</strong></p>';
                    echo '<div style="float:left;width:150px;height:280px;padding:30px;margin:8px 8px 8px 8px;border:1px solid #edeff4">';
                    echo ' <div style="width:150px;height:180px;border:1px solid #edeff4">';
                    echo "<img src='";
                    echo $value5."'";
                    echo ' height="180" width="150">';
                    echo '</div>';
                    echo ' <p style="font-weight:bold"><a href="showP.php?productid='.$row1['productid'].'">';
                    echo $value2."</a></p>";
                    $sql4="select * from specialsales where productid='".$row1['productid']."'";
                    $res4=mysql_query($sql4,$con);
                    
                    if( ($row4 = mysql_fetch_assoc($res4)) ){
                        //row4['startdate']/enddate
                        date_default_timezone_set("UTC");
                        // $nowtime=date("Y-m-d");
                        $nowyear=date("Y");
                        $nowmonth=date("m");
                        $nowday=date("d");
                        $nowstr=$nowyear.$nowmonth.$nowday;
                        //echo $nowstr;
                        $startstr=substr($row4['startdate'],0,4).substr($row4['startdate'],5,2).substr($row4['startdate'],8,2);
                        $endstr=substr($row4['enddate'],0,4).substr($row4['enddate'],5,2).substr($row4['enddate'],8,2);
                        
                        if( $startstr<=$nowstr &&$endstr>=$nowstr){
                            echo '<p style="margin:0px 0 0 0"><strike>Price: $';//echo specialsales
                            echo $value4.'</strike></p>';
                            echo '<p style="margin:0px 0 0 0">Price: $';//echo specialsales
                            echo $value4*0.7.'</p>';
                            
                            
                        }
                        else{
                            echo '<p>Price: $';//if date not OK just normal price
                            echo $value4.'</p>';
                        }
                    }
                    else{
                        
                        echo '<p>Price: $';//no special sale exist, just normal price
                        echo $value4.'</p>';
                    }
                    echo ' </div>';
                    
                }
    if($tmp=1){
        break;
    }
}
                if($tmp=1){
                    break;
                }
            }
        }
        ?>


            </div>





        </div>




    </body>
</html>
