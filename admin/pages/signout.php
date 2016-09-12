<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Sign out"
	));

	$User->Logout();
	CookieHelper::Remove(User::$UsernameSessionField);
	CookieHelper::Remove(User::$PasswordSessionField);

	header("Location: ".$PHPZevelop->CFG->SiteDirLocal);
	die();
?>