<?php
	/* Generate page by template
	------------------------------*/

	// Try to include template file
	if(file_exists($PHPZevelop->CFG->RootDirs->Templates."/".$PHPZevelop->CFG->Template.FILE_EXT))
	{
		require($PHPZevelop->CFG->RootDirs->Templates."/".$PHPZevelop->CFG->Template.FILE_EXT);
	}
	// Else just echo page content
	else
	{
		echo $PHPZevelop->PageContent;
	}