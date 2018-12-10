<?php
	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes", "functions"));
	extract($SubLoader->DefinedVariables);

	// Early instantiate
	if(file_exists($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT))
		require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate".FILE_EXT);
	
	// Default Breadcrumbs
	Breadcrumbs::$Items[""] = "home";

	// Logout if restricted
	if($PHPZevelop->CFG->PagePath != "/login" && isset($Administrator) && !$Administrator->LoggedIn())
		$PHPZevelop->Location("login");