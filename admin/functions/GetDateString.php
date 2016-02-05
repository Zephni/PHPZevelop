<?php
	function GetDateString($timeStamp){
		$minute = 60;
		$hour = 60 * $minute;

		$timeDifference = time() - $timeStamp;

		// Within a minute
		if($timeDifference < $minute)

			return "just now";

		// Within two minutes (non plural minute/s)
		if($timeDifference < $minute * 2)

				return (string)intval(date("i", $timeDifference))." minute ago";

		// Within 1 hour
		elseif($timeDifference <= $hour)

				return (string)intval(date("i", $timeDifference))." minutes ago";				
		
		// If same day
		elseif(date("z", time()) == date("z", $timeStamp))
			
			return "today ".date("H:ia", $timeStamp);

		// If day before
		elseif(date("z", time())-1 == date("z", $timeStamp))

			return "yesterday ".date("H:ia", $timeStamp);

		// If same week
		elseif(date("W", time()) == date("W", $timeStamp))

			return date("l H:ia", $timeStamp);

		// If same year
		elseif(date("Y", $timeStamp) == date("Y", time()))

			return date("jS F H:ia", $timeStamp);

		// If prior to current year
		elseif(date("Y", $timeStamp) < date("Y", time()))
		
			return date("jS M Y H:ia", $timeStamp);
	}