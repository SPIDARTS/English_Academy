<?php
// Datos de conexión a la base de datos
$host ="sql100.infinityfree.com";
$user = "if0_40538716";
$pass = "drt120612";
$db = "if0_40538716_proyecto_academy";

// Crear la conexión a la base de datos usando mysqli
$conn = new mysqli($host, $user, $pass,$db);

//Verificar se la conexión tuvo algún error
if ($conn->connect_error) {
    //Si hay error, detiene el scrip y muestra mensaje
    die("Error de conexión: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");

?>
