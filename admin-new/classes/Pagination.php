<?php
	class Pagination
	{
		/* Fields
		-------------------------------------*/

		// Public
		public $Options = array(
			"PerPage" => "12",
			"OverflowSides" => "3",
			"PageVar" => "(PN)",
			"URL" => "",
			"ContainerClass" => "pagination-container",
			"ButtonClass" => "pagination-button",
			"ActiveButtonClass" => "pagination-button-active",
			"DisableCSS" => false,
			"ContainerCSS" => array("display" => "block", "clear" => "both", "float" => "left", "padding" => "15px 0px", "color" => "#222222",),
			"ButtonCSS" => array("border" => "1px solid #CCCCCC", "padding" => "0px 7px", "background" => "#E6E6E6", "margin" => "0px 3px", "text-decoration" => "none", "display" => "inline-block", "border-radius" => "3px"),
			"ActiveButtonCSS" => array("background" => "#FCFCFC"),
			"HighlightedButtonCSS" => array("background" => "#FCFCFC"),
			"GapJumper" => ".."
		);

		public $TotalPages = 0;
		public $TotalItems = 0;
		public $BeginItems = 0;

		// Private
		private $HTML = "";
		private $CurrentPage = 1;

		/* METHODS
		-------------------------------------*/

		// Public
		public function __construct($options)
		{
			foreach($options as $k => $v)
				$this->Options[$k] = $v;
		}

		public function SetPage($I)
		{
			if($I == null)
				$I = 1;

			$this->CurrentPage = $I;
			$this->BeginItems = ($this->CurrentPage - 1) * $this->Options["PerPage"];
		}

		public function BuildHTML($totalItems = null)
		{
			if($totalItems != null)
				$this->TotalItems = $totalItems;

			$this->TotalPages = ceil($this->TotalItems / $this->Options["PerPage"]);

			$this->HTML .= "<div class='".$this->Options["ContainerClass"]."'>";

			if($this->Options["DisableCSS"] == false)
				$this->HTML .= $this->GetCSS();
			
			$this->HTML .= $this->GetButtons();
			$this->HTML .= "</div>";

			return $this->GetHTML();
		}

		public function GetHTML()
		{
			if($this->TotalPages < 1)
				return "";

			return $this->HTML;
		}

		public function SetCSS($Param1, $Param2, $Reset = false)
		{
			if($Reset)
				$this->Options[$Param1] = array();

			foreach($Param2 as $K => $V)
				$this->Options[$Param1][$K] = $V;
		}

		// Private
		private function GetCSS()
		{
			return "<style type='text/css'>
				.".$this->Options["ContainerClass"]." {".$this->ArrayToStyleString($this->Options["ContainerCSS"])."}
				.".$this->Options["ButtonClass"]." {".$this->ArrayToStyleString($this->Options["ButtonCSS"])."}
				.".$this->Options["ButtonClass"].":hover {".$this->ArrayToStyleString($this->Options["HighlightedButtonCSS"])."}
				.".$this->Options["ActiveButtonClass"]." {".$this->ArrayToStyleString($this->Options["ActiveButtonCSS"])."}
			</style>";
		}

		private function GetButtons()
		{
			$html = "";

			$Limits = $this->GetLimits();

			if($Limits["From"] > 1)
				$html .= "<a href='".$this->GetDecodedURL(1)."' class='".$this->Options["ButtonClass"]."'>1</a>";

			if($Limits["From"] > 1 && $this->Options["GapJumper"] != null)
				$html .= "<a href='".$this->GetDecodedURL($Limits["From"] - 1)."' class='".$this->Options["ButtonClass"]."'>".$this->Options["GapJumper"]."</a>";

			for($I = $Limits["From"]; $I <= $Limits["To"]; $I++)
			{
				if($I == $this->CurrentPage)
					$html .= "<div href='' class='".$this->Options["ButtonClass"]." ".$this->Options["ActiveButtonClass"]."'>".$I."</div>";
				else
					$html .= "<a href='".$this->GetDecodedURL($I)."' class='".$this->Options["ButtonClass"]."'>".$I."</a>";
			}

			if($Limits["To"] < $this->TotalPages && $this->Options["GapJumper"] != null)
				$html .= "<a href='".$this->GetDecodedURL($Limits["To"] + 1)."' class='".$this->Options["ButtonClass"]."'>".$this->Options["GapJumper"]."</a>";

			if($Limits["To"] < $this->TotalPages)
				$html .= "<a href='".$this->GetDecodedURL($this->TotalPages)."' class='".$this->Options["ButtonClass"]."'>".$this->TotalPages."</a>";

			return $html;
		}

		private function GetLimits()
		{
			$Limits = array("From" => 1, "To" => $this->TotalPages);

			if($this->CurrentPage <= $this->Options["OverflowSides"])
				$Limits = array("From" => 1, "To" => $this->Options["OverflowSides"] * 2 + 1);
			else if($this->CurrentPage >= $this->TotalPages - $this->Options["OverflowSides"])
				$Limits = array("From" => $this->TotalPages - $this->Options["OverflowSides"] * 2, "To" => $this->TotalPages);
			else
				$Limits = array("From" => $this->CurrentPage - $this->Options["OverflowSides"], "To" => $this->CurrentPage + $this->Options["OverflowSides"]);

			if($Limits["From"] < 1)
				$Limits["From"] = 1;

			if($Limits["To"] > $this->TotalPages)
				$Limits["To"] = $this->TotalPages;

			return $Limits;
		}

		private function GetDecodedURL($I)
		{
			return str_replace($this->Options["PageVar"], $I, $this->Options["URL"]);
		}

		private function ArrayToStyleString($Array)
		{
			if(gettype($Array) == "string")
				return $Array;

			$Tmp = "";

			foreach($Array as $K => $V)
				$Tmp[] = $K.": ".$V.";";

			return implode(" ", $Tmp);
		}
	}