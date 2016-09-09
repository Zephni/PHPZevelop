<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Edit",
		"PassParams" => true
	));

	if(isset($User) && !$User->LoggedIn())
		$PHPZevelop->Location("login");

	// Check if disabled
	if(isset($TableOptions[$_GET["param_0"]]["Status"]) && $TableOptions[$_GET["param_0"]]["Status"] == "disabled")
		die("Disabled");

	// Defaults
	foreach(array("Fields" => "*", "Where" => "", "Order" => "id DESC", "Limit" => "0,40") as $K => $V)
	{
		if(!isset($_POST[$K]))
		{
			if(isset($TableOptions[$_GET["param_0"]]["Default".$K]))
			{
				$_POST[$K] = $TableOptions[$_GET["param_0"]]["Default".$K];
			}
			else
			{
				if(isset($_SESSION[$_GET["param_0"]."_select_".$K]))
					$_POST[$K] = $_SESSION[$_GET["param_0"]."_select_".$K];
				else
					$_POST[$K] = $V;
			}

			if(isset($_GET[$K])) $_POST[$K] = $_GET[$K];

			$_POST[$K] = str_replace("ORDER BY ", "", $_POST[$K]);
		}

		$_SESSION[$_GET["param_0"]."_select_".$K] = $_POST[$K];
	}

	// Columns
	GetColumnsCommands($_GET["param_0"], $Columns, $ColumnCommands);
	$ColumnNames = array();
	foreach($Columns as $Item)
		$ColumnNames[] = $Item["column_name"];

	// Delete action
	if(ArrGet($_GET, "delete") != "")
	{
		$DB->Query("DELETE FROM ".$_GET["param_0"]." WHERE id=:id", array("id" => $_GET["delete"]));
		$DB->error = array();
	}
?>

<h2>Searching <?php echo ucfirst(str_replace("_", " ", $_GET["param_0"])); ?></h2>
<br />

<?php
	// SQL Form
	$FormGen = new FormGen();
	$FormGen->AddElement(array("name" => "Fields", "value" => $_POST["Fields"]), array("title" => "Fields"));
	$FormGen->AddElement(array("name" => "Where", "value" => $_POST["Where"], "placeholder" => "id=27 AND key/value"), array("title" => "Where"));
	$FormGen->AddElement(array("name" => "Order", "value" => $_POST["Order"]), array("title" => "Order"));
	$FormGen->AddElement(array("name" => "Limit", "value" => $_POST["Limit"]), array("title" => "Limit"));
	$FormGen->AddElement(array("type" => "submit", "value" => "Run query"));
	echo $FormGen->Build(array("ColNum" => 5));
?>

<?php
	// Checks
	foreach(array("Fields", "Where", "Order") as $Key)
	{
		if($Key == "Fields" && strlen($_POST["Fields"]) == 0)
			$Error = "Fields is empty, try *";
		else
		{
			foreach(array(";", "FROM", "DROP", "UNION", "DELETE", "REMOVE", "CREATE", "SHOW", "UPDATE", "FLUSH", "INSERT", "ALTER", "DESCRIBE", "LIMIT") as $BannedWord)
			{
				if(strstr(strtolower($_POST[$Key]), strtolower($BannedWord)) != false)
					$Error = "Cannot use '".$BannedWord."' in MySQL statement";
			}
		}
	}

	// Build FinalWhere statement and data array
	$FinalWhere = "";
	$Data = array();

	if(strlen(trim($_POST["Where"])) > 0)
	{
		$Parts = explode(" ", $_POST["Where"]);
		foreach($Parts as $Item)
		{
			if($Item == "AND" || $Item == "OR")
			{
				$FinalWhere .= " ".$Item;
				continue;
			}

			$Item2 = explode("=", $Item);
			if(count($Item2) == 2)
			{
				$FinalWhere .= " ".$Item2[0]."=:".$Item2[0];
				$Data[$Item2[0]] = urldecode($Item2[1]);
			}
			elseif(count($Item2) == 1)
			{
				$Item2 = explode("/", $Item);
				if(count($Item2)  == 2)
				{
					$FinalWhere .= " ".$Item2[0]." LIKE :".$Item2[0];
					$Data[$Item2[0]] = "%".urldecode($Item2[1])."%";
				}
				else
					$Error = "Invalid 'Where' statement";	
			}
			else
				$Error = "Invalid 'Where' statement";
		}
	}
	
	if($_POST["Fields"] != "*" && substr($_POST["Fields"], 0, 2) != "id")
		$_POST["Fields"] = "id,".$_POST["Fields"];
	if(count(explode(",", $_POST["Limit"])) == 2)
		$_POST["Limit"] = " LIMIT ".$_POST["Limit"];
	else
		$Errors[] = "Invalid 'Limit; statement";

	if(count($Data) > 0)
		$FinalWhere = "WHERE ".$FinalWhere;
	else
		$FinalWhere = "";

	$Rows = array();

	// If no error run query
	if(!isset($Error))
	{
		$_POST["Order"] = "ORDER BY ".$_POST["Order"]." ";
		$Query = "SELECT ".$_POST["Fields"]." FROM ".$_GET["param_0"]." ".$FinalWhere." ".$_POST["Order"].$_POST["Limit"];
		$Rows = $DB->Query($Query, $Data);

		if(count($DB->error) > 0)
			$Error = implode(" - ", $DB->error);
	}

	if(isset($_GET["param_1"]) && $_GET["param_1"] != 1 && count($Rows) == 0)
		$PHPZevelop->Location("select/".$_GET["param_1"]."/1");

	$Pagination->Options["URL"] = "/".ltrim($PHPZevelop->CFG->SiteDirLocal."/select/".$_GET["param_0"]."/(PN)", "/");
	$Pagination->SetPage((isset($_GET["param_1"])) ? $_GET["param_1"] : 1);
	$PaginationHTML = $Pagination->BuildHTML(count($Rows));

	// Session
	$_SESSION["Query"] = $Query;
	$_SESSION["Data"] = $Data;

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
								if(isset($V[$ColumnCommands[$RowKeys[$II]]["join"][1]]))
									$V = $V[$ColumnCommands[$RowKeys[$II]]["join"][1]];
								else
									$V = "";
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
							echo "<td style='".$Style." text-align: center;'>".$Link->Get("select/comp_entries?Where=comp_id%3D".$Item["id"]."&Order=ORDER%20BY%20RAND()&Limit=", "entries (".$Optins."/".$Total.")")."</td>";
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