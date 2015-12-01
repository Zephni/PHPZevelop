<?php
	/*
		Author: Craig Dennis
		Date:	16/06/2015
		Ver:	1.03

		// Properties:

			string $PageFile 		# The main page file (Usually the page that delivers content)
			string $Page404  		# The page to default to if $PageFile can't be found
			array $FileOrder 		# Order of files to load such as a header/footer, $PageFile should be in this list
			array $DefinedVars 		# Is an array list of variables that you may want to use inside the included files

		// Note:

			Make sure to pass get_defined_vars() from the PHP that calls this if you wish to use current defined variables inside the included files

		// Below is an example of usage:

			$PHPZevelop->Page->PageFile =		$PHPZevelop->Path->GetPageRoot($PAGE_PATH.".php");
			$PHPZevelop->Page->DefaultPageFile =	$PHPZevelop->Path->GetPageRoot("home.php");
			$PHPZevelop->Page->Page404 =		$PHPZevelop->Path->GetPageRoot("error/404.php");

			$PHPZevelop->Page->FileOrder = array(
				$PHPZevelop->Path->GetInc("header.php"),
				$PHPZevelop->Page->PageFile,
				$PHPZevelop->Path->GetInc("footer.php")
			);

			$PHPZevelop->Page->DefinedVars = get_defined_vars();
			$PHPZevelop->Page->LoadPage();
	*/

	class Page
	{
		public $PageFile;
		public $Page404;
		public $FileOrder;
		public $DefinedVars;
		public $PageBuffer;
		public $Errors;

		function __construct($page = "", $page404 = "", $fileOrder = array(), $definedVars = array())
		{
			$this->PageFile = $page;
			$this->Page404 = $page404;
			$this->FileOrder = $fileOrder;
			$this->DefinedVars = $definedVars;
			$this->Errors = array();

			// If $page or $fileOrder are not defined, do not LoadPage() yet
			if($page == "")
				return;
			else
				$this->LoadPage();	
		}

		function DissallowParameters($page){
			$this->NoParamPage = $page;
		}

		function LoadPage()
		{
			// Convert all paths to Unix mode
			$this->ConvertToUnixPath($this->PageFile);
			$this->ConvertToUnixPath($this->Page404);
			foreach($this->FileOrder as $k => $v)
				$this->ConvertToUnixPath($this->FileOrder[$k]);

			// Extract variables for use in included files
			if(is_array($this->DefinedVars))
				extract($this->DefinedVars, EXTR_OVERWRITE);

			// Include page to get variables for use in other files
			// Then store content for using later and callback when finished
			if(is_file($this->PageFile))
			{
				$this->PageBuffer = $this->GetPageBuffer($this->PageFile);
			}
			else
			{
				if(strlen($this->Page404) > 0 && is_file($this->Page404))
					$this->PageBuffer = $this->GetPageBuffer($this->Page404);
				else
					$this->Errors[] = "PageFile doesn't exist (".$this->PageFile.")";
			}

		    // Include files in specified order if FileOrder is set
		    // Inject page content if $page is amongst $this->FileOrder
		    if(count($this->FileOrder) > 0)
		    {
		    	foreach($this->FileOrder as $item){
					if($item != $this->PageFile)
					{
						if(strlen($item) > 0 && is_file($item))
							include($item);
						else
							$this->Errors[] = "Item doesn't exist in FileOrder (".$item.")";
					}
					else
					{
						if(isset($this->PageBuffer) && $this->PageBuffer != "")
							echo $this->PageBuffer;
					}
				}
		    }
		    // If FileOrder is not set then just display page
		    else
		    {
		    	echo $this->PageBuffer;
		    }
		}

		private function GetPageBuffer($file){
			// Extract variables for use in included files
			if(is_array($this->DefinedVars))
				extract($this->DefinedVars, EXTR_OVERWRITE);
			
			// Set gets
			if(count($PHPZevelop->Get("URLParameters")) > 0)
			{
				$Params = array_reverse($PHPZevelop->Get("URLParameters"));
				for($i = 0; $i < count($Params); $i++)
					$_GET[$PHPZevelop->CFG->PreParam.$i] = $Params[$i];
				unset($Params);
			}

			// Include file to check any config changes
			ob_start();
		    include($file);
		    ob_end_clean();

		    // Get final file buffer
			ob_start();
		    include($file);
		    $pageBuffer = ob_get_contents();
		    ob_end_clean();

		    // Check if allowed to pass parameters, return 404 if not
			if(!$PHPZevelop->CFG->PassParams && isset($_GET[$PHPZevelop->CFG->PreParam."0"])){
				ob_start();
			    include($PHPZevelop->Page->Page404);
			    $pageBuffer = ob_get_contents();
			    ob_end_clean();
			}

		    return $pageBuffer;
		}

		private function ConvertToUnixPath(&$path){
			if (strpos($path, "\\") !== FALSE){
				$path = str_replace("\\", "/", $path);
			}
		}
	}
?>