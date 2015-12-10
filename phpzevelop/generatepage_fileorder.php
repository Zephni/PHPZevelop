<?php
	/* Generate page by file order
	------------------------------*/
	function ConvertToUnixPath(&$path){
		if (strpos($path, "\\") !== FALSE){
			$path = str_replace("\\", "/", $path);
		}
	}

	// If PassParams is not set and param was passed
	if(!$PHPZevelop->CFG->PassParams && isset($_GET[$PHPZevelop->CFG->PreParam."0"]))
	{
		// Plus the tested page file does not exist
		if(!is_file($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->TestedPagePath.FILE_EXT)))
		{
			// Set page file to 404
			$PageFile = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT);
		}
	}
	else
	{
		// Set page to existing page
		$PageFile = $PHPZevelop->Path->GetPageRoot($FilePath.FILE_EXT);
	}

	// Check file order has been set
	if(!isset($PHPZevelop->CFG->FileOrder) || count($PHPZevelop->CFG->FileOrder) == 0)
		$PHPZevelop->CFG->FileOrder = array("[page]");

	// Convert to Unix strings
	ConvertToUnixPath($PageFile);

	foreach($PHPZevelop->CFG->FileOrder as $k => $v)
		if($PHPZevelop->CFG->FileOrder[$k] != "[page]")
			ConvertToUnixPath($PHPZevelop->CFG->FileOrder[$k]);

	// Run file order
	foreach($PHPZevelop->CFG->FileOrder as $k => $v)
	{
		if($v == "[page]")
		{
			if(is_file($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath)))
				echo $PHPZevelop->PageContent;
			else
				include($PageFile);
		}
		else
		{
			include($PHPZevelop->CFG->FileOrder[$k] = $PHPZevelop->CFG->SiteDirRoot."/".$v.FILE_EXT);
		}
	}