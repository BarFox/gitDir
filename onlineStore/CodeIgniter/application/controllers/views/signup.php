
<html>
    <head>

<meta name="viewport" content="width=device-width;initial-scale=1.0"/>
        <title>CS571 Assignment 3</title>
        <link rel="stylesheet" type="text/css" href=<?php echo "'".asset_url()."style.css'"; ?>  />
        <script type="text/javascript" src=<?php echo "'".asset_url()."jsfile.js'"; ?>></script>
    </head>

    <body class="font-common" onload="movetitle()" style="background-color:#edeff4;">
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
  //  $sql2="select * from productcategory";
  //  $res2=mysql_query($sql2,$con);
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
                <form method=POST action=<?php echo site_url('Login'); ?>>
                    <button class="rightbutton">Login</button>
                </form>
            </div>

        </div>
        <div class="box2" style="height:450px;margin:0 auto 0 auto;border:1px solid #edeff4;font-size:20px;color:#999999">
            <p style="font-size:30px;margin:0 auto auto 30px;font-weight:bold">Sign Up<span style="margin:0 auto auto 40px;font-size:18px;color:#e47911">
            To watch Awesome Videos Online!</span></p>
            <FORM METHOD=POST ACTION=<?php echo site_url('Signup'); ?> onsubmit="return validate_form1(this)">
            <div style="display:block;float:left;width:500px;height:350px;margin:30px;border:1px solid #bdc7d7;border-radius:6px;">

                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;username:</label>
                        <INPUT TYPE="text" name="username" id="username" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="username could not be empty."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;password:</label>
                        <INPUT TYPE="text" name="password0" id="password0" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="password could not be empty."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>

                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;name:</label>
                        <INPUT TYPE="text" name="customername" id="customername" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="name could not be empty."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;address:</label>
                        <INPUT TYPE="text" name="customeraddress" id="customeraddress" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="address could not be empty."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>

            </div>
            <div style="display:block;float:right;width:500px;height:350px;margin:30px;border:1px solid #bdc7d7;border-radius:6px;">
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;credit card #: </label>
                        <INPUT TYPE="text" name="creditcard" id="creditcard" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="creditcard # could not be empty and should be a number."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;security code:</label>
                        <INPUT TYPE="text" name="securitycode" id="securitycode" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="security code could not be empty and should be a 3-digit number."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;expiration date:</label>
                        <INPUT TYPE="text" name="expirationdate" id="expirationdate" class="inputbox" value="" onfocus='document.getElementById("errormsg").innerHTML="expiration date could not be empty and should be a valid date after 07/15, as the following format: MM/YY."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div style="float:left;margin:30px 0 0 40px;font-size:12px;width:250px">
                        <p id="errormsg" style="color:red;"><?php echo "".$errormsg.""; ?></p>
                    </div>
                    <div class="textbox" style="margin:40px 45px auto 30px;float:right;">
                        <INPUT TYPE="hidden" name="succeed" id="succeed"  value="succeed">
                        <BUTTON class="submitbutton" TYPE="submit" name="submit" style="color:#fff"> Submit</button>

                    </div>
                    

            </div>
            </form>
        </div>

    </body>
</html>
