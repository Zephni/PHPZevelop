<?php
	class FilterForm
	{
		public $Options;
		public $CSS;

		public function __construct($_Options = array(), $CSS = array())
		{
			$this->Options = (object)array_merge(array(
				"FormName"					=> "FilterForm",
				"ElementID"		 			=> "FilterForm",
				"AddFilterButtonClass"		=> "AddFilterButton",
				"RemoveFilterButtonClass"	=> "RemoveFilterButton",
				"FilterFormSetClass"		=> "FilterFormSet",
				"AddFilterText"				=> "+ Add filter",
				"SubmitText"				=> "Filter results",
				"Fields"					=> array("title", "content", "id")
			), $_Options);

			$this->CSS = array(
				".AddFilterButton"			=> array("color" => "#428BCA", "display" => "inline-block"),
				".RemoveFilterButton"		=> array("color" => "red"),
				".FilterFormSet *"			=> array("padding" => "4px", "font-size" => "13px", "display" => "inline-block"),
				".FilterFormSet .linker"	=> array("border" => "none !important"),
				".RemoveFilterButton:hover"	=> array("cursor" => "pointer"),
				".AddFilterButton:hover"	=> array("cursor" => "pointer"),
				"input[type='submit']:hover"=> array("cursor" => "pointer")
			);

			foreach($CSS as $K => $V)
			{
				if(isset($this->CSS[$K]))
					foreach($V as $K2 => $V2) $this->CSS[$K][$K2] = $V2;
				else
					$this->CSS[$K] = $V;
			}
		}

		public function DataToMySQLWhere($Data)
		{
			$MySQL = array();

			$I = 1; while(isset($Data["field".$I])){
				if($I > 1) $MySQL[] = $Data["linker".$I];
				$MySQL[] = array($Data["field".$I], $Data["comparison".$I], (strstr($Data["comparison".$I], "LIKE") != false && strstr($Data["value".$I], "%") == false) ? "%".$Data["value".$I]."%" : $Data["value".$I]);
			$I++;}

			return $MySQL;
		}

		public function BuildHeaderHTML()
		{
			ob_start(); ?>
				<style type="text/css">
					<?php 
						foreach($this->CSS as $K => $V)
						{
							echo $K." {"; foreach($V as $Attribute => $Value) echo $Attribute.": ".$Value.";"; echo "}\n";
						}
					?>
				</style>

				<script type="text/javascript">
					$(document).ready(function(){
						$("form[name='<?php echo $this->Options->FormName; ?>'] input[type='submit']").hide();

						<?php
							if(count($_POST) > 0)
							{
								$I = 1; while(isset($_POST["field".$I])){
									echo 'AddFilterSet("'.(isset($_POST["linker".$I]) ? $_POST["linker".$I] : "").'", "'.$_POST["field".$I].'", "'.$_POST["comparison".$I].'", "'.$_POST["value".$I].'");';
								$I++;}
							}
						?>

						$(".<?php echo $this->Options->AddFilterButtonClass; ?>").click(function(){
							AddFilterSet();
						});

						function AddFilterSet(v1 = null, v2 = null, v3 = null, v4 = null)
						{
							var formElements = $("<div class='<?php echo $this->Options->FilterFormSetClass; ?>'>");
							formElements.css({"margin-bottom":"6px"});

							var linker = $("<input value='WHERE' style='border: none; box-shadow: none; background: none !important;' disabled='disabled'>");
							if($("form[name='<?php echo $this->Options->FormName; ?>'] select[name='field1']").length > 0)
								linker = $("<select class='linker'>").append("<option>AND</option><option>OR</option>");
							if(v1 != null && v1 != "") linker.val(v1);

							var field = $("<select class='field'>");
							<?php
								foreach($this->Options->Fields as $Item)
									echo 'field.append("<option>'.$Item.'</option>");';
							?>
							if(v2 != null) field.val(v2);

							var comparison = $("<select class='comparison'>");
							comparison.append("<option>LIKE</option><option>=</option><option>!=</option><option>&gt;</option><option>&lt;</option><option>NOT LIKE</option>");
							if(v3 != null) comparison.val(v3);

							var value = $("<input class='value'>");
							if(v4 != null) value.val(v4);

							var remove = $("<span class='<?php echo $this->Options->RemoveFilterButtonClass; ?>'>Remove</span>");

							formElements.append(linker, field, comparison, value, remove);

							$("#<?php echo $this->Options->ElementID; ?>").append(formElements);
							$(".<?php echo $this->Options->AddFilterButtonClass; ?>").appendTo("#<?php echo $this->Options->ElementID; ?>");

							SortFilterSets();
						}

						function SortFilterSets()
						{
							var I = 0;
							$("#<?php echo $this->Options->ElementID; ?> .<?php echo $this->Options->FilterFormSetClass; ?>").each(function(){
								I++;
								$(this).attr("setfilterid", I);
								$(this).find("span").attr("filterid", I);
								$(this).find("select.linker").attr("name", "linker"+I);
								$(this).find("select.field").attr("name", "field"+I);
								$(this).find("select.comparison").attr("name", "comparison"+I);
								$(this).find("input.value").attr("name", "value"+I);
							});

							if($("form[name='<?php echo $this->Options->FormName; ?>'] select[name='linker1']").length > 0)
								$("form[name='<?php echo $this->Options->FormName; ?>'] select[name='linker1']").remove();

							if(I == 0)
								$("form[name='<?php echo $this->Options->FormName; ?>'] input[type='submit']").fadeOut(500);
							else
								$("form[name='<?php echo $this->Options->FormName; ?>'] input[type='submit']").fadeIn(500);

							$(".<?php echo $this->Options->RemoveFilterButtonClass; ?>").click(function(){
								$(this).parent().remove();
								SortFilterSets();
							});
						}
					});
				</script>
			<?php
			$HTML = ob_get_clean();

			return $HTML;
		}

		public function BuildHTML()
		{
			$HTML = array();

			$HTML[] =	"<form method='post' action='/admin343872/select/".$_SESSION["admin_current_table"]."/?page=1' name='".$this->Options->FormName."'>";
			$HTML[] =	"	<div id='".$this->Options->ElementID."'>";
			$HTML[] =	"		<p class='".$this->Options->AddFilterButtonClass."'>".$this->Options->AddFilterText."</p>";
			$HTML[] =	"	</div>";
			$HTML[] =	"	<input type='submit' value='".$this->Options->SubmitText."' />";
			$HTML[] =	"</form>";

			echo implode("\n", $HTML);
		}
	}

	if(false){
		$FilterForm = new FilterForm();
		?>
		<html>
			<head>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<?php
					echo $FilterForm->BuildHeaderHTML();
				?>		
			</head>
			<body>
				<?php
					if(count($_POST) > 0)
						echo "<pre>".print_r($FilterForm->DataToMySQLWhere($_POST), true)."</pre>";

					echo $FilterForm->BuildHTML();
				?>
			</body>
		</html>
	<?php
	}
	?>