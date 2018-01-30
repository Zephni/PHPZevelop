<?php
	class ValidateValues
	{
		public static $ErrorMessages = array();
		public static $ValidPairs = array();
		public static $InvalidPairs = array();

		public static function IsValid()
		{
			return (count(ValidateValues::$InvalidPairs) == 0);
		}

		/*
			Use like:
			ValidateValues::Run($_POST, array(
				"username" => function($Value){
					if(strlen($Value) < 6)
						return "Name must be at least 6 characters";
				}
			));
		*/
		public static function Run($Data, $CheckMethods = array(), $_Options = array())
		{
			$Options = array_merge(array(
				"HTMLEntity" => "span",
				"Style" => "color: red;",
				"Class" => ""
			), $_Options);

			foreach($Data as $Key => $Value)
			{
				$Output = (isset($CheckMethods[$Key])) ? $CheckMethods[$Key]($Value) : true;
				
				if($Output === null)
					$Output = true;

				if($Output !== true)
				{
					self::$ErrorMessages[] = "<".$Options["HTMLEntity"]." class='".$Options["Class"]."' style='".$Options["Style"]."'>".$Output."</".$Options["HTMLEntity"].">";
					self::$InvalidPairs[$Key] = $Data[$Key];
				}
				else
				{
					self::$ValidPairs[$Key] = $Data[$Key];
				}
			}
		}
	}