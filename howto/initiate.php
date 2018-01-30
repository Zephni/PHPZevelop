<?php
	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes"));
	extract($SubLoader->DefinedVariables);

	/* Database
	------------------------------*/
	if(class_exists("DB"))
	{
		if(strlen($PHPZevelop->CFG->DB->Host) > 0)
		{
			$DB = new DB($PHPZevelop->CFG->DB->Host, $PHPZevelop->CFG->DB->User, $PHPZevelop->CFG->DB->Pass, $PHPZevelop->CFG->DB->Name);
			
			if(!$DB->Connected)
				die($DB->ErrorHandler());
		}
	}

	/* Link
	------------------------------*/
	if(class_exists("Link"))
	{
		$Link = new Link($PHPZevelop->Path);
	}