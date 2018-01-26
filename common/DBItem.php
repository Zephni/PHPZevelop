<?php
	class DBItem
	{
		// Data
		public $Data;

		// DBTable
		private $DBTable;

		// Construct
		public function __construct(&$DBTable, $Search = null)
		{
			$this->Data = array();
			$this->DBTable = $DBTable;

			if($Search !== null)
			{
				if(is_numeric($Search))
					$this->Data = $this->DBTable->SelectSingle(array(array("id", "=", $Search)));
				else if(is_array($Search))
					$this->Data = $this->DBTable->SelectSingle($Search);
			}
		}

		// Insert
		public function Insert()
		{
			$this->DBTable->Insert($this->BuildCleanDataArray());
		}

		// Update
		public function Update()
		{
			$this->DBTable->Update($this->BuildCleanDataArray(), array(array("id", "=", $this->Data["id"])));
		}

		// Update
		public function Delete()
		{
			$this->DBTable->Delete(array(array("id", "=", $this->Data["id"])));
		}

		// BuildCleanDataArray
		private function BuildCleanDataArray()
		{
			$Array = array();

			foreach($this->DBTable->GetFields() as $IField)
				if($IField["column_name"] != "id")
					$Array[$IField["column_name"]] = (array_key_exists($IField["column_name"], $this->Data)) ? $this->Data[$IField["column_name"]] : "";

			return $Array;
		}
	}