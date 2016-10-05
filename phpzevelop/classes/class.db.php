<?php
	/*
		Author: Craig Dennis

		// Construct
	 	Class: Connecting and querying the Database
	 	@Param String host
	 	@Param String Database user
	 	@Param String Database password
	 	@param String Database name
			
	 	EXAMPLE:
			$db->query("SELECT * FROM table WHERE id=:id", array('id' => $_GET['id']));
	*/
	class DB{
		public $DBO, $error = array();
		public $Data = array();
		public $Fields = array();
		public $LastInsertId;
		public $Host, $User, $Pass, $Name;
		public $Connected = false;
		
		public function __construct($Host, $User, $Pass, $Name){
			$dsn = "mysql:dbname=".$Name.";host=".$Host;
			try {
				$this->DBO = new PDO($dsn, $User, $Pass);
				$this->DBO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$this->DBO->exec("set names utf8");
				$this->Connected = true;
			}catch (PDOException $e){
				$this->error[] = 'Connection failed: ' . $e->getMessage();
			}
		}

		public function Query($query, $Data=NULL, $options=NULL, $forceSingle=false, $dieOnQuery = false){
			try{
				if($dieOnQuery)
					die($query);
				$preparedQuery = $this->DBO->prepare($query);
				$preparedQuery->execute($Data);
				$this->LastInsertId = $this->DBO->LastInsertId();
				if(substr($query, 0, 6) == "UPDATE" || substr($query, 0, 6) == "INSERT"){
					return true;
				}else{
					if($options == NULL) $return = $preparedQuery->fetchAll(PDO::FETCH_ASSOC/*, $options*/);
					elseif(count($return) > 0) $return = $preparedQuery->fetchAll($options);
					if($forceSingle == true && count($return) > 0) $return = $return[0];
					else if($forceSingle == true) return array();
					return $return;
				}
			}catch(PDOException $e){
				$this->error[] = $e->getMessage();
				return array();
			}
		}

		public function QuerySingle($query, $Data=NULL, $options=NULL, $dieOnQuery = false){
			return $this->query($query, $Data, $options, true, $dieOnQuery);
		}

		public function Quote($string){
			return substr($this->DBO->quote($string), 1, -1);
		}
		
		public function ErrorHandler(){
			$return = "";
			foreach($this->error as $e){
				$return .= "<span class='error-text'>".$e."</span><br />";
			}
			return $return;
		}

		public function GetColumnNames($tbl){
	        $sql = 'SHOW COLUMNS FROM ' . $tbl;
	        
	        $preparedQuery = $this->DBO->prepare($sql);
	            
	        try {    
	            if($preparedQuery->execute()){
	                $raw_column_Data = $preparedQuery->fetchAll();
	                
	                foreach($raw_column_Data as $outer_key => $array){
	                    foreach($array as $inner_key => $value){
	                            
	                        if ($inner_key === 'Field'){
	                                if (!(int)$inner_key){
	                                    $this->Fields[] = $value;
	                                }
	                            }
	                    }
	                }        
	            }
	            return true;
	        } catch (Exception $e){
	                return $e->getMessage(); //return exception
	        }        
	    }

	    function Select($Fields, $From, $Where = array(), $Single = false)
		{
			$Arr = array();
			$Str = "SELECT";
			$Str .= " ".((is_array($Fields)) ? implode(",", $Fields) : $Fields);
			$Str .= " FROM ".$From;

			$Wheres = 0;

			$Str .= (count($Where) > 0) ? " WHERE" : "";
			foreach($Where as $K => $V)
			{
				if(!is_array($V))
				{
					$Str .= " ".$V;
				}
				else
				{
					$Wheres++;
					$Str .= " ".$V[0]." ".$V[1]." :w".$Wheres;
					$Arr["w".$Wheres] = $V[2];
				}
			}

			if(!$Single)
				return $this->Query($Str, $Arr);
			else
				return $this->QuerySingle($Str, $Arr);
		}

		function Insert($Table, $Values)
		{
			$Str = "INSERT INTO ".$Table." SET ";

			$Sets = 0;

			$Temp = array();
			foreach($Values as $K => $V)
			{
				$Sets++;
				$Temp[] = $K." = :"."s".$Sets;
				$Arr["s".$Sets] = $V;
			}

			$Str .= implode(", ", $Temp);

			$DB->Query(Str, $Arr);
			return $DB->LastInsertId;
		}

		function Update($Table, $Values, $Where = null)
		{
			if(!is_array($Where) || count($Where) == 0)
				die("Where can not be empty in DB->Update");

			$Str = "UPDATE ".$Table." SET ";

			$Sets = 0;
			$Wheres = 0;

			$Temp = array();
			foreach($Values as $K => $V)
			{
				$Sets++;
				$Temp[] = $K." = :"."s".$Sets;
				$Arr["s".$Sets] = $V;
			}

			$Str .= implode(", ", $Temp);

			$Str .= (count($Where) > 0) ? " WHERE" : "";
			foreach($Where as $K => $V)
			{
				if(!is_array($V))
				{
					$Str .= " ".$V;
				}
				else
				{
					$Wheres++;
					$Str .= " ".$V[0]." ".$V[1]." :w".$Wheres;
					$Arr["w".$Wheres] = $V[2];
				}
			}

			return $DB->QuerySingle(Str, $Arr);
		}
	}	
?>