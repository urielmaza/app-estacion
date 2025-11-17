<?php
session_start();
// Proteger panel: si no está logueado, ir a login
if(!isset($_SESSION['id_cliente'])){
    header('Location: ?slug=login');
    exit;
}

// Si es administrador, no permitir panel: redirigir a administrador
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: ?slug=administrator');
    exit;
}

// Registrar acceso en tracker (aunque no usemos datos del usuario)
require_once __DIR__ . '/../models/DBAbstract.php';
require_once __DIR__ . '/../models/Tracker.php';

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
list($os,$nav) = Tracker::parseUA($ua);
list($lat,$lng,$pais) = Tracker::enrichByIP($ip);
$trk = new Tracker();
$trk->insertVisit($ip,$lat,$lng,$pais,$nav,$os);

/* vista del panel: la lista se completa vía JS consumiendo la API externa */
$tpl = new Palta("panel");
$tpl->assign([
    "APP_SECTION" => "Panel"
]);
$tpl->printToScreen();
?>