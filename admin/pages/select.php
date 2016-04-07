<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Select",
		"PassParams" => true
	));

	if(isset($_GET["param_0"]))
		$_GET["tbl"] = $_GET["param_0"];

	$tableCfgPath = $PHPZevelop->CFG->SiteDirRoot."/config/".$_GET["tbl"].".php"; // Config path

	/* Pagination
	------------------------------*/
	$Pagination = new Pagination(array(
		"PerPage" => 20,
		"URL" => $PHPZevelop->Path->GetPage("select/".$_GET["tbl"]."?curpage=(PN)&".URLHelper::Array2URL($_GET, "&", array("curpage", "tbl", "param_0")), true)
	));

	$Pagination->SetCSS("ContainerCSS", array("display" => "inline-block", "width" => "auto"));
	$Pagination->SetCSS("ActiveButtonCSS", array("background" => "#00688B", "color" => "#FFFFFF"));
	$Pagination->SetCSS("HighlightedButtonCSS", array("background" => "#00688B", "color" => "#FFFFFF"));

	$Pagination->SetPage($_GET["curpage"]);

	/* Table config
	------------------------------*/
	if(file_exists($tableCfgPath))
	{
		require($tableCfgPath);

		if(!$table)
			$table = $_GET["tbl"];

		if(isset($_GET["action"]) && $_GET["action"] == "delete" && in_array("delete", $rowOptions))
			$DB->QuerySingle("DELETE FROM ".$table." WHERE id=:id", array("id"=>$_GET["id"]));

		// WHERE
		$injWhere = "WHERE ";
		if(isset($_GET["filter"]) && strlen($_GET["filter"]) > 0)
		{
			$field = $_GET["field"];
			$tbl = $_GET["tbl"];

			if(array_key_exists($_GET["field"], $joinTableFields))
			{
				$tbl = $joinTableFields[$_GET["field"]]["table"];
				$field = $joinTableFields[$_GET["field"]]["field"];
			}

			$injWhere .= $tbl.".".$field." ".$_GET["comp"]." :".$field." OR ";

			if($_GET["comp"] == "LIKE")
				$SQLarray[$field] = "%".$_GET["filter"]."%";
			else
				$SQLarray[$field] = $_GET["filter"];

			$injWhere = substr($injWhere, 0, strlen($injWhere) - 4);

			unset($tbl);
		}
		
		if(isset($forceFilter) && count((array)$forceFilter) > 0)
		{
			foreach($forceFilter as $item)
			{
				if(strpos($item["field"], '.') !== false)
				{
					$items = explode(".", $item["field"]);
					$items[0] = "jointbl1";
					$field = implode("_", $items);
				}
				
				if($injWhere != "WHERE ")
					$injWhere .= " AND ";
				
				$comparison = (isset($item["comparison"])) ? $item["comparison"] : "=";
				$injWhere .= $item["field"]." ".$comparison." :".$field;

				if(strtolower(trim($comparison)) == "like")
					$SQLarray[$field] = "%".$item["value"]."%";
				else
					$SQLarray[$field] = $item["value"];
			}
		}

		if($injWhere == "WHERE ") // Reset if nothing appended
			$injWhere = "";

		// JOINS
		if(isset($joinTableFields) && count((array)$joinTableFields) > 0)
		{
			$injJoinFields = "";
			$injJoinSQL = "";

			$i = 0;
			foreach($joinTableFields as $key => $item)
			{
				$i++;
				$joinTbl = "jointbl".$i;
				$injJoinFields .= ",".$joinTbl.".id AS '_join_ID',".$joinTbl.".".$item["field"]." AS '_join_Field'";
				$injJoinSQL .= " LEFT JOIN ".$item["table"]." AS ".$joinTbl." ON ".$table.".".$key."=".$joinTbl.".id";

				// Temp
				$injWhere = str_replace($item["table"], $joinTbl, $injWhere);
			}
		}

		// ORDER
		if(isset($_GET["ordby"]) && strlen($_GET["ordby"]))
			$injOrder = " ORDER BY ".$DB->Quote($_GET["ordby"])." ".$DB->Quote($_GET["ordsort"]);
		else
			$injOrder = "ORDER BY ".$table.".id DESC";

		if(!isset($SQLarray)) $SQLarray = array();
		if(!isset($injJoinFields)) $injJoinFields = "";
		if(!isset($injJoinSQL)) $injJoinSQL = "";

		$querySQL = "SELECT ".$table.".*".$injJoinFields." FROM ".$table." ".$injJoinSQL." ".$injWhere." ".$injOrder;

		$Limit = ($_GET["limit"] != "No limit") ? "LIMIT 80" : "";
		$Pagination->BuildHTML(count($DB->Query($querySQL." ".$Limit, $SQLarray)));

		$DB->Data["tbl"] = $DB->Query($querySQL." LIMIT ".$Pagination->BeginItems.",".$Pagination->Options["PerPage"], $SQLarray);

		//die($querySQL.print_r($SQLarray));

		// Remove join fields if exists
		$temp = array();

		foreach($DB->Data["tbl"] as $item)
		{
			unset($item["_join_ID"]);
			unset($item["_join_Field"]);
			$temp[] = $item;
		}

		$DB->Data["tbl"] = $temp;

		

		if(isset($_GET['filter']))
			$injP = "&filter=".$_GET['filter'];

		$available = true;
	}
	else
	{
		$available = false;
	}

	$DB->GetColumnNames($_GET["tbl"]);	
