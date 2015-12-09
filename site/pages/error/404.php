<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "404"
	));
?>

<div style="padding: 10px;">
	<div style="width: 100%; max-width: 1080px; margin: 50px auto;">
		<h2>404 - This page does not exist</h2>
		<hr />
		<p>Check your PHPZevelop configuration and that the page exists in (<span style='color: green;'><?php echo $PHPZevelop->CFG->TestedPagePath.FILE_EXT; ?></span>)</p>
	</div>
</div>