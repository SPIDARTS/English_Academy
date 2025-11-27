<?php 
    // Conexión a la base de datos y a la estructura principal
    include "../includes/db.php";
    include '../includes/header.php'; 

    // Solo los administradores pueden acceder a esta página
    if (!isset ($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
?>

<!-- Parte de HTML-->
<h2>Gestión de Noticias</h2>

<!-- Sección del fromulario para crear o editar noticias -->

<section id="form-section">
    <form id="form-noticia" enctype="multipart/form-data">
        <input type="hidden" id="idNoticia" name="idNoticia"> 

        <input type="hidden" id="imagen-actual" name="imagen_actual">

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br>

        <label for="texto">Texto:</label>
        <textarea id="texto" name="texto" required></textarea><br>

        <label for="imagen">Foto de la Noticia (opcional):</label>
        <input type="file" id="imagen" name="imagen" accept="image/*"><br> 
        <div id="vista-previa-imagen"></div>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br>

        <button type="submit">Guardar Noticia</button>
    </form>
</section>

<hr>

<!-- Tabla con todas las noticias -->
<section id="tabla-section">
    <h2>Noticias Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-noticias">
            <!-- Aquí el JS cargará los usuarios -->
        </tbody>
    </table>
</section>

<script src="../assets/js/noticias_admin.js"></script>

<?php
//Conexión al pie de página que cierra el <body> y el <html>
    include '../includes/footer.php';
?>