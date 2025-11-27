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

<!-- Parte de HTML -->
<h2>Administración de Usuarios</h2>

<div id="mensaje"></div>

<!-- Formulario para crear o editar un usuario -->
<form id="formUsuario" autocomplete="off">
    <input type="hidden" name="idUser" id="idUser">
    <label>Nombre: 
        <input type="text" name="nombre" required autocomplete="new-password">
    </label>
    <label>Email: 
        <input type="email" name="email" required autocomplete="new-password">
    </label>
    <label>Usuario (login): 
        <input type="text" name="usuario" required autocomplete="new-password">
    </label>
    <label for="password">Contraseña:</label><br>
    <input type="password" id="password" name="password" required autocomplete="new-password"><br>

    <label for="mostrarPassword" class="mostrar-pass">
    <input type="checkbox" id="mostrarPassword" autocomplete="new-password">
    Mostrar contraseña
    </label>
    
    <label>Rol:
        <select name="rol" required>
            <option value="">Seleccionar...</option>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
    </label>
    <button type="submit">Guardar</button>    
</form>

<hr>

<!-- Tabla donde se listarán los usuarios -->
<table id="tablaUsuarios">
    <thead class="tablaUsuario">
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <!-- Aquí el JS cargará los usuarios -->
    </tbody> 
</table>

<!-- Script con la lógica de administración de usuarios -->
<script src="../assets/js/usuarios_admin.js"></script>

<?php
//Conexión al pie de página que cierra el <body> y el <html>
include '../includes/footer.php';
?>