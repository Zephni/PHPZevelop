<?php
	function UploadFile($ColumnKey, $ColumnCommands, $File)
	{
		global $FrontEndImageLocationRoot;
		global $Table;

		$FileLocation = $FrontEndImageLocationRoot."/";
		if(ArrGet($ColumnCommands, "location", 0) != "")
			$FileLocation .= $ColumnCommands["location"][0];

		$Name = $File["name"];
		$Ext = pathinfo($Name, PATHINFO_EXTENSION);
		$Name = ArrGet($ColumnCommands, "name", 0);

		if(ArrGet($ColumnCommands, "name", 0) != "")
			$Name = str_replace("[id]", (isset($_GET["param_1"])) ? $_GET["param_1"] : $Table["information"]["auto_increment"], $Name).$Ext;
		else
			$Name = str_replace("[id]", (isset($_GET["param_1"])) ? $_GET["param_1"] : $Table["information"]["auto_increment"], $Table["real_name"]."_[id]").$Ext;

		move_uploaded_file($File["tmp_name"], $FileLocation."/".$Name);
		ValidateValues::$ValidPairs[$ColumnKey] = $Name;
	}