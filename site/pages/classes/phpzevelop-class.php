<?php
	/* Page meta data
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"PHPZevelop class");
?>

<h2>PHPZevelop class</h2>

<p>The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.</p>

<pre class="code">
class PHPZevelop
{
	public function NewObject($Alias, &amp;$Object){
		$this->$Alias = $Object;
	}
}
</pre>

<h3>Usage</h3>

<p>The "NewObject" method is for adding an already instantiated object as an accessible property of PHPZevelop. For instance "Page" is a class that PHPZevelop relies on, 
this is added to PHPZevelop by using: </p>

<pre class="code">
$PHPZevelop->NewObject("Page", new Page());
</pre>

<p>You can change the name of the property by changing the first parameter string value, the second parameter is the instantiated object itself. 
A property can by overwritten by applying a new object to the same string value. An example of why you may do this can be found here <a href="<?php $PHPZevelop->Path->GetPage("classes/path-class"); ?>">classes/path-class</a>.</p>