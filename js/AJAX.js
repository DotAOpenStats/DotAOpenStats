	   function handleHttpResponse() {  
       document.getElementById("divActivities").innerHTML = "<table style='width:80%;margin-left:22px;'><tr><td height='180'><div align='center'><img style='vertical-align: middle;' src='img/loader.gif'> Loading...</div></td></tr></table>";	

		if (http.readyState == 4) {
			  if(http.status==200) {
			  	var results=http.responseText;
			  document.getElementById('divActivities').innerHTML = results;
			  }
  			}
		}
		
	    function requestActivities() {
        //document.getElementById("divActivities").innerHTML = "Loading...";		
		    var url = document.getElementById("buildUrl2").value;
            var t1 = document.getElementById("text1").value;
			var t2 = document.getElementById("text2").value;
			var t3 = document.getElementById("text3").value;
			var t4 = document.getElementById("text4").value;
			http.open("GET", "" + url + escape(t1)+"&keyword2="+escape(t2)+"&keyword3="+escape(t3)+"&keyword4="+escape(t4) , true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
        }
function getHTTPObject() {
  var xmlhttp;
 
  if(window.XMLHttpRequest){
    xmlhttp = new XMLHttpRequest();
  }
  else if (window.ActiveXObject){
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    if (!xmlhttp){
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    
}
  return xmlhttp;

  
}
var http = getHTTPObject(); // We create the HTTP Object


  function handleHttpResponse() {  
       document.getElementById("divActivities2").innerHTML = "<br /><div align='center'><table style='width:80%;margin-left:22px;'><tr><td height='180'><br /><div align='center'><img style='vertical-align: middle;' src='img/loader.gif'> Loading...</div><br /></td></tr></table></div>";	

		if (http.readyState == 4) {
			  if(http.status==200) {
			  	var results=http.responseText;
			  document.getElementById('divActivities2').innerHTML = results;
			  }
  			}
		}
		
	    function requestActivities2(url2) {
        document.getElementById("divActivities2").innerHTML = "Loading...";		
			http.open("GET", "" + url2 , true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
        }
function getHTTPObject() {
  var xmlhttp;
 
  if(window.XMLHttpRequest){
    xmlhttp = new XMLHttpRequest();
  }
  else if (window.ActiveXObject){
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    if (!xmlhttp){
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    
}
  return xmlhttp;

  
}
var http = getHTTPObject(); // We create the HTTP Object

/////////////////////////////////////////////////////////////////////////////////////
  function showSpoiler(obj)    
	{    
    var inner = obj.parentNode.getElementsByTagName('div')[0];    
        if (inner.style.display == 'none')        
        inner.style.display = '';    
    else        
    inner.style.display = 'none';    
	}
	
	
	function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "show";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
} 
////////////////////////////////////////////////


document.onclick=check;

var Ary=[];

function check(e) {
 var target = (e && e.target) || (event && event.srcElement);
 while (target.parentNode){
  if (target.className.match('pop')||target.className.match('poplink')) return;
  target=target.parentNode;
 }
 var ary=zxcByClassName('pop')
 for (var z0=0;z0<ary.length;z0++){
  ary[z0].style.display='none';
 }
}
function zxcByClassName(nme,el,tag){
 if (typeof(el)=='string') el=document.getElementById(el);
 el=el||document;
 for (var tag=tag||'*',reg=new RegExp('\\b'+nme+'\\b'),els=el.getElementsByTagName(tag),ary=[],z0=0; z0<els.length;z0++){
  if(reg.test(els[z0].className)) ary.push(els[z0]);
 }
 return ary;
}

function toggle2(layer_ref) {
 var hza = document.getElementById(layer_ref);
 if (hza && hza.style){
  if (!hza.set){ hza.set=true;  Ary.push(hza); }
  hza.style.display = (hza.style.display == '')? 'none':'';
 }
}