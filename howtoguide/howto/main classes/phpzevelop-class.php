<p>The PHPZevelop class is simply a collection of PHPZevelop dependant objects that are fundamental to the workings of the framework.</p>

<pre class="code">
class PHPZevelop
{
	private $InternalData = array();

	public function NewObject($Alias, $Object)
	{
		$this->$Alias = $Object;
	}

	public function OverrideObjectData($Alias, $NewData)
	{
		if(isset($this->$Alias))
			foreach($NewData as $Key => $Value)
				$this->$Alias->$Key = $Value;
	}

	public function Set($Key, $Value)
	{
		$this->InternalData[$Key] = $Value;
	}

	public function Append($Key, $V1, $V2 = null)
	{
		if($V2 === null)
			$this->InternalData[$Key][] = $V1;
		else
			$this->InternalData[$Key][$V1] = $V2;
	}

	public function Get($Key)
	{
		if(array_key_exists($Key, $this->InternalData))
			return $this->InternalData[$Key];
		else
			return null;
	}
}
</pre>

<h4>Methods</h4>

<p style='color: green;'>AddObject(string $Alias, object $Object):</p>
<p>The "NewObject" method is for adding an already instantiated object as an accessible property of PHPZevelop. For instance "Page" is a class that PHPZevelop relies on, 
this is added to PHPZevelop by using: </p>

<pre class="code">
$PHPZevelop->NewObject("Page", new Page());
</pre>

<p>You can change the name of the property by changing the first parameter string value, the second parameter is the instantiated object itself. 
A property can by overwritten by applying a new object to the same string value. An example of why you may do this can be found here <?php $Link->Out("classes/path-class"); ?>.</p>

<p style='color: green;'>OverrideObjectData(string $Alias, object $NewData):</p>
<p>OverrideObjectData will loop through the $Alias object and ammend the properties with the new values in an array like so: </p>
<pre class="code">array("Key" => "PropertyName", "Value" => "NewValue")</pre>
<p>see more here: <?php $Link->Out("help/meta-data"); ?>.</p>

<p style='color: green;'>Append(string $Alias, string $V1, string $V2 = null):</p>
<p>Append will add an array element to an already existing one, the first parameter is the key of the item you want to append to. The second parameter is the item to append.
If the third parameter is passed it will set the key and value of the new element with the second and third parameter respectively.</p>


<p style='color: green;'>Set(string $Key, string $Value) / Get(string $Key):</p>
<p>The set and get methods are just for adding and returning elements of an private internal array, you can use this for global variables.</p>