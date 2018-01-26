<?php
	class URLHelper
	{
		// Clean a string for insersion into a URL
		public static function Clean($Param1, $Delimiter = "-")
		{
			if(gettype($Param1) == "string")
			{
				return preg_replace("/^-+|-+$/", "", strtolower(preg_replace("/[^a-zA-Z0-9]+/", $Delimiter, $Param1)));
			}
			else if(gettype($Param1) == "array")
			{
				foreach($Param1 as $K => $V)
					$Param1[$K] = URLHelper::Clean($V);

				return $Param1;
			}
			else
			{
				
				//die("URLHelper::Clean only accepts string or array type");
			}
		}

		// Cleans an entire URL (Not including the domain) and allows a delimiter
		public static function CleanURL($String, $Delimiter = "/")
		{
			return implode($Delimiter, URLHelper::Clean(explode($Delimiter, $String)));
		}
		
		// Converts an array to a query string, delimits by $Splitter. Parameters can be excluded with the $Exclude array
		public static function Array2QueryStr($Array, $Splitter = "&", $Exclude = array())
		{
			$URLParts = array();

			foreach($Array as $K => $V)
			{
				if($Exclude != null && !in_array($K, $Exclude))
					$URLParts[] = $K."=".$V;
			}

			return implode($Splitter, $URLParts);
		}
		
		// Converts query string into array
		public static function QueryStr2Array($QueryStr, $Splitter = "&")
		{
			$Array = array();

			foreach(explode($Splitter, $QueryStr) as $Item)
			{
				$KV = implode("=", $Item);
				$Array[$KV[0]] = $KV[1];
			}

			return $Array;
		}
	}