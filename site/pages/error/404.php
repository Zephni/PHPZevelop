<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "404"
	));
?>

<h2>404</h2>

<p>Check your PHPZevelop configuration and that the page exists in (<span style='color: green;'>pages/<?php echo $PHPZevelop->CFG->TestedPagePath.FILE_EXT; ?></span>)</p>