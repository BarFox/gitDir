
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

<input class="input" id="productid" name="productid" type="text" size="40px" value=<?php echo "'".$productid_res."'"; ?> style="border:1px; height:30px;  font-size:20px"/>

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
            <div style="float:left;width:150px;height:auto;padding:20px">

            </div>
            <div style="float:left;width:930px;height:auto;padding:30px;border:1px solid #edeff4">

                <?php
                     //for php
                   // echo "sss";
                  //  echo site_url('Login');
                    while($row1 = mysql_fetch_assoc($product_res))
                    {
                       // echo "got".$row1['productname'];
                        
                        $value1=$row1['productcategoryid'];
                        
                        $value2=$row1['productname'];
                        $value3=$row1['productdesc'];
                        $value4=$row1['productprice'];
                        $value5=$row1['productimage'];
                        //echo "here".$row1['productid'];
                        echo '<div style="float:left;width:150px;height:280px;padding:30px;margin:8px 8px 8px 8px;border:1px solid #edeff4">';
                        echo ' <div style="width:150px;height:180px;border:1px solid #edeff4">';
                        echo "<img src='".asset_url();
                        echo $value5."'";
                        echo ' height="180" width="150">';
                        echo '</div>';
                        echo ' <p style="font-weight:bold"><a href=';
                      //  $tmp="'ShowP/index/".$row1['productid']."'";
                        echo "'".site_url()."/ShowP/index/".$row1['pid']."'";
                        echo '>';
                        echo $value2."</a></p>";
                       // $sql4="select * from specialsales where productid='".$row1['productid']."'";
                       // $res4=mysql_query($sql4);
                      //  echo "got ".row1['speicalsalesid'];
                   //     if( ($row4 = mysql_fetch_assoc($res4)) ){
                     //   echo "got".$row1['specialsalesid'];
                        
                        if(($row1['specialsalesid']!="")&&($row1['specialsalesid']!=null))
                        {
                         //   echo 00;
                            //row4['startdate']/enddate
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
                          //  echo 11;
                            echo '<p>Price: $';//no special sale exist, just normal price
                            echo $value4.'</p>';
                        }
                        
                        echo ' </div>';
                         
    
                    }
                
                /*
                <div style="float:left;width:150px;height:280px;padding:30px;margin:8px 8px 8px 8px;border:1px solid #edeff4">
                    <div style="width:150px;height:180px;border:1px solid #edeff4">
                    </div>
                    <p style="font-weight:bold">title</p>
                    <p>price</p>
                </div>
                 */
                  //end of php
                ?>
                <!--
                <div style="float:left;width:150px;height:auto;padding:30px;margin:8px 8px 8px 8px">
                    <div style="width:150px;height:180px">
                    </div>
                </div>
                <div style="float:left;width:150px;height:auto;padding:30px;margin:8px 8px 8px 8px">
                    <div style="width:150px;height:180px">
                    </div>
                </div>
                <div style="float:left;width:150px;height:auto;padding:30px;margin:8px 8px 8px 8px">
                    <div style="width:150px;height:180px">
                    </div>
                </div>
                -->
            </div>

        </div>

    </body>
</html>
