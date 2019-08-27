<?php
	class ValidateValues
	{
		public static $ErrorMessages = array();
		public static $ValidPairs = array();
		public static $InvalidPairs = array();

		public static function Run($Data, $CheckMethods = array(), $_Options = array())
		{
			$Options = array_merge(array(
				"ErrorColor" => "red"
			), $_Options);

			foreach($Data as $Key => $Value)
			{
				$OutputString = "";
				$Valid = (isset($CheckMethods[$Key])) ? $CheckMethods[$Key]($Value, $OutputString) : true;
				if($Valid === null) $Valid = true;

				if(!$Valid) self::$ErrorMessages[] = "<span style='color: ".$Options["ErrorColor"].";'>".$OutputString."</span>";

				if($Valid) self::$ValidPairs[$Key] = $Data[$Key];
				else self::$InvalidPairs[$Key] = $Data[$Key];
			}
		}
	}