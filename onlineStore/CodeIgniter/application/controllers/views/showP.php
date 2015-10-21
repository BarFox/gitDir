<?php
   // session_start();
    
    
   // $id=$_SESSION['customerid'];
   // $un=$_SESSION['username'];
   // $pw=$_SESSION['password'];
   // $t0=$_SESSION['accesstime'];
   // $nowtime=time();
    //echo $nowtime;
    //echo '<br>';
    //echo $t0;

    if($row1 = mysql_fetch_assoc($productid_res))
    {
        $value1=$row1['productcategoryid'];
        $value2=$row1['productname'];
        $value3=$row1['productdesc'];
        $value4=$row1['productprice'];
        $value5=$row1['productimage'];
        
    }
    /*
    $sql2="select * from productcategory where productcategoryid='".$value1."'";
    $res2=mysql_query($sql2,$con);
    
    if($row2 = mysql_fetch_assoc($res2)){
        $value6=$row2['productcategorydesc'];
        $value7=$row2['productcategoryname'];
    }
     */
    
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CS571 Assignment 3</title>
<link rel="stylesheet" type="text/css" href= <?php echo "'".asset_url()."style.css'"; ?>  />
<script type="text/javascript" src=<?php echo "'".asset_url()."jsfile.js'"; ?> ></script>
    </head>

    <body class="font-common" onload="movetitle()">
        <div class="box1" style="background-color:#232f3d">

            <div style="display:inline-block;height:80px;width:140px;margin:0 40px 0 40px;">
                <img src=<?php echo "'".asset_url()."pic/bunnyfox.png'"; ?> height="80" width="140">
            </div>

            <div style="display:inline-block;color:#888888;height:40px;width:500px;position:relative;bottom:10px">

                <div style="width: 750px; height: 40px;position:relative;bottom:20px">
<form METHOD=POST action=<?php echo site_url('Search'); ?>>
<div style="width: auto; height: 40px; float: left; display:inline;">
<select id="productcategoryid" name="productcategoryid" style="font-size:10px;color:#444444;height:30px;width:150px;background-color:white">
<option value="%">All Product</option>
<?php
   // $sql2="select * from productcategory";
   // $res2=mysql_query($sql2);
    while($row2 = mysql_fetch_assoc($search_res))
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
    <FORM METHOD=POST ACTION=<?php echo site_url('Login'); ?>>
        <input type="hidden" name="operation" value="logout">
        <button class="rightbutton">Logout</button>
    </FORM>
</div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action=<?php echo site_url('Orders'); ?>>
<button class="rightbutton">Cart</button>
</form>
</div>

<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action=<?php echo site_url('Orderhis'); ?>>
<button class="rightbutton">Order History</button>
</form>
</div>
<div style="display:inline-block;height:40px;width:auto;float:right;padding:5px;position:relative;top:35px;">
<form method=POST action=<?php echo site_url('Accountinfo'); ?>>
<button class="rightbutton" ><span style="color:#888888">Hello <?php echo $un;?></br></span>
<strong>Your Account</strong></button>
</form>
</div>

        </div>
        <div class="box2" style="background-color:#2f2f2f;height:370px;">
            <div style="display:block;width:830px;height:auto;float:left">

                <div style="width:550px;height:50px;margin:0px  0 0px 30px;color:#fff;position:relative;bottom:15px">
                    <p id="title" style="font-size:20px;"><?php echo $value2 ?></p>
                </div>

                <div style="display:block;float:left;width:250px;height:300px;margin:10px auto 30px 30px;color:#fff;position:relative;bottom:15px ">
                    <img src=<?php echo "'".asset_url()."".$value5."'" ?> height="300" width="250">
                </div>

                <div style="display:block;float:right;width:450px;height:300px;margin:10px 20px 30px 30px;color:#fff">

                    <p id="desc" style="line-height:25px;color:#eeeeee "><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;".$value3 ?></p>
                    <br>

                    <?php
                        
                        
                    //    $sql8="select * from specialsales where productid='".$productid."'";
                     //   $res8=mysql_query($sql8);
                        
                     //   if( ($row8 = mysql_fetch_assoc($res8)) )
                   //     echo "hello ".$row1['productid'];
                        if(($row1['specialsalesid']!="")&&($row1['specialsalesid']!=null))
                        {
                          //  echo 10;
                            date_default_timezone_set("UTC");
                            // $nowtime=date("Y-m-d");
                            $nowyear=date("Y");
                            $nowmonth=date("m");
                            $nowday=date("d");
                            $nowstr=$nowyear.$nowmonth.$nowday;
                            //echo $nowstr;
                            $startstr=substr($row1['startdate'],0,4).substr($row1['startdate'],5,2).substr($row1['startdate'],8,2);
                            $endstr=substr($row1['enddate'],0,4).substr($row1['enddate'],5,2).substr($row1['enddate'],8,2);
                            
                            if( $startstr<=$nowstr &&$endstr>=$nowstr){
                                //echo specialsales
                                echo '<p id="price" style="color:#cccccc;font-size:18px;margin:0 0 0 30px"><strike><strong>Price:</strong> $';
                                echo $value4.'</strike></p>';
                                echo '<p id="price" style="color:#cccccc;font-size:18px;margin:0 0 0 30px"><strong>Price:</strong> $';
                                echo $value4*0.7.'</p>';
                                
                                
                            }
                            else{
                                //if date not OK just normal price
                                echo '<p id="price" style="color:#cccccc;font-size:18px;margin:0 0 0 30px"><strong>Price:</strong> $';
                                echo $value4.'</p>';
                            }
                        }
                        else{
                           // echo 11;
                            //no special sale exist, just normal price
                            echo '<p id="price" style="color:#cccccc;font-size:18px;margin:0 0 0 30px"><strong>Price:</strong> $';
                            echo $value4.'</p>';
                        }

                   

                    ?>
                </div>

            </div>
            <div style="display:block;width:350px;height:auto;color:#fff;float:right">

                    <div style="margin:30px auto 0 40px">
                    <img src=<?php echo "'".asset_url()."pic/bunnyfox1.png'"; ?> height="80" width="140"><span style="position:relative;bottom:7px"> Video Store</span>

                    </div>
                    <form method=POST action=<?php echo site_url('Orders')."/index/".$productid."/addtocart"; ?>>
                        <input type="hidden" id="productid" name="productid" value=<?php echo "'".$productid."'"; ?>>
                        <input type="hidden" id="addtocart" name="addtocart" value="addtocart">
                        <button id="buynow" class="buybutton" style="margin:50px auto 20px 50px">Buy Now</button>
                    </form>
                    <form method=POST action=<?php echo site_url('ShowP')."/index/".$productid."/addtocart"; ?>>

                        <button id="addcart" class="cartbutton" style="margin:0px auto 30px 50px">Add to Shopping Cart</button>
                    </form>

                    <input type="checkbox" name="terms" id="Terms"
                        value="1" checked="checked" />  By placing your order, you agree to our Terms of Use. Sold by Bunny&Fox Digital Services, Inc.

            </div>





        </div>

