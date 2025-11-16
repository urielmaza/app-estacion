<?php 
// Requiere sesión: si no está logueado, redirigir a login (preservando chipid)
// el detalle se completa por JS desde la API. Solo pasamos el chipid.
$chipid = isset($_GET['chipid']) ? $_GET['chipid'] : (isset($_GET['id']) ? $_GET['id'] : '');

session_start();
if(!isset($_SESSION['id_cliente'])){
    $dest = '?slug=login' . ($chipid ? ('&chipid='.urlencode($chipid)) : '');
    header('Location: '.$dest);
    exit;
}

$tpl = new Palta("detalle");
$tpl->assign([
    "APP_SECTION" => "Detalle",
    "CHIPID" => htmlspecialchars($chipid)
]);
$tpl->printToScreen();

?>
