<?php
session_start();
// Proteger panel: si no está logueado, ir a login
if(!isset($_SESSION['id_cliente'])){
    header('Location: ?slug=login');
    exit;
}

/* vista del panel: la lista se completa vía JS consumiendo la API externa */
$tpl = new Palta("panel");
$tpl->assign([
    "APP_SECTION" => "Panel"
]);
$tpl->printToScreen();
?>