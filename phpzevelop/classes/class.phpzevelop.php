<?php
	/*
		Author: Craig Dennis
		Ver:	1.4

		The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.

		// Properties:

			private array $InternalData			// Internal data available through Set and Get methods
			Others defined after instantiated	// Objects added via NewObect method

		// Below is an example of usage:

			The "NewObject" method is for adding an already instantiated object as an accessible property of PHPZevelop.
			For instance "Page" is a class that PHPZevelop relies on, this is added to PHPZevelop by using:
				$PHPZevelop->NewObject("Page", new Page());
	*/

	class PHPZevelop
	{
		private $InternalData = array();

		public function NewObject($Alias, $Object)
		{
			$this->$Alias = $Object;
		}

		public function OverrideObjectData($Alias, $NewData)
		{
			if(isset($this->$Alias))
				foreach($NewData as $Key => $Value)
					$this->$Alias->$Key = $Value;
		}

		public function Set($Key, $Value)
		{
			$this->InternalData[$Key] = $Value;
		}

		public function Append($Key, $V1, $V2 = null)
		{
			if($V2 === null)
				$this->InternalData[$Key][] = $V1;
			else
				$this->InternalData[$Key][$V1] = $V2;
		}

		public function Get($Key)
		{
			if(array_key_exists($Key, $this->InternalData))
				return $this->InternalData[$Key];
			else
				return null;
		}
	}