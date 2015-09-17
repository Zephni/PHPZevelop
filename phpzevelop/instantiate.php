<?php
	/* PHPZevelop
	------------------------------*/
	$PHPZevelop = new PHPZevelop();

		/* CFG
		------------------------------*/
		$PHPZevelop->NewObject("CFG", $CFG);

		/* Path
		------------------------------*/
		$PHPZevelop->NewObject("Path", new Path($PHPZevelop->CFG));

		/* Page
		------------------------------*/
		$PHPZevelop->NewObject("Page", new Page());