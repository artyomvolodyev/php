// JavaScript Document
function run_timer()
{
var today = new Date();
var ndate=today.toString();	//April 23, 2009, 02:04:25 pm
ndate=ndate.split('GMT');
document.getElementById("TimeDiv").style.border='transparent';
document.getElementById("TimeDiv").innerHTML=ndate[0];	
setTimeout(run_timer,1000);
}

function getHeightWidht()
{
	var viewportwidth;
	var viewportheight;
 	// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight 
	if (typeof window.innerWidth != 'undefined')
 	{
    viewportwidth = window.innerWidth,
    viewportheight = window.innerHeight
 	}
 	// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
	else if (typeof document.documentElement != 'undefined'   && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0)
	{
    viewportwidth = document.documentElement.clientWidth;
    viewportheight = document.documentElement.clientHeight
 	} 
 	// older versions of IE 
	else
 	{
    viewportwidth = document.getElementsById('bodyid').clientWidth;
    viewportheight = document.getElementsById('bodyid').clientHeight;
 	}
	return parseInt(viewportwidth);
}

function checkall(thisid)
{
	for(var i=1;document.getElementById('check'+i);i++)
	{
		if(document.getElementById(thisid.id).checked==true)
		{	
			document.getElementById('check'+i).checked = true;
		}
		if(document.getElementById(thisid.id).checked==false)
		{	
			document.getElementById('check'+i).checked = false;
		}
	}	
}

function check_check(spanid,checkid)
{ 
 var chkchkstat = true;
 for(var i=1; document.getElementById('check'+i); i++)
 {
  if(document.getElementById('check'+i).checked == false){
   chkchkstat = false;   
   break;
  } 
 } 
 //alert(chkchkstat);
 if(chkchkstat == false){
  $('#'+spanid).html('');
  document.getElementById(checkid).checked = false;  
 } else {
  document.getElementById(checkid).checked = true;  
 }
 return true;
}
function checknummsp(e)
{

	evt=e || window.event;
	var keypressed=evt.which || evt.keyCode;
	//alert(keypressed);
	if(keypressed!="48" &&  keypressed!="49" && keypressed!="50" && keypressed!="51" && keypressed!="52" && keypressed!="53" && keypressed!="54" && keypressed!="55" && keypressed!="8" && keypressed!="56" && keypressed!="57" && keypressed!="45" && keypressed!="46" && keypressed!="37" && keypressed!="39" && keypressed!="9")
	{
 		return false;
	}	
}
function hrefHandler()
{
	var a=window.location.pathname;
	var b=a.match(/[\/|\\]([^\\\/]+)$/);
	var ans=confirm("Are you sure? You want to delete.");
	return ans;
}
function EmptyListbox(listBoxId)
{
	var elSel = document.getElementById(listBoxId);
	for (i = elSel.length - 1; i>=0; i--) {
		elSel.remove(i);   
	}
}
function AddOptiontoListBox(listBoxId,Value,Text)
{
	var elSel = document.getElementById(listBoxId);	
	var opt = document.createElement("option");
	elSel.options.add(opt);
	opt.text=Text;
	opt.value=Value;
}
function CollaspeExpand(divName)
{
	 $('#Child'+divName).slideToggle("slow");

	/*if(document.getElementById('Child'+divName).style.display=="block")
	{
		document.getElementById('Child'+divName).style.display="none";
	}
	else
	{
		document.getElementById('Child'+divName).style.display="block";
	}*/
}
function item(com)
{

var i=1;
var mbno2=[com.value,""];
var mobileNo = mbno2.join(",");
	
if (com.checked==true)
{
document.getElementById('busniess_subcat').value+=mobileNo;
}
else
{
var re2=new RegExp('(.*)'+mobileNo+'(.*)$');

document.getElementById('busniess_subcat').value=document.getElementById('busniess_subcat').value.replace(re2,'$1$2');
}
}