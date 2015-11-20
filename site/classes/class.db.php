<?php
	/*
	 *	Class: Connecting and querying the Database, class instantiates itself instantly
	 *	@Param String host
	 *	@Param String Database user
	 *	@Param String Database password
	 *	@param String Database name
			
	 *	EXAMPLE:
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
				if(substr($query, 0, 6) == "UPDATE" || substr($query, 0, 6) == "INSERT" || substr($query, 0, 4) == "SHOW"){
					return true;
				}else{
					if($options == NULL) $return = $preparedQuery->fetchAll(PDO::FETCH_ASSOC/*, $options*/);
					elseif(count($return) > 0) $return = $preparedQuery->fetchAll($options);
					if($forceSingle == true) $return = $return[0];
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
	}	
?>