<?php
	/* Include all classes
	------------------------------*/
	$SubLoader = new SubLoader($PHPZevelop->CFG->SiteDirRoot);
	$SubLoader->RunIncludes(array("classes"));
	extract($SubLoader->DefinedVariables);