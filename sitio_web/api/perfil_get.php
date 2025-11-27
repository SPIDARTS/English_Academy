<?php
    // Inicia la sesión y prepara la conexión a la base de datos
    session_start();
    include "../includes/db.php";
    header('Content-Type:application/json');

    // Verifica que el usuario esté logueado
    if(!isset($_SESSION['idUser'])) {
        echo json_encode(["success" => false, "error" => "No autorizado"]);
        exit;
    }

    $idUser = $_SESSION['idUser'];

    // Consulta SQL para obtener los datos del perfil del usuario
    $sql = "SELECT u.usuario, d.nombre, d.apellidos, d.email, d.telefono, d.fecha_nacimiento, d.direccion, d.sexo
            FROM users_login u
            JOIN users_data d ON u.idUser = d.idUser
            WHERE u.idUser = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUser);
    $stmt->execute();
    $result = $stmt->get_result();

    // Devuelve los datos en formato JSON o error si no se encuentra el usuario
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true] + $row);
    } else {
        echo json_encode(["success" => false, "error" => "Usuario no encontrado"]);
    }




