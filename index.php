<?php
	/*------------------------------
	|      PHPZevelop V1.65        |
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

	/* Initiate PHPZevelop
	------------------------------*/
	require_once(MAIN_DIR."/initiate".FILE_EXT);