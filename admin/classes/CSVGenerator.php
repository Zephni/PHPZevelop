<?php
	if(!class_exists("CSVGenerator"))
	{
		class CSVGenerator
		{
			public $FileName = "Data";
			public $Rows = array();
			public $Headings = array();
			public $TestMode = false;

			public function Generate()
			{
				if(!$this->TestMode)
					$this->Generate_CSVMode();
				else
					$this->Generate_TestMode();
			}

			private function Generate_CSVMode()
			{
				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename='.$this->FileName.'.csv');

				// Create output pointer
				$Output = fopen('php://output', 'w');

				// Create headings
				if(isset($this->Headings) && is_array($this->Headings) && count($this->Headings) > 0)
					fputcsv($Output, $this->Headings);			

				// Create rows
				foreach($this->Rows as $Item)
					fputcsv($Output, $Item);
			}

			private function Generate_TestMode()
			{
				echo "<table style='width: 100%;'>";
				
				// Create headings
				if(isset($this->Headings) && is_array($this->Headings) && count($this->Headings) > 0)
				{
					echo "<tr>";
					foreach($this->Headings as $Item)
						echo "<td style='font-weight: bold;'>".$Item."</td>";
					echo "</tr>";
				}

				foreach($this->Rows as $Item)
				{
					foreach($Item as $K => $V)
						$Item[$K] = htmlentities($V);

					echo "<tr><td>".implode("</td><td>", $Item)."</td></tr>";
				}

				echo "</table>";
			}
		}
	}