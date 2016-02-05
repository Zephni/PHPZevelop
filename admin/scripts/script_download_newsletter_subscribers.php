<?php
	echo "test";

	/* INCLUDE MANDATORY CLASSES
	----------------------------------------------------*/
	require("../classes/class.config.php");
	require("../classes/class.db.php");
	require("../classes/class.page.php");
	require("../inc/functions.php");

	/* INSTANTIATE DATABASE AND CONNECT
	----------------------------------------------------*/
	$db = new db($cfg->db->host, $cfg->db->user, $cfg->db->pass, $cfg->db->name);

	/* PAGE
	----------------------------------------------------*/
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=yh_nl_subs.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$query = $db->query("SELECT DISTINCT email, fname, lname FROM yh_newsletter_subscribers");
	foreach($query as $item){
		$html .= "\"".trim($item['email'])."\"\n";
	}
	
	echo substr($html, 0, strlen($html)-1);
?>