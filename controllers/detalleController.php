<?php 

// el detalle se completa por JS desde la API. Solo pasamos el chipid.
$chipid = isset($_GET['chipid']) ? $_GET['chipid'] : (isset($_GET['id']) ? $_GET['id'] : '');

$tpl = new Palta("detalle");
$tpl->assign([
    "APP_SECTION" => "Detalle",
    "CHIPID" => htmlspecialchars($chipid)
]);
$tpl->printToScreen();

?>
