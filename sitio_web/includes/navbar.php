<?php
// Se obtiene el nombre del archivo actual para saber en qué página está el usuario
$pagina = basename($_SERVER['PHP_SELF']);

// Aseguramos que haya sesión activa
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<!-- Barra de navegación -->
<nav>
    <ul>
        <!-- Si la página actual es index.php, se aplica la clase "activo" -->
        <li>
            <a href="index.php"<?php if($pagina=="index.php") echo "class='activo'"; ?>>Inicio</a>
        </li>
        <li>
            <a href="noticias.php"<?php if($pagina=="noticias.php") echo "class='activo'"; ?>>Noticias</a>
        </li>
        <?php if (!isset($_SESSION['usuario'])): ?>
        <!-- Si el usuario NO ha iniciado sesión, mostramos Registro y Login -->
            <li class="right">
                <a href="registro.php" <?php if($pagina=="registro.php") echo "class='activo'"; ?>>Registro</a>
            </li>
            <li>
                <a href="login.php" <?php if($pagina=="login.php") echo "class='activo'"; ?>>Login</a>
            </li>
        <?php else: ?>
            <!-- Si el usuario SÍ ha iniciado sesión -->
            
            <li><a href="../sitio_web/perfil.php" <?php if($pagina=="perfil.php") echo "class='activo'"; ?>>Mi Perfil</a></li>
            
            <?php if ($_SESSION['rol'] === "admin"): ?>
                <!-- Rol ADMIN -->
                <li><a href="citas_admin.php" <?php if($pagina=="citas_admin.php") echo "class='activo'"; ?>>Admin de Citas</a></li>
                <li><a href="noticias_admin.php" <?php if($pagina=="noticias_admin.php") echo "class='activo'"; ?>>Admin de Noticias</a></li>
                <li class="panelAdmin"><a href="usuarios_administracion.php" <?php if($pagina=="usuarios_administracion.php") echo "class='activo'"; ?>>Admin de Usuarios</a></li>    
            <?php else: ?>
                <!-- Rol USER -->
                <li><a href="citaciones.php" <?php if($pagina=="citaciones.php") echo "class='activo'"; ?>>Mis Citas</a></li>
            <?php endif; ?>
        <!-- Mostramos botón de cerrar sesión con el nombre de usuario si esta esta iniciada -->
            <li class="right">
                <a href="logout.php">Cerrar sesión (<?= htmlspecialchars($_SESSION['usuario']); ?>)</a>
            </li>        
            <?php endif; ?>
    </ul>
</nav>