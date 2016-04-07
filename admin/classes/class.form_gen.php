<?php
	class formGen {
		public $elements = array();
		public $formAttributes = array();
		//public $availableFormAttributes = array("name", "action", "method", "enctype", "class");
		//public $availableElements = array("text", "textarea", "file", "checkbox", "tel", "email", "select", "date", "datetime", "color", "submit");
		//public $availableAttributes = array("name", "required", "class", "value", "placeholder", "checked", "disabled", "style", "maxlength");
		public $html = "";
		public $captcha = "";
		public $totalElements = 0;
		public $keepValues = true;

		private $USERDATA = array();

		/* CONSTRUCT
		------------------------------------*/
		public function __construct(){
			
		}

		/* editData((array)$data)
		------------------------------------*/
		public function editData($data){
			$this->USERDATA = $data;
		}

		/* setFormAttributes((array)$data)
		------------------------------------*/
		public function setFormAttributes($data){
			$this->formAttributes = $data;

			if(isset($data["method"]) && count($this->USERDATA) < 1){
				if(strtolower($data["method"]) == "post")
					$this->USERDATA = $_POST;
				elseif(strtolower($data["method"]) == "get")
					$this->USERDATA = $_GET;
			}
		}

		/* getFormAttributes()
		------------------------------------*/
		public function getFormAttributes(){
			$ret = "";

			foreach($this->formAttributes as $k => $v){
				//if(in_array($k, $this->availableFormAttributes))
					$ret .= $k."='".$v."' ";
			}

			return $ret;
		}

		/* setElements((array)$data)
		------------------------------------*/
		public function setElements($data){

		}

		/* addElement((array)$data)
		------------------------------------*/
		public function addElement($text, $type, $data, $param1 = null, $param2 = null){
			$ret = true;

			// Check element type is available
			//if(!in_array($type, $this->availableElements))
			//	$ret = false;

			// Check all attributes are available
			foreach($data as $k => $v){
				//if(!in_array($k, $this->availableAttributes))
				//	$ret = false;
			}

			// If available then add the element to the elements array			
			if($ret != false){
				$this->elements[] = array($text, $type, $data, $param1, $param2);
				$this->totalElements++;
			}

			return $ret;
		}

		public function setCaptcha($html){
			$this->captcha = $html;
		}

		/* generateForm()
		------------------------------------*/
		public function generateForm($returnType = false){
			$this->html = "";
			
			$this->html .= "<form ".$this->getFormAttributes().">";
			$this->html .= "	<table>";

			$i = 0;
			foreach($this->elements as $v){
				$i++;
				if($i == $this->totalElements && strlen($this->captcha) > 0){ // Inject captcha if exists
					$this->html .= "<tr><td></td><td>".$this->captcha."</td></tr>";
				}
				$this->html .= $this->elementHTML($v[0], $v[1], $v[2], $v[3], $v[4]);
			}
			
			$this->html .= "	</table>";
			$this->html .= "</form>";

			if($returnType == true)
				return $this->html;
			else
				echo $this->html;
		}

		/* addFormElement()
		------------------------------------*/
		private function elementHTML($text, $type, $data, $param1 = null, $param2 = null){
			if(isset($data["name"])){
				if($this->keepValues && isset($this->USERDATA[$data["name"]])){
					$data["value"] = $this->USERDATA[$data["name"]];
					if($type == "checkbox" && $this->USERDATA[$data["name"]] != "0")
						$data["checked"] = "checked";
					if($type == "select")
						$param2 = $data["value"];
				}
			}
			

			// Remove check if it has been set as the default state, but user data differs
			if(isset($data["name"])){
				if($this->keepValues && !isset($this->USERDATA[$data["name"]]) && count($this->USERDATA) > 0
					|| $this->keepValues && isset($this->USERDATA[$data["name"]]) && $this->USERDATA[$data["name"]] == "0")
					unset($data["checked"]);
			}
				
			$ret = "";
			$ret .= "<tr>";

			$ret .= "<td>".$text."</td>";			
			$ret .= "<td>";

			// TEXTAREA
			if($type == "textarea"){
				if(isset($data["value"])){
					$value = $data["value"];
					unset($data["value"]);
				}else{
					$value = "";
				}
				$ret .= "<textarea ".$this->attributesHTML($data).">".htmlentities($value)."</textarea> ".$param1;

			// SELECT
			}elseif($type == "select" && gettype($param1) == "array"){
				$ret .= "<select ".$this->attributesHTML($data).">";
				
				foreach($param1 as $k => $v){
					if(gettype($k) == "int")
						$k = $v;

					$injSelect = ($k == $param2 && $param2 != "") ? "selected='selected'" : "";
					$ret .= "<option ".$injSelect." value='".$k."'>".$v."</option>";
				}
				
				$ret .= "</select>";

			// CHECKBOX
			}elseif($type == "checkbox"){
				$data["value"] = "1";
				$ret .= "<input type='".$type."' ".$this->attributesHTML($data)." > ".$param1;

			// OTHER
			}else{
				$ret .= "<input type='".$type."' ".$this->attributesHTML($data)." > ".$param1;
			}

			$ret .= "</td>";

			$ret .= "</tr>";
			return $ret;
		}

		/* injectAttributes((array)$data)
		------------------------------------*/
		private function attributesHTML($data){
			$ret = "";
			foreach($data as $k => $v){
				$v = str_replace("Â£", "£", $v);
				$v = str_replace("â€“", "€", $v);
				$ret .= $k."=\"".$v."\" ";
			}
			return $ret;
		}
	}
?>