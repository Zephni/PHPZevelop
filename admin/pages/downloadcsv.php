<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"Template"	=> "none",
		"PassParams" => true
	));

	$Data = $DB->Select("*", $_GET["param_0"], $_SESSION["DBWhere"]);

	$Table = DBTool::GetTable($_GET["param_0"]);

	// Build rows
	$Rows = array();
	$I = -1; foreach($Data as $Item)
	{$I++;
		$II = -1; foreach($Item as $K => $V)
		{$II++;
			$ColumnCommands = DBTool::FieldConfigArray($Table["columns"][$K]["column_comment"]);
			
			// Manipulate value if needed
			if(isset($ColumnCommands) && isset($ColumnCommands["join"]))
			{
				$V = $DB->QuerySingle("SELECT ".$ColumnCommands["join"][1]." FROM ".$ColumnCommands["join"][0]." WHERE id=:id", array("id" => $Item[$K]));
				$V = $V[$ColumnCommands["join"][1]];
			}

			if(isset($ColumnCommands) && isset($ColumnCommands["type"]) && $ColumnCommands["type"][0] == "timestamp")
				$V = date("Y/m/d H:ia", $V);
			
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