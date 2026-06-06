					
// Check if user opted in to DO NOT TRACK or set 'webstatisticsoff' to '1', thus doesn't want to be counted. 
// We honor this.

var enabledDoNotTrack = false;
try 
{
	var dnt = navigator.doNotTrack || window.doNotTrack || navigator.msDoNotTrack;
	if (dnt == "1" || dnt == "yes") 
		enabledDoNotTrack = true;
	
	if (localStorage.getItem('webstatisticsoff') == '1')
		enabledDoNotTrack = true;
	
} catch(e) {}	
			
if (!enabledDoNotTrack)
{	
	// invoke register visit
	
	var r = new XMLHttpRequest(); 				 	
	var regvisitloc = '';						 	
													
	var scriptloc = document.currentScript.src;	
	var idx = scriptloc.lastIndexOf('/');		 	
	if (idx != -1)								 	
		scriptloc = scriptloc.substring(0, idx+1) + "regvisit.php";	 	
	else										 	
		scriptloc = "regvisit.php";				 	
													
	var url = scriptloc + "?";					 	
	url += "ref=";								 	
	url += encodeURIComponent(document.referrer); 	
							
	function fphsh(s) { for(var i=0,h=9;i<s.length;) h = (h^s.charCodeAt(i++)*(387420489))|0; return Math.abs(h^h >>> 9); } 	
							
	var fp = navigator.language + ' ' + screen.width + "x" + screen.height + ' ' + navigator.userAgent; 	
	var fph = fphsh(fp).toString(16); 	
							
	url += '&fph=x';	 	
	url += fph;		 	
							
	r.open("GET", url); 	
	r.send();				
}
