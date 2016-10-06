<?php
	//if(!class_exists("User"))
	//{
		class User extends DBItem
		{
			private $ActualPass = null;
			public $ImageLocation = "users";
			public static $UsernameSessionField = "username";
			public static $PasswordSessionField = "password";
			public static $InactiveTime;

			// Initiate
			public function Initiate()
			{
				$this->Config["Table"] = "users";
				self::$InactiveTime = (60*20);
			}

			public function UniqueChecks($Uploading)
			{
				global $DB;
				$Errors = array();

				if(!isset($this->Data["username"]) || strlen($this->Data["username"]) < 5 || strlen($this->Data["username"]) > 20)
					$Errors[] = "Username length must be between 5 and 20 characters";

				if($this->ActualPass !== null)
				{
					if(!isset($this->ActualPass) || !is_string($this->ActualPass))
						$Errors[] = "Password must be a string";
					else if(strlen($this->ActualPass) < 6 || strlen($this->ActualPass) > 40)
						$Errors[] = "Password length must be between 6 and 40 characters";
				}

				$CheckUser = User::Get(array("username" => $this->Data["username"]));
				if(isset($CheckUser->Data["id"]) && ($Uploading || (!$Uploading && $this->Data["id"] != $CheckUser->Data["id"])))
				{
					if($CheckUser->Data["created_tstamp"] < time()-86400 && $CheckUser->Data["activated"] < 1) $CheckUser->Delete();
					else $Errors = array("Username already exists");
				}

				$CheckUser = User::Get(array("email" => $this->Data["email"]));
				if(isset($CheckUser->Data["id"]) && ($Uploading || (!$Uploading && $this->Data["id"] != $CheckUser->Data["id"])))
				{
					if($CheckUser->Data["created_tstamp"] < time()-86400 && $CheckUser->Data["activated"] < 1) $CheckUser->Delete();
					else $Errors = array("Email already exists");
				}

				return $Errors;
			}

			/*
			*	PUBLIC METHODS
			*/

			// SetNewPassword (note this will generate a new salt)
			public function SetPassword($Password)
			{
				$this->ActualPass = $Password;
				$this->Data["salt"] = substr(md5(microtime()), rand(0, 26), 15);
				$this->Data["password"] = sha1($this->Data["salt"].$Password);
			}

			// CheckPassword
			public function CheckPassword($Password)
			{
				if(sha1($this->Data["salt"].$Password) == $this->Data["password"])
					return true;
				else
					return false;
			}

			public function GetActivationCode()
			{
				if(!isset($this->Data["id"]) || !isset($this->Data["salt"]))
					die("id and salt not set when getting activation code");

				return urlencode(base64_encode(strrev($this->Data["salt"])))."-".(6400 + $this->Data["id"] * 34)."-".$this->Data["created_tstamp"];
			}

			public function SpendCredits($Cost)
			{
				global $Config;

				if($this->Data["credits"] == $Config["UnlimitedCreditsString"])
					return true;

				$Cost = abs($Cost);

				if($this->Data["credits"] < $Cost)
					return false;
				
				$this->Data["credits"] -= $Cost;
				return $this->Update();
			}

			public function AddCredits($Amount)
			{
				$this->Data["credits"] += abs((int)$Amount);
				return $this->Update();
			}

			// &$User is by reference, returns string on failure and true on success
			public static function AttemptLogin(&$User, $Username, $Password)
			{
				$MSG = null;
				$UserLogin = User::Get(array(array("username", "LIKE", $Username), "OR", array("email", "LIKE", $Username)));

				// Attempts
				$AllowedAttempts = 5;
				$ExpireAttempts = 3600;
				$Attempts = explode(",", $UserLogin->Data["login_attempts"]);

				foreach($Attempts as $K => $V)
					if($V < time() - $ExpireAttempts)
						unset($Attempts[$K]);

				if(count($Attempts) < $AllowedAttempts)
				{
					foreach($Attempts as $K => $V)
						if(strlen($V) <= 1) unset($Attempts[$K]);

					$Attempts[] = time();
					$UserLogin->Data["login_attempts"] = implode(",", $Attempts);
					$UserLogin->Update();
				}
				else
				{
					return "<h3 style='color: #BA1F24;'>Too many login attempts, please contact the administrator.</h3>";
				}
				// Attempts

				if(isset($UserLogin->Data["id"]))
				{
					if($UserLogin->CheckPassword($Password))
					{
						if($UserLogin->Data["activated"] == "0")
							$MSG = "<h3 style='color: #BA1F24;'>Your account has not yet been activated, please check your emails and click the activation link.</h3>";
					}
					else
						$MSG = "<h3 style='color: #BA1F24;'>Incorrect username/password combination (Attempts: ".(int)(count($Attempts)).")</h3>";
				}
				else
					$MSG = "<h3 style='color: #BA1F24;'>Incorrect username/password combination (Attempts: ".(int)(count($Attempts)).")</h3>";

				if($MSG == null)
				{
					$User = $UserLogin;
					$User->Data["login_attempts"] = "";
					$User->Update();
					$User->Login();
					return true;
				}
				else
				{
					return $MSG;
				}
			}

			public function IsActive()
			{
				if(substr($this->Data["last_active"], 0, 1) == "+" && $this->Data["last_active"] > time()-User::$InactiveTime)
					return true;
				else
					return false;
			}

			public function Login()
			{
				unset($_SESSION["logged_out"]);
				$this->Data["last_active"] = "+".time();
				$this->Update();
				$_SESSION[self::$UsernameSessionField] = $this->Data["username"];
				$_SESSION[self::$PasswordSessionField] = $this->Data["password"];
			}

			public function Logout()
			{
				$this->Data["last_active"] = "-".time();
				$this->Update();
				unset($_SESSION[self::$UsernameSessionField]);
				unset($_SESSION[self::$PasswordSessionField]);
				$this->Data = array();
			}

			public function LoggedIn()
			{
				if(!isset($_SESSION[self::$PasswordSessionField]) || !isset($this->Data["id"]) || strlen($this->Data["id"]) == 0)
					return false;

				$TempUser = User::Get(array(array("username", "=", $_SESSION[self::$UsernameSessionField])));
				if(isset($TempUser->Data) && $_SESSION[self::$PasswordSessionField] == $TempUser->Data["password"])
					return true;

				return false;
			}

			public function CheckRelocate()
			{
				global $PHPZevelop;

				$PageParts = explode("/", $PHPZevelop->CFG->PagePath);
				$RootDirectory = $PageParts[0];

				if($RootDirectory == "seeker")
				{
					if(!$this->LoggedIn()) $PHPZevelop->Location("user/login");
					else if($this->Data["type"] == "Recruiter") $PHPZevelop->Location("recruiter");
				}
				else if($RootDirectory == "recruiter")
				{
					if(!$this->LoggedIn()) $PHPZevelop->Location("user/login");
					else if($this->Data["type"] == "Seeker") $PHPZevelop->Location("seeker");
				}
			}

			public static function GetActivationCodeParts($String)
			{
				$Split = explode("-", $String);
				return array(
					"salt" => strrev(base64_decode(urldecode($Split[0]))),
					"id" => ($Split[1]-6400) /34,
					"created_tstamp" => $Split[2]
				);
			}

			public static function GetUsers($Where = "", $Data = array(), $Active = true)
			{
				global $DB;
				$Users = array();

				if($Active)
				{
					$Where .= ((strlen($Where) == 0) ? "WHERE" : "AND")." active!=:active";
					$Data["activated"] = "0";
				}

				foreach(@$DB->Query("SELECT * FROM ".$this->Config["Table"]." ".$Where, $Data) as $User)
					$Users[] = new User($User);

				return $Users;
			}

			public function UploadImage($File, $Overwrite = false) // Expects SimpleImage object
			{
				global $PHPZevelop;
				$Image = new upload($File);

				if($Image->uploaded)
				{
					$Image->file_new_name_body	= (!$Overwrite) ? "u".strtolower($this->Data["id"]) : rtrim($this->Data["image"], ".jpg");
					$Image->file_overwrite		= $Overwrite;
					$Image->file_new_name_ext	= "jpg";	
					$Image->image_resize        = true;
					$Image->image_x             = 400;
					$Image->image_y       		= 400;
					//$Image->image_ratio_crop	= true;
					$Image->process($PHPZevelop->CFG->RootDirs->Images."/".$this->ImageLocation);
					
					if($Image->processed)
					{
						$this->Data["image"] = $Image->file_dst_name_body.".".$Image->file_dst_name_ext;
						return true;
					}
					else
					{
						$this->Errors[] = $Image->error;
						return false;
					}
				}
			}
			
			public function GetImage()
			{
				global $PHPZevelop;

				if(isset($this->Data["image"]) && strlen($this->Data["image"]) > 0 && file_exists($PHPZevelop->CFG->RootDirs->Images."/".$this->ImageLocation."/".$this->Data["image"]))
					return $PHPZevelop->Path->GetImage($this->ImageLocation."/".$this->Data["image"], true);
				else
				{
					if(!isset($this->Data["type"]) || $this->Data["type"] == "Recruiter")
						return $PHPZevelop->Path->GetImage("components/default_company_logo.png", true);
					else
						return $PHPZevelop->Path->GetImage("components/default_seeker_logo.png", true);
				}
					
			}
		}
	//}