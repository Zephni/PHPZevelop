<?php
	/*
		Pagination class
		Author: Craig Dennis
		Version: 0.3
		Last edit: 04/12/2014
	*/
	class Pagination
	{
		public $PerPage;
		public $TotalItems;
		public $BeginItems;
		public $EndItems;
		public $TotalPages;
		public $MaxPages;
		public $Variables;
		public $CurrentPage;
		public $Options;
		public $ForceLink = null;

		private $HTML;

		public function __construct($_perPage = 10, $_maxPages = 10, $_options = array())
		{
			$this->Options = array(
				"link_prepend" => (isset($_options["link_prepend"])) ? $_options["link_prepend"] : "",
				"cur_page_var" => (isset($_options["cur_page_var"])) ? $_options["curpage_var"] : "p",
				"page_name" => (isset($_options["page_name"])) ? $_options["page_name"] : "",
				"first_page_text" => (isset($_options["first_page_text"])) ? $_options["first_page_text"] : "first",
				"last_page_text" => (isset($_options["last_page_text"])) ? $_options["last_page_text"] : "last",
				"show_first_last" => (isset($_options["show_first_last"])) ? $_options["show_first_last"] : true,
				"insert_page_num" => (isset($_options["insert_page_num"])) ? $_options["insert_page_num"] : "*string*",
				"append_vars" => (isset($_options["append_vars"])) ? $_options["append_vars"] : ""
			);

			$this->MaxPages = $_maxPages;
			$this->PerPage = $_perPage;
			$this->CalculatePages();

			$this->SetPage(1);

			$this->BuildVariablesString();
		}

		public function AddVariable($key, $value)
		{
			$this->Options["append_vars"] .= "&".$key."=".$value;
		}

		public function BuildVariablesString()
		{
			$this->Variables = "?".$this->Options["cur_page_var"]."=".$this->Options["insert_page_num"].$this->Options["append_vars"];
		}

		public function GetLink($_pageNumber = null){
			if($this->ForceLink == null)
				$mainLink = $this->Options["link_prepend"].$this->Options["page_name"].$this->Variables;
			else
				$mainLink = $this->ForceLink;

			if($_pageNumber != null)
				return str_replace($this->Options["insert_page_num"], (string)$_pageNumber, $mainLink);
			else
				return str_replace($this->Options["insert_page_num"], (string)$this->CurrentPage, $mainLink);
		}

		public function SetTotalItems($_totalItems = null)
		{
			if(isset($_totalItems) && $_totalItems > 0)
				$this->TotalItems = $_totalItems;

			$this->CalculatePages();
			$this->SetBeginEndItems();
		}		

		public function SetPage($_page = null)
		{
			if(isset($_page) && $_page > 0)
				$this->CurrentPage = $_page;

			$this->SetBeginEndItems();
		}

		public function SetBeginEndItems()
		{
			$this->BeginItems = ($this->CurrentPage * $this->PerPage) - $this->PerPage;
			$this->EndItems = $this->CurrentPage * $this->PerPage;
		}

		public function CalculatePages()
		{
			$this->TotalPages = ceil($this->TotalItems / $this->PerPage);
		}

		public function GenerateHTML()
		{
			$this->CalculatePages();
			$this->BuildVariablesString();
			$this->SetBeginEndItems();
			
			$this->HTML = "";
			$this->HTML .= "<div class='pagination'>";

			if($this->TotalPages > 1 && $this->Options["show_first_last"])
			{
				$tag = ($this->CurrentPage == 1) ? "div" : "a";
				$this->HTML .= "<".$tag." href='".$this->GetLink(1)."' class='item'>".$this->Options["first_page_text"]."</".$tag.">";
			}
				
			for($i = 0; $i < $this->TotalPages; $i++)
			{
				if($i+1 > $this->MaxPages)
					break;

				$selectedInj = ($this->CurrentPage == $i+1) ? "selected" : "";
				$this->HTML .= "<a href='".$this->GetLink($i+1)."' class='item ".$selectedInj."'>";
				$this->HTML .= (string)($i+1);
				$this->HTML .= "</a>";
			}

			if($this->TotalPages > 1 && $this->Options["show_first_last"])
			{
				$tag = ($this->CurrentPage == $this->TotalPages) ? "div" : "a";
				$this->HTML .= "<".$tag." href='".$this->GetLink($this->TotalPages)."' class='item'>".$this->Options["last_page_text"]."</".$tag.">";
			}

			$this->HTML .= "</div>";

			return $this->HTML;
		}
	}
?>