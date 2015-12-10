<?php
	/* Generate page by file order
	------------------------------*/
	$PHPZevelop->Page->PageFile = $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->PagePath.FILE_EXT);

	if(isset($PHPZevelop->CFG->Page404) && strlen($PHPZevelop->CFG->Page404) > 0)
		$PHPZevelop->Page->Page404	= $PHPZevelop->Path->GetPageRoot($PHPZevelop->CFG->Page404.FILE_EXT);

	$PHPZevelop->Page->FileOrder = array(
		$PHPZevelop->Path->GetInc("header".FILE_EXT),
		$PHPZevelop->Page->PageFile,
		$PHPZevelop->Path->GetInc("footer".FILE_EXT)
	);

	$PHPZevelop->Page->SetDefinedVariables(get_defined_vars());
	$PHPZevelop->Page->LoadPage();