<?php
	function PrintDie()
	{
		$MSGS = array();

		if(func_num_args() > 0)
		{
			foreach(func_get_args() as $Item)
			{
				if(gettype($Item) == "string")
				{
					$MSGS[] = $Item."<br />";
				}
				else if(gettype($Item) == "array")
				{
					$MSGS[] = print_r($Item, true);
				}
			}
		}
		else
		{
			$MSGS[] = "Nothing passed to PrintDie method";
		}

		die("<pre>".implode("<br />", $MSGS)."</pre>");
	}