<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"PassParams" => false,
		"Template"   => "main"
	));
?>
<h1 class="display-4"><?php echo $PHPZevelop->CFG->SiteTitle; ?></h1>
<hr />