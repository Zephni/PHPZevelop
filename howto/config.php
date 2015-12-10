<?php
	/* Config
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"SiteTitle"			=> "PHPZevelop",
		"PageTitle"			=> "",
		"MetaTitle"			=> "PHPZevelop PHP FrameWork",
		"MetaDescription"	=> "PHP framework for ease of use and adaptability",
		"MetaKeywords"		=> "PHP, Framework, Zephni"
	));

	$PHPZevelop->CFG->DB = (object) array(
		"Host" => "",
		"User" => "",
		"Pass" => "",
		"Name" => ""
	);