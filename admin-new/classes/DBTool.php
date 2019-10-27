<?php
	class DBTool
	{
		public static function NiceName($Name)
		{
			$Value = str_replace("_", " ", $Name);
			return ucfirst($Value);
		}
		
		public static function GetAllTables()
		{
			global $PHPZevelop;
			global $DB;
			
			$Tables = array();
			
			foreach($DB->Query("SELECT * FROM information_schema.tables t WHERE t.table_schema='".$PHPZevelop->CFG->DB->Name."'") as $Item)
			{
				if(isset($Item["TABLE_NAME"]))
				{
					$Tables[$Item["TABLE_NAME"]] = array(
						"real_name" => $Item["TABLE_NAME"],
						"name" => DBTool::NiceName($Item["TABLE_NAME"]),
						"columns" => self::GetTableColumns($Item["TABLE_NAME"]),
						"information" => array()
					);
					
					foreach($Item as $K => $V)
						$Tables[$Item["TABLE_NAME"]]["information"][strtolower($K)] = $V;
				}
			}
			
			return $Tables;
		}
		
		public static function GetTable($TableName)
		{
			$Tables = self::GetAllTables();
			return $Tables[$TableName];
		}
		
		public static function GetTableColumns($TableName)
		{
			global $PHPZevelop;
			global $DB;

			$Array = array();
			foreach($DB->Query("SELECT `column_name`, `column_default`, `column_comment` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$PHPZevelop->CFG->DB->Name."' AND `TABLE_NAME`=:table_name", array("table_name" => $TableName)) as $Item)
				$Array[$Item["column_name"]] = $Item;

			return $Array;
		}
		
		public static function DisplayList($_Config)
		{
			global $DB;
			global $Pagination;
			global $FrontEndImageLocationLocal;
			global $FrontEndImageLocationRoot;

			$Config = array_merge(array(
				"Table" => null,
				"HideFields" => array(),
				"TableClass" => "DBToolTable",
				"InfoRowClass" => "DBToolInfoRow",
				"RowClass" => "DBToolRow",
				"ExtraFieldClass" => "DBToolExtraField",
				"Where" => array(),
				"ExtraFields" => array(),
				"NoDataHTML" => "<p style='color: #888888; text-align: center;'><br /><br /> - No data available - </p>",
				"FieldMaxLength" => 50,
				"Order" => "ORDER BY id DESC",
				"Limit" => ""
			), $_Config);
			
			if($Config["Table"] == null)
				die("DBTool::DisplayList() cannot have null table");
			
			$TableConfig = self::TableConfigArray($Config["Table"]);

			$HTML = "<table class='".$Config["TableClass"]."'>";
			
			// Build info row
			$HTML .= "<thead><tr class='".$Config["InfoRowClass"]."'>";
			$ColumnComments = array();
			foreach(self::GetTableColumns($Config["Table"]) as $K => $Item)
			{
				if(isset($TableConfig["HideFields"])){
					foreach($TableConfig["HideFields"] as $K => $V) $TableConfig["HideFields"][$K] = trim($V);
					if(in_array($Item["column_name"], $TableConfig["HideFields"]))
						continue;
				}

				if(isset($TableConfig["DefaultFields"])){
					foreach($TableConfig["DefaultFields"] as $K => $V) $TableConfig["DefaultFields"][$K] = trim($V);
					if(!in_array($Item["column_name"], $TableConfig["DefaultFields"]))
						continue;
				}

				$ColumnComments[$Item["column_name"]] = $Item["column_comment"];
				$HTML .= "<th style='".(($Item["column_name"]== "id") ? "width: 1%; white-space: nowrap;" : "auto")."'>".self::NiceName($Item["column_name"])."</th>";
			}

			if(!isset($TableConfig["AllowExtraFields"]))
				foreach($Config["ExtraFields"] as $ExtraField)
					$HTML .= "<th style='width: 1%; white-space: nowrap;' class='".$Config["ExtraFieldClass"]."'></th>";

			$HTML .= "</tr></thead>";
			// Build info row
			
			// Final Where
			if(count($Config["Where"]) == 0)
				$Config["Where"][] = array("1", "=", "1");

			$Config["Where"][] = $Config["Order"];
			if(strlen($Config["Limit"]) > 0) $Config["Where"][] = "LIMIT ".$Config["Limit"];

			// Build rows
			$DataAvailable = false;
			$Data = $DB->Select("*", $Config["Table"], $Config["Where"]);
			
			foreach($Data as $Row)
			{
				$DataAvailable = true;

				$HTML .=  "<tr class='".$Config["RowClass"]."'>";
				foreach($Row as $Key => $Field)
				{
					if(isset($TableConfig["HideFields"]) && in_array($Key, $TableConfig["HideFields"]))
						continue;

					if(isset($TableConfig["DefaultFields"]) && !in_array($Key, $TableConfig["DefaultFields"]))
						continue;

					$TDStyle = "";

					$FieldConfigArray = DBTool::FieldConfigArray($ColumnComments[$Key]);

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "select")
					{
						if(isset($FieldConfigArray["join"]))
						{
							$Field = $DB->Select($FieldConfigArray["join"][1], $FieldConfigArray["join"][0], array(array("id", "=", $Field)), true);
							$Field = (isset($Field[$FieldConfigArray["join"][1]])) ? $Field[$FieldConfigArray["join"][1]] : "- none -";
						}
						else if(isset($FieldConfigArray["configkv"]))
						{
							$TempConf = $DB->Select("*", "config", array(array("_key", "=", $FieldConfigArray["configkv"][0])), true);
							$TempData = array();
							foreach(explode(PHP_EOL, $TempConf["_value"]) as $KV){
								$KV = explode($TempConf["delimiter_2"], $KV);
								$TempData[trim($KV[0])] = trim($KV[1]);
							}
							$Field = (isset($TempData[$Field])) ? $TempData[$Field] : "";
						}
					}

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "select")
					{
						if(isset($FieldConfigArray["value"]))
						{
							$Field = $DB->Select($FieldConfigArray["join"][1], $FieldConfigArray["join"][0], array(array("id", "=", $Field)), true);
							$Field = (isset($Field[$FieldConfigArray["join"][1]])) ? $Field[$FieldConfigArray["join"][1]] : "- none -";
						}
					}

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "checkbox")
					{
						if(isset($FieldConfigArray["join"]))
						{
							$StringValues = array();
							$CheckboxValues = explode("|", $Field);
							foreach($CheckboxValues as $Item)
							{
								$Temp = $DB->SelectSingle($FieldConfigArray["join"][1], $FieldConfigArray["join"][0], array(array("id", "=", $Item)));
								$Temp = (isset($Temp[$FieldConfigArray["join"][1]])) ? $Temp[$FieldConfigArray["join"][1]] : "- none -";
								$StringValues[] = $Temp;
							}
							
							$Field = implode(", ", $StringValues);
						}
					}

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "tel")
					{
						$Field = substr($Field, 0, 5)." ".substr($Field, 6);
					}

					if(isset($FieldConfigArray["money"]) && $FieldConfigArray["money"][0] == "true")
					{
						$Field = "&pound;".$Field;
					}

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "timestamp")
						$Field = date("d-m-Y H:ia", $Field);

					if(isset($FieldConfigArray["type"]) && $FieldConfigArray["type"][0] == "image")
					{
						if(strlen($Field) > 0 && file_exists($FrontEndImageLocationRoot."/".$FieldConfigArray["filelocation"][0]."/".$Field))
						{
							$Field = "<center><img src='".$FrontEndImageLocationLocal."/".$FieldConfigArray["filelocation"][0]."/".$Field."' style='width: 100%; max-width: 250px;' /></center>";
							$TDStyle = "padding: 0px;";
						}
						else
							$Field = "<center> - none - </center>";
					}
					else
					{
						$Field = strip_tags($Field);
						$Field = substr($Field, 0, $Config["FieldMaxLength"]).((strlen($Field) > $Config["FieldMaxLength"]) ? "..." : "");
					}

					$HTML .= "<td style='".$TDStyle."'>".$Field."</td>";
				}

				if(!isset($TableConfig["AllowExtraFields"]))
				{
					foreach($Config["ExtraFields"] as $ExtraField){
						if(strpos($ExtraField, "*special|showentries*") !== false)
						{
							$Total = $DB->QuerySingle("SELECT "); //count($DB->Select("id", "comp_entries", array(array("comp_id", "=", $Row["id"]))));
							$OptIns = count($DB->Select("id", "comp_entries", array(array("comp_id", "=", $Row["id"]), "AND", array("`options`", "LIKE", "%optin:1%"))));
							$ExtraField = str_replace("*special|showentries*", $OptIns."/".$Total, $ExtraField);
						}

						if(strpos($ExtraField, "*special|linktoentries*") !== false)
						{
							$ExtraField = str_replace("*special|linktoentries*", "<a href='/yhadmin/select/comp_entries/?field1=comp_id&comparison1=%3D&value1=".$Row["id"]."'>entries</a>", $ExtraField);
						}

						if(strpos($ExtraField, "*special|showprojectcount*") !== false)
						{
							$Total = count($DB->Select("id", "projects", array(array("uid", "=", $Row["id"]))));
							$ExtraField = str_replace("*special|showprojectcount*", $Total, $ExtraField);
						}

						$HTML .= "<td class='".$Config["ExtraFieldClass"]."'>".str_replace("#", $Row["id"], $ExtraField)."</td>";
					}
					
				}

				$HTML .=  "</tr>";
			}
			// Build rows
			
			$HTML .= "</table>";

			if(!$DataAvailable)
				$HTML .= $Config["NoDataHTML"];

			return $HTML;
		}

		public static function TableConfigArray($Table)
		{
			$Data = self::GetTable($Table);
			$String = $Data["information"]["table_comment"];
			return self::TableConfigStringArray($String);
		}

		public static function TableConfigStringArray($String)
		{
			$FinalArray = array();

			if(strlen($String) == 0)
				return $FinalArray;

			foreach(explode(PHP_EOL, $String) as $Item)
			{
				$StringParts = explode("::", $Item);
				$Key = $StringParts[0];
				if(!isset($StringParts[1])) continue;
				$Value = explode(",", $StringParts[1]);
				$FinalArray[$Key] = $Value;
			}

			return $FinalArray;
		}

		public static function FieldConfigArray($String)
		{
			$FinalArray = array();

			foreach(explode(";", $String) as $Comment){
				$Comment = explode(":", $Comment);
				$Key = array_shift($Comment);
				$FinalArray[$Key] = $Comment;
			}

			return $FinalArray;
		}
	}