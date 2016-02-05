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

	/* Pagination */
	$perpage = 20;
	if(isset($_GET['s'])){
		$pstart = $_GET['s'];
	}else{
		$pstart = 0;
		$_GET['curpage'] = 1;
	}

	if(file_exists($tableCfgPath)){
		require($tableCfgPath);

		if(!$table) $table = $_GET["tbl"];

		if(isset($_GET["action"]) && $_GET["action"] == "delete" && in_array("delete", $rowOptions)){
			$DB->QuerySingle("DELETE FROM ".$table." WHERE id=:id", array("id"=>$_GET["id"]));
		}

		// WHERE
		$injWhere = "WHERE ";
		if(isset($_GET["search"]) && count($searchFields) > 0 && strlen($_GET["search"]) > 0){
			foreach($searchFields as $item){
				$field = str_replace(".", "_", $item);
				$injWhere .= $item." LIKE :".$field." OR ";
				$SQLarray[$field] = "%".$_GET["search"]."%";
			}
			$injWhere = substr($injWhere, 0, strlen($injWhere) - 4);
		}
		if(isset($forceFilter) && count((array)$forceFilter) > 0){
			foreach($forceFilter as $item){
				$field = str_replace(".", "_", $item["field"]);
				if($injWhere != "WHERE ") $injWhere .= " AND ";
				$comparison = (isset($item["comparison"])) ? $item["comparison"] : "=";
				$injWhere .= $item["field"]." ".$comparison." :".$field;
				if(strtolower(trim($comparison)) == "like") $SQLarray[$field] = "%".$item["value"]."%";
				else $SQLarray[$field] = $item["value"];
			}
		}
		if($injWhere == "WHERE "){ // Reset if nothing appended
			$injWhere = "";
		}

		// JOINS
		if(isset($joinTableFields) && count((array)$joinTableFields) > 0){
			$injJoinFields = "";
			$injJoinSQL = "";
			foreach($joinTableFields as $key => $item){
				$injJoinFields .= ",".$item["table"].".id AS '_join_ID',".$item["table"].".".$item["field"]." AS '_join_Field'";

				if($item["table"] == $table)
					$injJoinSQL .= " LEFT JOIN ".$item["table"]." AS _joinTable ON ".$table.".".$key."="."_joinTable.id";
				else
					$injJoinSQL .= " LEFT JOIN ".$item["table"]." ON ".$table.".".$key."=".$item["table"].".id";
			}
		}

		// ORDER
		if(isset($_GET["ordby"]) && strlen($_GET["ordby"])){
			$injOrder = " ORDER BY ".$DB->Quote($_GET["ordby"])." ".$DB->Quote($_GET["ordsort"]);
		}else{
			$injOrder = "ORDER BY ".$table.".id DESC";
		}

		if(!isset($SQLarray)) $SQLarray = array();
		if(!isset($injJoinFields)) $injJoinFields = "";
		if(!isset($injJoinSQL)) $injJoinSQL = "";

		$querySQL = "SELECT ".$table.".*".$injJoinFields." FROM ".$table." ".$injJoinSQL." ".$injWhere." ".$injOrder;
		$DB->Data["tbl"] = $DB->Query($querySQL." LIMIT ".$pstart.",".$perpage, $SQLarray);

		//die($querySQL);

		// Remove join fields if exists
		$temp = array();
		foreach($DB->Data["tbl"] as $item){
			unset($item["_join_ID"]);
			unset($item["_join_Field"]);
			$temp[] = $item;
		} $DB->Data["tbl"] = $temp;
		$total = count($DB->Query($querySQL, $SQLarray));

		if(isset($_GET['search']))
			$injP = "&search=".$_GET['search'];

		$available = true;
	}else{
		$available = false;
	}

	if(isset($total))
	{
		if(!isset($injP)) $injP = "";
		if(!isset($_GET["ordsort"])) $_GET["ordsort"] = "";
		if(!isset($_GET["ordby"])) $_GET["ordby"] = "";

		$pages = ceil($total) / $perpage;
		$pagination_html = "";
		for($i=1; $i<=$pages+1; $i++){
			if($_GET['curpage'] == $i){$class = "selected";}else{$class= "";}
			$pagination_html .= "<a href='?p=select&tbl=".$table."&s=".(($i*$perpage)-$perpage)."&curpage=".$i.$injP."&ordby=".$_GET["ordby"]."&ordsort=".$_GET["ordsort"]."' class='jumper ".$class."'>".$i."</a>";
		}
		$num = (($pstart+$perpage) < $total) ? ($pstart+$perpage) : $total;
		$pagination_html = "<div class='pagination'><span>".($pstart+1)." - ".$num." of ".ceil($total)."</span>".$pagination_html."</div>";
	}
	else
	{
		$pagination_html = "";
	}

	
?>

