<?php
	function UploadImage($ColumnKey, $ColumnCommands, $File)
	{
		global $PHPZevelop;
		global $Table;
		global $FrontEndImageLocationRoot;
		global $FrontEndImageLocationLocal;

		if($File["name"] != "")
		{
			$Image = new upload($_FILES[$ColumnKey]);
			
			if($Image->uploaded)
			{
				$Image->file_overwrite = true;
				$Image->file_src_name_body = $Table["real_name"]."_".((isset($_GET["param_1"])) ? $_GET["param_1"] : $Table["information"]["auto_increment"]);
				$Image->file_src_name_ext  = "jpg";
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

				$Image->process($FrontEndImageLocationRoot."/".(string)$ColumnCommands["filelocation"][0], true);

				if($Image->processed)
					ValidateValues::$ValidPairs[$ColumnKey] = $Image->file_dst_name_body.".".$Image->file_dst_name_ext;
				else
					die($Image->error."<br /><br />".$FrontEndImageLocationRoot."/".(string)$ColumnCommands["filelocation"][0]);
			}
		}
	}