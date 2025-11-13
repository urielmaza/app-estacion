<?php
	/* vista del panel: la lista se completa vía JS consumiendo la API externa */
	$tpl = new Palta("panel");
	$tpl->assign([
		"APP_SECTION" => "Panel"
	]);
	$tpl->printToScreen();
?>