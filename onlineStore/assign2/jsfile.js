
//not empty
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {/*alert(alerttxt);*/return false}
  else {return true}
  }
}

//price validate
function validate_price(field,alerttxt)
{
    with (field)
    {
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            if(value<0){
                return false;
            }else{
                if (isNaN(value))
                {/*alert(alerttxt);*/return false}
                
                else{
                    return true}
            }
        }
    }
}

//date
function validate_date(field,alerttxt)
{
    with (field)
    {
        
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            
            
            arr = value.split("-");
            /*  alert(arr[0]);
             alert(arr[1]);
             alert(arr[2]);
             alert(arr[3]);*/
            if( isNaN(arr[0])||isNaN(arr[1])||isNaN(arr[2])||(arr[3]!=undefined) ){
                return false;
            }
            if(arr[0]<1860||arr[0]>2015){
                return false;
            }
            if(arr[1]<1||arr[1]>12){
                return false;
            }
            if(arr[2]<1||arr[2]>31){
                return false;
            }
            return true;
        }
    }
}

//date compare

function validate_startend(field1,field2)
{
 
            arr1 = field1.split("-");
            arr2 = field2.split("-");
            //  alert(arr[0]);
            // alert(arr[1]);
            // alert(arr[2]);
            // alert(arr[3]);
            if(arr1[0]>arr2[0]){
              //  alert(11);
                return true;
            }
            else if(arr1[0]<arr2[0]){
                return false;
            }
            else if(arr1[0]==arr2[0]){
                if(arr1[1]>arr2[1]){
                    return true;
                }else if(arr1[1]<arr2[1]){
                    return false;
                }else if(arr1[1]==arr2[1]){
                    if(arr1[2]>arr2[2]){
                        return true;
                    }else if(arr1[2]<arr2[2]){
                        return false;
                    }else if(arr1[2]==arr2[2]){
                        return true;
                    }
                }
            }
         //   alert(14);
            return false;
}


function validate_age(field,alerttxt)
{
    //alert(0);
    with (field)
    {
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            if(value<0){
                return false;
            }else{
                if (isNaN(value))
                {
                    /*alert(alerttxt);*/return false
                }
                
                else
                {
                    if( (value.indexOf("."))!=-1 )
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
            }
        }
    }
}




function validate_form(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //name
   // alert("lol");
with(thisform){
    
    
    //category
    if(document.getElementById("productcategoryid").value=="undefined"){
        formvalid=1;
        document.getElementById("productcategoryid").style.border="1px solid red";
       // document.getElementById("racialvalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("productcategoryid").style.border="1px solid rgba(255,255,255,.55)";
     //   document.getElementById("racialvalid").style.visibility="hidden";
    }

    
    if (validate_required(document.getElementById("productname"),"Empty name")==false)
    {
        if(focusbox==0){
            productname.focus();
            focusbox=1;
        }
        document.getElementById("productname").style.border="1px solid red";
       // document.getElementById("productnamevalid").style.visibility="visible";
        formvalid=1;
    }
    else{
        document.getElementById("productname").style.border="1px solid rgba(255,255,255,.55)";
       // document.getElementById("productnamevalid").style.visibility="hidden";
    }
    
    if (validate_required(document.getElementById("productdesc"),"Empty desc")==false)
    {
        if(focusbox==0){
            productdesc.focus();
            focusbox=1;
        }
        document.getElementById("productdesc").style.border="1px solid red";
        // document.getElementById("productnamevalid").style.visibility="visible";
        formvalid=1;
    }
    else{
        document.getElementById("productdesc").style.border="1px solid rgba(255,255,255,.55)";
        // document.getElementById("productnamevalid").style.visibility="hidden";
    }

    if (validate_price(document.getElementById("productprice"),"Empty price")==false)
    {
        if(focusbox==0){
            productprice.focus();
            focusbox=1;
        }
        document.getElementById("productprice").style.border="1px solid red";
        // document.getElementById("productnamevalid").style.visibility="visible";
        formvalid=1;
    }
    else{
        document.getElementById("productprice").style.border="1px solid rgba(255,255,255,.55)";
        // document.getElementById("productnamevalid").style.visibility="hidden";
    }

    
    if(formvalid==0){
        
        return true;
    }else{
        return false;
    }
}

}


