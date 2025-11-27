<?php
    // Conexión a la cabecera que contiene la estructura inicial
    include '../includes/header.php';

    // Inicia la sesión y verifica
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    if (!isset($_SESSION['idUser'])) {
    header("Location: login.php");
    exit;
    }
?>

<!-- Contenido de la página -->
<section>
    <h2>Mi perfil</h2>
    <div id="perfil"></div>
    <div id="mensaje"></div>
</section>
<?php
    //Conexión al pie de página que cierra el <body> y el <html>
    include "../includes/footer.php";
?>

<!-- Conexión a la pagina de JAVASCRIPT-->
<script src="../assets/js/perfil.js"></script>

    