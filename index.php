<?php
	session_start();

	/* Get page path and unset $_GET["page"]
	------------------------------*/
	$PAGE_PATH = (isset($_GET["page"])) ? (string)$_GET["page"] : "";
	unset($_GET["page"]);

	/* Define dependant directories
	------------------------------*/
	define("ROOT_DIR", __DIR__);
	define("LOCAL_DIR", rtrim(array_shift(explode("?", str_replace($PAGE_PATH, "", $_SERVER["REQUEST_URI"]))), "/"));
	define("MAIN_DIR", ROOT_DIR."/phpzevelop");

	/* Include global file
	------------------------------*/
	require_once(MAIN_DIR."/global.php");

	/* Generate page
	------------------------------*/
	$PHPZevelop->Page->PageFile =			$PHPZevelop->Path->GetPageRoot($PAGE_PATH.".php");
	$PHPZevelop->Page->Page404 =			$PHPZevelop->Path->GetPageRoot("error/404.php");

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer.php")
	);

	$PHPZevelop->Page->DefinedVars = get_defined_vars();
	$PHPZevelop->Page->LoadPage();