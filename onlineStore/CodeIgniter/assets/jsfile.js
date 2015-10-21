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



