<?php

	error_reporting(E_ALL & ~E_NOTICE); 	
	
	// diagnostic mode 
	
	$diag = 0;
	if (isset($_GET["diag"])) $diag = intval($_GET["diag"]);
			
	// get hashed visitor fingerprint to anonymize data
	
	$fph = '';
	if (isset($_GET["fph"])) $fph = $_GET["fph"];
	if (($fph[0] ?? null) != 'x')
		die; // invalid fph sent by js
	
	$fph = dechex(crc32($fph . $_SERVER['REMOTE_ADDR'] . date('Ymd'))); // append ip and day to hash (only one visit per day)
	
	
	// write to last visitors and compare in order not to count one visitor multiple times
		
	$max_recent_visitors = 100;
	
	$recent_visitors_filename = 'log-recent.php';
	$recent_visitors_filename_fd = @fopen($recent_visitors_filename, 'r');
	
	if (!$recent_visitors_filename_fd)
	{
		// create file
		$recent_visitors_filename_fd = @fopen($recent_visitors_filename, 'w');
		if ($recent_visitors_filename_fd != null)
			@fclose($recent_visitors_filename_fd);
		else
		{
			if ($diag)
			{
				echo 'cannot write visitors file log-recent.php: ';
				$e = error_get_last();
				if ($e != null && isset($e['message']))	echo $e['message'];
				echo '<br/>';	
			}
			
			die;
		}
		
		$recent_visitors_filename_fd = @fopen($recent_visitors_filename, 'r');		
	}
	
	if (!$recent_visitors_filename_fd)
	{
		if ($diag)
		{
			echo 'cannot read visitors file log-recent.php: ';
			$e = error_get_last();
			if ($e != null && isset($e['message']))	echo $e['message'];
			echo '<br/>';	
		}
			
		die;
	}
	
	// read recent visitors 
	
	$lines = [];
	$valueSeparator = ' ';
	
	while( ($line = fgets($recent_visitors_filename_fd)) !== false ) 
	{
		if (strlen($line) < 12)
			continue; // for php begin and end, or invalid lines
		
		$line = substr($line, 3); // skip comment
		
		$lineparts = null;
		if ($line != "")
			$lineparts = explode($valueSeparator, $line);
		
		if ($lineparts != null && sizeof($lineparts) > 0 && $lineparts[0] == $fph && !$diag)
			die; // already visited today
				
		if ($line != '')
			array_push($lines, $line);
	}
	fclose($recent_visitors_filename_fd);
	
	
	// get host of referrer
	
	$refstr = '';
	if (isset($_GET["ref"])) 
		$refstr = $_GET["ref"];
	
	$refstr = str_replace(array("\n", "\r", " "), '_', $refstr);	
	if (!$refstr || $refstr == '') $refstr = "?";
	$datestr = date('Y-m-d/H:i'); // :s
	
	
	// get page this is from
	
	$usedfrompage = '';
	if (isset($_SERVER['HTTP_REFERER']))
	{
		$parsed_url = parse_url($_SERVER['HTTP_REFERER']);
		if ($parsed_url && isset($parsed_url["path"]))
		{
			$usedfrompage = $parsed_url["path"];
			$usedfrompage = str_replace(array("\n", "\r", " "), '_', $usedfrompage);	
		}
	}
	if (!$usedfrompage || $usedfrompage == '') $usedfrompage = "?";
	
	
	// get simple user agent string
	
	function getOS() 
	{ 
		$os_platform    =   "unknown";
		
		if (!isset($_SERVER['HTTP_USER_AGENT']))
			return $os_platform;
		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];		
		$os_array       =   array(
			'/googlebot/i'			=>  'googlebot',
			'/bingbot/i'			=>  'bingbot',
			'/windows nt 10.0/i'    =>  'Windows10/11',
			'/windows nt 6.3/i'     =>  'Windows8.1',
			'/windows nt 6.2/i'     =>  'Windows8',
			'/windows nt 6.1/i'     =>  'Windows7',
			'/windows/i'     		=>  'Windows',
			'/macintosh|mac os x/i' =>  'macOS',
			'/mac_powerpc/i'        =>  'MacOS9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/webos/i'              =>  'Mobile'
							);

		foreach ($os_array as $regex => $value) 
			if (preg_match($regex, $user_agent)) 
			{
				$os_platform = $value;
				break;
			}

		return $os_platform;
	}
	
	$useragentstr = getOS();
	$useragentstr = str_replace(array("\n", "\r", " "), '_', $useragentstr);	
	if (!$useragentstr || $useragentstr == '') $useragentstr = "?";
	
	$langstr = '';
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE'] != null)
		$langstr = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);	
	$langstr = str_replace(array("\n", "\r", " "), '_', $langstr);	
	if (!$langstr || $langstr == '') $langstr = "?";
	
	
	// append and write new recent visitors
	
	if (!$diag)
	{
		while(sizeof($lines) > $max_recent_visitors)
			array_pop($lines);
		
		array_unshift($lines, $fph . $valueSeparator . $datestr . $valueSeparator . $useragentstr . $valueSeparator . $langstr . $valueSeparator . $refstr . $valueSeparator . $usedfrompage . "\n"); // put into front of visitors
	}
	
	
	$recent_visitors_filename_fd = @fopen($recent_visitors_filename, 'w');
	
	if (!$recent_visitors_filename_fd )
	{
		if ($diag)
		{
			echo 'cannot write visitors file log-recent.php: ';
			$e = error_get_last();
			if ($e != null && isset($e['message']))	echo $e['message'];
			echo '<br/>';	
		}
		
		die;
	}
	
	fputs($recent_visitors_filename_fd, "<?php\n");
	
	for ($i=0; $i<sizeof($lines); ++$i)
		fputs($recent_visitors_filename_fd, "// " . $lines[$i]);
	
	fputs($recent_visitors_filename_fd, "?>");
	fclose($recent_visitors_filename_fd);
	
		
	// increase visitors of month 
	function increaseVisitorCountInFile($filename)
	{
		$visitorCountInFile = 0;
		
		$file = @fopen($filename, 'r');
		if ($file)
		{
			fgets($file); // read "<?php"
			$line = fgets($file);		
			if ($line !== false && (substr( $line, 0, 12 ) === "// visitors:"))
				$visitorCountInFile = intval(substr($line, 12));
			
			$visitorCountInFile += 1;
			
			fclose($file);
		}
		else
			$visitorCountInFile = 1;
		
		$file = @fopen($filename, 'w');
		if ($file)
		{
			@fputs($file, "<?php\n// visitors:$visitorCountInFile\n?>" );		
			@fclose($file);
		}
	}	
	
	
	increaseVisitorCountInFile('log-' . date("Y-m") . '.php');	
?>