<?php
	class URLHelper
	{
		public static function Array2URL($Array, $Splitter = "&", $Exclude = array())
		{
			$URLParts = array();

			foreach($Array as $K => $V)
			{
				if($Exclude != null && !in_array($K, $Exclude))
					$URLParts[] = $K."=".$V;
			}

			return implode($Splitter, $URLParts);
		}

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
				die("URLHelper::Clean only accepts string or array type");
			}
		}

		public static function CleanURL($String, $Delimiter = "/")
		{
			return implode($Delimiter, URLHelper::Clean(explode($Delimiter, $String)));
		}
	}