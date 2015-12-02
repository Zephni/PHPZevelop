<?php
	/* Begin session
	------------------------------*/
	session_start();

	/* PHP settings
	------------------------------*/
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* Define dependant directories
	------------------------------*/
	define("ROOT_DIR", __DIR__);
	define("MAIN_DIR", ROOT_DIR."/phpzevelop");
	define("FILE_EXT", ".php");

	/* Include base classes
	------------------------------*/
	require_once(MAIN_DIR."/classes/class.subloader".FILE_EXT);
	$SubLoader = new SubLoader(MAIN_DIR."/classes");
	$SubLoader->RunIncludes();
	
	/* PHPZevelop setup
	------------------------------*/
	$PHPZevelop = new PHPZevelop();
	require_once(ROOT_DIR."/config".FILE_EXT);

	/* Get page path and unset $_GET["page"]
	------------------------------*/
	$PHPZevelop->CFG->PagePath = rtrim((isset($_GET["page"])) ? (string)$_GET["page"] : "", "/");
	$PHPZevelop->CFG->LocalDir = rtrim(array_shift(explode("?", str_replace($PHPZevelop->CFG->PagePath, "", $_SERVER["REQUEST_URI"]))), "/");
	unset($_GET["page"]);

	/* Initiate PHPZevelop
	------------------------------*/
	require_once(MAIN_DIR."/initiate".FILE_EXT);

	/* Generate page
	------------------------------*/
	$PHPZevelop->Page->PageFile = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath.FILE_EXT);

	if(isset($PHPZevelop->CFG->Page404) && strlen($PHPZevelop->CFG->Page404) > 0)
		$PHPZevelop->Page->Page404	= $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT);

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header".FILE_EXT),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer".FILE_EXT)
	);

	$PHPZevelop->Page->DefinedVars = get_defined_vars();
	$PHPZevelop->Page->LoadPage();