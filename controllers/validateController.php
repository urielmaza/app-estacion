<?php
session_start();
if(isset($_SESSION['id_cliente'])){ header('Location: ?slug=panel'); exit; }
$token_action = '';
if(isset($_GET['slug'])){
    $parts = explode('/', $_GET['slug']);
    if(count($parts)>1) $token_action = $parts[1];
}
$msg = '';
if($token_action){
    $usr = new ClienteAppEstacion();
    $r = $usr->setActiveByTokenAction($token_action);
    if($r['ok']){
        $usr->sendEmail($r['user']['email'],'Usuario activo','Tu usuario ha sido activado correctamente.');
        // Redirigir inmediatamente al login para evitar ver la página 404
        header('Location: ?slug=login');
        exit;
    } else {
        $msg = 'El token no corresponde a un usuario';
    }
} else {
    $msg = 'Token faltante';
}
// Mostrar vista de error solo en casos no exitosos
$tpl = new Palta('error');
$tpl->assign(['ERROR_MESSAGE'=>$msg]);
$tpl->printToScreen();
?>