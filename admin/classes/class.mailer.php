<?php
	class Mailer
	{
		public $PHPMailer;

		function __construct($PHPMailer){
			$this->PHPMailer = $PHPMailer;

			// Default settings
			$this->PHPMailer->IsSMTP();
			$this->PHPMailer->IsHTML(true);
			$this->PHPMailer->Host     		= "auth.smtp.1and1.co.uk"; 
			$this->PHPMailer->Port       	= 25;                   
			$this->PHPMailer->SMTPAuth   	= true;
			$this->PHPMailer->Username   	= "contact@eventsdaily.co.uk";
			$this->PHPMailer->Password   	= "Cloud-=45%";
			$this->PHPMailer->From 	  		= "no-reply@eventsdaily.co.uk";
			$this->PHPMailer->FromName		= "EventsDaily";
		}

		public function AddAddress($email, $name = ""){
			if($name == "")
				$name = $email;

			$this->PHPMailer->AddAddress($email, $name);
		}

		public function Send($subject = null, $body = null){
			if($subject != null)
				$this->PHPMailer->Subject = $subject;
			
			if($body != null)
				$this->PHPMailer->Body = $body;		

			$this->PHPMailer->Send();
		}
	}