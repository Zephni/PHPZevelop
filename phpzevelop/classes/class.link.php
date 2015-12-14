<?php
	/*
		Author: Craig Dennis
		Ver:	1.4

		// Properties:

			private object $Path - The $PHPZevelop->Path object
		
		// Methods

			public function __construct($Path)
				// Parameters
				$Path -> Requires $PHPZevelop->Path object

			public funtion Get($URL, $Text = null, $Options = array())
				// Parameters
				$URL		-> Either a local or public URL, should not begin with "/"
				$Text 		-> Either (string) LinkText or (array) $Options. Options for this parameter will set the link text to $URL
				$Options 	-> Associative array that represent the attributes list for the link

				// Description
				Returns HTML link based on URL, Link text and attributes list.
			
			public funtion Set($URL, $Text = null, $Options = array())
				// Parameters
				$URL		-> Either a local or public URL, should not begin with "/"
				$Text 		-> Either (string) LinkText or (array) $Options. Options for this parameter will set the link text to $URL
				$Options 	-> Associative array that represent the attributes list for the link
				
				// Description
				Echos HTML link based on URL, Link text and attributes list.
	*/
	class Link
	{
		private $Path;

		public function __construct($_Path)
		{
			if(get_class($_Path) == "Path")
				$this->Path = $_Path;
			else
				die("Link construct expecting \$PHPZevelop->Path object");
		}

		public function Get($URL, $Text = null, $Options = array())
		{
			$OriginalURL = $URL;

			// If not external, or begins with '/' don't automatially prepend site path
			if(substr($URL, 0, 7) != "http://" && substr($URL, 0, 8) != "https://" && substr($URL, 0, 1) != "/")
				$URL = $this->Path->GetPage($URL, true);

			if($Text === null)
				$Text = $OriginalURL;

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