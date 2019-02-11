<?php
	function UploadImage($ColumnKey, $ColumnCommands, $File, $Offset = 0)
	{
		global $PHPZevelop;
		global $Table;
		global $FrontEndImageLocationRoot;
		global $FrontEndImageLocationLocal;
		global $Data;

		if($File["name"] != "")
		{
			$OrigionalValue = (isset($Data) && isset($Data[$ColumnKey])) ? $Data[$ColumnKey] : "";
			ValidateValues::$ValidPairs[$ColumnKey] = array();

			$Files = $_FILES[$ColumnKey];
			
			$Count = (is_array($File["name"])) ? count($File["name"]) : 1;
			//die("<pre>".print_r($File, true)."</pre>");

			for($I = 0; $I < $Count; $I++)
			{
				$AppendName = "";

				if(is_array($Files["name"]))
				{
					$File = array(
						"name"		=> $Files["name"][$I],
						"type"		=> $Files["type"][$I],
						"tmp_name"  => $Files["tmp_name"][$I],
						"error"		=> $Files["error"][$I],
						"size"		=> $Files["size"][$I]
					);

					$AppendName = "_".($I + $Offset + 1);
				}
				else
				{
					$File = $Files;
				}

				$Image = new upload($File);

				if($Image->uploaded)
				{
					$Image->file_overwrite = true;
					$Image->file_src_name_body = $Table["real_name"]."_".((isset($_GET["param_1"])) ? $_GET["param_1"] : $Table["information"]["auto_increment"]);
					$Image->file_src_name_ext = "jpg";
					$Image->image_convert = 'jpg';
					$Image->jpeg_quality = 100;
					
					foreach(array_keys($ColumnCommands) as $Item)
					{
						if(substr($Item, 0, 3) == "io_")
						{
							$ImgOptionKey = substr($Item, 3);
							$Val = $ColumnCommands["io_".$ImgOptionKey][0];
							$Val = str_replace("[id]", (isset($_GET["param_1"])) ? $_GET["param_1"] : $Table["information"]["auto_increment"], $Val);
							$Val = str_replace("[timestamp]", time(), $Val);
							$Image->$ImgOptionKey = $Val;
						}
					}

					$Image->file_src_name_body .= $AppendName;
					
					$Image->process($FrontEndImageLocationRoot."/".(string)$ColumnCommands["filelocation"][0], true);
					
					if($Image->processed)
						ValidateValues::$ValidPairs[$ColumnKey][] = $Image->file_dst_name_body.".".$Image->file_dst_name_ext;
					else
						die($Image->error."<br /><br />".$FrontEndImageLocationRoot."/".(string)$ColumnCommands["filelocation"][0]);
				}
				else
				{
					die($Image->error."<br /><br />".$FrontEndImageLocationRoot."/".(string)$ColumnCommands["filelocation"][0]);
				}
			}

			ValidateValues::$ValidPairs[$ColumnKey] = $OrigionalValue.((strlen($OrigionalValue) > 0 && count(ValidateValues::$ValidPairs[$ColumnKey]) > 0) ? "," : "").implode(",", ValidateValues::$ValidPairs[$ColumnKey]);
		}
	}