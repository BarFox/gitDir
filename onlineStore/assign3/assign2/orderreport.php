<?php
    session_start();
    //destroy session
   
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
    
    
    
    //usertype
    if($ut!='manager'){
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
<title>Manager</title>
<link rel="stylesheet" type="text/css" href="loginstyle.css" />
<script type="text/javascript" src="jsfile.js"></script>
<script type="text/javascript">
function validate_report(thisform){
    //alert("1");
    var formvalid=0;
    var focusbox=0;
    with(thisform){
       // alert("1");
        if(document.getElementById("lowlimit").value==""||document.getElementById("lowlimit").value==null){
        }else{
          //  alert("0");
            if (validate_date(document.getElementById("lowlimit"),"Empty start")==false)
            {
                //    alert("21");
                if(focusbox==0){
                    document.getElementById("lowlimit").focus();
                    focusbox=1;
                }
                document.getElementById("lowlimit").style.border="1px solid red";
                //document.getElementById("birthvalid").style.visibility="visible";
                formvalid=1;
            }
            else{
                //   alert("22");
                document.getElementById("lowlimit").style.border="1px solid #D8D8D8";
                //document.getElementById("birthvalid").style.visibility="hidden";
            }
        }
        
        if(document.getElementById("highlimit").value==""||document.getElementById("highlimit").value==null){
        }else{
            //  alert("0");
            if (validate_date(document.getElementById("highlimit"),"Empty start")==false)
            {
                //    alert("21");
                if(focusbox==0){
                    document.getElementById("highlimit").focus();
                    focusbox=1;
                }
                document.getElementById("highlimit").style.border="1px solid red";
                //document.getElementById("birthvalid").style.visibility="visible";
                formvalid=1;
            }
            else{
                //   alert("22");
                document.getElementById("highlimit").style.border="1px solid #D8D8D8";
                //document.getElementById("birthvalid").style.visibility="hidden";
            }
        }
        
        if(document.getElementById("highlimit").value==""||document.getElementById("highlimit").value==null|| document.getElementById("lowlimit").value==""||document.getElementById("lowlimit").value==null){
        }else{
            
            if ( (validate_date(document.getElementById("highlimit"),"Empty end")==false )||(validate_startend(document.getElementById("highlimit").value,document.getElementById("lowlimit").value)==false))
            {
                if(focusbox==0){
                    document.getElementById("highlimit").focus();
                    focusbox=1;
            }
            document.getElementById("highlimit").style.border="1px solid red";
                    //document.getElementById("birthvalid").style.visibility="visible";
            formvalid=1}
            else{
                document.getElementById("highlimit").style.border="1px solid #D8D8D8";
            //document.getElementById("birthvalid").style.visibility="hidden";
            }
        }

        
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
        
    }
    
}

</script>
</head>
<body style="background-color:#555555;" class="font-common" >
<div class="mainbox opacy" id="box1" style="display:block;text-align:left;<!--background:url(pic/bg1.jpg);-->">
<div style="postion:relative;top:150px;left:100px">
<!--<h3 >BetterAmazon</h3>
<h5 >Please login!</h5>-->
<img src="pic/bunnyfox.jpg">
</div>
<div class="welcome">
<p style="margin:0 0 0 60px">Manager <?php echo $un;?>, You are login!</p>
</div>
<div>
<FORM METHOD=POST ACTION="showreport.php" onsubmit="return validate_report(this)">

    <div class="textbox" style="top:15px;margin:0 0 0 10px">
        <label>Start Date</label>
        <INPUT TYPE="date" name="lowlimit" id="lowlimit" value="">
    </div>

    <div class="textbox" style="top:15px;margin:0 0 0 19px">
        <label>End Date</label>
        <INPUT TYPE="date" name="highlimit" id="highlimit" value="">
    </div>
    <div class="textbox" style="top:15px;text-align:left;margin:0 0 0 12px">
        <label>&nbsp;Category:</label>
        <select id="categorylist" name="categorylist" class="selectbox" style="display:inline;">
            <option value="undefined">Select One</option>
            <?php
                $sql="select * from productcategory";
                $con=mysql_connect(':/home/scf-27/chentian/mysql.sock', 'root', '1992');//when to close?????
                if(!$con){
                    die;//
                }
                mysql_select_db('assignment3',$con);
                $res=mysql_query($sql,$con);
                while ($row=mysql_fetch_assoc($res)  ){
                    echo "<option value='".$row['productcategoryid']."'>".$row['productcategoryname']."</option>";
                    // echo "tmp.options.add(new Option('".$row['productname']."','".$row['productid']."'));";
                }
            ?>
        </select>
    </div>

    <div class="textbox" style="top:15px;margin:20px 0 0 13px">
        <label>Search Type: </label>
        <input type="radio" name="searchtype" value="productcategoryid" id="searchtype"  />Product Category&nbsp;&nbsp;
        <input type="radio" name="searchtype" value="productid" id="searchtype" checked/>Product&nbsp;&nbsp;&nbsp;
        <input type="radio" name="searchtype" value="specialsales" id="searchtype" style="margin:20px 0 0 109px"/>Special Sales&nbsp;&nbsp;
        <input type="radio" name="searchtype" value="totalsales" id="searchtype"  />Total Sales
    </div>

    <div class="textbox" style="top:15px;margin:20px 0 0 10px">
        <label>Search Topic: </label>
        <input type="radio" name="searchtopic" value="quantitysold" id="searchtopic"  checked/>Quantity Sold&nbsp;&nbsp;
        <input type="radio" name="searchtopic" value="price" id="searchtopic" />Price&nbsp;&nbsp;
    </div>

    <div class="textbox" style="top:15px;margin:20px 0 0 25px">
        <label>Sort Order: </label>
        <input type="radio" name="sortorder" value="ASC" id="sortorder"  checked/>Ascending&nbsp;&nbsp;
        <input type="radio" name="sortorder" value="DESC" id="sortorder" />Descending&nbsp;&nbsp;

    </div>

    <div class="textbox welcome" style="position:relative;top:15px">
        <BUTTON TYPE="submit" name="report">Show Report</button>
    </div>
</FORM>


<br>

<FORM METHOD=POST ACTION="managerpage.php">
<div class="textbox welcome">
<BUTTON TYPE="submit" name="mainpage" >Main Page</button>
</div>
</FORM>



</div>
</div>
</body>
</html>
