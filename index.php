<?php
	/* Begin session
	------------------------------*/
	session_start();

	/* Define dependant directories
	------------------------------*/
	define("ROOT_DIR", __DIR__);
	define("MAIN_DIR", ROOT_DIR."/phpzevelop");

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