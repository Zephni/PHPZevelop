<?php
	// PHPZevelop config
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PassParams" => true,
		"Template" => "none"
	));

	if($_GET["param_0"] == "currentdata")
	{
		if(!isset($_SESSION["csv_data"]))
			die("No data set");

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$_GET["param_0"].'.csv');

		// create a file pointer connected to the output stream
		$Output = fopen('php://output', 'w');

		// loop over the rows, outputting them
		foreach($_SESSION["csv_data"] as $Item)
			fputcsv($Output, $Item);
	}
	else
	{
		// Security
		$AvailableTables = array(
			"comp_entries"
		);

		foreach($_GET as $k => $v)
			$_GET[$k] = $DB->Quote($v);

		if(!in_array($_GET["param_0"], $AvailableTables))
			die("Invalid table");

		if(empty($_GET["param_3"]))
			die("Invalid parameters");

		$Fields = array();
		foreach($DB->Query("DESCRIBE ".$_GET["param_0"]) as $Field)
			$Fields[] = $Field["Field"];

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$_GET["param_0"].'.csv');

		// create a file pointer connected to the output stream
		$Output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($Output, $Fields);

		// fetch the data
		$Items = $DB->Query("SELECT * FROM ".$_GET["param_0"]." WHERE ".$_GET["param_1"]." ".$_GET["param_2"]." :argument", array("argument" => $_GET["param_3"]));

		// loop over the rows, outputting them
		foreach($Items as $Item)
			fputcsv($Output, $Item);
	}