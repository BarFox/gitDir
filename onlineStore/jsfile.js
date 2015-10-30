var firstname=new Array();
var lastname=new Array();
var email=new Array();
var isex=new Array();
var racial=new Array();
var password=new Array();
var confirmpassword=new Array();


var tage=new Array();
var tincome=new Array();
var tsex=new Array();
var tcharacter=new Array();

var location1=new Array();
var location2=new Array();
var birth=new Array();
var iheight=new Array();
var iincome=new Array();
var imarriage=new Array();
var kids=new Array();
var self=new Array();

var theight=new Array();
var tmarriage=new Array();
var belief=new Array();



var i=0;

function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {/*alert(alerttxt);*/return false}
  else {return true}
  }
}

function validate_iheight(field,alerttxt)
{
    with (field)
    {
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            if(value<0||value>100){
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

function validate_iincome(field,alerttxt)
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
                    return true;
                }
            }
                
        }
    }
}

function validate_birth(field,alerttxt)
{
    with (field)
    {
        
        if (value==null||value=="")
        {/*alert(alerttxt);*/return false}
        else {
            
                return true;

        }
    }
}


function validate_email(field,alerttxt)
{
with (field)
{
apos=value.indexOf("@")
dotpos=value.lastIndexOf(".")
if (apos<1||dotpos-apos<2) 
  {return false}
else {return true}
}
}

