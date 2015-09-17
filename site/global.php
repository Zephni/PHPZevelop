<?php
	/* Config
	------------------------------*/
	$CFG->DB = (object) array(
		"Host" => "",
		"User" => "",
		"Pass" => "",
		"Name" => ""
	);

	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDir);
	$SubLoader->RunIncludes(array("classes"));
	extract($SubLoader->DefinedVariables);

	/* Instantiate
	------------------------------*/
	require_once($PHPZevelop->CFG->SiteDir."/instantiate.php");