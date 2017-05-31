<?php
	class FilterTool
	{
		public static $FilterCache = array();

		public static function GetFilterCache($String)
		{
			$FilterGroups = explode(", ", $String);

			foreach($FilterGroups as $Item)
			{
				$KV = explode(" [", rtrim($Item, "]"));
				$FilterGroups[$KV[0]] = $KV[1];
			}

			self::$FilterCache = $FilterGroups;

			return self::$FilterCache;
		}

		public static function GetFilter($Key, $String = null)
		{
			if($String !== null)
				self::GetFilterCache($String);

			return (isset(self::$FilterCache[$Key])) ? self::$FilterCache[$Key] : "";
		}
	}