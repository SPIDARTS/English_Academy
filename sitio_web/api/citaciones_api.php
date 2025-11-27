<?php
    // Conexión a la base de datos e inicia sesión
    include "../includes/db.php";
    session_start();
    header("Content-Type: application/json");

    // Usuario logueado y acción recibida (por POST o GET)
    $idUser = $_SESSION['idUser'] ?? null;
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    // Respuesta por defecto
    $response = ["success" => false, "msg" => "Acción no válida"];

    // Si no hay sesión activa, se corta 
    if (!$idUser) {
        echo json_encode(["success" =>false, "msg"=>"Usuario no autenticado"]);
        exit;
    }

    // Controlador de acciones (listar, nueva, editar, borrar)
    switch ($action) {
        case "listar":
            // Obtener todas las citas del usuario
            $sql = "SELECT * FROM citas WHERE idUser='$idUser' ORDER BY fecha_cita ASC";
            $result = mysqli_query($conn, $sql);
            $citas = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $citas[] = $row;
            }
            $response = ["success"=>true, "citas"=>$citas];
            break;
        case "nueva":
            // Crear nueva cita
            $fecha = $_POST['fecha_cita'];
            $hora = $_POST['hora_cita'];
            $motivo = trim($_POST['motivo']);
            if ($fecha && $hora && $motivo ) {
                $sql = "INSERT INTO citas (idUser, fecha_cita, hora_cita, motivo)
                        VALUES ('$idUser','$fecha','$hora','$motivo')";
                if (mysqli_query($conn, $sql)) {
                    $response = ["success"=>true, "msg"=>"Cita creada"];
                } else {
                    $response = ["success"=>false, "msg"=>mysqli_error($conn)];
                }
            }
            break;
        case "editar":
            // Editar cita 
            $idCita = $_POST['idCita'];
            $fecha = $_POST['fecha_cita'];
            $hora = $_POST['hora_cita'];
            $motivo = $_POST['motivo'];

            $check = mysqli_query($conn, "SELECT fecha_cita FROM citas WHERE idCita='$idCita' AND idUser='$idUser'");
            $row = mysqli_fetch_assoc($check);
            if ($row && $row['fecha_cita'] >= date("Y-m-d")) {
                $sql = "UPDATE citas
                        SET fecha_cita='$fecha', hora_cita='$hora', motivo='$motivo'
                        WHERE idCita='$idCita' AND idUser='$idUser'";
                if (mysqli_query($conn, $sql)) {
                    $response = ["success"=>true, "msg"=>"Cita actualizada"];

                }
            } else {
                $response = ["seccess"=>false, "msg"=>"No se puede editar una cita pasada"];
            }
            break;
        case "borrar":
            // Borrar cita
            $idCita =$_POST['idCita'];
            $check = mysqli_query($conn, "SELECT fecha_cita FROM citas WHERE idCita='$idCita' AND idUser='$idUser'");
            $row = mysqli_fetch_assoc($check);
            if ($row && $row['fecha_cita'] >= date("Y-m-d")) {
                mysqli_query($conn, "DELETE FROM citas WHERE idCita='$idCita' AND idUser='$idUser'");
                $response = ["success"=>true, "msg"=>"Cita borrada"];
            } else {
                $response = ["success"=>false, "msg"=>"No se puede borrar una cita pasada"];
            }
            break;
    }

    // Devuelve la respuesta en JSON
    echo json_encode($response);
?>