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
					$Item["Attributes"]["style"] = (isset($Item["Attributes"]["style"]) ? $Item["Attributes"]["style"]." " : "")."white-space: pre;";
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
					foreach($Item["Options"]["data"] as $K => $V)
					{
						if(isset($this->PrepopData[$Item["Attributes"]["name"]]))
						{
							$HTML .= "<option value='".$K."' ".(($this->PrepopData[$Item["Attributes"]["name"]] == $K) ? "selected='selected'" : "").">".$V."</option>";
							if($this->PrepopData[$Item["Attributes"]["name"]] == $K) $FoundSelectedItem = true;
						}
						else
						{
							$HTML .= "<option value='".$K."' ".(($Value == $K) ? "selected='selected'" : "").">".$V."</option>";
							if($Value == $K) $FoundSelectedItem = true;
						}
					}
						
					$HTML .= "</select>".$Item["Options"]["posthtml"];
				}
				else if($Item["Attributes"]["type"] == "checkbox")
				{
					foreach($Item["Options"]["data"] as $K => $V)
					{
						if(strlen($V) > 0 && isset($this->PrepopData[$Item["Attributes"]["name"]]) && strstr($this->PrepopData[$Item["Attributes"]["name"]], $V) !== false)
							$HTML .= "<div style='display: inline-block;'><input type='checkbox' name='".$Item["Attributes"]["name"]."[]' value='".$K."' style='width: 23px; height: 20px; margin-right: 5px;' checked='checked' /><span style='position: relative; top: -4px;'>".$V."</span></div>";
						else
							$HTML .= "<div style='display: inline-block;'><input type='checkbox' name='".$Item["Attributes"]["name"]."[]' value='".$K."' style='width: 23px; height: 20px; margin-right: 5px;' /><span style='position: relative; top: -4px;'>".$V."</span></div>";
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
		}
	}