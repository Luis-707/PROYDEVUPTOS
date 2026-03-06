<?php
session_start();

// Si no hay sesión, enviar al login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.html?clear=1');
    exit;
}

// (Opcional) Evitar caché del dashboard
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
?>