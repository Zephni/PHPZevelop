<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home",
		"PassParams" => true
	));
?>

<div id="pageContent">
	<h1>Administration Panel</h1>	
	<p>
		Logged in as: <span style="color: green; font-weight: bold;"><?php echo $_SESSION["username"]; ?></span><br />
		<br />
		Please contact <a href="mailto:craig.dennis@burdamagazines.co.uk">craig.dennis@burdamagazines.co.uk</a> if you have any issues
	</p>
</div>