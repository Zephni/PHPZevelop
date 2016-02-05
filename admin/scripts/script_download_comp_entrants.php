<?php
	/* INCLUDE MANDATORY CLASSES
	----------------------------------------------------*/
	require("../classes/class.config.php");
	require("../classes/class.db.php");
	require("../classes/class.page.php");
	require("../inc/functions.php");

	/* INSTANTIATE DATABASE AND CONNECT
	----------------------------------------------------*/
	$db = new db($cfg->db->host, $cfg->db->user, $cfg->db->pass, $cfg->db->name);

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=ll_comp_entrants_".$_GET["id"].".csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	function array_to_csv_fmt($array){
		$newarray = array();
		foreach($array as $item){$newarray[] = '"'.$item.'"';}
		$html = implode(",", $newarray);
		return $html."\n";
	}
	
	$query = $db->query("SELECT 
						 	`comp_id`,`name`,`address1`,`address2`,`city`,`postcode`,`tel`,`email`,`answer`,`notes`,
							`checkbox`,`check_terms`,`timestamp`
							FROM `comp_entries`
							WHERE comp_id=:comp_id
							", array("comp_id" => $_GET['id']));
	
	$html = array_to_csv_fmt(array(
								   'comp_id','name','address1','address2','city','postcode','tel','email','answer','notes',
									'checkbox','check_terms','timestamp'
								   ));	
	foreach($query as $item){
		$item["timestamp"] = date("d-m-Y h:ia", $item["timestamp"]);
		$html .= array_to_csv_fmt($item);
	}
	
	echo $html;
?>