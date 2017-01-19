<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Edit",
		"PassParams" => true
	));

	// Check if disabled
	if(isset($TableOptions[$_GET["param_0"]]["Status"]) && $TableOptions[$_GET["param_0"]]["Status"] == "disabled")
		die("Disabled");

	// Columns
	GetColumnsCommands($_GET["param_0"], $Columns, $ColumnCommands);
	$ColumnNames = array();
	foreach($Columns as $Item)
		$ColumnNames[] = $Item["column_name"];

	// Session saving and last search type
	if(isset($_GET["SQL"]))
	{
		$_SESSION[$_GET["param_0"]."_LASTSEARCH"] = "ADVANCED";
		$_SESSION[$_GET["param_0"]."_SQL"] = $_GET["SQL"];
		$_SESSION[$_GET["param_0"]."_DATA"] = array();
	}
	else if(isset($_POST["SQL"]))
	{
		$_SESSION[$_GET["param_0"]."_LASTSEARCH"] = "ADVANCED";
		$_SESSION[$_GET["param_0"]."_SQL"] = $_POST["SQL"];
		$_SESSION[$_GET["param_0"]."_DATA"] = $_POST["DATA"];
	}
	else if(isset($_POST["FIELD"]))
	{
		$_SESSION[$_GET["param_0"]."_LASTSEARCH"] = "SIMPLE";
		$_SESSION[$_GET["param_0"]."_FIELD"] = $_POST["FIELD"];
		$_SESSION[$_GET["param_0"]."_STYPE"] = $_POST["STYPE"];
		$_SESSION[$_GET["param_0"]."_VALUE"] = $_POST["VALUE"];
	}
	else if(!isset($_GET["param_1"]))
	{
		unset($_SESSION[$_GET["param_0"]."_LASTSEARCH"], $_SESSION[$_GET["param_0"]."_FIELD"], $_SESSION[$_GET["param_0"]."_STYPE"], $_SESSION[$_GET["param_0"]."_VALUE"]);
	}

	// Session loading
	if(isset($_SESSION[$_GET["param_0"]."_LASTSEARCH"]))
	{
		if($_SESSION[$_GET["param_0"]."_LASTSEARCH"] == "ADVANCED")
		{
			$_POST["SQL"] = $_SESSION[$_GET["param_0"]."_SQL"];
			$_POST["DATA"] = $_SESSION[$_GET["param_0"]."_DATA"];
		}
		else if($_SESSION[$_GET["param_0"]."_LASTSEARCH"] = "SIMPLE")
		{
			$_POST["FIELD"] = $_SESSION[$_GET["param_0"]."_FIELD"];
			$_POST["STYPE"] = $_SESSION[$_GET["param_0"]."_STYPE"];
			$_POST["VALUE"] = $_SESSION[$_GET["param_0"]."_VALUE"];
		}
	}

	// Build query and data
	if(isset($_POST["SQL"]))
	{
		$Query = $_POST["SQL"];
		$Data = json_decode(str_replace("'", '"', $_POST["DATA"]), true); // FIX THIS
	}
	else if(isset($_POST["FIELD"]))
	{
		if(isset($ColumnCommands[$_POST["FIELD"]]) && isset($ColumnCommands[$_POST["FIELD"]]["join"]))
			$Query = "SELECT ".$_GET["param_0"].".*,".$ColumnCommands[$_POST["FIELD"]]["join"][0].".".$ColumnCommands[$_POST["FIELD"]]["join"][1]." FROM ".$_GET["param_0"]." LEFT JOIN ".$ColumnCommands[$_POST["FIELD"]]["join"][0]." ON ".$ColumnCommands[$_POST["FIELD"]]["join"][0].".id=".$_GET["param_0"].".".$_POST["FIELD"]." WHERE ".$ColumnCommands[$_POST["FIELD"]]["join"][0].".".$ColumnCommands[$_POST["FIELD"]]["join"][1]." ".$_POST["STYPE"]." :".$_POST["FIELD"];
		else
			$Query = "SELECT * FROM ".$_GET["param_0"]." WHERE `".$_POST["FIELD"]."` ".$_POST["STYPE"]." :".$_POST["FIELD"];

		if($_POST["STYPE"] == "LIKE")
			$Data = array($_POST["FIELD"] => "%".$_POST["VALUE"]."%");
		else
			$Data = array($_POST["FIELD"] => $_POST["VALUE"]);
	}
	else
	{
		if(isset($TableOptions[$_GET["param_0"]]["DefaultQuery"]))
		{
			$Query = $TableOptions[$_GET["param_0"]]["DefaultQuery"];
			$Data = array();
		}
		else
		{
			$Query = "SELECT * FROM ".$_GET["param_0"]." ORDER BY ".((isset($TableOptions[$_GET["param_0"]]["DefaultOrder"])) ? $TableOptions[$_GET["param_0"]]["DefaultOrder"] : "id DESC")." LIMIT 100";
			$Data = array();
		}
	}

	// Fix data as array
	if(!isset($Data) || !is_array($Data))
		$Data = array();

	// Set FIELD if none set and title exists
	if(in_array("title", $ColumnNames))
		$_POST["FIELD"] = "title";

	// Illegals
	foreach(array("UPDATE", "DELETE", "INSERT") as $Item)
	{
		if(strstr($Query, $Item) != false)
			die("Illegal");
	}

	// Delete action
	if(ArrGet($_GET, "delete") != "")
	{
		$DB->Query("DELETE FROM ".$_GET["param_0"]." WHERE id=:id", array("id" => $_GET["delete"]));
		$DB->error = array();
		$PHPZevelop->Location("select/".$_GET["param_0"]);
	}