function validate_form1(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
   // alert("lol");
with(thisform){
    
    if (validate_required(document.getElementById("productcategoryname"),"Empty name")==false)
    {
        if(focusbox==0){
            productcategoryname.focus();
            focusbox=1;
        }
        document.getElementById("productcategoryname").style.border="1px solid red";
        // document.getElementById("productnamevalid").style.visibility="visible";
        formvalid=1;
    }
    else{
        document.getElementById("productcategoryname").style.border="1px solid rgba(255,255,255,.55)";
        // document.getElementById("productnamevalid").style.visibility="hidden";
    }

    if (validate_required(document.getElementById("productcategorydesc"),"Empty name")==false)
    {
        if(focusbox==0){
            productcategorydesc.focus();
            focusbox=1;
        }
        document.getElementById("productcategorydesc").style.border="1px solid red";
        // document.getElementById("productnamevalid").style.visibility="visible";
        formvalid=1;
    }
    else{
        document.getElementById("productcategorydesc").style.border="1px solid rgba(255,255,255,.55)";
        // document.getElementById("productnamevalid").style.visibility="hidden";
    }

    
    if(formvalid==0){
        return true;
    }else{
        return false;
    }

}
}



function validate_form2(thisform){
    //return true;
    /*check value*/
    var formvalid=0;
    var focusbox=0;
  //  alert(0);
    with(thisform){
        if (validate_required(document.getElementById("username"),"Empty name")==false)
        {
            if(focusbox==0){
                username.focus();
                focusbox=1;
            }
            document.getElementById("username").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("username").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

     //   alert(1);
        if (validate_required(document.getElementById("password"),"Empty name")==false)
        {
            if(focusbox==0){
                password.focus();
                focusbox=1;
            }
            document.getElementById("password").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("password").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

    //   alert(2);
        if(document.getElementById("usertype").value=="undefined"){
            formvalid=1;
            document.getElementById("usertype").style.border="1px solid red";
            // document.getElementById("racialvalid").style.visibility="visible";
            
        }
        else{
            document.getElementById("usertype").style.border="1px solid rgba(255,255,255,.55)";
            //   document.getElementById("racialvalid").style.visibility="hidden";
        }

      //   alert(3);
        if (validate_required(document.getElementById("employeefname"),"Empty name")==false)
        {
            if(focusbox==0){
                employeefname.focus();
                focusbox=1;
            }
            document.getElementById("employeefname").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("employeefname").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

     //    alert(4);
        if (validate_required(document.getElementById("employeelname"),"Empty name")==false)
        {
            if(focusbox==0){
                employeelname.focus();
                focusbox=1;
            }
            document.getElementById("employeelname").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("employeelname").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
     //    alert(5);
        if (validate_age(document.getElementById("age"),"Empty price")==false)
        {
            if(focusbox==0){
                age.focus();
                focusbox=1;
            }
            document.getElementById("age").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("age").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

       //  alert(6);
        if (validate_price(document.getElementById("salary"),"Empty price")==false)
        {
            if(focusbox==0){
                salary.focus();
                focusbox=1;
            }
            document.getElementById("salary").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("salary").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        
        
        
       //  alert(7);
        if(formvalid==0){
            return true;
        }else{
            return false;
        }

    }
}


function validate_form3(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
     //alert("0");
with(thisform){
    // alert("11");
    //category
  //  alert(document.getElementById("productname").value);
    if(document.getElementById("productname").value=="undefined"){
        formvalid=1;
        document.getElementById("productname").style.border="1px solid red";
        // document.getElementById("racialvalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("productname").style.border="1px solid rgba(255,255,255,.55)";
        //   document.getElementById("racialvalid").style.visibility="hidden";
    }
    
 
    //startdate
   // alert("25");
   // alert(document.getElementById("start").value);
  //  alert(validate_date(document.getElementById("start"),"Empty start"));
    if (validate_date(document.getElementById("start"),"Empty start")==false)
    {
    //    alert("21");
        if(focusbox==0){
            document.getElementById("start").focus();
            focusbox=1;
        }
        document.getElementById("start").style.border="1px solid red";
        //document.getElementById("birthvalid").style.visibility="visible";
        formvalid=1}
    else{
     //   alert("22");
        document.getElementById("start").style.border="1px solid #D8D8D8";
        //document.getElementById("birthvalid").style.visibility="hidden";
    }
   // alert("3");
    //alert(document.getElementById("end").value);
    //enddate
    if ( (validate_date(document.getElementById("end"),"Empty end")==false )||(validate_startend(document.getElementById("end").value,document.getElementById("start").value)==false))
    {
        if(focusbox==0){
            document.getElementById("end").focus();
            focusbox=1;
        }
        document.getElementById("end").style.border="1px solid red";
        //document.getElementById("birthvalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("end").style.border="1px solid #D8D8D8";
        //document.getElementById("birthvalid").style.visibility="hidden";
    }

   // alert(0);
   // alert(document.getElementById("start").value);
   // alert(document.getElementById("end").value);
    /*
    if(validate_startend(document.getElementById("end").value,document.getElementById("start").value)==false ){
        if(focusbox==0){
            document.getElementById("end").focus();
            focusbox=1;
        }
       // alert(88);
        document.getElementById("end").style.border="1px solid red";
        document.getElementById("endvalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("end").style.border="1px solid #D8D8D8";
        document.getElementById("endvalid").style.visibility="hidden";
    
    }
    
    */
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
        
}
}

function validate_number(field){
    with(field){
        if(value==null||value==""){
            return true;
        }else{
            if(value<0){
                return false;
            }else{
                if(isNaN(value)){
                    return false;
                }else{
                    return true;
                }
            }
        }
    }

}



function validate_formM1(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //alert("0");
    with(thisform){
       // alert(10);
        if (validate_number(document.getElementById("lowlimit"))==false)
        {
      //      alert(1);
            if(focusbox==0){
                lowlimit.focus();
                focusbox=1;
            }
            document.getElementById("lowlimit").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
        //     alert(2);
            document.getElementById("lowlimit").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        //alert(3);
        if (validate_number(document.getElementById("highlimit"))==false)
        {
            if(focusbox==0){
                highlimit.focus();
                focusbox=1;
            }
            document.getElementById("highlimit").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("highlimit").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        
        
        
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
        
    }
}



function validate_formM2(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //alert("0");
    with(thisform){
        // alert(10);
        if (validate_number(document.getElementById("lowlimitS"))==false)
        {
            //      alert(1);
            if(focusbox==0){
                lowlimitS.focus();
                focusbox=1;
            }
            document.getElementById("lowlimitS").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            //     alert(2);
            document.getElementById("lowlimitS").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        //alert(3);
        if (validate_number(document.getElementById("highlimitS"))==false)
        {
            if(focusbox==0){
                highlimitS.focus();
                focusbox=1;
            }
            document.getElementById("highlimitS").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("highlimitS").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        
        
        
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
        
    }
}



function validate_formM3(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //alert("0");
    with(thisform){
        // alert(10);
        if (validate_number(document.getElementById("lowlimitH"))==false)
        {
            //      alert(1);
            if(focusbox==0){
                lowlimitH.focus();
                focusbox=1;
            }
            document.getElementById("lowlimitH").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            //     alert(2);
            document.getElementById("lowlimitH").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        //alert(3);
        if (validate_number(document.getElementById("highlimitH"))==false)
        {
            if(focusbox==0){
                highlimitH.focus();
                focusbox=1;
            }
            document.getElementById("highlimitH").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("highlimitH").style.border="1px solid rgba(255,255,255,.55)";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        //for date
    if(document.getElementById("start").value==""||document.getElementById("start").value==null){
    }else{
        if (validate_date(document.getElementById("start"),"Empty start")==false)
        {
            //    alert("21");
            if(focusbox==0){
                document.getElementById("start").focus();
                focusbox=1;
            }
            document.getElementById("start").style.border="1px solid red";
            //document.getElementById("birthvalid").style.visibility="visible";
            formvalid=1}
        else{
            //   alert("22");
            document.getElementById("start").style.border="1px solid #D8D8D8";
            //document.getElementById("birthvalid").style.visibility="hidden";
        }
    }
        // alert("3");
        //alert(document.getElementById("end").value);
        //enddate
    if(document.getElementById("end").value==""||document.getElementById("end").value==null){
    }else{
        if ( validate_date(document.getElementById("end"),"Empty end")==false)
        {
            if(focusbox==0){
                document.getElementById("end").focus();
                focusbox=1;
            }
            document.getElementById("end").style.border="1px solid red";
            //document.getElementById("birthvalid").style.visibility="visible";
            formvalid=1}
        else{
            document.getElementById("end").style.border="1px solid #D8D8D8";
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