function check1(){
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //name
    if (validate_required(document.getElementById("first_name"),"Empty first name")==false)
    {
        if(focusbox==0){
            first_name.focus();
            focusbox=1;
        }
        document.getElementById("first_name").style.border="1px solid red";
        document.getElementById("firstnamevalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("first_name").style.border="1px solid #D8D8D8";
        document.getElementById("firstnamevalid").style.visibility="hidden";
    }
    
    if (validate_required(document.getElementById("last_name"),"Empty last name")==false)
    {
        if(focusbox==0){
            last_name.focus();
            focusbox=1;
        }
        document.getElementById("last_name").style.border="1px solid red";
        document.getElementById("lastnamevalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("last_name").style.border="1px solid #D8D8D8";
        document.getElementById("lastnamevalid").style.visibility="hidden";
    }
    //isex
    tmp=document.getElementsByName("isex");
    var isOK=false;
   
    for(k=0;k<tmp.length;k++){
        if(tmp[k].checked){
            isOK=true;
            break;
        }
    }
    if(!isOK){
        formvalid=1;
         document.getElementById("isexvalid").style.visibility="visible";
       
    }
    else{
         document.getElementById("isexvalid").style.visibility="hidden";
    }
    
    
    //email
    if (validate_email(document.getElementById("email"),"Empty email")==false)
    {
        if(focusbox==0){
            document.getElementById("email").focus();
            focusbox=1;
        }
        document.getElementById("email").style.border="1px solid red";
        document.getElementById("emailvalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("email").style.border="1px solid #D8D8D8";
        document.getElementById("emailvalid").style.visibility="hidden";
    }

    
    
    
    
    
    
    //racial
    if(document.getElementById("racial").value=="undefined"){
        formvalid=1;
        document.getElementById("racialvalid").style.visibility="visible";

    }
    else{
        document.getElementById("racialvalid").style.visibility="hidden";
    }
    //tage
    tmp=document.getElementsByName("tage");
    isOK=false;
    
    for(k=0;k<tmp.length;k++){
        if(tmp[k].checked){
            isOK=true;
            break;
        }
    }
    if(!isOK){
        formvalid=1;
        document.getElementById("tagevalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("tagevalid").style.visibility="hidden";
    }

    
    
    
    if(formvalid==0){
 
        document.getElementById("box1").style.display="none";
        document.getElementById("box2").style.display="block";
    }
   }


function check2(){
    
    /*check value*/
    var formvalid=0;
    var focusbox=0;
    //location
    if(document.getElementById("location1").value=="undefined"){
        formvalid=1;
        document.getElementById("locationvalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("locationvalid").style.visibility="hidden";
    }

    //imarriage
    tmp=document.getElementsByName("imarriage");
    isOK=false;
    
    for(k=0;k<tmp.length;k++){
        if(tmp[k].checked){
            isOK=true;
            break;
        }
    }
    if(!isOK){
        formvalid=1;
        document.getElementById("imarriagevalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("imarriagevalid").style.visibility="hidden";
    }

    //tmarriage
    tmp=document.getElementsByName("tmarriage");
    isOK=false;
    
    for(k=0;k<tmp.length;k++){
        if(tmp[k].checked){
            isOK=true;
            break;
        }
    }
    if(!isOK){
        formvalid=1;
        document.getElementById("tmarriagevalid").style.visibility="visible";
        
    }
    else{
        document.getElementById("tmarriagevalid").style.visibility="hidden";
    }
    
    //birthday
    if (validate_birth(document.getElementById("birth"),"Empty birth")==false)
    {
        if(focusbox==0){
            document.getElementById("birth").focus();
            focusbox=1;
        }
        document.getElementById("birth").style.border="1px solid red";
        document.getElementById("birthvalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("birth").style.border="1px solid #D8D8D8";
        document.getElementById("birthvalid").style.visibility="hidden";
    }

    
    
    
    
    
    //iheight
    if (validate_iheight(document.getElementById("iheight"),"Empty height")==false)
    {
        if(focusbox==0){
            document.getElementById("iheight").focus();
            focusbox=1;
        }
        document.getElementById("iheight").style.border="1px solid red";
        document.getElementById("iheightvalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("iheight").style.border="1px solid #D8D8D8";
        document.getElementById("iheightvalid").style.visibility="hidden";
    }

    //iincome
    if (validate_iincome(document.getElementById("iincome"),"Empty income")==false)
    {
        if(focusbox==0){
            document.getElementById("iincome").focus();
            focusbox=1;
        }
        document.getElementById("iincome").style.border="1px solid red";
        document.getElementById("iincomevalid").style.visibility="visible";
        formvalid=1}
    else{
        document.getElementById("iincome").style.border="1px solid #D8D8D8";
        document.getElementById("iincomevalid").style.visibility="hidden";
    }

    
    
    
    
if(formvalid==0){

    
    /*store value*/
    var j=0;
    firstname[i]=document.getElementById("first_name").value;
    lastname[i]=document.getElementById("last_name").value;
    email[i]=document.getElementById("email").value;
    
    var tmp=document.getElementsByName("isex");
    for(j=0;j<3;j++){
        if(tmp[j].checked){
            isex[i]=tmp[j].value;
        }
    }
    
    racial[i]=document.getElementById("racial").value;
    
    password[i]=document.getElementById("pass").value;
    confirmpassword[i]=document.getElementById("confirm_pass").value;
    location1[i]=document.getElementById("location1").value;
    location2[i]=document.getElementById("location2").value;
    birth[i]=document.getElementById("birth").value;
    iheight[i]=document.getElementById("iheight").value;
    iincome[i]=document.getElementById("iincome").value;
    
    tmp=document.getElementsByName("imarriage");
    for(j=0;j<3;j++){
        if(tmp[j].checked){
            imarriage[i]=tmp[j].value;
        }
    }
    tmp=document.getElementsByName("kids");
    for(j=0;j<4;j++){
        if(tmp[j].checked){
            kids[i]=tmp[j].value;
        }
    }

    
    self[i]=document.getElementById("describe").value;
    
    
    tage[i]="";
    var tmp=document.getElementsByName("tage");
    for(j=0;j<4;j++){
        if(tmp[j].checked){
            tage[i]=tage[i]+"  "+tmp[j].value;
        }
    }

    tincome[i]="";
    var tmp=document.getElementsByName("tincome");
    for(j=0;j<5;j++){
        if(tmp[j].checked){
            tincome[i]=tincome[i]+"  "+tmp[j].value;
        }
    }
    
    var tmp=document.getElementsByName("tsex");
    for(j=0;j<2;j++){
        if(tmp[j].checked){
            tsex[i]=tmp[j].value;
        }
    }
    
    tcharacter[i]=document.getElementById("tcharacter").value;
    
    theight[i]="";
    var tmp=document.getElementsByName("theight");
    for(j=0;j<3;j++){
        if(tmp[j].checked){
            theight[i]=theight[i]+"  "+tmp[j].value;
        }
    }

    tmarriage[i]="";
    var tmp=document.getElementsByName("tmarriage");
    for(j=0;j<3;j++){
        if(tmp[j].checked){
            tmarriage[i]=tmarriage[i]+"  "+tmp[j].value;
        }
    }

    belief[i]="";
    var tmp=document.getElementsByName("belief");
    for(j=0;j<5;j++){
        if(tmp[j].checked){
            belief[i]=belief[i]+"  "+tmp[j].value;
        }
    }

    
    
    
    /*add a line in div 3*/
    
    document.getElementById("searchbox").options.add(new Option(firstname[i]+" "+lastname[i]+", "+isex[i],i));
    /*i++;*/
    i++;
    /*delete all data in form*/
    document.getElementById("first_name").value="";
    document.getElementById("last_name").value="";
    document.getElementById("email").value="";
    tmp=document.getElementsByName("isex");
    for(j=0;j<3;j++){
        tmp[j].checked=false;
    }
    document.getElementById("racial").selectedIndex = undefined;
    document.getElementById("pass").value="";
    document.getElementById("confirm_pass").value="";
    
    document.getElementById("location1").selectedIndex = undefined;
    document.getElementById("location2").selectedIndex = undefined;
    document.getElementById("birth").value="";
    document.getElementById("iheight").value="";
    document.getElementById("iincome").value="";
    tmp=document.getElementsByName("imarriage");
    for(j=0;j<3;j++){
        tmp[j].checked=false;
    }
    tmp=document.getElementsByName("kids");
    for(j=0;j<4;j++){
        tmp[j].checked=false;
    }
    document.getElementById("describe").value="";
    tmp=document.getElementsByName("tage");
    for(j=0;j<4;j++){
        tmp[j].checked=false;
    }
    tmp=document.getElementsByName("tincome");
    for(j=0;j<5;j++){
        tmp[j].checked=false;
    }
    tmp=document.getElementsByName("tsex");
    for(j=0;j<2;j++){
        tmp[j].checked=false;
    }
    document.getElementById("tcharacter").selectedIndex = undefined;
    tmp=document.getElementsByName("theight");
    for(j=0;j<3;j++){
        tmp[j].checked=false;
    }
    tmp=document.getElementsByName("tmarriage");
    for(j=0;j<3;j++){
        tmp[j].checked=false;
    }
    tmp=document.getElementsByName("belief");
    for(j=0;j<5;j++){
        tmp[j].checked=false;
    }
    

    
    
    
    document.getElementById("box2").style.display="none";
    document.getElementById("box3").style.display="block";
}
}

function from1to3(){
    
    /*check value*/
    
    document.getElementById("box1").style.display="none";
    document.getElementById("box3").style.display="block";
}
/*
function from1to2(){
    
 
    
    document.getElementById("box1").style.display="none";
    document.getElementById("box2").style.display="block";
}

*/




function from3to1(){
    
    /*check value*/
    
    document.getElementById("box3").style.display="none";
    document.getElementById("box1").style.display="block";
}

function from4to1(){
    
    /*check value*/
    
    document.getElementById("box4").style.display="none";
    document.getElementById("box1").style.display="block";
}

function from4to3(){
    
    /*check value*/
    
    document.getElementById("box4").style.display="none";
    document.getElementById("box3").style.display="block";
}

function from2to1(){
    
    /*check value*/
    
    document.getElementById("box2").style.display="none";
    document.getElementById("box1").style.display="block";
}


function from2to3(){
    
    /*check value*/
    
    document.getElementById("box2").style.display="none";
    document.getElementById("box3").style.display="block";
}



function validate_form(thisform)
{
    document.getElementById("box2").style.display="none";
    document.getElementById("box3").style.display="block";
}


function from3to4(){
    i=document.getElementById("searchbox").value;
    
   
    /*check value*/
   
    document.getElementById("showfirstname").innerHTML=firstname[i];
   
    document.getElementById("showlastname").innerHTML=lastname[i];
    
    document.getElementById("showemail").innerHTML=email[i];
    
    document.getElementById("showisex").innerHTML=isex[i];
    
    document.getElementById("showpass").innerHTML=password[i];
    
    document.getElementById("showconpass").innerHTML=confirmpassword[i];
    
    document.getElementById("showracial").innerHTML=racial[i];
    
    document.getElementById("showlocation1").innerHTML=location1[i];
    document.getElementById("showlocation2").innerHTML=location2[i];
    document.getElementById("showbirth").innerHTML=birth[i];
    document.getElementById("showiheight").innerHTML=iheight[i];
    document.getElementById("showiincome").innerHTML=iincome[i];
    document.getElementById("showimarriage").innerHTML=imarriage[i];
    document.getElementById("showkids").innerHTML=kids[i];
    document.getElementById("showself").innerHTML=self[i];
    
    
     document.getElementById("showtage").innerHTML=tage[i];
     document.getElementById("showtincome").innerHTML=tincome[i];
    document.getElementById("showtsex").innerHTML=tsex[i];
    document.getElementById("showchar").innerHTML=tcharacter[i];
     document.getElementById("showtheight").innerHTML=theight[i];
    document.getElementById("showtmarriage").innerHTML=tmarriage[i];
    document.getElementById("showbelief").innerHTML=belief[i];
    
    document.getElementById("box3").style.display="none";
    document.getElementById("box4").style.display="block";
}

function showcity(){
    
    var tmp=document.getElementById("location2");
 
    if(document.getElementById("location1").value=="America"){
        tmp.options.length = 0;
        tmp.options.add(new Option("Select one","Undefined"));
        tmp.options.add(new Option("Los Angeles","Los Angeles"));
        tmp.options.add(new Option("New York","New York"));
        tmp.options.add(new Option("Washton","Washton"));
        tmp.options.add(new Option("San Francisco","San Francisco"));
        
    }
    if(document.getElementById("location1").value=="China"){
        tmp.options.length = 0;
        tmp.options.add(new Option("Select one","Undefined"));
        tmp.options.add(new Option("Beijing","Beijing"));
        tmp.options.add(new Option("Hefei","Hefei"));
        tmp.options.add(new Option("Sichuan","Sichuan"));
        tmp.options.add(new Option("Wuhan","Wuhan"));
        
    }

    if(document.getElementById("location1").value=="Japan"){
        tmp.options.length = 0;
        tmp.options.add(new Option("Select one","Undefined"));
        tmp.options.add(new Option("Tokyo","Tokyo"));
        tmp.options.add(new Option("Ginza","Ginza"));
        tmp.options.add(new Option("Shibuya","Shibuya"));
        tmp.options.add(new Option("Uneo","Uneo"));
        
    }

    if(document.getElementById("location1").value=="Korea"){
        tmp.options.length = 0;
        tmp.options.add(new Option("Select one","Undefined"));
        tmp.options.add(new Option("Seoul","Seoul"));
        tmp.options.add(new Option("Daegn","Daegn"));
        tmp.options.add(new Option("Pohanp","Pohanp"));
        tmp.options.add(new Option("jeonju","jeonju"));
        
    }

    if(document.getElementById("location1").value=="undefined"){
        tmp.options.length = 0;
        tmp.options.add(new Option("Select one","Undefined"));
       
        
    }

}

function showchar(){
    var tmp=document.getElementById("tcharacter");
    var tmp1=document.getElementsByName("tsex");
    if(tmp1[1].checked){
        tmp.options.length = 0;
        tmp.options.add(new Option("Soft","Soft"));
        tmp.options.add(new Option("Weird","Weird"));
        tmp.options.add(new Option("Lovely","Lovely"));
        tmp.options.add(new Option("S","S"));
        tmp.options.add(new Option("M","M"));
        
    }
    if(tmp1[0].checked){
        tmp.options.length = 0;
        tmp.options.add(new Option("Awesome","Awesome"));
        tmp.options.add(new Option("Handsome","Handsome"));
        tmp.options.add(new Option("Hardworking","Hardworking"));
        tmp.options.add(new Option("S","S"));
        tmp.options.add(new Option("M","M"));
        
    }

}



