<?php
	// User authentication
	if((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) && $PHPZevelop->CFG->PagePath != "/login")
		header("Location: ".$PHPZevelop->Path->GetPage("login", true));

	// Replenish $_SESSION variables
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
		$_SESSION["loggedin"] = true;
		$_SESSION["username"] = $_SESSION["username"];
		$_SESSION["tries"] = 0;
	}

	/* Plugins
	------------------------------*/
	require_once($PHPZevelop->CFG->SiteDirRoot."/plugins/phpmailer/PHPMailerAutoload.php");

	/* Include all classes and functions
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes", "functions"));
	extract($SubLoader->DefinedVariables);

	/* Instantiate
	------------------------------*/
	require_once($PHPZevelop->CFG->SiteDirRoot."/instantiate.php");