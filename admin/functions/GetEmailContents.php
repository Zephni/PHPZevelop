<?php
	function GetEmailContent($path, $vars = null){
		global $PHPZevelop;
		$included = true;
		ob_start();
		require_once($PHPZevelop->Path->GetPageRoot("emails/".$path));
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}