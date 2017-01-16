<?php
 	function AppendLog($Text)
 	{
 		global $PHPZevelop;
 		global $User;
 		$File = $PHPZevelop->CFG->SiteDirRoot."/log.txt";

 		$UName = ($User->LoggedIn()) ? $UName = $User->Data["username"]." at " : "";

 		if(@touch($File))
 		{
			$Prepend =  $Text." (".$UName.date("Y/m/d H:ia").")\r\n";
			$Temp = file_get_contents($File);
			$Content = $Prepend.$Temp;
			file_put_contents($File, $Content);
 		}
 	}