?>

<div id="pageContent">
	<h1><a href="?p=select&amp;tbl=<?php echo $table; ?>">Browsing "<?php if(isset($title)) echo $title; else echo $_GET["tbl"]; ?>"</a></h1>

	<?php echo $constMsg; ?>

	<form action="" method="get" class='mainForm' style="padding-bottom: 10px;">
		<select name="field" style="width: 100px;"><?php
			foreach($DB->Fields as $Item)
				echo "<option ".(($_GET["field"] == $Item) ? "selected='selected'" : "").">".$Item."</option>";
		?></select>
		
		<select name="comp" style="width: 60px;"><?php
			foreach(array("LIKE", "=", "!=", ">", "<", ">=", "<=") as $Item)
				echo "<option ".(($_GET["comp"] == $Item) ? "selected='selected'" : "").">".$Item."</option>";
		?></select>
		
		<input type="text" name="filter" style="width: 155px;" value="<?php if(isset($_GET["filter"])) echo $_GET["filter"]; ?>" />
		
		<div style="display: inline-block;">order by</div>
		<select name="ordby" style="width: 100px;"><?php
			foreach($DB->Fields as $Item)
				echo "<option ".(($_GET["ordby"] == $Item) ? "selected='selected'" : "").">".$Item."</option>";
		?></select>

		<select name="ordsort" style="width: 100px;"><?php
			foreach(array("DESC", "ASC") as $Item)
				echo "<option ".(($_GET["ordsort"] == $Item) ? "selected='selected'" : "").">".$Item."</option>";
		?></select>

		<select name="limit" style="width: 100px;"><?php $LimitOptions = array("true" => "Limit", "false" => "No limit");
			foreach($LimitOptions as $K => $V)
				echo "<option ".(($_GET["limit"] == $LimitOptions[$K]) ? "selected='selected'" : "").">".$V."</option>";
		?></select>

		<input type='submit' value='Search' style='width: 100px;' />
	</form>

	<?php
		if($available)
		{
			echo $Pagination->GetHTML();

			?>

			<p style="margin: 14px 0px 0px 0px; font-size: 14px; padding-top: 5px; text-align: right;"><?php
				echo "Viewing: ".($Pagination->BeginItems +1)." - ".((($Pagination->BeginItems + $Pagination->Options["PerPage"]) < $Pagination->TotalItems) ? ($Pagination->BeginItems + $Pagination->Options["PerPage"]) : $Pagination->TotalItems);
				echo " of ".$Pagination->TotalItems;
			?></p>

			<?php

			echo "<table class='tableList'>";
			echo "<thead>";
			
			foreach($DB->Fields as $item)
			{
				if(!in_array($item, (array)$removeFields))
					echo "<td>".$item."</td>";
			}
			
			// Row options
			if(in_array("comp_entries", $rowOptions)) echo "<td style='width: 100px;'>entries</td>";
			if(in_array("download_entries", $rowOptions)) echo "<td style='width: 100px;'>download</td>";
			if(in_array("edit", $rowOptions)) echo "<td style='width: 50px;'>&nbsp;</td>";
			if(in_array("delete", $rowOptions)) echo "<td style='width: 50px;'>&nbsp;</td>";
			
			echo "</thead>";
			
			$row = "1";
			foreach($DB->Data["tbl"] as $item)
			{
				echo "<tr href='".$PHPZevelop->Path->GetPage("edit/".$_GET["tbl"]."/".$item["id"], true)."' class='row".(($row == "1") ? "2" : "1")."'>";
				
				foreach($item as $k => $v)
				{
					if(!in_array($k, (array)$removeFields))
					{
						if(isset($joinTableFields))
						{
							if(array_key_exists($k, $joinTableFields))
							{
								$temp = $DB->QuerySingle("SELECT ".$joinTableFields[$k]["field"]." FROM ".$joinTableFields[$k]["table"]." WHERE id='".$v."'");
								$v = $temp[$joinTableFields[$k]["field"]];
							}
						}

						// Check wether timestamp
						if(isValidTimeStamp($v))
							$v = date("d-m-Y h:ia", $v);
						
						// Substr > 100
						if(strlen($v) > 100)
							$v = substr($v, 0, 100)."..";

						// HTML entities
						$v = strip_tags($v);
						
						// Replace characters
						$v = str_replace("Â", "", $v);

						if(isset($showImageFields))
						{
							if(in_array($k, $showImageFields)){
								$v = "<img src='".str_replace("../", "/", $fileUploadLocations[$k].$v)."' style='width: 150px; display: inline-block; margin: 0px; padding: 0px; vertical-align: bottom;' />";
								$applyTDStyle = "style='width: 150px;'";
							}else{
								$applyTDStyle = "";
							}
						}

						if(!isset($applyTDStyle))
							$applyTDStyle = "";

						echo "<td ".$applyTDStyle.">".$v."</td>";
					}
				}

				// Row options
				$totalEntries = $DB->Query("SELECT id FROM comp_entries WHERE comp_id=:comp_id", array("comp_id" => $item["id"]));
				$optinEntries = $DB->Query("SELECT id FROM comp_entries WHERE comp_id=:comp_id AND options LIKE :options", array("comp_id" => $item["id"], "options" => "%, 1, %"));

				if(in_array("comp_entries", $rowOptions)) echo "<td style='text-align: center;'>".count($optinEntries)." / ".count($totalEntries)."</td>";
				if(in_array("download_entries", $rowOptions)) echo "<td style='text-align: center;'><a href='".$PHPZevelop->Path->GetPage("download-csv/comp_entries/comp_id/=/".$item["id"], true)."'>download</a></td>";
				if(in_array("edit", $rowOptions)) echo "<td style='text-align: center;'><a href='".$PHPZevelop->Path->GetPage("edit/".$_GET["tbl"]."/".$item["id"], true)."'>edit</a></td>";
				if(in_array("delete", $rowOptions)) echo "<td style='text-align: center;'><a href='".$PHPZevelop->Path->GetPage("select/".$_GET["tbl"]."?action=delete&id=".$item["id"], true)."' class='delete'>delete</a></td>";

				echo"</tr>";
			}

			echo "</table>";

			if(count($DB->Data["tbl"]) < 1)
				echo "<p style='text-align: center; margin-top: 15px; margin-bottom: 15px;'>No rows exist in this table or filter, please expand your search.</p>";

		}
		else
		{
			if(!file_exists($tableCfgPath))
				echo "Cannot find config file for this table";

			if(isset($DB->Data["tbl"]))
				echo "<br /><br />Error with query: ".$DB->ErrorHandler();
		}

		// SQL text
		echo "<div style='font-size: 12px; background: #CCCCCC; padding: 10px;'>".$querySQL."</div>";

		// $_GET description
		$get = $_GET; unset($get["p"]); unset($get["tbl"]); unset($get["s"]); unset($get["curpage"]);
		echo "<div style='font-size: 12px; background: #CCCCCC; padding: 10px; margin-bottom: 10px;'>";
		var_dump($SQLarray); echo "<br />";
		$strArr = array();
		
		if(count($get) > 0)
		{
			echo "<br />";
			foreach($get as $k => $v)
				if(strlen($v) > 0)
					$strArr[] = $k." = ".$v;
		}
		
		echo implode("<br />", $strArr);

		echo "</div>";
	?>
</div>

<style type="text/css">
	tr[href]:hover {cursor: pointer;}
</style>

<script>
	$(document).ready(function(){
		$("tr[href]" ).hover(function(){
			$(this).find("td").css({"background":"#E5E5FF"});
			$(this).find("td").attr("oldbg", $(this).css("background"));
		}, function(){
			$(this).find("td").css({"background":$(this).find("td").attr("oldbg")});
		});

		$("tr[href]" ).one("click", function(){
			window.location.href = $(this).attr("href");
		});

		$(".delete").click(function(){
			link = $(".delete").attr("href");
		    if(confirm("Are you sure you want to delete this item?")){
		    	//alert(link);
		    	window.location.href(link);
		    }
		    return false;
		});
	});
</script>