<div id="pageContent">
	<h1><a href="?p=select&amp;tbl=<?php echo $table; ?>">Browsing "<?php if(isset($title)) echo $title; else echo $_GET["tbl"]; ?>"</a></h1>

	<?php echo $constMsg; ?>

	<p>
		<br />
		<form action="" method="get" class='mainForm'>
			<input type="hidden" name="p" value="<?php if(isset($_GET["p"])) echo $_GET["p"]; ?>">
			<input type="hidden" name="tbl" value="<?php if(isset($_GET["tbl"])) echo $_GET["tbl"]; ?>">
			<input type="hidden" name="ordby" value="<?php if(isset($_GET["ordby"])) echo $_GET["ordby"]; ?>">
			<input type="hidden" name="ordsort" value="<?php if(isset($_GET["ordsort"])) echo $_GET["ordsort"]; ?>">
			<input type="hidden" name="s" value="0">
			<input type="hidden" name="curpage" value="1">
			<input type='text' name='search' value='<?php if(isset($_GET["search"])) echo $_GET['search']; ?>' placeholder='Search' />
			<input type='submit' value='Search' style='width: 100px;' />
		</form>
		<br />
		<?php
			if($available){

				echo $pagination_html;

				$DB->GetColumnNames($_GET["tbl"]);

				echo "<table class='tableList'>";
				echo "<thead>";
				$UsedFields = array(); // Fix because ATGTGHTGHGH
				foreach($DB->Fields as $item){
					if(!in_array($item, (array)$removeFields) && !in_array($item, $UsedFields)){
						if(isset($joinTableFields)){
							$k = $item;
							if(array_key_exists($item, $joinTableFields)){
								$ordby = $joinTableFields[$item]["table"].".".$joinTableFields[$item]["field"];
								$item = $joinTableFields[$item]["newName"];
							}else
								$ordby = $item;
						}else
							$ordby = $item;
						if(isset($replaceFieldNames)){
							$k = $item;
							if(array_key_exists($item, $replaceFieldNames))
								$item = $replaceFieldNames[$item];
						}

						if($_GET["ordsort"] == "ASC"){$ordsort = "DESC";}else{$ordsort = "ASC";}

						if(!isset($_GET["search"])) $_GET["search"] = "";
						if(!isset($ordby)) $ordby = "";
						if(!isset($ordsort)) $ordsort = "";

						$string = "<a href='?p=select&tbl=".$table."&ordby=".$ordby."&ordsort=".$ordsort."&search=".$_GET["search"]."' style='color: white; text-decoration: none;'>".$item."</a>";
						echo "<td>".$string."</td>";

						$UsedFields[] = $item;
					}					
				}
				
				// Row options
				if(in_array("edit", $rowOptions)) echo "<td style='width: 50px;'>&nbsp;</td>";
				if(in_array("delete", $rowOptions)) echo "<td style='width: 50px;'>&nbsp;</td>";
				
				echo "</thead>";
				
				$row = "1";
				foreach($DB->Data["tbl"] as $item){
					if($row == "1") $row = "2"; else $row = "1";
					echo "<tr class='row".$row."'>";
					
					foreach($item as $k => $v){
						if(!in_array($k, (array)$removeFields)){

							if(isset($joinTableFields)){
								if(array_key_exists($k, $joinTableFields)){
									$temp = $DB->QuerySingle("SELECT ".$joinTableFields[$k]["field"]." FROM ".$joinTableFields[$k]["table"]." WHERE id='".$v."'");
									$v = $temp[$joinTableFields[$k]["field"]];
								}
							}

							if(isValidTimeStamp($v)) // Check wether timestamp
								$v = date("d-m-Y h:ia", $v);
							
							if(strlen($v) > 100) // Substr > 100
								$v = substr($v, 0, 100)."..";

							$v = strip_tags($v); // HTML entities
							
							$v = str_replace("Â", "", $v); // Replace characters

							if(isset($showImageFields)){
								if(in_array($k, $showImageFields)){
									$v = "<img src='".str_replace("../", "/", $fileUploadLocations[$k].$v)."' style='width: 150px; display: inline-block; margin: 0px; padding: 0px; vertical-align: bottom;' />";
									$applyTDStyle = "style='width: 150px;'";
								}else{
									$applyTDStyle = "";
								}
							}

							if(!isset($applyTDStyle)) $applyTDStyle = "";

							echo "<td ".$applyTDStyle.">".$v."</td>";
						}
					}

					// Row options
					if(in_array("edit", $rowOptions)) echo "<td style='text-align: center;'><a href='".$PHPZevelop->Path->GetPage("edit/".$_GET["tbl"]."/".$item["id"], true)."'>edit</a></td>";
					if(in_array("delete", $rowOptions)) echo "<td style='text-align: center;'><a href='".$PHPZevelop->Path->GetPage("select/".$_GET["tbl"]."?action=delete&id=".$item["id"], true)."' class='delete'>delete</a></td>";

					echo"</tr>";
				}
				echo "</table>";

				if(count($DB->Data["tbl"]) < 1){
					echo "<p style='text-align: center; margin-top: 15px; margin-bottom: 15px;'>No rows exist in this table or filter, please expand your search.</p>";
				}

			}else{
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
			if(count($get) > 0){
				echo "<br />";
				foreach($get as $k => $v){
					if(strlen($v) > 0){
						$strArr[] = $k." = ".$v;
					}
				}
			}
			echo implode("<br />", $strArr);
			echo "</div>";
		?>
	</p>
</div>

<script>
	$(".delete").click(function(){
		link = $(".delete").attr("href");
	    if(confirm("Are you sure you want to delete this item?")){
	    	//alert(link);
	    	window.location.href(link);
	    }
	    return false;
	});
</script>