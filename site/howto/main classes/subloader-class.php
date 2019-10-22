<p>A class for simplified inclusion of mass files. For example, pass it a directory, or a list of directories as an array to recursively include all files inside those directories.</p>

<h4>Public properties</h4>

<pre class="code">
array $DefinedVariables = array();	// An array of variables to pass to the included files
array $IncludedFiles;			// List of successfully included files after RunIncludes() has ran
array $ErrorFiles;			// List of unsuccessfully included files after RunIncludes() has ran
</pre>

<h4>Usage</h4>

<p>If you wanted to include all PHP files in the "site/classes" directory, use the below: </p>

<pre class="code">
<span style="color: green;">1.</span> $SubLoader = new SubLoader($PHPZevelop->CFG->SiteDir);
<span style="color: green;">2.</span> $SubLoader->RunIncludes(array("classes"));
<span style="color: green;">3.</span> extract($SubLoader->DefinedVariables);
</pre>

<p><span style="color: green;">Line 1</span> is intantiating the SubLoader class, the constructor takes the root path of the website.</p>

<p><span style="color: green;">Line 2</span> is running the includes, the parameter can be a string or an array of strings that represent the directory names.</p>

<p><span style="color: green;">Line 3</span> is extracting the variables that may have been in the files that were included.</p>