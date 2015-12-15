<?php
	/* Generate page by file order
	------------------------------*/

	// If file order not set just include page
	if(!isset($PHPZevelop->CFG->FileOrder) || count($PHPZevelop->CFG->FileOrder) == 0)
		$PHPZevelop->CFG->FileOrder = array("[page]");

	// Run file order
	foreach($PHPZevelop->CFG->FileOrder as $k => $v)
	{
		if($v == "[page]")
		{
			echo $PHPZevelop->PageContent;
		}
		else
		{
			require($PHPZevelop->CFG->FileOrder[$k] = $PHPZevelop->CFG->SiteDirRoot."/".$v.FILE_EXT);
		}
	}