
<html>
    <head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<meta name="viewport" content="width=device-width" />
        <title>CS571 Assignment 3</title>
<link rel="stylesheet" type="text/css" href= <?php echo "'".asset_url()."style.css'"; ?>  />
<script type="text/javascript" src=<?php echo "'".asset_url()."jsfile.js'"; ?> ></script>
    </head>
<!--
    <script language="JavaScript">
    var titletxt="The world’s largest online video retail store!";
    var pos=0;
    function movetitle()
    {
        document.form1.titlefield.value=titletxt.substring(0,pos);
        if(pos++!=titletxt.length)
        {
            setTimeout("movetitle()",150);
        }
    }
    </script>
-->
    <body class="font-common" onload="movetitle()">
        <div class="box1" style="background-color:#232f3d">

            <div style="display:inline-block;height:80px;width:140px;margin:0 40px 0 40px;">
                <img src=<?php echo "'".asset_url()."pic/bunnyfox.png'"; ?> height="80" width="140">
            </div>

<div style="display:inline-block;color:#888888;height:40px;width:50px;position:relative;bottom:30px">
<div style=" height: 40px;position:relative;" id="phonebox2"> 
<form METHOD=POST action=<?php echo site_url('Search'); ?>>
<div style="width: auto; height: 40px; float: left; display:inline;">
<select id="productcategoryid" name="productcategoryid" style="font-size:10px;color:#444444;height:30px;width:150px;background-color:white" class="selectcategory">
<option value="%">All Product</option>
<?php
   // $sql2="select * from productcategory";
   // $res2=mysql_query($sql2);
   // while($row2 = mysql_fetch_assoc($search_res))
    foreach ($search_res as $row2)
    {
        echo '<option value="';
        echo $row2['productcategoryid'].'">';
        echo $row2['productcategoryname'].'</option>';
    }
    ?>
</select>
</div>

<div style="width: auto; height: auto; float: left; display: inline">

<input class="inputitle" id="productid" name="productid" type="text" size="40px" style="border:1px; height:30px;  font-size:20px"/>

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
<div class="box2" style="border:1px solid white">

        </div>
        <div class="box3" style="border:1px solid white">
            <span style="font-size:40px;"><center>Your order has been successfully placed.</center></span>
        </div>
    </body>
</html>
