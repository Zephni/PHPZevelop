<?php
	/*
		TO USE:

		Extend the DBItem class and add the following function:

			function Initiate()
			{
				$this->Config["Table"] = "tablename";
			}

		.. where "tablename" is the name of the table you want to use. Now you can use the Upload, Update and Delete methods associated.
		
		To create a new item use:

			$Item = new ExtendedDBItem(array("title" => "something"));
			$Item->Upload();

		To select an item (1) or items (2) from the database use:

			(eg 1) $Item = ExtendedDBItem::Get(array(array("title", "LIKE", "something")));
			(eg 2) $Items = ExtendedDBItem::Get(array(array("id", ">", 5)));
	*/
	class DBItem
	{
		public $Config = array("Table" => "tablename");
		public $Data = array();

		public function __construct($_Data = array())
		{
			$this->Data = $_Data;
			$this->Initiate();
		}

		public function Upload()
		{
			global $DB;

			$DB->Insert($this->Config["Table"], $this->Data);
		}

		public function Update()
		{
			global $DB;

			$DB->Update($this->Config["Table"], $this->Data, array(array("id", "=", $this->Data["id"])));
		}

		public function Delete()
		{
			global $DB;

			$DB->QuerySingle("DELETE FROM ".$this->Config["Table"]." WHERE id=:id", array("id" => $this->Data["id"]));
		}

		public static function Get()
		{
			global $DB;

			$Where = func_get_args();

			if(is_numeric($Where[0]))
				$Where = array(array("id", "=", $Where[0]));

			$ClassName = get_called_class();
			$Temp = new $ClassName();

			$GetData = $DB->Select("*", $Temp->Config["Table"], $Where);

			if(!isset($GetData[0]))
				return array();
			else
			{
				$Items = array();
				foreach($GetData as $Item)
					$Items[] = new $ClassName($Item);

				return $Items;
			}
		}

		public static function GetSingle()
		{
			$Where = func_get_args();
			$ClassName = get_called_class();

			$Items = call_user_func_array($ClassName."::Get", $Where);

			if(isset($Items[0]))
				return $Items[0];
			else
				return new $ClassName();
		}
	}