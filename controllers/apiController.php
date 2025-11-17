<?php

// Controlador API minimalista para endpoints JSON
header('Content-Type: application/json; charset=utf-8');
// CORS abierto (ajustar según necesidad)
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../models/Tracker.php';

try {
    if (isset($_GET['list-clients-location'])) {
        $tracker = new Tracker();
        $data = $tracker->listClientsLocation();
        echo json_encode([
            'ok' => true,
            'count' => count($data),
            'data' => $data
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    // Si llega aquí, parámetro no reconocido
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Parámetro no soportado']);
} catch (Throwable $e){
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Error interno']);
}

?>
