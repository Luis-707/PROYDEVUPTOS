<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Limpiar y destruir la sesión
$_SESSION = [];
session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Sesión cerrada']);
exit;