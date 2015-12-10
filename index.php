<?php
	/*------------------------------
	|       PHPZevelop V1.5        |
	------------------------------*/

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

	/* Initiate PHPZevelop
	------------------------------*/
	require_once(MAIN_DIR."/initiate".FILE_EXT);

	/* Generate page
	------------------------------*/
	ob_start();
	include($PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath.FILE_EXT));
	$PHPZevelop->PageContent = ob_get_contents();
	ob_clean();

	if(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "Template" || !isset($PHPZevelop->CFG->LoadStyle))
		require_once(MAIN_DIR."/generatepage_template".FILE_EXT);
	elseif(isset($PHPZevelop->CFG->LoadStyle) && $PHPZevelop->CFG->LoadStyle == "FileOrder")
		require_once(MAIN_DIR."/generatepage_fileorder".FILE_EXT);