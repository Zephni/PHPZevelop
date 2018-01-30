<?php
	/*
		EXAMPLE USAGE:
	
		// NOTE: REQUIRED FIELD IN TABLE BY DEFAULT ARE: username, password, salt, email, last_active, active

		// Upload a new user
		$User = new User(array("username" => "Zephni"));
		$User->SetPassword("tester34");
		$User->Upload();

		// Get user from database
		$User = $User = User::Get(array("username" => "Zephni")); // Note you can use any array elements to select a user, you can use % wildcards

		// Update user details
		$User = User::Get(array("username" => "Zephni"));
		$User->Data["username"] = "somenewname";
		$User->SetPassword("tester34");
		$User->Update();

		// Sync from database
		$User = User::Get(array("username" => "Zephni"));
		$User->Data["username"] = "Billy";
		$User->Sync(); // This will reset the username back to Zephni

		// Check password
		$User = User::Get(array("username" => "Zephni"));
		return ($User->CheckPassword("tester34")) ? true : false;

		// Check if user exists
		return (User::CheckExists(array("username" => "Zephni"))) ? true : false;
	*/

	//if(!class_exists("User"))
	//{
		class Administrator extends DBItem
		{
			private $ActualPass = null;
			public static $UsernameSessionField = "username";
			public static $PasswordSessionField = "password";
			public static $InactiveTime;

			// Initiate

			public function UniqueChecks($Uploading)
			{
				global $DB;
				$Errors = array();

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
			
			// GetActivationCode
			public function GetActivationCode()
			{
				if(!isset($this->Data["id"]) || !isset($this->Data["salt"]))
					die("id and salt not set when getting activation code");

				return urlencode(base64_encode(strrev($this->Data["salt"])))."-".(6400 + $this->Data["id"] * 34)."-".$this->Data["created_tstamp"];
			}
			
			// &$User is by reference, returns string on failure and true on success
			public static function AttemptLogin(&$User, $Username, $Password)
			{
				$MSG = null;
				global $DB;
				$UserLogin = Administrator::GetSingle("administrators", array("username", "LIKE", $Username), "OR", array("email", "LIKE", $Username));

				if(isset($UserLogin->Data["id"]))
				{
					// Attempts
					$AllowedAttempts = 5;
					$ExpireAttempts = 3600;
					$Attempts = explode(",", (isset($UserLogin->Data["login_attempts"])) ? $UserLogin->Data["login_attempts"] : "");

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

					if($UserLogin->CheckPassword($Password))
					{
						
					}
					else
						$MSG = "<h3 style='color: #BA1F24;'>Incorrect username/password combination</h3>";
				}
				else
					$MSG = "<h3 style='color: #BA1F24;'>Incorrect username/password combination</h3>";

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
				if(substr($this->Data["last_active"], 0, 1) == "+" && $this->Data["last_active"] > time()-Administrator::$InactiveTime)
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
				global $DB;

				if(!isset($_SESSION[self::$PasswordSessionField]) || !isset($this->Data["id"]) || strlen($this->Data["id"]) == 0)
					return false;

				$TempUser = Administrator::GetSingle("administrators", array("username", "LIKE", $_SESSION[self::$UsernameSessionField]));

				if(isset($TempUser->Data["password"]) && $_SESSION[self::$PasswordSessionField] == $TempUser->Data["password"])
					return true;
				
				return false;
			}

			public function CheckRelocate()
			{
				global $PHPZevelop;

				$PageParts = explode("/", $PHPZevelop->CFG->PagePath);
				$RootDirectory = $PageParts[0];

				if(!$this->LoggedIn())
					$PHPZevelop->Location("user/login");
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
		}
	//}