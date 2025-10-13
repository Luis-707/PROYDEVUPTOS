<?php
session_start();

$id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;
   
   if ($id_usuario) {
       echo "<script>console.log('Usuario autenticado con id_usuario: {$id_usuario}');</script>";
   } else {
       echo "<script>console.log('Usuario no autenticado');</script>";
       echo "<h2>Error: Usuario no autenticado</h2>";
       echo "<p>Por favor, inicie sesión para acceder a esta página.</p>";
       exit;
   }