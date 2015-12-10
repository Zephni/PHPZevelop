<?php
	/* Generate page by file order
	------------------------------*/
	function ConvertToUnixPath(&$path){
		if (strpos($path, "\\") !== FALSE){
			$path = str_replace("\\", "/", $path);
		}
	}

	// Get page files
	$PageFile = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath.FILE_EXT);
	$Page404 = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT);

	// Convert to Unix strings
	ConvertToUnixPath($PageFile);
	ConvertToUnixPath($Page404);

	foreach($PHPZevelop->CFG->FileOrder as $k => $v)
		ConvertToUnixPath($PHPZevelop->CFG->FileOrder[$k]);

	// Run file order
	if(!isset($PHPZevelop->CFG->FileOrder))
	{
		$PHPZevelop->CFG->FileOrder = array($PageFile);
	}
	else
	{
		foreach($PHPZevelop->CFG->FileOrder as $k => $v)
		{
			if($v == "[page]")
			{
				if(is_file(ROOT_DIR.$PHPZevelop->CFG->TestedPagePath.FILE_EXT))
					echo $PHPZevelop->PageContent;
				else
					include($Page404);
			}
			else
			{
				include($PHPZevelop->CFG->FileOrder[$k] = $PHPZevelop->CFG->SiteDirRoot."/".$v);
			}
		}
	}