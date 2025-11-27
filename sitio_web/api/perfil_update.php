<?php
    // Inicia la sesión y conecta a la base de datos
    session_start();
    include "../includes/db.php";
    header('Content-Type: application/json');

    // Verifica que el usuario esté logueado
    if (!isset($_SESSION['idUser'])) {
        echo json_encode(["success" => false, "error" => "No autorizado"]);
        exit;
    }

    $idUser = $_SESSION['idUser'];

    // Recibe y lispia los datos enviados desde el formulario
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $fecha     = trim($_POST['fecha_nacimiento'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $sexo      = trim($_POST['sexo'] ?? '');

    try {
        // Si se envió una nueva contraseña, la actualiza en la tabla de login
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql1 = "UPDATE users_login SET password=? WHERE idUser=?";
            $stmt1 = $conn->prepare($sql1);
            if (!$stmt1) {
                throw new Exception($conn->error);
            }
            $stmt1->bind_param("si", $password, $idUser);
            if (!$stmt1->execute()) {
                throw new Exception($stmt1->error);
            }
            $stmt1->close();
        }

        // Actualiaz el resto de los datos en la tabla de perfil
        $sql2 = "UPDATE users_data
                 SET nombre=?, apellidos=?, email=?, telefono=?, fecha_nacimiento=?, direccion=?, sexo=?
                 WHERE idUser=?";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            throw new Exception($conn->error);
        }
        $stmt2->bind_param("sssssssi", $nombre, $apellidos, $email, $telefono, $fecha, $direccion, $sexo, $idUser);
        if (!$stmt2->execute()) {
            throw new Exception($stmt2->error);
        }
        $stmt2->close();

        // Respuesta de éxito en formato JSON
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        // Respuesta de error en formato JSON
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
?>