<?php
session_start();
if (!isset($_SESSION['is_admin'])){
    header('Location: ?slug=panel');
    exit;
}

$tpl = new Palta('map');
$tpl->assign(["APP_SECTION"=>"Mapa de clientes"]);
$tpl->printToScreen();
?>
