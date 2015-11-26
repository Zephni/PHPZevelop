<?php
	/*
		Author: Craig Dennis
		Date:	16/06/2015
		Ver:	1.0

		The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.

		// Properties:

			Defined after instantiated

		// Below is an example of usage:

			The "NewObject" method is for adding an already instantiated object as an accessible property of PHPZevelop.
			For instance "Page" is a class that PHPZevelop relies on, this is added to PHPZevelop by using:
				$PHPZevelop->NewObject("Page", new Page());
	*/

	class PHPZevelop
	{
		private $InternalData = array();

		public function NewObject($Alias, &$Object){
			$this->$Alias = $Object;
		}

		public function Set($Key, $Value){
			$this->InternalValues[$Key] = $Value;
		}

		public function Get($Key){
			if(array_key_exists($Key, $this->InternalValues))
				return $this->InternalValues[$Key];
			else
				return null;
		}
	}