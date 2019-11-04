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

				// Force ColNum to 1 as was causeing issues
				$BuildOptions["ColNum"] = 1;
				
				// Build HTML
				$HTML = "<form ".$this->BuildAttributes($BuildOptions["FormAttributes"]).">";

				for($Y = 0; $Y < count($this->Elements) / $BuildOptions["ColNum"]; $Y++)
				{
					$HTML .= "<div class='form-group'>";

					if($BuildOptions["Style"] == FormGen::STYLE_TITLEABOVEINPUT)
					{
						for($X = 0; $X < $BuildOptions["ColNum"]; $X++)
						{
							$I = ($Y * $BuildOptions["ColNum"]) + $X;
							if($BuildOptions["ColNum"] > 1 || (isset($this->Elements[$I]) && isset($this->Elements[$I]["Options"]["title"]) && $this->Elements[$I]["Options"]["title"] != ""))
							{
								//$HTML .= "<td style='width: ".(100 / $BuildOptions["ColNum"])."%;'>";
								if(isset($this->Elements[$I]) && isset($this->Elements[$I]["Options"]["title"]))
									$HTML .= $this->Elements[$I]["Options"]["title"];
								//$HTML .= "</td>";
							}
						}
							
						$HTML .= "";

						for($X = 0; $X < $BuildOptions["ColNum"]; $X++)
						{
							$I = ($Y * $BuildOptions["ColNum"]) + $X;
							//$HTML .= "<td>";
							if(isset($this->Elements[$I]))
								$HTML .= $this->BuildElement($this->Elements[$I]);
							//$HTML .= "</td>";
						}
					}

					$HTML .= "</div>";
				}

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
					$HTML .= $Item["Options"]["prehtml"]."<input class='form-control' ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "textarea")
				{
					$Value = (isset($Item["Attributes"]["value"])) ? $Item["Attributes"]["value"] : "";
					if(isset($Item["Attributes"]["name"]) && isset($this->PrepopData[$Item["Attributes"]["name"]]))
						$Value = $this->PrepopData[$Item["Attributes"]["name"]];

					unset($Item["Attributes"]["value"]);
					$HTML .= "<textarea class='form-control' ".$this->BuildAttributes($Item["Attributes"]).">".$Value."</textarea>";
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
					
					$HTML .= $Item["Options"]["prehtml"]."<select class='form-control' ".$this->BuildAttributes($Item["Attributes"]).">";
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
					$HTML .= "<div class='d-block'>";
					foreach($Item["Options"]["data"] as $K => $V)
					{
						if(strlen($V) > 0 && isset($Item["Options"]["forceselected"]) && strstr($Item["Options"]["forceselected"], $V) !== false)
							$HTML .= '<div class="form-check form-check-inline"><input checked="checked" class="form-check-input" type="checkbox" name="'.$Item["Attributes"]["name"].'[]" value="'.$K.'"><label class="form-check-label">'.$V.'</label></div>';
						else if(strlen($V) > 0 && isset($this->PrepopData[$Item["Attributes"]["name"]]) && in_array($K, explode("|", $this->PrepopData[$Item["Attributes"]["name"]])))
							$HTML .= '<div class="form-check form-check-inline"><input checked="checked" class="form-check-input" type="checkbox" name="'.$Item["Attributes"]["name"].'[]" value="'.$K.'"><label class="form-check-label">'.$V.'</label></div>';
						else
							$HTML .= '<div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="'.$Item["Attributes"]["name"].'[]" value="'.$K.'"><label class="form-check-label">'.$V.'</label></div>';
					}
					$HTML .= "</div>";
				}
				else if($Item["Attributes"]["type"] == "file")
				{
					$Item["Attributes"]["style"] = "width: auto;";
					$HTML .= $Item["Options"]["prehtml"]."<input ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "submit")
				{
					$HTML .= $Item["Options"]["prehtml"]."<input class='form-control btn btn-primary' ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
				}
				else
				{
					$HTML .= $Item["Options"]["prehtml"]."<input class='form-control' ".$this->BuildAttributes($Item["Attributes"])." />".$Item["Options"]["posthtml"];
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
					"DisableFields" => array(),
					"CustomFields" => array(),
					"AddFields" => array(),
					"SubmitText" => "Submit",
					"ColNum" => 1,
					"SubmitValue" => false
				), $_Config);

				foreach($Table["columns"] as $Item)
				{
					// If id then skip
					if(in_array($Item["column_name"], $Config["HideFields"])) continue;
					$Disabled = (in_array($Item["column_name"], $Config["DisableFields"])) ? true : false;
					$Multiple = false;

					if(array_key_exists($Item["column_name"], $Config["CustomFields"]))
					{
						$Config["CustomFields"][$Item["column_name"]]($FormGen, $Item);
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
						if(isset($ColumnOptions["values"]))
						{
							foreach($ColumnOptions["values"] as $V)
							{
								$Temp = explode("|", $V);
								$Data[$Temp[0]] = $Temp[1];
							}
						}
						else if(isset($ColumnOptions["join"]))
						{
							foreach($DB->Select("id,".$ColumnOptions["join"][1], $ColumnOptions["join"][0]) as $Row)
								$Data[$Row["id"]] = $Row[$ColumnOptions["join"][1]];
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
						global $FrontEndLocationLocal;
						$ColumnOptions["type"][0] = "file";
						$ColumnOptions["class"][0] = "form-control-file ".((isset($ColumnOptions["class"][0])) ? $ColumnOptions["class"][0] : "");
						$Item["column_name"] = $Item["column_name"]."[]";

						if(isset($Config["Data"][rtrim($Item["column_name"], "[]")]) && strlen($Config["Data"][rtrim($Item["column_name"], "[]")]) > 0)
						{
							$PostHTML = '<div class="w-100 d-block mt-3 p-2" style="overflow: auto; background: white; border: 1px solid #CCCCCC;">';
							
							foreach(explode(",", $Config["Data"][rtrim($Item["column_name"], "[]")]) as $Temp)
							{if(empty($Temp)) continue;
								$Path = urlencode($ColumnOptions["filelocation"][0]."/".$Temp);
								$PostHTML .= "
									<div style='padding: 8px; width: 180px; float: left; position: relative; border: 1px solid #CCCCCC; margin-right: 7px; '>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgdel=".$Path."&imgfield=".rtrim($Item["column_name"], "[]")."' class='font-weight-bold' style='font-size: 18px; width: 24px; height: 24px; text-align: center; vertical-align: middle; color: red; background: #FFFFFF; border-radius: 14px; position: absolute; right: 5px; top: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>x</a>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgshift=".$Temp."&imgfield=".rtrim($Item["column_name"], "[]")."&imgdir=left"."' class='text-primary font-weight-bold' style='font-weight: bold; width: 24px; height: 24px; text-align: center; vertical-align: middle; font-size: 16px; color: #333333; background: #FFFFFF; border-radius: 12px; position: absolute; left: 6px; bottom: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>&lt;</a>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgshift=".$Temp."&imgfield=".rtrim($Item["column_name"], "[]")."&imgdir=right"."' class='text-primary font-weight-bold' style='font-weight: bold; width: 24px; height: 24px; text-align: center; vertical-align: middle; font-size: 16px; color: #333333; background: #FFFFFF; border-radius: 12px; position: absolute; right: 6px; bottom: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>&gt;</a>
										<img src='".$FrontEndLocationLocal."/images/".$ColumnOptions["filelocation"][0]."/".$Temp."' style='width: 100%;' />
									</div>
								";
							}

							$PostHTML .= '</div>';
						}
					}
					
					// Images type
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "images")
					{
						global $FrontEndLocationLocal;
						$ColumnOptions["type"][0] = "file";
						$ColumnOptions["class"][0] = "form-control-file ".((isset($ColumnOptions["class"][0])) ? $ColumnOptions["class"][0] : "");
						$Item["column_name"] = $Item["column_name"]."[]";
						$Multiple = true;

						if(isset($Config["Data"][rtrim($Item["column_name"], "[]")]) && strlen($Config["Data"][rtrim($Item["column_name"], "[]")]) > 0)
						{
							$PostHTML = '<div class="w-100 d-block mt-3 p-2" style="overflow: auto; background: white; border: 1px solid #CCCCCC;">';
							
							foreach(explode(",", $Config["Data"][rtrim($Item["column_name"], "[]")]) as $Temp)
							{if(empty($Temp)) continue;
								$Path = urlencode($ColumnOptions["filelocation"][0]."/".$Temp);
								$PostHTML .= "
									<div style='padding: 8px; width: 180px; float: left; position: relative; border: 1px solid #CCCCCC; margin-right: 7px; '>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgdel=".$Path."&imgfield=".rtrim($Item["column_name"], "[]")."' class='font-weight-bold' style='font-size: 18px; width: 24px; height: 24px; text-align: center; vertical-align: middle; color: red; background: #FFFFFF; border-radius: 14px; position: absolute; right: 5px; top: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>x</a>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgshift=".$Temp."&imgfield=".rtrim($Item["column_name"], "[]")."&imgdir=left"."' class='text-primary font-weight-bold' style='font-weight: bold; width: 24px; height: 24px; text-align: center; vertical-align: middle; font-size: 16px; color: #333333; background: #FFFFFF; border-radius: 12px; position: absolute; left: 6px; bottom: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>&lt;</a>
										<a href='".$PHPZevelop->CFG->SiteDirLocal.$PHPZevelop->CFG->PagePath."?imgshift=".$Temp."&imgfield=".rtrim($Item["column_name"], "[]")."&imgdir=right"."' class='text-primary font-weight-bold' style='font-weight: bold; width: 24px; height: 24px; text-align: center; vertical-align: middle; font-size: 16px; color: #333333; background: #FFFFFF; border-radius: 12px; position: absolute; right: 6px; bottom: 5px; text-decoration: none; box-shadow: 0 0 12px rgba(0, 0, 0, .3); line-height: 18px; border: 1px solid #CCCCCC;'>&gt;</a>
										<img src='".$FrontEndLocationLocal."/images/".$ColumnOptions["filelocation"][0]."/".$Temp."' style='width: 100%;' />
									</div>
								";
							}

							$PostHTML .= '</div>';
						}
					}

					// Timestamp type
					if(isset($ColumnOptions["type"]) && $ColumnOptions["type"][0] == "timestamp")
					{
						$ColumnOptions["type"][0] = "text";
						$ColumnOptions["class"][0] = "datetimepicker";
						
						if(isset($Config["Data"][$Item["column_name"]]))
							$Config["Data"][$Item["column_name"]] = date("Y/m/d H:i", $Config["Data"][$Item["column_name"]]);
						//else
						//	$Config["Data"][$Item["column_name"]] = date("Y/m/d H:i", time());
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
							"id"   => "ref_".$Item["column_name"],
							"name" => $Item["column_name"],
							"value" => (isset($Config["Data"][$Item["column_name"]])) ? $Config["Data"][$Item["column_name"]] : $Item["column_default"],
							"class" => (isset($ColumnOptions["class"][0])) ? $ColumnOptions["class"][0] : "",
							($Disabled) ? "disabled" : "null" => ($Disabled) ? "disabled" : "null",
							($Multiple) ? "multiple" : "null" => ($Multiple) ? "multiple" : "null",
						), array(
							"title" => "<label for='ref_".$Item["column_name"]."'>".$Title."</label>",
							"data" => $Data,
							"prehtml" => (isset($PreHTML)) ? $PreHTML : "",
							"posthtml" => (isset($PostHTML)) ? $PostHTML : ""
					));
				}
				
				$SubmitElement = array("type" => "submit", "value" => $Config["SubmitText"]);
				if($Config["SubmitValue"]) $SubmitElement["name"] = "_submit";
				$FormGen->AddElement($SubmitElement);
				return $FormGen->Build(array("data" => $Config["Data"], "enctype" => "multipart/form-data", "ColNum" => $Config["ColNum"]));
			}
		}
	}