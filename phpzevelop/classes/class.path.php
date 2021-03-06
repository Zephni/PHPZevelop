<?php
	/*
		Author: Craig Dennis

		// Properties:

			object $CFG 		# $CFG is the already defined $PHPZevelop->CFG object (This is required in the Path constructor)

		// Below is an example of usage:

			To get the local URL for a page in pages/ you could use:
				$PHPZevelop->Path->GetPage("home");
			
			If the page was multiple levels deep you can use:
				$PHPZevelop->Path->GetPage("articles/article-name");
			
			Note the second parameter of each method has a $return boolean that determines whether the method will "return" the value or "echo" the value.
			If you would like the value to be stored or used within a PHP script then you would pass "true" as the second parameter if "true" isn't the default (shown above):
				$PHPZevelop->Path->GetPage("articles/article-name", true);
			
			Where-as if you need the path string to be echo'ed directly to the page you would pass false as the second parameter, or leave blank in cases where $return is false by default:
				<a href="<?php $PHPZevelop->Path->GetPage("article"); ?>">Link text</a>
	*/
	
	class Path
	{
		private $CFG;

		public function __construct($CFG)
		{
			$this->CFG = $CFG;
		}

		public function GetClass($string, $return = true)
		{
			if(!$return)
				echo $this->StripBad($path = $this->CFG->RootDirs->Classes."/".$string);
			else
				return $this->StripBad($path = $this->CFG->RootDirs->Classes."/".$string);
		}	
	
		public function GetInc($string, $return = true)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->RootDirs->Inc."/".$string);
			else
				return $this->StripBad($this->CFG->RootDirs->Inc."/".$string);
		}

		public function GetImage($string, $return = false)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->LocalDirs->Images."/".$string);
			else
				return $this->StripBad($this->CFG->LocalDirs->Images."/".$string);
		}

		public function GetImageRoot($string, $return = true)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->RootDirs->Images."/".$string);
			else
				return $this->StripBad($this->CFG->RootDirs->Images."/".$string);
		}

		public function GetPage($string, $return = false)
		{
			$string = ($this->CFG->IsMultiSite) ? LOCAL_DIR."/".$this->CFG->Site."/".$string : "/".LOCAL_DIR."/".$string;
			if(substr($string, 0, 2) == "//") $string = ltrim($string, "//");
			if(substr($string, 0, 1) != "/") $string = "/".$string;

			if(!$return)
				echo $this->StripBad($string);
			else
				return $this->StripBad($string);
		}

		public function GetPageRoot($string, $return = true)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->RootDirs->Pages."/".$string);
			else
				return $this->StripBad($this->CFG->RootDirs->Pages."/".$string);
		}

		public function GetScript($string, $return = false)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->LocalDirs->Scripts."/".$string);
			else
				return $this->StripBad($this->CFG->LocalDirs->Scripts."/".$string);
		}

		public function GetCSS($string, $return = false)
		{
			if(!$return)
				echo $this->StripBad($this->CFG->LocalDirs->CSS."/".$string);
			else
				return $this->StripBad($this->CFG->LocalDirs->CSS."/".$string);
		}

		private function StripBad($string)
		{
			return str_replace("//", "/", $string);
		}
	}
?>