<?php
 	function AppendLog($Text)
 	{
 		global $PHPZevelop;
 		global $Administrator;
 		$File = $PHPZevelop->CFG->SiteDirRoot."/log.txt";

 		$UName = ($Administrator->LoggedIn()) ? $UName = $Administrator->Data["username"]." at " : "";

 		if(@touch($File))
 		{
			$Prepend =  $Text." (".$UName.date("Y/m/d H:ia").")\r\n";
			$Temp = file_get_contents($File);
			$Content = $Prepend.$Temp;
			file_put_contents($File, $Content);
 		}
 	}