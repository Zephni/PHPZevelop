<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Page class");

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Page->PageFile
	);
?>

<h2>Page class</h2>

<h3>Properties</h3>

<pre class="code" style="font-size: 13px;">
string $PageFile 		// The main page file (Usually the page that delivers content)
string $DefaultPageFile 	// The default page if $PageFile isn't specified (Eg. When no query string is passed)
string $Page404  		// The page to default to if $PageFile can't be found
array $FileOrder 		// Order of files to load such as a header/footer, $PageFile should be in this list
array $DefinedVars 		// Is an array list of variables that you may want to use inside the included files
</pre>

<h3>Usage</h3>

<pre class="code">
// 1. (Preferred) Defining properties individually and loading page when ready

	$PHPZevelop->Page->PageFile =		$PHPZevelop->Path->GetPageRoot($PAGE_PATH.".php");
	$PHPZevelop->Page->DefaultPageFile =	$PHPZevelop->Path->GetPageRoot("home.php");
	$PHPZevelop->Page->Page404 =		$PHPZevelop->Path->GetPageRoot("error/404.php");

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer.php")
	);

	$PHPZevelop->Page->DefinedVars = get_defined_vars();
	$PHPZevelop->Page->LoadPage();
</pre>

<pre class="code">
// 2. Using constructor

new Page(
	$PHPZevelop->Path->GetPageRoot($PAGE_PATH.".php"),
	$PHPZevelop->Path->GetPageRoot("home.php"),
	$PHPZevelop->Path->GetPageRoot("error/404.php"),
	array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Path->GetPageRoot($PAGE_PATH.".php"),
		$PHPZevelop->Path->GetInc("footer.php")"
	),
	get_defined_vars()
);
</pre>

<p>Note: Make sure to pass get_defined_vars() from the PHP that calls this if you wish to use current defined variables inside the included files</p>

<h3>FileOrder exaplained</h3>

<p>Notice above we are setting the "FileOrder" (array) property. You would always want to include your "PageFile" in this list, in most cases you would have a header,
body and footer as your structure where "body" is the actual page content that changes.</p>

<p>In some cases you may have a right hand column or another common section throughout the site, in this case you would add the .php file in the inc/ folder and include it
in the "FileOrder". But then what if on some pages you don't want to include it?</p>

<p>In this case you can change the "$PHPZevelop->Page->FileOrder" on the page you want to remove it. Remember at the top of the page we have some meta data: </p>

<pre class="code">
&lt;?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");
?&gt;
</pre>

<p>.. here we could also make a change to the "$PHPZevelop->Page->FileOrder": </p>

<pre class="code">
&lt;?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"Meta data");

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header.php"),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer.php")
	);
?&gt;
</pre>

<p>.. this would remove the right hand column from the normal file order. In this case notice there is no footer, this is because the file order has been changed at the top of this page.</p>