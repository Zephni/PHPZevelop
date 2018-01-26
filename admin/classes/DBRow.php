<?php
	if(!class_exists("DBRow"))
	{
		class DBRow
		{
			// Static properties
			public static $DB = null;
			public static $Config = array();

			// Public properties
			public $Data = array();
			public $Errors = array();

			// Construct (if self::$DB is set this User object will try to find a user with the key values passed as $_Data)
			public function __construct($_Data = array())
			{
				global $DB;

				if(!isset($DB))
					die('$DB must be set for DBRow');
				else
					self::$DB = $DB;

				$this->Initiate();
				$this->Data = $_Data;
			}

			/*
			*	OVERRIDE METHODS
			*/

			public function Initiate()
			{

			}

			public function UniqueChecks()
			{
				return array();
			}

			/*
			*	PUBLIC METHODS
			*/

			public static function GetTableFromClass($ClassString)
			{
				$Class = get_called_class();
				$Class = new $Class();
				return $Class->Table;
			}

			// Upload (username must be unique AND all required fields must be set. Note this will grab the id of the new user and set it on the object)
			public function Upload()
			{
				if(!$this->CheckData(true))
					return false;

				$NewData = $this->Data;
				if(isset($NewData["id"]))
					unset($NewData["id"]);

				foreach($NewData as $K => $V)
					$NewData[$K] = (string)$V;

				$Fields = self::BuildFieldsStr($NewData);
				$Success = self::$DB->QuerySingle("INSERT INTO ".self::GetTableFromClass(get_called_class())." SET ".$Fields, $NewData);
				$this->Data["id"] = self::$DB->LastInsertId;

				return $Success;
			}

			// Update (id must be set, username must be unique and all required fields must be set)
			public function Update($CheckData = false)
			{
				if(!$this->CheckData(false))
					return false;

				if(!isset($this->Data["id"]))
					$this->FatalError("id not set on sync");

				$Fields = self::BuildFieldsStr($this->Data);

				foreach($this->Data as $K => $V)
					$this->Data[$K] = (string)$V;

				if($CheckData)
					die("<pre>".$Fields."

						".print_r($this->Data)."</pre>");
				
				if(self::$DB->QuerySingle("UPDATE ".self::GetTableFromClass(get_called_class())." SET ".$Fields." WHERE id=:id", $this->Data))
					return true;
				else
					die(print_r(self::$DB));
			}

			// Sync (overwrites the current item data by re selecting the data from the database based on this users id)
			public function Sync()
			{
				if($this->Exists())
					$this->Data = User::GetUserData(array("id" => $this->Data["id"]), self::$DB);
				else
					$this->FatalError("unable to sync: item does not exist");
			}

			// CheckData (checks that all required fields are set and non empty)
			public function CheckData($UploadingNew)
			{
				$this->Errors = $this->UniqueChecks($UploadingNew);

				if(count($this->Errors) > 0)
					return false;
				else
					return true;
			}

			// Exists
			public function Exists()
			{
				if(!isset($this->Data["id"]) || strlen($this->Data["id"]) == 0)
					return false;

				if(count(self::$DB->QuerySingle("SELECT id FROM ".self::GetTableFromClass(get_called_class())." WHERE id=:id", array("id" => $this->Data["id"])) > 0))
					return true;
				else
					return false;
			}

			public function Delete()
			{
				global $DB;
				self::$DB->Query("DELETE FROM ".self::GetTableFromClass(get_called_class())." WHERE id=:id", array("id" => $this->Data["id"]));
			}

			public function FatalError($String)
			{
				die("DBRow error: ".$String);
			}

			/*
			*	PUBLIC STATIC METHODS
			*/
			
			public static function Get($_Data, $Joiner = "AND", $Die = false)
			{
				$Class = get_called_class();				
				$Data = self::GetItemData($_Data, $Joiner, self::GetTableFromClass($Class), $Die);

				if(isset($Data["id"]))
					return new $Class($Data);
				else
					return false;
			}

			// CheckExists
			private static function GetItemData($_Array, $Joiner = "AND", $Table, $Die = false)
			{
				global $DB;
				$Fields = self::BuildFieldsStr($_Array, " ".$Joiner." ", true);
				if($Die) PrintDie("SELECT * FROM ".$Table." WHERE ".$Fields, $_Array);
				return @$DB->QuerySingle("SELECT * FROM ".$Table." WHERE ".$Fields, $_Array);
			}

			// CheckExists
			public static function CheckExists($_Array)
			{
				global $DB;
				$Fields = self::BuildFieldsStr($_Array, " AND ", true);

				if(count(@$DB->Query("SELECT id FROM ".self::GetTableFromClass(get_called_class())." WHERE ".$Fields, $_Array)) > 0)
					return true;
				else
					return false;
			}

			// Build string (key=:key, key2=:key2 style) based on array for query
			public static function BuildFieldsStr($_KVArray, $Delimiter = ", ", $AllowID = false)
			{
				$Fields = array();
				foreach($_KVArray as $K => $V)
				{
					if($AllowID || $K != "id")
					{
						if(strpos($V, '%') !== FALSE)
							$Fields[] = $K." LIKE :".$K;
						else
							$Fields[] = $K."=:".$K;
					}
				}

				return implode($Delimiter, $Fields);
			}
		}
	}