?>

<h2>Searching <?php echo ucfirst(str_replace("_", " ", $_GET["param_0"])); ?></h2>
<br />

<script type="text/javascript">
	$(document).ready(function(){
		$("#advSearchButton").click(function(){
			if(!$("#advSearch").is(":visible") && !$("#advSearch").is(':animated'))
			{
				$("#splSearch").slideUp(300);
				$("#advSearch").slideDown(300);
			}
			else if(!$("#advSearch").is(':animated'))
			{
				$("#splSearch").slideDown(300);
				$("#advSearch").slideUp(300);
			}
		});

		<?php if(isset($_SESSION[$_GET["param_0"]."_LASTSEARCH"]) && $_SESSION[$_GET["param_0"]."_LASTSEARCH"] == "ADVANCED"){ ?>
			$("#splSearch").slideUp(0);
			$("#advSearch").slideDown(0);
		<?php } ?>
	});
</script>
<div id="advSearchButton" style="color: #009ACD;">Advanced -></div>
<div id="splSearch">
<?php
	$ColumnNamesTemp = array();
	foreach($ColumnNames as $Item)
		$ColumnNamesTemp[$Item] = $Item;
 
	// SQL Form
	$FormGen = new FormGen();
	$FormGen->AddElement(array("name" => "FIELD", "value" => (isset($_POST["FIELD"])) ? $_POST["FIELD"] : "event", "type" => "select"), array("title" => "Where", "data" => $ColumnNamesTemp));
	$FormGen->AddElement(array("name" => "STYPE", "type" => "select", "value" => $_POST["STYPE"]), array("data" => array("LIKE" => "LIKE", "=" => "=", "!=" => "!=", ">=" => ">=", "<=" => "<=")));
	$FormGen->AddElement(array("name" => "VALUE", "value" => (isset($_POST["VALUE"])) ? trim($_POST["VALUE"], "%") : "", "type" => "text"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Run"));
	echo $FormGen->Build(array("ColNum" => 4));
?>
</div>
<div id="advSearch" style="display: none;">
<?php
	// SQL Form
	$FormGen = new FormGen();
	$FormGen->AddElement(array("name" => "SQL", "value" => $Query), array("title" => "SQL"));
	$FormGen->AddElement(array("name" => "DATA", "value" => json_encode($Data)), array("title" => "Data"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Run query"));
	echo $FormGen->Build(array("ColNum" => 1));
?>
</div>

<?php
	// Checks
	foreach(array(";", "DROP", "UNION", "DELETE", "REMOVE", "CREATE", "SHOW", "UPDATE", "FLUSH", "INSERT", "ALTER", "DESCRIBE") as $BannedWord)
	{
		if(strstr($Query, $BannedWord) !== false)
			$Error = "Cannot use '".$BannedWord."' in MySQL statement";
	}

	// If no error run query
	$Rows = array();
	if(!isset($Error))
	{
		$Rows = $DB->Query($Query, $Data);
		
		if(count($DB->error) > 0)
			$Error = implode(" - ", $DB->error);
	}

	if(isset($_GET["param_1"]) && $_GET["param_1"] != 1 && count($Rows) == 0)
		$PHPZevelop->Location("select/".$_GET["param_1"]."/1");
	
	$Pagination->Options["URL"] = $PHPZevelop->Path->GetPage("select/".$_GET["param_0"]."/(PN)", true);
	$Pagination->SetPage((isset($_GET["param_1"])) ? $_GET["param_1"] : 1);
	$PaginationHTML = $Pagination->BuildHTML(count($Rows));
	
	// Session
	if(isset($Query) && isset($Data))
	{
		$_SESSION["Query"] = $Query;
		$_SESSION["Data"] = $Data;
	}
	
	if(!isset($Error))
	{
		echo $PaginationHTML;
		?>
		
		<div style="float: right; display: inline-block; margin-top: 17px;">
			Total results: <?php echo count($Rows); ?>&nbsp;&nbsp;&nbsp;
			<?php $Link->Out("downloadcsv/".$_GET["param_0"], "Download CSV dataset", array("style" => "padding: 4px 10px; background: #009ACD; color: white; border: 5px; font-size: 16px;")); ?>
		</div>
		
		<div style="width: 100%; overflow: auto;">
			<table style="width: 100%; font-size: 13px;">
				<?php
					$StartedOn = $Pagination->BeginItems;
					for($I = $Pagination->BeginItems; $I < $Pagination->BeginItems + $Pagination->Options["PerPage"]; $I++)
					{
						if(!isset($Rows[$I]))
							break;
						
						$Item = $Rows[$I];
						$RowKeys = array_keys($Item);

						// Bulid header
						if($I == $StartedOn)
						{
							echo "<tr>";
							foreach(array_merge(array_keys($Item), array("", "")) as $Field)
								echo "<td style='font-weight: bold; padding: 5px; border-bottom: 1px solid #CCCCCC; word-wrap: break-word;'>".ucfirst(str_replace("_", " ", $Field))."</td>";
							echo "</tr>";
						}

						// Build row
						echo "<tr>";

						$II = -1;
						foreach($Item as $V)
						{$II++;
							
							$Style = "padding: 5px; border-bottom: 1px solid #CCCCCC; max-width: 200px; word-wrap: break-word;";

							// Manipulate value if needed
							if(isset($ColumnCommands[$RowKeys[$II]]) && isset($ColumnCommands[$RowKeys[$II]]["join"]))
							{
								$V = $DB->QuerySingle("SELECT ".$ColumnCommands[$RowKeys[$II]]["join"][1]." FROM ".$ColumnCommands[$RowKeys[$II]]["join"][0]." WHERE id=:id", array("id" => $Item[$RowKeys[$II]]));
								$V = (isset($V[$ColumnCommands[$RowKeys[$II]]["join"][1]])) ? $V[$ColumnCommands[$RowKeys[$II]]["join"][1]] : "";
							}
							
							if(strlen($V) > 60) $V = substr($V, 0, 57)."...";
							$V = strip_tags($V);

							if(ArrGet($ColumnCommands, $RowKeys[$II], "type", 0) == "image")
							{
								if(is_file($FrontEndImageLocationRoot."/".$ColumnCommands[$RowKeys[$II]]["filelocation"][0]."/".$V))
								{
									echo "<td style='".$Style." text-align: center;'>
										<a href='".($FrontEndImageLocationLocal."/".$ColumnCommands[$RowKeys[$II]]["filelocation"][0]."/".$V)."' target='_blank'>
											<img src='".($FrontEndImageLocationLocal."/".$ColumnCommands[$RowKeys[$II]]["filelocation"][0]."/".$V)."' style='height: 70px; margin: auto;' />
										</a>
									</td>";
								}
								else
									echo "<td style='".$Style." text-align: center;'></td>";									
							}
							else
							{
								if(ArrGet($ColumnCommands, $RowKeys[$II], "type", 0) == "timestamp")
									$V = date("Y-m-d G:i", $Item[$RowKeys[$II]]);

								echo "<td style='".$Style."'>".$V."</td>";
							}
						}
						
						$Options = (ArrGet($TableOptions, $_GET["param_0"], "Options")) ? explode(",", $TableOptions[$_GET["param_0"]]["Options"]) : array();
						
						// Special
						if(in_array("entries", $Options))
						{
							$Optins = count($DB->Query("SELECT id FROM comp_entries WHERE comp_id=:comp_id AND `options` LIKE :options", array("comp_id" => $Item["id"], "options" => "%optin:1%")));
							$Total = count($DB->Query("SELECT id FROM comp_entries WHERE comp_id=:comp_id", array("comp_id" => $Item["id"])));
							echo "<td style='".$Style." text-align: center;'>".$Link->Get("select/comp_entries?SQL=".urlencode("SELECT * FROM comp_entries WHERE comp_id='".$Item["id"]."' ORDER BY RAND()"), "entries (".$Optins."/".$Total.")")."</td>";
						}

						if(count($Options) == 0 || in_array("edit", $Options))
							echo "<td style='".$Style." text-align: center;'>".$Link->Get("edit/".$_GET["param_0"]."/".$Item["id"], "edit")."</td>";

						if(count($Options) == 0 || in_array("delete", $Options))
							echo "<td style='".$Style." text-align: center;'>".$Link->Get("select/".$_GET["param_0"]."/?delete=".$Item["id"], "delete", array("class" => "delete"))."</td>";

						echo "</tr>";
					}
				?>
			</table>
		</div>
<?php }else{ ?>
	<p style="text-align: center; padding-top: 20px; font-size: 26px;"><span style="color: #BD2B30;">Error:</span> <?php echo $Error; ?></p>
<?php } ?>

<div style="background: #CCCCCC; font-size: 13px;">
	<pre style="padding: 10px;">
<?php
if(isset($Query))
{
echo str_replace("  ", " ", $Query);
foreach($Data as $K => $V)
	echo "
".$K." => ".$V;
}
else
echo "Fatal error";
?>
	</pre>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("a.delete").click(function(event){
			event.preventDefault();
			var result = confirm("Are you sure you want to delete this item?");

			if(result)
				window.location = $(this).attr("href");
		});
	});
</script>