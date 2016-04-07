<?php
	/*
	* File: upload_file.class.php
	* Author: Craig Dennis
	* Date: 29th November 2012
	
	Basic usage example:
	
		$file = new UploadFile($_FILES['image']);
		$file->setLimitToImage(true);
		$file->process('images/');
		
		echo $file->getMsg();
	
	*/
	class UploadFile{
		/* PRIVATE PROPERTIES
		---------------------------------------------------*/
		private $uploaded = false;
		private $processed = false;
		private $FILE = NULL;
		private $validTypes = array();
		private $msg = "";
		private $filePath = "";
		private $errors = array();
		
		/* PUBLIC PROPERTIES
		---------------------------------------------------*/
		public $fileName = "";
		public $limitToImage = false;
		public $invalidFileRegEx = '/[^0-9a-z\.\_\-]/i';
		public $successMsg = "Successfully uploaded file '[file_path][image_name]'"; // Valid placeholders ([image_name], [file_path])
		public $validCharsMsg = " | Valid characters (a-z)(A-Z)(0-9)(-)(_)";
		public $validImageTypes = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'application/pdf');
		public $wrapSuccessMsg = array("<span style='color: green;'>","</span><br />");
		public $wrapErrorMsg = array("<span style='color: red;'>","</span><br />");
		
		/* CONSTRUCTOR -> takes posted type=file element ($_FILES['element_name'])
		---------------------------------------------------*/
		public function __construct($FILE){
			$this->FILE = $FILE;
			$this->uploaded = $this->checkUploaded();
			$this->fileName = basename($this->FILE['name']);
			if(strlen($this->FILE['name']) < 1){$this->errors[] = "Non file element";}
			return $this->uploaded;
		}
		
		/* PRIVATE METHODS
		---------------------------------------------------*/
		// checkUploaded() | Private check if file uploaded properly
		private function checkUploaded(){
			$success = is_uploaded_file($this->FILE['tmp_name']);
			if(!$success){
				$this->errors[] = "Could not upload file to server";
			}
			return $success;
		}
		
		//compileErrors()
		private function compileMsg(){
			if(count($this->errors) > 0){
				$this->msg = implode(', ', $this->errors);
			}else{
				$this->successMsg = str_replace('[file_path]', $this->filePath, $this->successMsg);
				$this->successMsg = str_replace('[image_name]', $this->fileName, $this->successMsg);
				$this->msg = $this->wrapSuccessMsg[0].$this->successMsg.$this->wrapSuccessMsg[1];
			}
		}
		
		/* PUBLIC METHODS
		---------------------------------------------------*/
		// uploaded() | Checks if file upload was successfull
		public function uploaded(){
			return $this->uploaded;
		}
		// process($filePath) | Put file in live folder
		public function process($filePath=NULL, $strict=false){
			if($filePath == NULL){$filePath = $this->filePath;}
			$this->filePath = $filePath;
			$valid = true;
			# Check File name
			$origional = $this->fileName;
			$this->fileName = preg_replace($this->invalidFileRegEx, '', $this->fileName);
			if($this->fileName != $origional && $strict == true){
				$this->errors[] = "Filename not valid '".$this->fileName."' ".$this->validChars;
				$valid = false;
			}
			# Check File type
			if($this->limitToImage == true){$this->validTypes = $this->validImageTypes;}
			if(count($this->validTypes) > 0){
				if(!in_array($this->FILE['type'], $this->validTypes)){
					$this->errors[] = "File type is not valid '".$this->FILE['type']."' | Valid types '".implode(', ', $this->validTypes)."'";
					$valid = false;
				}
			}
			# Process
			if($valid){
				$this->processed = move_uploaded_file($this->FILE['tmp_name'], $filePath.$this->fileName);
				
				if($this->processed){
					return true;
				}else{
					$this->errors[] = 'Error moving temporary file - Permissions: '.substr(sprintf('%o', fileperms($filePath)), -4);
					return false;
				}
			}else{
				return false;	
			}
		}
		// processed() | Checks if file process was successfull
		public function processed(){
			return $this->processed;
		}
		// renameFile($newName, $strict=false) | Renames current file, if $strict is true, the method will return false if invalid filename
		/*public function renameFile($newName, $strict=false){
			$original = $newName;
			$newName = preg_replace($this->invalidFileRegEx, '', $newName);
			if($newName == $origional || $strict == false){
				$this->fileName = $newName;
				return true;
			}else{
				$this->errors[] = "File rename not valid '".$this->fileName."' ".$this->validChars;
				return false;
			}
		}*/

		public function renameFile($newName, $current = null){
			if($current != null){
				$current = pathinfo($current);
				$this->fileName = $newName.".".$current["extension"];
			}else{
				$this->fileName = $newName;
			}
		}

		// setLimitToImage($bool)
		public function setLimitToImage($boolean=true){
			$this->limitToImage = $boolean;
		}
		// setValidTypes($array) | Sets allowed mime types, pass as a 1 dimentional array eg. array('application/octet-stream','application/pdf')
		public function setValidTypes($array){
			$this->validTypes = $array;
		}
		// getMsg() | Returns string of message whether it be a list of compiled errors or success
		public function getMsg($replace = "", $with = ""){
			$this->compileMsg();
			return str_replace($replace, $with, $this->wrapErrorMsg[0].$this->msg.$this->wrapErrorMsg[1]);
		}
		// checkErrors() | Returns if errors present
		public function checkErrors(){
			return (count($this->errors) > 0) ? true : false;
		}
	}
?>