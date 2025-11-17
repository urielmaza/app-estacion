<?php
session_start();

// Requiere sesión de usuario; si no está logueado, ir a login
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ?slug=login');
    exit;
}

// Fallback: si el usuario logueado normal es el admin, marcar is_admin (sin manejar logout aquí)
if (!isset($_SESSION['is_admin']) && isset($_SESSION['email']) && $_SESSION['email'] === 'admin-estacion') {
    $_SESSION['is_admin'] = true;
}

require_once __DIR__ . '/../models/DBAbstract.php';
require_once __DIR__ . '/../models/Stats.php';

$tpl = new Palta('administrator');
$tpl->assign(["APP_SECTION" => "Administrador"]);

// Si no tiene privilegios admin, ir al panel
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ?slug=panel');
    exit;
}

// Si es admin: cargar contadores (usuarios=admins, clientes=normales)
$stats = new Stats();
$totalUsers = $stats->countAdmins();
$totalClients = $stats->countNormalClients();

$content = 
           '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-top:1rem;">'
         . '  <div style="background:#fff;border:1px solid rgba(0,0,0,.1);padding:1rem;border-radius:8px;">'
         . '    <h3 style="margin-bottom:.25rem;">Usuarios registrados</h3>'
         . '    <p style="font-size:2rem;font-weight:700;color:var(--color-acento-secundario);">'.(int)$totalUsers.'</p>'
         . '  </div>'
         . '  <div style="background:#fff;border:1px solid rgba(0,0,0,.1);padding:1rem;border-radius:8px;">'
         . '    <h3 style="margin-bottom:.25rem;">Clientes registrados</h3>'
         . '    <p style="font-size:2rem;font-weight:700;color:var(--color-acento-secundario);">'.(int)$totalClients.'</p>'
         . '  </div>'
         . '</div>';

$tpl->assign(['ADMIN_CONTENT' => $content]);
$tpl->printToScreen();
?>
