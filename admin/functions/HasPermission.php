<?php
    function HasPermission()
	{
		$Args = func_get_args();
		global $Administrator;
		
		if($Administrator->Data["permissions"] == "")
			return true;

        $Permissions = json_decode($Administrator->Data["permissions"]);
        
		$Field = $Permissions;
		foreach($Args as $K => $Item)
		{
            if(in_array($Item, $Field))
                return false;

            if(isset($Field[0]->$Item))
				$Field = $Field[0]->$Item;
		}

		return true;
	}  