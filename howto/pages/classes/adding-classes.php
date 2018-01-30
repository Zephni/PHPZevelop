<?php
	/* Page setup
	------------------------------*/
	$PHPZevelop->OverrideObjectData("CFG", array(
		"PageTitle"  => "Adding classes"
	));
?>

<h2>Adding classes</h2>

<p>By default the "site/initiate.php" file contains a snippet that automatically includes all the class files in the "site/classes/" directory.</p>

<p>NOTE: You can include all from other directories by appending the directory name string to the SubLoader->RunIncludes() parameter array,
you may wish to do this with something like "functions". like below: </p>

<pre class="code">
	$SubLoader->RunIncludes(array("classes", "functions"));
</pre>

<p>After this you should instantiate your class in the "site/initiate.php" file, something like below: </p>

<pre class="code">
	/* MyClass
	------------------------------*/	
	$MyClass = new MyClass("Parameter1");
	$MyClass->AddParameter("Parameter2");
</pre>

<p>Here you should instantiate the object and configure it anyway that is necessary.</p>