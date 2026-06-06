<?php

 error_reporting(E_ALL & ~E_NOTICE); 

$title = 'Simple Web Statistics Admin Interface';
print "<!doctype html><html><head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><title>$title</title>";
print '<style type="text/css">* { font-family: Inter, Roboto, \'Helvetica Neue\', \'Arial Nova\', \'Nimbus Sans\', Arial, sans-serif; } 
 body { max-width: 1324px; }
 h3 { margin: 0; padding: 0;}
 .monthstats { background-color: #E5EBEE; border-radius: 15px; width:90%; display: inline-block; margin: 10px; padding: 10px; max-height: 300px; overflow-y: auto;}
 .statline { width: 100%; margin-bottom: 10px; position: relative; }
 .statline .vdate { font-weight: bold; }
 .statline .value { font-weight: normal; margin-left: 10px; width: 60px; display:inline-block; }
 .statline .bgstat { background-color: #4a4cff; display:inline-block; margin-left: 10px; height: 8px; vertical-align: middle; margin-top: -4px; opacity: 0.5;}
 table { width: 100% }
 table td { font-size: 12px; word-wrap: break-word; max-width: 30px; text-align: center; }
 tr:nth-child(even) {background: #E5EBEE}
 td:nth-child(4) { text-align: left; }
 td:nth-child(5) { text-align: left; }
 .warningcontainer { background-color: #ffb490;  margin: 10px 0px 10px 0px;  padding: 5px; }
 .settingswarning { color: red; }
</style></head>
<body>';
print "<h1>$title</h1><br/>";

$pwd = '';

if (isset($_POST["pwd"]))
	$pwd = $_POST["pwd"];

if (!isset($pwd) || $pwd != "hunter2")
{	
	print '<br/>Password: ';
	print '<form action="index.php" method="post">';
	print '<input name="pwd" type="password" >';
	print '<input type="submit" value="ok">';
	print '</form>';	
}
else
{
	// load stats
	
	$dates = [];
	$stats = [];
	
	$yearnow = intval(date("Y"));
	$monthnow = intval(date("n"));
	$visitorsThisMonth = 0;
	
	while(true)
	{
		if ($monthnow > 9)
			$monthstr = $monthnow;
		else
			$monthstr = "0" . $monthnow;
		
		$datestr = $yearnow . '-' . $monthstr;
		$filename = 'log-' . $datestr . '.php';
		$file = @fopen($filename, 'r');
		$visitorCountInFile = 0;
				
		if ($file)
		{
			fgets($file); // read "<?php"
			$line = fgets($file);		
			if ($line !== false && (substr( $line, 0, 12 ) === "// visitors:"))
				$visitorCountInFile = intval(substr($line, 12));						
			fclose($file);
			
			array_push($dates, $datestr);
			array_push($stats, $visitorCountInFile);
			
			if ($datestr == date('Y-m'))
				$visitorsThisMonth = $visitorCountInFile;
		}
		else
		{
			array_push($dates, $datestr);
			array_push($stats, 0);
			break;		
		}
		
		$monthnow = $monthnow - 1;
		if ($monthnow < 1)
		{
			$monthnow = 12;
			$yearnow = $yearnow - 1;
		}
	}
	
	$maxcount = 0;
	
	for ($i=0; $i<sizeof($dates); ++$i)
		if ($maxcount < $stats[$i])
			$maxcount = $stats[$i];
		
	print "<h3>Time now: " . date('Y-m-d/H:i') . "</h3><br/>";
	print "<h3>Visitors this month: " . $visitorsThisMonth . " </h3>";
	
		
	$dir = dirname(__FILE__);
		
	if (!is_writable($dir . DIRECTORY_SEPARATOR))
	{
		print('<div class="warningcontainer" ><span class="settingswarning">Warning: </span> The directory <i>' . $dir . '</i> isn\'t writeable - statistics won\'t work. To fix the problem, open your webserver with an FTP client, right-click the folder &quot;' . $dir . '&quot; and set the file permissions so that it is writeable. (Usually, you need to set some checkboxes to let more groups be able to write to this directory)</div>');
	}
	
	if (file_exists('log-recent.php') && !is_writable('log-recent.php'))
	{
		print('<div class="warningcontainer" ><span class="settingswarning">Warning: </span> The file <i>log-recent.php</i> isn\'t writeable - statistics won\'t work. To fix the problem, open your webserver with an FTP client, right-click the file &quot;log-recent.php&quot; and set the file permissions so that it is writeable. (Usually, you need to set some checkboxes to let more groups be able to write to this file)</div>');
	}

	print('
	<br/>Statistics won\'t work? Click <a href="javascript:analyzeproblems();">here</a> for analyzing the problem.
	<div class="problemcontainer"></div>
	<script>function analyzeproblems() 
	{ 
		var cont = document.querySelectorAll("div.problemcontainer");
		var iframe = document.createElement("iframe");
		iframe.style = "border:1px solid #cccccc; overflow:auto; height:40px; width:90%";
		iframe.src = "regvisit.php?fph=x343433&diag=1";
		var dv = document.createElement("div");
		dv.innerHTML  = "<br/>In case there is a problem with the counter, this box might show if there is an error:";
		cont[0].appendChild(dv);
		cont[0].appendChild(iframe);

	}</script>');

	
	print "<br/><div class=\"monthstats\">";
	
	for ($i=0; $i<sizeof($dates); ++$i)
	{
		$date = $dates[$i];
		$stat = $stats[$i];
		$width = 0;
		
		if ($maxcount > 0 && $stat > 0)
			$width = intval(round($stat / $maxcount * 70));
				
		print "<div class=\"statline\"><span class=\"vdate\">$date</span><span class=\"value\">$stat</span><div class=\"bgstat\" style=\"width:$width%\">&nbsp;</div></div>";
	}
	
	print "</div>";
	
	print "<br/><br/><h3>Recent Visitors</h3>A unique visitor is usually only counted as new visitor once a day. Only pages which have the web statistics component on them with the same directory set will be counted.<br/><br/>";
	
	print "\n<script>\r\nfunction sortTable(columnIndex) \r\n{\r\n    let table = document.getElementById('recentvistable');\r\n    let rows = Array.from(table.rows).slice(1); // exclude header row\r\n    \r\n    let ascending = table.dataset.sortOrder !== \"asc\";\r\n    table.dataset.sortOrder = ascending ? \"asc\" : \"desc\";\r\n\r\n    rows.sort((rowA, rowB) => {\r\n        let cellA = rowA.cells[columnIndex].innerText.trim();\r\n        let cellB = rowB.cells[columnIndex].innerText.trim();\r\n        \r\n\t\t// date \r\n\t\t\r\n\t\tlet datePattern = /(\d{4}-\d{2}-\d{2})\/(\d{2}:\d{2})/;\r\n        let matchA = cellA.match(datePattern);\r\n        let matchB = cellB.match(datePattern);\r\n        \r\n        if (matchA && matchB) {\r\n            let dateA = new Date(matchA[1] + 'T' + matchA[2]);\r\n            let dateB = new Date(matchB[1] + 'T' + matchB[2]);\r\n            return ascending ? dateA - dateB : dateB - dateA;\r\n        }\r\n\t\t\r\n\t\t// float \r\n\t\t\r\n        let numA = parseFloat(cellA);\r\n        let numB = parseFloat(cellB);\r\n        \r\n\t\t// number \r\n\t\t\r\n        if (!isNaN(numA) && !isNaN(numB)) {\r\n            return ascending ? numA - numB : numB - numA;\r\n        }\r\n\t\t        \r\n        return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);\r\n    });\r\n\r\n    rows.forEach(row => table.appendChild(row));\r\n}\r\n</script>";
	
	print "<table id=\"recentvistable\"><tr><th><a href=\"javascript:sortTable(0);\">time</a></th><th><a href=\"javascript:sortTable(1);\">os</a></th><th style=\"width:10%\"><a href=\"javascript:sortTable(2);\">language</a></th><th><a href=\"javascript:sortTable(3);\">page</a></th><th><a href=\"javascript:sortTable(4);\">referrer</a></th></tr>";
	
	$valueSeparator = ' ';
	$recent_visitors_filename_fd = @fopen('log-recent.php', 'rb');
	
	while( $recent_visitors_filename_fd != null && ($line = fgets($recent_visitors_filename_fd)) !== false ) 
	{
		if (strlen($line) < 12)
			continue; // for php begin and end, or invalid lines
		
		$line = substr($line, 3); // skip comment
		
		$lineparts = null;
		if ($line != "")
			$lineparts = explode($valueSeparator, $line);
		
		if ($lineparts == null && sizeof($lineparts) == 0)
			continue;
		
		$date = $lineparts[1];
		$os = htmlentities($lineparts[2]);
		$lang = htmlentities($lineparts[3]);
		$ref = htmlentities($lineparts[4]);
		$page = htmlentities($lineparts[5]);
		
		print"<tr><td>$date</td><td>$os</td><td>$lang</td><td>$page</td><td>$ref</td></tr>	";			
	}
	if ($recent_visitors_filename_fd)
		fclose($recent_visitors_filename_fd);
	
	print "</table>";
}

echo '<body></html>';
	

				
?>