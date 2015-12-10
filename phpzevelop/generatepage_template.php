<?php
	/* Generate page by template
	------------------------------*/

	// If PassParams is not set and param was passed
	if(!$PHPZevelop->CFG->PassParams && isset($_GET[$PHPZevelop->CFG->PreParam."0"]))
	{
		// Plus the tested page file does not exist
		if(!is_file($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->TestedPagePath.FILE_EXT)))
		{
			// Pull the 404 page instead if it exists, otherwise error
			if(strlen($PHPZevelop->CFG->Page404) > 0 && is_file($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT)))
			{
				ob_start();
				include($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT));
				$PHPZevelop->PageContent = ob_get_contents();
				ob_clean();
			}
			else
			{
				// Set what happens here if page not found
				header("Location: ".$PHPZevelop->Path->GetPage("", true));
			}
		}
	}

	// Include template file
	include($PHPZevelop->CFG->RootDirs->Templates."/".$PHPZevelop->CFG->Template.FILE_EXT);