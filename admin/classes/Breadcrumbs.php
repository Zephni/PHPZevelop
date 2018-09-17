<?php
	class Breadcrumbs
	{
		public static $Items = array();

		public static function GBuild($Array = null, $Delimiter = " &gt; ")
		{
			global $PHPZevelop;
			$FinalGrouping = array();

			if($Array != null && count($Array) > 0)
				$FinalItems = array_merge(self::$Items, $Array);
			else
				$FinalItems = array_merge(self::$Items);

			$I = 0;
			foreach($FinalItems as $K => $V)
			{
				if($I < count($FinalItems)-1)
					$FinalGrouping[] = "<a href='".$PHPZevelop->Path->GetPage($K, true)."'>".strtolower($V)."</a>";
				else
					$FinalGrouping[] = strtolower($V);

				$I++;
			}

			return implode($Delimiter, $FinalGrouping);
		}

		public static function Build($Array = array(), $Delimiter = " &gt; ")
		{
			echo self::GBuild($Array, $Delimiter);
		}
	}

	/*
	Breadcrumbs::Build(array(
		"" => "home",
		"/articles" => "articles",
		"/page-im-on" => "page im on",
	));
	*/

	/*
	Breadcrumbs::$Items[""] = "home";
	Breadcrumbs::$Items["/categories"] = "categories";
	Breadcrumbs::$Items["/articles"] = "articles";
	Breadcrumbs::Build(array(
		"/article" => "article"
	));
	*/