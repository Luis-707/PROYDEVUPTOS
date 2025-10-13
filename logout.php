<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// Redirigir al login
header("Location: login.html");
exit;