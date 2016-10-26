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
	}

	/* Link
	------------------------------*/
	if(class_exists("Link"))
	{
		$Link = new Link($PHPZevelop->Path);
	}

	/* PHPMailer
	------------------------------*/
	if(include $PHPZevelop->CFG->SiteDirRoot."/classes/PHPMailer/PHPMailerAutoload.php")
	{
		$Mail = new PHPMailer;
		$Mail->isSMTP();                                 	// Set Mailer to use SMTP
		$Mail->Host      	= "auth.smtp.1and1.co.uk"; 		// SMTP server example
		$Mail->Port      	= 25;                   		// set the SMTP port for the GMAIL server
	    $Mail->Username  	= "email@burdamagazines.co.uk";	// SMTP account username example
	    $Mail->Password  	= "pass343872";        			// SMTP account password example
		$Mail->SMTPAuth		= true;                         // Enable SMTP authentication
		$Mail->isHTML(true);                             	// Set eMail format to HTML

		/*
			$Mail->setFrom 		( "email@burdamagazines.co.uk", "KBBDaily Jobs");
			$Mail->addAddress	( "craig.dennis@burdamagazines.co.uk", "Craig Dennis");
			$Mail->Subject 		= "Here is the subject";
			$Mail->Body 		= "This is the HTML message body <b>in bold!</b>";
			echo ($Mail->send()) ? "Success" : "Failed: ".$Mail->ErrorInfo;
		*/
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

			$Administrator = Administrator::GetSingle(array("username", "=", $_SESSION[Administrator::$UsernameSessionField]));

			if(substr($Administrator->Data["last_active"], 1) > time()-Administrator::$InactiveTime)
				$Administrator->Login();
			else
				$Administrator->Logout();
		}

		if(!isset($Administrator) || !$Administrator->LoggedIn())
			$Administrator =	new Administrator();
	}
	else if(true) // Change to false to disable user loggin
		die("Administrator class required");

	/* Pagination
	------------------------------*/
	if(class_exists("Pagination"))
	{
		$Pagination = new Pagination(array("PerPage" => 12));
		$Pagination->Options["ContainerCSS"]["display"] = "inline-block";
		$Pagination->Options["ContainerCSS"]["width"] = "auto";
		$Pagination->SetCSS("ButtonCSS", array("background" => "#EEEEEE", "color" => "#009ACD", "border-radius" => "0px", "border" => "none", "padding" => "2px 10px"));
		$Pagination->SetCSS("ActiveButtonCSS", array("background" => "#009ACD", "color" => "white !important"));
		$Pagination->SetCSS("HighlightedButtonCSS", array("background" => "#009ACD", "color" => "white !important"));
	}