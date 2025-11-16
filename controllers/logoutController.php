<?php
session_start();
// Destruir sesión de forma segura
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();
// Redirigir a login
header('Location: ?slug=login');
exit;
?>