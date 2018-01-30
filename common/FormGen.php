<?php
	if(!class_exists("FormGen"))
	{
		class FormGen
		{
			// Constants
			const STYLE_TITLEABOVEINPUT = 0;
			const STYLE_TEXTSAMEINPUT = 1;

			// Public properties
			public $Elements = array();

			public $DefaultAttributes = array(
				"type" => "text"
			);

			public $DefaultOptions = array(
				
			);

			public $DefaultBuildOptions = array(
				"Style" => FormGen::STYLE_TITLEABOVEINPUT,
				"ColNum" => 1,
				"FormAttributes" => array("action" => "", "method" => "post", "enctype" => "multipart/form-data"),
				"TableAttributes" => array("class" => "FormGen")
			);

			public $PrepopData = array();

			// Public methods
			public function AddElement($ApplyAttributes = array(), $ApplyOptions = array())
			{
				// Set attributes and options
				$Attributes = array_merge($this->DefaultAttributes, $ApplyAttributes);
				$Options = array_merge($this->DefaultOptions, $ApplyOptions);

				// Add element
				$this->Elements[] = array("Attributes" => $Attributes, "Options" => $Options);
			}

			public function Build($ApplyBuildOptions = array())
			{
				if(isset($ApplyBuildOptions["data"]) && $ApplyBuildOptions["data"] != null)
					$this->PrepopData = $ApplyBuildOptions["data"];

				// Set build options
				$BuildOptions = array_merge($this->DefaultBuildOptions, $ApplyBuildOptions);
				
				// Build HTML
				$HTML = "<form ".$this->BuildAttributes($BuildOptions["FormAttributes"]).">";
				$HTML .= "<table ".$this->BuildAttributes($BuildOptions["TableAttributes"]).">";

				for($Y = 0; $Y < count($this->Elements) / $BuildOptions["ColNum"]; $Y++)
				{
					$HTML .= "<tr>";

					if($BuildOptions["Style"] == FormGen::STYLE_TITLEABOVEINPUT)
					{
						for($X = 0; $X < $BuildOptions["ColNum"]; $X++)
						{
							$I = ($Y * $BuildOptions["ColNum"]) + $X;
							if($BuildOptions["ColNum"] > 1 || (isset($this->Elements[$I]) && isset($this->Elements[$I]["Options"]["title"]) && $this->Elements[$I]["Options"]["title"] != ""))
							{
								$HTML .= "<td>";
								if(isset($this->Elements[$I]) && isset($this->Elements[$I]["Options"]["title"]))
									$HTML .= $this->Elements[$I]["Options"]["title"];
								$HTML .= "</td>";
							}
						}
							
						$HTML .= "</tr><tr>";

						for($X = 0; $X < $BuildOptions["ColNum"]; $X++)
						{
							$I = ($Y * $BuildOptions["ColNum"]) + $X;
							$HTML .= "<td>";
							if(isset($this->Elements[$I]))
								$HTML .= $this->BuildElement($this->Elements[$I]);
							$HTML .= "</td>";
						}
					}

					$HTML .= "</tr>";
				}

				$HTML .= "</table>";
				$HTML .= "</form>";

				return $HTML;
			}

			// Private methods
			private function BuildElement($Item)
			{
				if(!isset($Item["Attributes"]["type"]) || !is_string($Item["Attributes"]["type"]))
					return "";

				if($Item["Attributes"]["type"] == "html")
					return $Item["Attributes"]["value"];

				if(!isset($Item["Options"]["prehtml"])) $Item["Options"]["prehtml"] = "";
				if(!isset($Item["Options"]["posthtml"])) $Item["Options"]["posthtml"] = "";

				$HTML = "";

				if($Item["Attributes"]["type"] == "text")
				{
					$HTML .= $Item["Options"]["prehtml"]."<input ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "textarea")
				{
					$Value = (isset($Item["Attributes"]["value"])) ? $Item["Attributes"]["value"] : "";
					if(isset($Item["Attributes"]["name"]) && isset($this->PrepopData[$Item["Attributes"]["name"]]))
						$Value = $this->PrepopData[$Item["Attributes"]["name"]];

					unset($Item["Attributes"]["value"]);
					$HTML .= "<textarea ".$this->BuildAttributes($Item["Attributes"]).">".$Value."</textarea>";
				}
				else if($Item["Attributes"]["type"] == "select")
				{
					$Value = "";
					$FoundSelectedItem = false;
					if(isset($Item["Attributes"]["value"]))
					{
						$Value = $Item["Attributes"]["value"];
						unset($Item["Attributes"]["value"]);
					}
					
					$HTML .= $Item["Options"]["prehtml"]."<select ".$this->BuildAttributes($Item["Attributes"]).">";
					$HTML .= "<option value=''> - none - </option>";
					foreach($Item["Options"]["data"] as $K => $V)
					{
						if(isset($Item["Options"]["forceselected"]))
						{
							$HTML .= "<option value='".$K."' ".(($Item["Options"]["forceselected"] == $K) ? "selected='selected'" : "").">".$V."</option>";
							if($Item["Options"]["forceselected"] == $K) continue;
						}
						else if(isset($this->PrepopData[$Item["Attributes"]["name"]]))
						{
							$HTML .= "<option value='".$K."' ".(($this->PrepopData[$Item["Attributes"]["name"]] == $K) ? "selected='selected'" : "").">".$V."</option>";
							if($this->PrepopData[$Item["Attributes"]["name"]] == $K) continue;
						}
						else
						{
							$HTML .= "<option value='".$K."' ".(($Value == $K && $Value != 0) ? "selected='selected'" : "").">".$V."</option>";
							if($Value == $K) continue;
						}
					}
						
					$HTML .= "</select>".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "checkbox")
				{
					foreach($Item["Options"]["data"] as $K => $V)
					{
						if(strlen($V) > 0 && isset($Item["Options"]["forceselected"]) && strstr($Item["Options"]["forceselected"], $V) !== false)
							$HTML .= "<div style='display: inline-block;'><input type='checkbox' name='".$Item["Attributes"]["name"]."[]' value='".$K."' style='width: 23px; height: 20px; margin-right: 4px;' checked='checked' /><span style='position: relative; top: -5px; padding-right: 10px;'>".$V."</span></div>";
						else if(strlen($V) > 0 && isset($this->PrepopData[$Item["Attributes"]["name"]]) && strstr(strtolower($this->PrepopData[$Item["Attributes"]["name"]]), strtolower($V)) !== false)
							$HTML .= "<div style='display: inline-block;'><input type='checkbox' name='".$Item["Attributes"]["name"]."[]' value='".$K."' style='width: 23px; height: 20px; margin-right: 4px;' checked='checked' /><span style='position: relative; top: -5px; padding-right: 10px;'>".$V."</span></div>";
						else
							$HTML .= "<div style='display: inline-block;'><input type='checkbox' name='".$Item["Attributes"]["name"]."[]' value='".$K."' style='width: 23px; height: 20px; margin-right: 4px;' /><span style='position: relative; top: -5px; padding-right: 10px;'>".$V."</span></div>";
					}
				}
				else if($Item["Attributes"]["type"] == "file")
				{
					$Item["Attributes"]["style"] = "width: auto;";
					$HTML .= $Item["Options"]["prehtml"]."<input ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "submit")
				{
					$HTML .= $Item["Options"]["prehtml"]."<input ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else
				{
					$HTML .= $Item["Options"]["prehtml"]."<input ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}

				return $HTML;
			}

			private function BuildAttributes($Array)
			{
				$FinalArray = array();

				if(isset($Array["name"]) && isset($this->PrepopData[$Array["name"]]))
					$Array["value"] = $this->PrepopData[$Array["name"]];

				foreach($Array as $K => $V)
					$FinalArray[] = $K.'="'.str_replace('"', "'", $V).'"';

				return implode(" ", $FinalArray);
			}

			// Added 05/05/2017 (Pass it DBTool::GetTable("tablename"))
			public static function DBFormBuild($Table, $_Config)
			{
				global $DB;
				global $PHPZevelop;
				global $FrontEndImageLocationLocal;

				$FormGen = new FormGen();
				$Config = array_merge(array(
					"Data" => array(),
					"HideFields" => array("id", "uid"),
					"CustomFields" => array(),
					"SubmitText" => "Submit"
				), $_Config);

				foreach($Table["columns"] as $Item)
				{
					// If id then skip
					if(in_array($Item["column_name"], $Config["HideFields"])) continue;

					if(array_key_exists($Item["column_name"], $Config["CustomFields"]))
					{
						$Config["CustomFields"][$Item["column_name"]]($FormGen, $Item);
						continue;
					}

					$PreHTML = "";
					$PostHTML = "";

					// Get nice column title
					$Title = ucfirst(str_replace("_", " ", $Item["column_name"]));

					// Build column options array
					$ColumnOptions = DBTool::FieldConfigArray($Item["column_comment"]);

					// Data array
					$Data = array();

					// Select data
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "select")
					{
						if(isset($ColumnOptions["values"]))
						{
							foreach($ColumnOptions["values"] as $Opt){
								$Opt = explode("|", $Opt);
								$Data[$Opt[0]] = $Opt[1];
							}
						}
						else if(isset($ColumnOptions["join"]))
						{
							foreach($DB->Select("id,".$ColumnOptions["join"][1], $ColumnOptions["join"][0]) as $Row)
								$Data[$Row["id"]] = $Row[$ColumnOptions["join"][1]];
						}
						else if(isset($ColumnOptions["configkv"]))
						{
							$TempConf = $DB->Select("*", "config", array(array("_key", "=", $ColumnOptions["configkv"][0])), true);
							
							foreach(explode(PHP_EOL, $TempConf["_value"]) as $KV){
								$KV = explode($TempConf["delimiter_2"], $KV);
								$Data[trim($KV[0])] = trim($KV[1]);
							}
						}
					}

					// Checkbox
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "checkbox")
					{
						foreach($ColumnOptions["data"] as $V)
						{
							$Temp = explode("|", $V);
							$Data[$Temp[0]] = $Temp[1];
						}
					}

					// File type
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "file")
					{
						$ColumnOptions["type"][0] = "file";

						if(isset($Config["Data"][$Item["column_name"]]) && strlen($Config["Data"][$Item["column_name"]]) > 0)
						{
							$PreHTML = "<table style='width: 100%;'><tr><td style='width: 10%;'>
								<a href='".$FrontEndImageLocationLocal."/".$ColumnOptions["location"][0]."/".$Config["Data"][$Item["column_name"]]."' target='_blank'>".$Config["Data"][$Item["column_name"]]."</a>
							</td><td style='width: 90%;'>";
							$PostHTML = "</td></tr></table>";
						}
					}
					
					// Image type
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "image")
					{
						$ColumnOptions["type"][0] = "file";
						$ColumnOptions["class"][0] = "ImageSelector ".((isset($ColumnOptions["class"][0])) ? $ColumnOptions["class"][0] : "");

						if(isset($Config["Data"][$Item["column_name"]]))
							$Img = $FrontEndImageLocationLocal."/".$ColumnOptions["filelocation"][0]."/".$Config["Data"][$Item["column_name"]];
						else
							$Img = $PHPZevelop->Path->GetImage("components/no-image-icon.jpg", true);
						
						$PreHTML = "<table style='width: 100%;'><tr><td style='width: 10%;'><img src='".$Img."' class='PreviewImage' /></td><td style='width: 90%;'>";
						$PostHTML = "</td></tr></table>";
					}

					// Timestamp type
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "timestamp")
					{
						$ColumnOptions["type"][0] = "text";
						$ColumnOptions["class"][0] = "datetimepicker";

						if(isset($Config["Data"][$Item["column_name"]]))
							$Config["Data"][$Item["column_name"]] = date("Y/m/d H:i", $Config["Data"][$Item["column_name"]]);
						else
							$Config["Data"][$Item["column_name"]] = date("Y/m/d H:i", time());
					}
					
					// Default config passer
					if(substr($Item["column_default"], 0, 7) == "Config:")
					{
						$Temp = explode(":", $Item["column_default"]);
						$Default = $DB->Select("*", "config", array(array("_key", "=", $Temp[1])), true);
						$Item["column_default"] = $Default["_value"];
					}

					// Add element
					$FormGen->AddElement(array(
							"type" => (isset($ColumnOptions["type"][0])) ? $ColumnOptions["type"][0] : "text",
							"name" => $Item["column_name"],
							"value" => (isset($Config["Data"][$Item["column_name"]])) ? $Config["Data"][$Item["column_name"]] : $Item["column_default"],
							"class" => (isset($ColumnOptions["class"][0])) ? $ColumnOptions["class"][0] : ""
						), array(
							"title" => $Title,
							"data" => $Data,
							"prehtml" => (isset($PreHTML)) ? $PreHTML : "",
							"posthtml" => (isset($PostHTML)) ? $PostHTML : ""
					));
				}
				
				$FormGen->AddElement(array("type" => "submit", "value" => $Config["SubmitText"]));
				return $FormGen->Build(array("data" => $Config["Data"], "enctype" => "multipart/form-data"));
			}
		}
	}