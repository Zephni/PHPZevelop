<?php
	/* Database
	------------------------------*/	
	if(strlen($CFG->DB->Host) > 0){
		$DB = new db($PHPZevelop->CFG->DB->Host, $PHPZevelop->CFG->DB->User, $PHPZevelop->CFG->DB->Pass, $PHPZevelop->CFG->DB->Name);
		
		if(!$DB->Connected)
			die($DB->ErrorHandler());
	}