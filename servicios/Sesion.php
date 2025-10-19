<?php
session_start();

// Validar usuario en sesión
$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$rolUsuario = $_SESSION['usuario']['rol'] ?? 'otro';

// Normalizar rol
$rolUsuario = strtolower(trim($rolUsuario));

if ($id_usuario) {
    echo "<script>console.log('Usuario autenticado con id_usuario: {$id_usuario}, rol: {$rolUsuario}');</script>";
} else {
    echo "<script>console.log('Usuario no autenticado');</script>";
    echo "<h2>Error: Usuario no autenticado</h2>";
    echo "<p>Por favor, inicie sesión para acceder a esta página.</p>";
    exit;
}

// Exponer rol al frontend
echo "<script>window.rolUsuario = '" . htmlspecialchars($rolUsuario, ENT_QUOTES, 'UTF-8') . "';</script>";