<?php
	// Build insert query from array
	function InsertQueryFromArray($tbl, $arr){
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
	function UpdateQueryFromArray($tbl, $arr, $where, $id){
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