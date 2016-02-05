<?php
	// VARS
	$table = "pages";
	$title = "Pages";

	// SELECT
	$removeFields = array("id", "html");
	
	$searchFields = array("title", "content");

	$rowOptions = array("edit", "delete");

	// FORM
	if(isset($formGen)){
		$formGen->addElement("Alias",						"text", 	array("name" => "alias"));
		$formGen->addElement("Title",						"text", 	array("name" => "title"));
		$formGen->addElement("Content",						"textarea", array("name" => "content"));
		$formGen->addElement("HTML (overrides content)",	"textarea", array("name" => "html", "class" => "mceNoEditor", "style" => "margin-top: 5px; width: 702px; height: 150px;"));
		$formGen->addElement("Hide (from footer)",			"select",	array("name" => "hide"), array("0" => "No", "1" => "Yes"));
		$formGen->addElement("", "submit", array("value" => "Submit"));
	}
?>