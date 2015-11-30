<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Database class");
?>

<h2>Database</h2>

<h3>Note</h3>

<p>The class.db.php file must be included in the "classes/" directory for this class to be available.</p>

<p>By default the database class (which uses PDO) is configured in the "site/config.php" file and instantiated in the "site/instantiate.php": </p>

<pre class="code" style="font-size: 13px;">
# file: site/config.php

/* Config
------------------------------*/
$PHPZevelop->CFG->DB = (object) array(
	"Host" => "",
	"User" => "",
	"Pass" => "",
	"Name" => ""
);

# file: site/instantiate.php.php

/* Database connection
------------------------------*/
if(strlen($PHPZevelop->CFG->DB->Host) > 0){
	$DB = new db($PHPZevelop->CFG->DB->Host, $PHPZevelop->CFG->DB->User, $PHPZevelop->CFG->DB->Pass, $PHPZevelop->CFG->DB->Name);
	if(!$DB->Connected)
		die($DB->ErrorHandler());
}
</pre>

<p>If the host has not been set in the site/config.php then the database will not attempt to connect.
If the the host property is set, then it will try to connect and die on error if it can't connect with a PDOExecption error message.</p>

<h3>Usage</h3>

<p>Query a single row from the database</p>

<pre class="code">$DB->QuerySingle("SELECT * FROM tablename WHERE id=:id", array("id" => 5));</pre>

<p>The "QuerySingle" method is accepting two parameters here, a string and an array. The string (param 1) is the query, and field that is being tested against or changed must use an
alias prepended by a ":" (colon) that will represent the value, this value can then be assigned in the array (param 2) by using the alias as the key, and the value as the value of the key</p>

<p>You would also use "QuerySingle" to INSERT or to UPDATE:</p>

<pre class="code">$DB->QuerySingle("UPDATE tablename SET name=:name WHERE id=:id", array("username" => "Zephni", "id" => 5));</pre>

<p>By using this system which is a wrapper class of PDO, all values passed through as the second parameter will be escaped appropriately.</p>

<p>To query and return multiple rows just replace "QuerySingle" with "Query": </p>

<pre class="code">$DB->QuerySingle("SELECT * FROM tablename ORDER BY id DESC");</pre>

<p>"QuerySingle" will return an array with key=>value elements, where key is the column name, and value is the value in that row. "Query" will return an indexed array with key=>value elements. So
after making a multi-row "Query" you could loop through the results by using: </p>

<pre class="code">
// Query tablename
$data = $DB->Query("SELECT id,name FROM tablename ORDER BY id DESC");

foreach($data as $item){
	echo $item["name"]."&lt;br /&gt;";
}
</pre>