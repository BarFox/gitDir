<?php
 //   $sql3="select * from customer where customerid='".$id."'";
 //   $res3=mysql_query($sql3);
    //$row3 = mysql_fetch_assoc($res3);
    //show info
    foreach ($res3 as $row3)
    {}
?>
<html>
    <head>
        <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
        <meta name="viewport" content="width=device-width" />
        <title>CS571 Assignment 3</title>
        <link rel="stylesheet" type="text/css" href= <?php echo "'".asset_url()."style.css'"; ?>  />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js">
        </script>
        <script type="text/javascript" src=<?php echo "'".asset_url()."jsfile.js'"; ?> ></script>
    </head>

    <body class="font-common" onload="movetitle()" style="background-color:#edeff4;">
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
                                    //  $sql2="select * from productcategory";
                                    //  $res2=mysql_query($sql2);
                               //     while($row2 = mysql_fetch_assoc($search_res))
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
                <FORM METHOD=POST ACTION=<?php echo site_url('Login'); ?> >
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
        <div class="box2" style="height:450px;margin:0 auto 0 auto;border:1px solid #edeff4;font-size:20px;color:#999999">
            <p style="font-size:30px;margin:0 auto auto 30px;font-weight:bold">Confirm Your Information<span style="margin:0 auto auto 40px;font-size:18px;color:#e47911">
            To watch Awesome Videos Online!</span></p>
            <FORM METHOD=POST ACTION=<?php echo site_url('Ordersucc'); ?> id="infoform">
                <div style="display:block;float:left;width:500px;height:350px;border:1px solid #bdc7d7;border-radius:6px;" id="accountbox1">

                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;username:</label>
                        <INPUT TYPE="text" name="username" id="username" class="inputbox" value=<?php echo "'".$row3['username']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="username could not be empty, only digits and letters (capital or lowercase) are allowed."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    
                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;name:</label>
                        <INPUT TYPE="text" name="customername" id="customername" class="inputbox" value=<?php echo "'".$row3['customername']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="name could not be empty, only digits, letters (capital or lowercase) and space are allowed."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 100px auto 30px;float:right">
                        <label>&nbsp;address:</label>
                        <INPUT TYPE="text" name="customeraddress" id="customeraddress" class="inputbox" value=<?php echo "'".$row3['customeraddress']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="address could not be empty, only digits, letters (capital or lowercase) and space are allowed."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>

                </div>
                <div style="display:block;float:right;width:500px;height:350px;border:1px solid #bdc7d7;border-radius:6px;" id="accountbox2">
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;credit card #: </label>
                        <INPUT TYPE="text" name="creditcard" id="creditcard" class="inputbox" value=<?php echo "'".$row3['creditcard']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="creditcard # could not be empty and should be a number."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;security code:</label>
                        <INPUT TYPE="text" name="securitycode" id="securitycode" class="inputbox" value=<?php echo "'".$row3['securitycode']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="security code could not be empty and should be a 3-digit number."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div class="textbox" style="margin:10px 60px auto 30px;float:right">
                        <label>&nbsp;expiration date:</label>
                        <INPUT TYPE="text" name="expirationdate" id="expirationdate" class="inputbox" value=<?php echo "'".$row3['expirationdate']."'"; ?> onfocus='document.getElementById("errormsg").innerHTML="expiration date could not be empty and should be a valid date  as the following format: MM/YY."' onblur='document.getElementById("errormsg").innerHTML=""'>
                    </div>
                    <div style="float:left;margin:30px 0 0 40px;font-size:12px;width:250px">
                        <p id="errormsg" style="color:red;"><?php echo "".$errormsg.""; ?></p>
                    </div>

                    <div class="textbox" style="margin:40px 45px auto 30px;float:right;">
                        <INPUT TYPE="hidden" name="succeed" id="succeed"  value="succeed">
                        <BUTTON class="submitbutton" TYPE="submit" name="submit" style="color:#fff;">Checkout</button>

                    </div>
                </div>
            </form>
        </div>

<script language="JavaScript">
$("#infoform").submit(function (event){
                      // alert(0);
                      if( validate_required(document.getElementById("username"),"Empty name")==false ){
                      document.getElementById("username").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("username").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      
                      
                      if (validate_required(document.getElementById("customername"),"Empty name")==false)
                      {
                      document.getElementById("customername").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("customername").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      if (validate_required(document.getElementById("customeraddress"),"Empty name")==false)
                      {
                      document.getElementById("customeraddress").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("customeraddress").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      if (validate_creditcard(document.getElementById("creditcard"),"Empty name")==false)
                      {
                      document.getElementById("creditcard").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("creditcard").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      if (validate_code(document.getElementById("securitycode"),"Empty name")==false)
                      {
                      document.getElementById("securitycode").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("securitycode").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      if (validate_date(document.getElementById("expirationdate"),"Empty name")==false)
                      {
                      document.getElementById("expirationdate").style.border="1px solid red";
                      event.preventDefault();
                      }else{
                      document.getElementById("expirationdate").style.border="1px solid #bdc7d7";
                      //    return;
                      }
                      
                      
                      return;
                      
                      });

</script>



    </body>
</html>
