<?php
    // Conexión a la base de datos y a la estructura principal
    include "../includes/db.php";
    include "../includes/header.php";

    // Inicia la sesión y obtiene el id del usuario (si existe)
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    $idUser = $_SESSION['idUser'] ?? null;
?>

<!-- Parte de HTML-->
<section id="gestionCitas">
    <h2>Gestón de Citaciones</h2>
    <div id="mensaje"></div>

    <!-- Formulario para solicitar una nueva cita -->
    <div class="contenedor-citas">
        <form id="formCita">
            <label>Fecha: <input type="date" name="fecha_cita" requiered></label><br>
            <label>Hora: <input type="time" name="hora_cita" requiered></label><br>
            <label>Motivo: <input type="text" name="motivo" requiered></label><br>
            <button type="submit">Solicitar Cita</button>
        </form>

        <!-- Tabla para mostrar las citas del usuario -->
        <div class="tabla-container">
            <h3>Mis Citas</h3>
            <table id="tablaCitas">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Aquí el JavaScript cargará las citas desde la BD-->
                </tbody>
            </table>
        </div>    
    </div>
</section>

<!-- Script con la lógica de citas-->
<script src="../assets/js/citaciones.js"></script>

<?php
//Conexión al pie de página que cierra el <body> y el <html>
include '../includes/footer.php';
?>



