<?php
	// VARS
	$table = "articles";
	$title = "Articles";

	// SELECT
	$removeFields = array("id", "gallery_id", "image");
	
	$searchFields = array("title", "content");

	$forceFilter = array(
		"f1" => array("field" => "comp_id", "value" => (string)$_GET["f1"])
	);

	$joinTableFields = array(
		"cat_id" => array("table" => "categories", "field" => "title", "newName" => "category")
	);

	$fileUploadLocations = array(
		"image" => "../images/articles/"
	);

	if(isset($data["image"])){
		$forceImageNames = array(
			"image" => (strlen($data["image"]) < 1) ? time().".".pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION) : $data["image"] // Uploads image as timestamp, and then keeps name for overrides
			//"image" => "newname.jpg" // Hard replaces image name
		);
	}

	$rowOptions = array("edit", "delete");

	// FORM
	if(isset($formGen)){
		$imageString = (isset($data["image"])) ? "Current: <a href='".str_replace(ROOT_DIR, "", $fileUploadLocations["image"]).$data["image"]."' target='_blank'>".$data["image"]."</a>" : "";

		$formGen->addElement("Date",	 					"date", 	array("name" => "date"));
		$formGen->addElement("Title",						"text",		array("name" => "title"));
		$formGen->addElement("Description",					"textarea", array("name" => "description"));
		$formGen->addElement("Image",						"file", 	array("name" => "image"), $imageString);
		$formGen->addElement("", "submit", array("value" => "Submit"));
	}
?>