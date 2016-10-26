<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Sign out"
	));

	$Administrator->Logout();
	CookieHelper::Remove(Administrator::$UsernameSessionField);
	CookieHelper::Remove(Administrator::$PasswordSessionField);

	header("Location: ".$PHPZevelop->CFG->SiteDirLocal);
	die();
?>