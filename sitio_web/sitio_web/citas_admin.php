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

<section id="gestionCitas">
    <h2>Gestión de Citas de Usuarios</h2>
    <div id="mensaje"></div>

    <div class="contenedor-citas">
        <form id="formCita">

        <!-- Elegir usuario -->
        <label for="selectUsuario">Seleccionar usuario:</label>
        <select id="selectUsuario" class="form-control">
            <option value="" disabled selected>Selecionar</option>
        </select>

        <!-- Crear o modificar una cita -->
        <h3>Crear / Modificar cita</h3>
        <input type="hidden" id="idCita">
        
        <label>Fecha:</label>
        <input type="date" id="fecha_cita" required>

        <label>Descripción:</label>
        <input type="text" id="descripcion" required>
        
        <button type="submit">Guardar</button>
    </form>

    <!-- Mostrar citas del usuario seleccionado -->
    <div class="tabla-container">
        <h3>Citas del usuario seleccionado</h3>
        <table id="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                 <!-- Aquí se cargarán las citas con JS-->
            </tbody>   
        </table>
    </div>
</div>
</section>

<!-- Script con la lógica de administración de usuarios -->
<script src="../assets/js/citas_admin.js"></script>

<?php
//Conexión al pie de página que cierra el <body> y el <html>
include '../includes/footer.php';
?>