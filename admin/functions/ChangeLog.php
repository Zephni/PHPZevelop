<?php
	function ChangeLog($String)
	{
		global $DB;
		global $Administrator;

		$DB->Insert("change_log", array(
			"user" => $Administrator->Data["username"],
			"log" => $String,
			"tstamp" => time()
		));
	}