<div class="box3" style="width:920px;height:400px;border:1px solid white">
<p><strong>Related Special Sales Product</strong></p>

<?php
   // $productid=$this->uri->segment(3);
   // echo "here ".$productid;
   // $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
   // if(!$con){
    //    die;//
   // }
   // mysql_select_db('assignment3',$con);

        
      //  echo "suc".$row5['productid'];
        
        
      //  $sql6="select * from product where productid='".$saleproduct."'";
      //  $res6=mysql_query($sql6);
        $row6 = mysql_fetch_assoc($saledetails);
if($row6['productid']!=null&&$row6['productid']!=""){
        $countnum=$countnum+1;
        echo '<div style="float:left;width:150px;height:280px;padding:30px;margin:8px 8px 8px 8px;border:1px solid #edeff4">';
        echo '<div style="width:150px;height:180px;border:1px solid #edeff4">';
        echo "<img src='".asset_url();
        echo $row6['productimage']."'";
        echo ' height="180" width="150">';
        echo ' </div>';
        
        echo ' <p style="font-weight:bold"><a href=';
        echo "'".site_url()."/ShowP/index/".$row6['productid']."'";
        echo '>';
        echo $row6['productname']."<a></p>";
       // $sql4="select * from specialsales where productid='".$saleproduct."'";
       // $res4=mysql_query($sql4);
        
        //if( ($row4 = mysql_fetch_assoc($res4)) )
   // echo "here1".$row6['productimage'];
   // echo "here2".$row6['specialsalesid'];
        if($row6['specialsalesid']!=""&&$row6['specialsalesid']!=null)
        {
            //row4['startdate']/enddate
            date_default_timezone_set("UTC");
            // $nowtime=date("Y-m-d");
            $nowyear=date("Y");
            $nowmonth=date("m");
            $nowday=date("d");
            $nowstr=$nowyear.$nowmonth.$nowday;
            //echo $nowstr;
            $startstr=substr($row6['startdate'],0,4).substr($row6['startdate'],5,2).substr($row6['startdate'],8,2);
            $endstr=substr($row6['enddate'],0,4).substr($row6['enddate'],5,2).substr($row6['enddate'],8,2);
            
            if( $startstr<=$nowstr &&$endstr>=$nowstr){
                //echo 10;
                echo '<p>Price: $';//echo specialsales
                echo $row6['productprice']*0.7.'</p>';
                
                
            }
            else{
                //echo 11;
                echo '<p>Price: $';//if date not OK just normal price
                echo $row6['productprice'].'</p>';
            }
        }
        else{
            //echo 12;
            echo '<p>Price: $';//no special sale exist, just normal price
            echo $row6['productprice'].'</p>';
        }
        
        
        echo '</div>';
        if($countnum==4){
            break;
        }
}
?>

</div>
<!--
        <div class="box3">
            <p style="font-size:20px;">Customers Who Watched This Item Also Watched</p>
            <div style="display:block-inline;float:left;width:150px;height:180px;margin:10px auto 30px 30px;color:#fff ">
                <p id="pic">pic</p>
            </div>
            <div style="display:block-inline;float:left;width:150px;height:180px;margin:10px auto 30px 30px;color:#fff ">
                <p id="pic">pic</p>
            </div>
            <div style="display:block-inline;float:left;width:150px;height:180px;margin:10px auto 30px 30px;color:#fff ">
                <p id="pic">pic</p>
            </div>
        </div>
-->
    </body>
</html>
