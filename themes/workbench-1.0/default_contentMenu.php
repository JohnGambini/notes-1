<?php
/*--------------------------------------------------------------------------------------------
 * default_contentMenu.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
global $contentObj;
global $userObj;

$menuId = 'popupContentMenu';
?>
<!-- calliope-sutra-1.0 default_contentMenu -->
<div style="width:100%; height:2em">&nbsp;</div>
<div id="contentMenu" class="contentMenu">
	<div id="mainmenuItem2" style="padding:0px 0px 0px 10px">
			<?php set_siteNameMenu($contentObj,$userObj,'contentMenuTrigger-2') ?>
	</div>
	<div id="mainmenuItem3">
		<div id="pageTitle" class="fontSpecSmall" style="width:100%;margin:0em 0em 0em 0.5em;text-align:center"><?php set_pageTitle($contentObj) ?></div>
	</div>
	<div id="mainmenuItem4" style="align-items:center; text-align:right">
		<?php set_siteUserMenu($contentObj, $userObj); set_articleEditLink($contentObj, $userObj) ?>
	</div>
</div>
