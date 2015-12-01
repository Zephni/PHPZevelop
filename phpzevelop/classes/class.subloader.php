<?php
	/*
		Author: Craig Dennis
		Date:	16/06/2015
		Ver:	1.0

		The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.

		// Properties:

			array $DefinedVariables = array();	// An array of variables to pass to the included files
			array $IncludedFiles;				// List of successfully included files after RunIncludes() has ran
			array $ErrorFiles;					// List of unsuccessfully included files after RunIncludes() has ran

		// Below is an example of usage:

			If you wanted to include all PHP files in the "site/classes" directory, use the below:

			1. $SubLoader = new SubLoader($PHPZevelop->CFG->SiteDir);
			2. $SubLoader->RunIncludes(array("classes"));
			3. extract($SubLoader->DefinedVariables);

			Line 1 is intantiating the SubLoader class, the constructor takes the root path of the website.
			Line 2 is running the includes, the parameter can be a string or an array of strings that represent the directory names.
			Line 3 is extracting the variables that may have been in the files that were included.
	*/

	class SubLoader
	{
		public $DefinedVariables = array();
		public $IncludedFiles;
		public $ErrorFiles;
		private $RootPath;
		private $Directories;

		public function __construct($rootPath)
		{
			$this->IncludedFiles = array();
			$this->ErrorFiles = array();
			$this->RootPath = $rootPath;
		}

		public function RunIncludes($directories = null)
		{
			if($directories != null)
				$this->Directories = $directories;

			if(!is_array($this->Directories))
				$this->Directories = array($this->Directories);

			foreach($this->Directories as $item)
			{
				$this->IncludeFiles(
					$this->GetRecursiveFileList(rtrim($this->RootPath, "/")."/".$item)
				);
			}
		}

		public function GetRecursiveFileList($path)
		{
			$array = array();
			$RDI = new RecursiveDirectoryIterator($path);

			foreach (new RecursiveIteratorIterator($RDI) as $item => $cur)
			{
				if(basename($item) != ".." && basename($item) != "." && substr(basename($item), -4, 4) == FILE_EXT)
			   		$array[] = $item;
			}

			return $array;
		}

		public function IncludeFile($file)
		{
			if(file_exists($file))
			{
				ob_start();
				include_once($file);
				ob_end_clean();
				$vars = get_defined_vars();
				unset($vars["file"]);
				$this->DefinedVariables = array_merge($this->DefinedVariables, $vars);
				$this->IncludedFiles[] = $file;
			}
			else
			{
				$this->ErrorFiles[] = $file;
			}
		}

		public function IncludeFiles($files)
		{
			foreach($files as $file)
				$this->IncludeFile($file);
		}
	}