<?php
session_start();

// Validar usuario en sesión
$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesUsuario = $_SESSION['usuario']['roles'] ?? [];

if ($id_usuario) {
    $rolesTexto = implode(", ", $rolesUsuario);
    echo "<script>console.log('Usuario autenticado con id_usuario: {$id_usuario}, roles: {$rolesTexto}');</script>";
} else {
    echo "<script>console.log('Usuario no autenticado');</script>";
    echo "<h2>Error: Usuario no autenticado</h2>";
    echo "<p>Por favor, inicie sesión para acceder a esta página.</p>";
    exit;
}

// Exponer roles al frontend
echo "<script>
    window.rolesUsuario = " . json_encode($rolesUsuario) . ";
</script>";

echo "<script>
    window.rolesUsuario = " . json_encode($rolesUsuario) . ";
    sessionStorage.setItem('roles', JSON.stringify(window.rolesUsuario));
</script>";