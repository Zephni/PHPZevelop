<?php
	class CurlHelper
	{
		public static $Error = "";

		public static function Post($URL, $Fields = array(), $CurlOptionsExtra = array(), $DieExplainError = false)
		{
			$CurlOptions = array(
				CURLOPT_URL				=> $URL,
				CURLOPT_RETURNTRANSFER	=> true,
				CURLOPT_POST 			=> count($Fields),
				CURLOPT_POSTFIELDS		=> http_build_query($Fields)
			);

			$CurlOptions += $CurlOptionsExtra;

			//die("<pre>".print_r($CurlOptions, true)."</pre>");

			return self::Call($CurlOptions, $DieExplainError);
		}

		public static function Get($URL, $Fields = array(), $CurlOptionsExtra = array(), $DieExplainError = false)
		{
			$QueryString = (count($Fields) > 0) ? ((strpos($URL, "?") !== false) ? "&" : "?").http_build_query($Fields) : "";

			$CurlOptions = array(
				CURLOPT_URL				=> $URL.$QueryString,
				CURLOPT_RETURNTRANSFER	=> true
			);

			$CurlOptions += $CurlOptionsExtra;

			return self::Call($CurlOptions, $DieExplainError);
		}

		public static function Call($Array, $DieExplainError = false)
		{
			$ch = curl_init();

			curl_setopt_array($ch, $Array);

			$Result = curl_exec($ch);

			if(curl_error($ch))
			{
				self::$Error = curl_error($ch);

				if($DieExplainError && strlen(self::$Error) > 0)
					die(self::$Error);
			}

			curl_close($ch);

			// TEMP
			$Start = stripos($Result, "<body>");
     		$End = stripos($Result, "</body>");
     		$Result = str_replace("<body>", "", substr($Result, $Start, $End-$Start));
     		// TEMP

			return $Result;
		}
	}