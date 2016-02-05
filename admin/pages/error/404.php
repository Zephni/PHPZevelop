<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "404"
	));
?>

<div id="pageContent">
	<h1>Error 404</h1>
	<p>The page you are looking for does not exist, please <a href="<?php $PHPZevelop->Path->GetPage(""); ?>">clik here</a> to return to the home page</p>
</div>