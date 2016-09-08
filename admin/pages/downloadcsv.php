<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"Template"	=> "none",
		"PassParams" => true
	));

	// Check if invalid
	if(isset($_GET["param_0"]))
		foreach($TableOptions as $K => $V)
		{
			if(strstr(str_replace(" ", "", $_SESSION["Query"]), "FROM".$K) !== false && (isset($V["Status"]) && $V["Status"] == "disabled"))
				die("Invalid table");
		}
	else
		die("Invalid table");

	AppendLog("Downloaded CSV dataset from ".$_GET["param_0"]);

	$Data = $DB->Query($_SESSION["Query"], $_SESSION["Data"]);

	// Get column commands
	GetColumnsCommands($_GET["param_0"], $Columns, $ColumnCommands);
	$ColumnNames = array();
	foreach($Columns as $Item)
		$ColumnNames[] = $Item["column_name"];

	// Build rows
	$Rows = array();
	$I = -1; foreach($Data as $Item)
	{$I++;
		$II = -1; foreach($Item as $K => $V)
		{$II++;
			// Manipulate value if needed
			if(isset($ColumnCommands[$K]) && isset($ColumnCommands[$K]["join"]))
			{
				$V = $DB->QuerySingle("SELECT ".$ColumnCommands[$K]["join"][1]." FROM ".$ColumnCommands[$K]["join"][0]." WHERE id=:id", array("id" => $Item[$K]));
				$V = $V[$ColumnCommands[$K]["join"][1]];
			}
			
			if(strlen($V) > 60) $V = substr($V, 0, 57)."...";
			$V = strip_tags($V);

			$Rows[$I][$K] = $V;
		}
	}

	// Generate CSV
	if(count($Rows) > 0)
	{
		$Headings = array_keys($Rows[0]);
		$CSVGenerator = new CSVGenerator();

		if(isset($_GET["param_1"]) && $_GET["param_1"] == "test")
			$CSVGenerator->TestMode = true;

		$CSVGenerator->Headings = $Headings;
		$CSVGenerator->Rows = $Rows;
		$CSVGenerator->Generate();
	}
	else
	{
		die("No rows to export");
	}