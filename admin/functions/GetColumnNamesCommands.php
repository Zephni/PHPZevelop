<?php
	function GetColumnNamesCommands($Table, &$ColumnNames, &$ColumnCommands)
	{
		global $DatabaseName;
		global $DB;
		
		$Columns = $DB->Query("SELECT `column_name`, `column_default`, `column_comment` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$DatabaseName."' AND `TABLE_NAME`=:table_name", array("table_name" => $_GET["param_0"]));
		$ColumnNames = array();
		$ColumnCommands = array();
		foreach($Columns as $Item)
		{
			$ColumnNames[] = $Item["column_name"];
			if(strlen($Item["column_comment"]) > 0)
			{
				$Coms = explode(";", $Item["column_comment"]);
				$ColumnCommands[$Item["column_name"]] = array();
				foreach($Coms as $Part)
				{
					$Part = explode(":", $Part);
					$ColumnCommands[$Item["column_name"]][$Part[0]] = array_slice($Part, 1);
				}
			}
		}
	}