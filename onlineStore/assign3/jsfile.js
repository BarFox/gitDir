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

function validate_creditcard(field,alerttxt)
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

function validate_code(field,alerttxt)
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
                        if(value.length==3){
                            return true;
                        }
                        else{
                            return false;
                        }
                    }
                }
            }
        }
    }
}

function validate_date(field,alerttxt)
{
    var d = new Date();
    var month=d.getMonth()+1;
    var year=d.getFullYear();
    var year=year-2000;
    with (field)
    {
        
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            
            if( (value.indexOf("."))!=-1 )
            {
                return false;
            }

            arr = value.split("/");
            /*  alert(arr[0]);
             alert(arr[1]);
             alert(arr[2]);
             alert(arr[3]);*/
            if( isNaN(arr[0])||isNaN(arr[1])||(arr[2]!=undefined) ){
                return false;
            }
            if(arr[1]<0||arr[1]>=100){
                return false;
            }
            if(arr[0]<=0||arr[0]>12){
                return false;
            }
            //real time
            if(arr[1]>year){
                return true;
            }
            else if(arr[1]=year){
                if(arr[0]<=month){
                    return false;//not receive card that expire this month
                }
                else{
                    return true;
                }
            }
            else{
                return false;
            }
        }
    }
}



function validate_form1(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //name
    // alert("lol");
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
            document.getElementById("username").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        
        if (validate_required(document.getElementById("password0"),"Empty name")==false)
        {
            if(focusbox==0){
                password0.focus();
                focusbox=1;
            }
            document.getElementById("password0").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("password0").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        if (validate_required(document.getElementById("customername"),"Empty name")==false)
        {
            if(focusbox==0){
                customername.focus();
                focusbox=1;
            }
            document.getElementById("customername").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("customername").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        if (validate_required(document.getElementById("customeraddress"),"Empty name")==false)
        {
            if(focusbox==0){
                customeraddress.focus();
                focusbox=1;
            }
            document.getElementById("customeraddress").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("customeraddress").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        if (validate_creditcard(document.getElementById("creditcard"),"Empty name")==false)
        {
            if(focusbox==0){
                creditcard.focus();
                focusbox=1;
            }
            document.getElementById("creditcard").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("creditcard").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        if (validate_code(document.getElementById("securitycode"),"Empty name")==false)
        {
            if(focusbox==0){
                securitycode.focus();
                focusbox=1;
            }
            document.getElementById("securitycode").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("securitycode").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }

        if (validate_date(document.getElementById("expirationdate"),"Empty name")==false)
        {
            if(focusbox==0){
                expirationdate.focus();
                focusbox=1;
            }
            document.getElementById("expirationdate").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("expirationdate").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }


        
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
    
    }
   // return true;
}

function validate_form9(thisform){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //name
    // alert("lol");
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
            document.getElementById("username").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        
                
        if (validate_required(document.getElementById("customername"),"Empty name")==false)
        {
            if(focusbox==0){
                customername.focus();
                focusbox=1;
            }
            document.getElementById("customername").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("customername").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        if (validate_required(document.getElementById("customeraddress"),"Empty name")==false)
        {
            if(focusbox==0){
                customeraddress.focus();
                focusbox=1;
            }
            document.getElementById("customeraddress").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("customeraddress").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        if (validate_creditcard(document.getElementById("creditcard"),"Empty name")==false)
        {
            if(focusbox==0){
                creditcard.focus();
                focusbox=1;
            }
            document.getElementById("creditcard").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("creditcard").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        if (validate_code(document.getElementById("securitycode"),"Empty name")==false)
        {
            if(focusbox==0){
                securitycode.focus();
                focusbox=1;
            }
            document.getElementById("securitycode").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("securitycode").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        if (validate_date(document.getElementById("expirationdate"),"Empty name")==false)
        {
            if(focusbox==0){
                expirationdate.focus();
                focusbox=1;
            }
            document.getElementById("expirationdate").style.border="1px solid red";
            // document.getElementById("productnamevalid").style.visibility="visible";
            formvalid=1;
        }
        else{
            document.getElementById("expirationdate").style.border="1px solid #bdc7d7";
            // document.getElementById("productnamevalid").style.visibility="hidden";
        }
        
        
        
        if(formvalid==0){
            return true;
        }else{
            return false;
        }
        
    }
    // return true;
}