<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Home"
	));
?>

<h2>Welcome TO PHPZAdmin!</h2>

<p>Logged in as: <?php echo $User->Data["username"]; ?> (<?php $Link->Out("signout", "sign out"); ?>)</p>

<p>For information on implementing PHPZAdmin: <?php $Link->Out("howto.txt"); ?></p>

<?php
	if(@touch($PHPZevelop->CFG->SiteDirRoot."/log.txt"))
	{
		$Log = file_get_contents($PHPZevelop->CFG->SiteDirRoot."/log.txt");
		$Log = explode("\r\n", $Log);
		$Log = array_slice($Log, 0, 50);

		echo "<h3 style='margin: 20px 0px 12px 0px;'>PHPZAdmin Log</h3>";

		echo "<div style='background: #EEEEEE; padding: 7px 10px 10px 10px; height: 240px; overflow: auto;'>";

		if(count($Log) > 0)			
			echo "<p style='margin: 0px; padding: 0px; font-size: 12px; line-height: 26px;'>".implode("<br />", $Log)."</p>";
		else
			echo "<p style='margin: 0px; padding: 0px; font-size: 12px; line-height: 26px;'>Nothing in log</p>";

		echo "</div>";
	}
	