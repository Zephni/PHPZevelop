<?php
	if(!class_exists("CookieHelper"))
	{
		class CookieHelper
		{
			public static $DefaultTime = 3600;

			public static function Set($Name, $Value, $Time = null)
			{
				if($Time == null)
					$Time = self::$DefaultTime;

				setcookie($Name, $Value, time() + $Time, "/");
			}

			public static function Get($Name)
			{
				if(isset($_COOKIE[$Name]))
					return $_COOKIE[$Name];
				else
					return null;
			}

			public static function GetAll()
			{
				return $_COOKIE;
			}

			public static function Remove($Name)
			{
				setcookie($Name, null, time() - 1, "/");
				$_COOKIE[$Name] = null;
			}

			public static function RefreshAll($Time = null)
			{
				if($Time == null)
					$Time = self::$DefaultTime;

				foreach(self::GetAll() as $Key => $Val)
					self::Refresh($Key);
			}

			public static function Refresh($Name, $Time = null)
			{
				if($Time == null)
					$Time = self::$DefaultTime;

				self::Set($Name, self::Get($Name), $Time, "/");
			}
		}	
	}