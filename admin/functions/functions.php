<?php
	/* set_exception_handler
	--------------------------------------*/
	set_exception_handler('exceptionHandler');

	function exceptionHandler($exception){
		die("There was an error: ".$exception->getMessage());
	}

	// Check if is timestamp
	function isValidTimeStamp($timestamp){
	    return ((string) (int) $timestamp === $timestamp) 
	        && ($timestamp <= PHP_INT_MAX)
	        && ($timestamp >= ~PHP_INT_MAX)
	        && strlen($timestamp) > 8;
	}

	// Build insert query from array
	function insertQueryFromArray($tbl, $arr){
		global $DB;
		$items = array();
		$finalarr = array();
		foreach($arr as $k => $v){
			if($v != "#/noupdate/#"){$items[] = $k."=:".$k; $finalarr[$k] = str_replace("£", "&pound;", $v);}
		}
		$items = (string)implode(",", $items);

		$DB->QuerySingle("INSERT INTO ".$tbl." SET ".$items, $finalarr);

		if(count($DB->error) < 1)
			return true;
		else
			return false;
	}
	
	// Build update query from array
	function updateQueryFromArray($tbl, $arr, $where, $id){
		global $DB;
		$items = array();
		$finalarr = array();
		foreach($arr as $k => $v){
			if($v != "#/noupdate/#"){
				$items[] = $k."=:".$k;
				$finalarr[$k] = str_replace("£", "&pound;", $v);
			}
		}
		$items = (string)implode(",", $items);

		$finalarr["id"] = $id;
		//var_dump($arr);
		//echo "UPDATE ".$tbl." SET ".htmlentities($items)." ".$where;
		//var_dump($finalarr);
		$DB->QuerySingle("UPDATE ".$tbl." SET ".$items." ".$where, $finalarr);

		if(count($DB->error) < 1)
			return true;
		else
			return false;
	}

	/* getIp() | attempts to return the user's IP address
	--------------------------------------*/
	function getIP(){ 
		$ip = NULL; 
		if(getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
		else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
		else $ip = "UNKNOWN";
		return $ip; 
	}

	// Commented out on 25/11/2014 (As asked by Jenna Morgan on 21/11/2014 by email)
	/*$allowedIPs = array("62.254.7.226");
	if(!in_array(getIP(), $allowedIPs) && substr(getIP(), 0, 6) != "10.195"){
		header("Location: /");
		die("Invalid IP");
	}*/

	function getImagePath($data, $fileUploadLocations, $fieldName){
		$name = $data[$fieldName];
		$path = $fileUploadLocations[$fieldName];
		return $path.$name;
	}
?>