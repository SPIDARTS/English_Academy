<?php
// Verificamos si aún no hay sesión activa
// Si no la hay, se inicia una nueva
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<!-- Estructura HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia SPEAK ENGLISH</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    
<?php 
// Conexión a la barra de navegación
    include "navbar.php"; ?>
<main>