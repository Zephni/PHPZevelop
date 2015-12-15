<?php
	/* Generate page by template
	------------------------------*/

	// Try to include template file
	try
	{
		require($PHPZevelop->CFG->RootDirs->Templates."/".$PHPZevelop->CFG->Template.FILE_EXT);
	}
	// Else just echo page content
	catch(Exception $e)
	{
		echo $PHPZevelop->PageContent;
	}