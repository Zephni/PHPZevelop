<?php
	// $Article = DBItem::GetSingle("articles", array("id", "=", 1));
	// $Article->Data["title"] = "New title";
	// $Article->Update();
	
	class DBItem
	{
		public $TableName;
		public $Data;

		public function __construct($TableName, $Data = array())
		{
			$this->TableName = $TableName;
			$this->Data = $Data;
		}

		public function GetData($Where)
		{
			global $DB;
			$this->Data = $DB->SelectSingle("*", $this->TableName, $Where);
		}

		public function Insert()
		{
			global $DB;

			if(isset($this->Data["id"]))
				unset($this->Data["id"]);

			$DB->Insert($this->TableName, $this->Data);
		}

		public function Update()
		{
			global $DB;

			if(!isset($this->Data["id"]))
				die("ID not set on embed object");

			$DB->Update($this->TableName, $this->Data, array(array("id", "=", $this->Data["id"])));
		}
		
		public function Remove()
		{
			global $DB;

			if(!isset($this->Data["id"]))
				die("ID not set on embed object");

			$DB->QuerySingle("DELETE FROM ".$this->TableName." WHERE id=:id", array(array("id", "=", $this->Data["id"])));
		}
		
		public static function GetSingle()
		{
			global $DB;

			$Args = func_get_args();
			$TableName = $Args[0];
			$Where = (count($Args) > 1) ? array_slice($Args, 1) : array();
			
			$CalledClass = get_called_class();
			$Item = new $CalledClass($TableName);
			$Item->GetData($Where);
			return $Item;
		}

		public static function GetMultiple()
		{
			global $DB;
			
			$Args = func_get_args();
			$TableName = $Args[0];
			$Joins = array();

			if(is_array($Args[1]) && array_key_exists("join", $Args[1]))
			{
				foreach($Args[1]["join"] as $V) $Joins[] = explode("/", $V);
				$Where = (count($Args) > 1) ? array_slice($Args, 2) : array();
			}
			else
			{
				$Where = (count($Args) > 1) ? array_slice($Args, 1) : array();
			}

			$Items = array();
			
			$CalledClass = get_called_class();
			
			foreach($DB->Select("*", $TableName, $Where) as $K => $V)
			{
				$Item = new $CalledClass($TableName, $V);
				
				foreach($Joins as $JoinData)
				{
					if(isset($JoinData[3]))
					{
						$Temp = $DB->QuerySingle("SELECT ".$JoinData[2]." FROM ".$JoinData[1]." WHERE id=:id", array("id" => $Item->Data[$JoinData[3]]));
						$Item->Data[$JoinData[0]] = $Temp[$JoinData[2]];
					}
					else
					{
						$Temp = $DB->QuerySingle("SELECT ".$JoinData[2]." FROM ".$JoinData[1]." WHERE id=:id", array("id" => $Item->Data[$JoinData[0]]));
						$Item->Data[$JoinData[0]] = $Temp[$JoinData[2]];
					}
				}

				$Items[] = $Item;
			}
			
			return $Items;
		}

		/*
			Join example:
			DBItem::GetMultiple("table_name", array("join" => array("author/authors/name")));

			or 

			DBItem::GetMultiple("table_name", array("join" => array("image/authors/image/author")));
		*/
	}