<?php
	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes/base", "../common/", "../common/classes", "../common/functions", "classes", "functions"));
	extract($SubLoader->DefinedVariables);

	// Early instantiate
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT);

	// Find Tables
	$TablesReal = array();
	$Tables = array();
	$TableInformation = array();
	foreach($DB->Query("SELECT * FROM information_schema.tables t WHERE t.table_schema='".$DatabaseName."'") as $Item)
	{
		if(isset($Item["TABLE_NAME"]))
		{
			$TablesReal[] = $Item["TABLE_NAME"];
			$Tables[$Item["TABLE_NAME"]] = str_replace("_", " ", ucfirst($Item["TABLE_NAME"]));

			$TableInformation[$Item["TABLE_NAME"]] = array();
			foreach($Item as $K => $V)
				$TableInformation[$Item["TABLE_NAME"]][strtolower($K)] = $V;
		}
	}
		
	$TableOptions = array();
	foreach($TablesReal as $Table)
	{
		$TableOptions[$Table] = array();

		foreach(explode("\r\n", $TableInformation[$Table]["table_comment"]) as $Item)
		{
			if(strstr($Item, "::") !== false)
			{
				$Split = explode("::", $Item);
				$TableOptions[$Table][$Split[0]] = $Split[1];
			}
		}
	}

	//die("<pre>".print_r($TableOptions, true)."</pre>");