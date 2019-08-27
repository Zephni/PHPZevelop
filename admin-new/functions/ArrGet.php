<?php
	function ArrGet($Arr, $Key)
	{
		$Args = func_get_args();
		$Arr = $Args[0];		
		$Keys = array_slice($Args, 1);
		$CurrentValue = $Arr;

		foreach($Keys as $Key)
		{
			if(!isset($CurrentValue[$Key]))
				return false;
			else
				$CurrentValue = $CurrentValue[$Key];
		}

		return $CurrentValue;
	}