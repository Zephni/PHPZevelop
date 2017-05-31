<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Logout",
		"PassParams" => false
	));

	$Administrator->Logout();
	CookieHelper::Remove(Administrator::$UsernameSessionField);
	CookieHelper::Remove(Administrator::$PasswordSessionField);
	$PHPZevelop->Location("login");
?>