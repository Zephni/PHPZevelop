<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Logout",
		"PassParams" => true
	));
?>

<?php
	$_SESSION["loggedin"] = false;
	session_destroy();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=">';
?>