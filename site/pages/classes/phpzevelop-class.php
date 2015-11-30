<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->Set("PAGE_TITLE",		"PHPZevelop class");
?>

<h2>PHPZevelop class</h2>

<p>The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.</p>

<pre class="code">
class PHPZevelop
{
	private $InternalData = array();

	public function NewObject($Alias, $Object){
		$this->$Alias = $Object;
	}

	public function Set($Key, $Value){
		$this->InternalValues[$Key] = $Value;
	}

	public function Get($Key){
		if(array_key_exists($Key, $this->InternalValues))
			return $this->InternalValues[$Key];
		else
			return null;
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

<p>The set and get methods are just for adding and returning elements of an private internal array, this is used by default in the PHPZevelop framework when setting the page
<a href="<?php $PHPZevelop->Path->GetPage("help/meta-data"); ?>">meta data</a>.</p>