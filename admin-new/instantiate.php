<?php
	/* Database
	------------------------------*/
	if(class_exists("DB"))
	{
		if(strlen($PHPZevelop->CFG->DB->Host) > 0)
		{
			$DB = new DB($PHPZevelop->CFG->DB->Host, $PHPZevelop->CFG->DB->User, $PHPZevelop->CFG->DB->Pass, $PHPZevelop->CFG->DB->Name);
			
			if(!$DB->Connected)
				die($DB->ErrorHandler());
		}
		else
		{
			die("Database must be setup first. See config.php");
		}
	}
	
	/* Link
	------------------------------*/
	if(class_exists("Link"))
	{
		$Link = new Link($PHPZevelop->Path);
	}

	/* Administrator
	------------------------------*/
	if(true && class_exists("Administrator"))
	{
		Administrator::$UsernameSessionField = "admin_username";
 		Administrator::$PasswordSessionField = "admin_password";
 		
		if(!isset($_SESSION[Administrator::$UsernameSessionField]))
		{
			$_SESSION[Administrator::$UsernameSessionField] = CookieHelper::Get(Administrator::$UsernameSessionField);
			$_SESSION[Administrator::$PasswordSessionField] = CookieHelper::Get(Administrator::$PasswordSessionField);
		}

		if(isset($_SESSION[Administrator::$UsernameSessionField]))
		{
			CookieHelper::Set(Administrator::$UsernameSessionField, $_SESSION[Administrator::$UsernameSessionField]);
			CookieHelper::Set(Administrator::$PasswordSessionField, $_SESSION[Administrator::$PasswordSessionField]);

			$Administrator = Administrator::GetSingle(Administrator::$DBTABLEDEFAULT, array("username", "LIKE", $_SESSION[Administrator::$UsernameSessionField]));

			if(isset($Administrator->Data["last_active"]) && substr($Administrator->Data["last_active"], 1) > time()-Administrator::$InactiveTime)
			{
				$Administrator->Login();
				CookieHelper::Set("keep_admin_username", $_SESSION[Administrator::$UsernameSessionField]);
			}
			
			//else
			//	$Administrator->Logout();
		}

		if(!isset($Administrator) || !$Administrator->LoggedIn())
			$Administrator = new Administrator(Administrator::$DBTABLEDEFAULT);
	}
	else if(true) // Change to false to disable user loggin
		die("Administrator class required");

	/* Pagination
	------------------------------*/
	if($Administrator->LoggedIn() && class_exists("Pagination"))
	{
		$Pagination = new Pagination(array("PerPage" => 20));
		$Pagination->Options["ContainerCSS"]["display"] = "inline-block";
		$Pagination->Options["ContainerCSS"]["width"] = "auto";
		$Pagination->Options["ContainerCSS"]["padding"] = "0px 0px 20px 0px";
		$Pagination->SetCSS("ButtonCSS", array("background" => "#EEEEEE", "color" => $Administrator->Data["theme_color"], "border-radius" => "0px", "border" => "none", "padding" => "2px 10px"));
		$Pagination->SetCSS("ActiveButtonCSS", array("background" => $Administrator->Data["theme_color"], "color" => "white !important"));
		$Pagination->SetCSS("HighlightedButtonCSS", array("background" => $Administrator->Data["theme_color"], "color" => "white !important"));
	}

	/* FieldTypeOptions
	------------------------------*/
	$FieldTypeOptions = array(
		"VARCHAR(255)",
		"TEXT",
		"MEDIUMTEXT",
		"INT(11)"
	);