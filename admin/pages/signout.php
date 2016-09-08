<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Sign out"
	));

	$User->Logout();

	header("Location: ".$PHPZevelop->CFG->SiteDirLocal);
	die();
?>