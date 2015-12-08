<?php
	class Link
	{
		private $Path;

		public function __construct($_Path)
		{
			$this->Path = $_Path;
		}

		public function Get($URL, $Text = null, $Options = array())
		{
			// If not external, get interal page
			if(substr($URL, 0, 7) != "http://" && substr($URL, 0, 8) != "https://")
				$URL = $this->Path->GetPage($URL, true);

			if($Text === null)
				$Text = $URL;

			if(is_array($Text))
			{
				$Options = $Text;
				$Text = $URL;
			}

			// Options HTML
			$OptionsHTML = "";
			foreach($Options as $k => $v)
				$OptionsHTML .= $k."='".$v."'";

			if(strlen($OptionsHTML) > 0)
				$OptionsHTML = " ".$OptionsHTML;

			// Echo HTML code
			return "<a href='".$URL."'".$OptionsHTML.">".htmlentities($Text)."</a>";
		}

		public function Out($URL, $Text = null, $Options = array())
		{
			echo $this->Get($URL, $Text, $Options);
		}
	}