<?php
	class DBTable
	{
		private $TableName;
		private $Fields;

		// Construct
		public function __construct($TableName)
		{
			$this->TableName = $TableName;
			$this->Fields = DBTool::GetTableColumns($this->TableName);
		}

		// GetTableName
		public function GetTableName()
		{
			return $this->TableName;
		}

		// GetFieldsArray
		public function GetFields()
		{
			return $this->Fields;
		}

		// Insert
		public function Insert($Data)
		{
			global $DB;
			return $DB->Insert($this->TableName, $Data);
		}

		// Update
		public function Update($Sets, $Where)
		{
			global $DB;
			$DB->Update($this->TableName, $Sets, $Where);
		}

		// Select
		public function Select($Where = array())
		{
			global $DB;
			return $DB->Select("*", $this->TableName, $Where);
		}

		// SelectSingle
		public function SelectSingle($Where = array())
		{
			global $DB;
			return $DB->SelectSingle("*", $this->TableName, $Where);
		}

		// Delete
		public function Delete($Where)
		{
			global $DB;
			$DB->Delete($this->TableName, $Where);
		}

		// DBItems
		public function DBItems($Where = array())
		{
			$DBItems = array();

			foreach($this->Select($Where) as $Item)
			{
				$DBItem = new DBItem($this);
				$DBItem->Data = $Item;
				$DBItems[] = $DBItem;
			}

			return $DBItems;
		}

		// DBItem
		public function DBItem($Where)
		{
			return new DBItem($this, $Where);
		}
	}