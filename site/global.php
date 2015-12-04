<?php
	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes"));
	extract($SubLoader->DefinedVariables);

	/* Instantiate
	------------------------------*/
	require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT);