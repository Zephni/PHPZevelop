<?php
	/* Begin session
	------------------------------*/
	session_start();

	/* Define dependant directories
	------------------------------*/
	define("ROOT_DIR", __DIR__);
	define("MAIN_DIR", ROOT_DIR."/phpzevelop");

	/* Include base classes
	------------------------------*/
	require_once(MAIN_DIR."/classes/class.subloader.php");
	$SubLoader = new SubLoader(MAIN_DIR."/classes");
	$SubLoader->RunIncludes();
	
	/* PHPZevelop setup
	------------------------------*/
	$PHPZevelop = new PHPZevelop();
	require_once(ROOT_DIR."/config.php");

	/* Get page path and unset $_GET["page"]
	------------------------------*/
	$PHPZevelop->CFG->PagePath = rtrim((isset($_GET["page"])) ? (string)$_GET["page"] : "", "/");
	$PHPZevelop->CFG->LocalDir = rtrim(array_shift(explode("?", str_replace($PHPZevelop->CFG->PagePath, "", $_SERVER["REQUEST_URI"]))), "/");
	unset($_GET["page"]);

	/* Initiate PHPZevelop
	------------------------------*/
	require_once(MAIN_DIR."/initiate.php");

	/* Generate page
	------------------------------*/
	$PHPZevelop->Page->PageFile = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath.".php");
	$PHPZevelop->Page->Page404	= $PHPZevelop->Path->GetPageRoot("error/404.php");

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer.php")
	);

	$PHPZevelop->Page->DefinedVars = get_defined_vars();
	$PHPZevelop->Page->LoadPage();