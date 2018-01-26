<?php
	// $Article = DBItem::GetSingle(array("id", "=", 1));
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
			
			$Item = new DBItem($TableName);
			$Item->GetData($Where);
			return $Item;
		}

		public static function GetMultiple()
		{
			global $DB;
			
			$Args = func_get_args();
			$TableName = $Args[0];
			$Where = (count($Args) > 1) ? array_slice($Args, 1) : array();
			
			$Items = array();
			
			foreach($DB->Select("*", $TableName, $Where) as $K => $V)
				$Items[] = new DBItem($TableName, $V);
			
			return $Items;
		}
	}