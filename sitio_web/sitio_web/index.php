<?php
// Conexión a la cabecera que contiene la estructura inicial 
// (<html>, <head>, barra de navegación y apertura del <body>)
    include "../includes/header.php";
?>

<!-- Sección de Portada -->
<section id="inicio">
    <h2>Academia privada de Inglés</h2>
    <p>Aprende inglés con nosotros, ¿Are you ready?.</p>
<!-- Imagen principal de portada -->
    <img src="../assets/images/principal.jpg" alt="Imagen de portada">
</section>

<!-- Sección sobre nosotros -->
<section>
    <h3>Sobre nosotros</h3>
    <p>Somos una de las mejores academias para aprender ingles de nuestra zona</p>
    <p>Contamos en nuestro equipo con los mejores profesionales del sector tanto nacionales especializados en el idioma como gente nativa que viene a enseñarnos sus conocimientos</p>
</section>

<!-- Sección Galería -->
<section id="galeria">
    <h3>Nuestras instalaciones</h3>
    <p>A continuación os enseñamos nuestras instalaciones disponibles de la academia</p>
    <!-- Imagenes -->
    <img src="../assets/images/entrada1.jpg" alt="Imagen de portada">
    <img src="../assets/images/entrada.jpg" alt="Imagen de portada">
    <img src="../assets/images/clase.jpg" alt="Imagen de portada">
</section>

<!-- Sección de Contacto -->
<section id="contacto">
    <h3>Contacto</h3>
    <p>Para información sobre nuestros horarios lectivos y precios no dudes en contactarnos a través de nuestras redes sociales, llamarnos o visitarnos, estaremos encantados de atenderte.</p>
    <!-- Datos de contacto -->
    <a href="https://www.facebook.com" target="_blank">Facebook</a><br>
    <a href="https://www.instagram.com" target="_blank">Instagram</a><br>
    <p>Telefono : 925346271</p>
    <p>Dirección : Calle dinamarca 8 (Toledo)</p>
</section>
    
<?php
//Conexión al pie de página que cierra el <body> y el <html>
include '../includes/footer.php';
?>