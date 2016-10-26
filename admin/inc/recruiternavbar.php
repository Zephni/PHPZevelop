<div id="userNavBlock" class="fullWidthOnly">
	<div class="head"><?php echo strtoupper($User->Data["username"])."'S"; ?> DASHBOARD</div>
	<a href="<?php $PHPZevelop->Path->GetPage("recruiter/manage") ?>">MANAGE ACCOUNT</a>
	<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs/create") ?>">NEW JOB ADVERT</a>
	<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs") ?>">JOB ADVERTS (<?php echo count($DB->Query("SELECT id FROM jobs WHERE uid=:uid", array("uid" => $User->Data["id"]))); ?>)</a>
	<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs/applications") ?>">APPLICATIONS (<?php echo count($DB->Query("SELECT id FROM applications WHERE rid=:rid", array("rid" => $User->Data["id"]))); ?>)</a>
	<a href="">BUY CREDITS (<?php echo $User->Data["credits"]; ?>)</a>
	<a href="<?php $PHPZevelop->Path->GetPage("user/signout"); ?>">SIGN OUT</a>
</div>

<div class="underFullWidthOnly">
	<div class="mobileDropdownButton mobileDropdownHead" dropdown="2" style="border-top: 1px solid #DDDDDD; background: #3A475B; color: white;">
		<span style="padding-left: 7px;"><?php echo strtoupper($User->Data["username"])."'S"; ?> DASHBOARD &gt;</span>
	</div>
	<div class="mobileDropdown">
		<a href="<?php $PHPZevelop->Path->GetPage("recruiter/manage") ?>"><div class="mobileDropdownButton"><span>MANAGE ACCOUNT</span></div></a>
		<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs/create") ?>"><div class="mobileDropdownButton"><span>NEW JOB ADVERT</span></div></a>
		<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs") ?>"><div class="mobileDropdownButton"><span>JOB ADVERTS (<?php echo count($DB->Query("SELECT id FROM jobs WHERE uid=:uid", array("uid" => $User->Data["id"]))); ?>)</span></div></a>
		<a href="<?php $PHPZevelop->Path->GetPage("recruiter/jobs/applications") ?>"><div class="mobileDropdownButton"><span>APPLICATIONS (<?php echo count($DB->Query("SELECT id FROM applications WHERE rid=:rid", array("rid" => $User->Data["id"]))); ?>)</span></div></a>
		<a href=""><div class="mobileDropdownButton"><span>BUY CREDITS (<?php echo $User->Data["credits"]; ?>)</span></div></a>
	</div>
</div>