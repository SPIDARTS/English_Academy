<?php
// Conexión a la base de datos y a la estructura principal
    include '../includes/header.php';
    include '../includes/db.php';
?>

<!-- Sección de Noticias -->
<section id="noticias">
    <h2>Noticias y Eventos Recientes</h2>
    <p>Mantente informado sobre nuestras últimas novedades y nuestros ultimos eventos</p>

<?php
    $sql = "SELECT 
                n.idNoticia AS id, 
                n.titulo, 
                n.texto, 
                n.fecha,
                n.imagen,  
                u.nombre AS usuario
            FROM noticias n
            INNER JOIN users_data u ON n.idUser = u.idUser
            ORDER BY n.fecha DESC";
            
    $result = $conn->query($sql);

    $ruta_base_imagenes = '../assets/images/';

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            ?>
            <article>
                <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                <p><strong>Publicado por:</strong> <?php echo htmlspecialchars($row['usuario']); ?> | 
                    <strong>Fecha:</strong> <?php echo htmlspecialchars($row['fecha']); ?></p>
                <?php if (!empty($row['imagen'])): ?>
                    <div class="noticia-imagen">
                        
                        <img src="<?php echo htmlspecialchars($ruta_base_imagenes . $row['imagen']); ?>" 
                             alt="Imagen de la noticia: <?php echo htmlspecialchars($row['titulo']); ?>" 
                             style="max-width: 100%; height: auto; margin-bottom: 15px;">
                    </div>
                <?php endif; ?>
                
                <p><?php echo nl2br(htmlspecialchars($row['texto'])); ?></p>  
            </article>

            <?php
        }
    } else {
        echo '<p>No hay noticias disponibles en este momento.</p>';
    }
    $conn->close();
    ?>
</section>

<?php
//Conexión al pie de página que cierra el <body> y el <html>
    include '../includes/footer.php';
?>