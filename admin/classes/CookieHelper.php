<?php
	class CookieHelper
	{
		public static $DefaultTime = 115200;

		public static function Set($Name, $Value, $Time = null)
		{
			if($Time == null)
				$Time = self::$DefaultTime;

			setcookie($Name, $Value, time() + $Time, "/");
		}

		public static function Get($Name)
		{
			return (isset($_COOKIE[$Name])) ? $_COOKIE[$Name] : null;
		}

		public static function GetAll()
		{
			return $_COOKIE;
		}

		public static function Remove($Name)
		{
			setcookie($Name, null, time() - 1, "/");
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