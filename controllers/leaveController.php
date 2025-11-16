<?php
// Endpoint liviano para registrar abandono
// Se invoca vía navigator.sendBeacon / fetch keepalive al cerrar/pasar de página
session_start();
http_response_code(204); // No Content por defecto

try {
    if (!isset($_SESSION['id_cliente'])) {
        // No hay sesión; nada que registrar
        exit;
    }
    require_once __DIR__ . '/../models/DBAbstract.php';
    require_once __DIR__ . '/../models/ClienteAppEstacion.php';
    $usr = new ClienteAppEstacion();
    $usr->setDeleteDateNowById($_SESSION['id_cliente']);
    // Retornar 204 sin cuerpo
    exit;
} catch (Throwable $e) {
    // Evitar ruido en cierre de página; opcionalmente loguear en servidor
    exit;
}
