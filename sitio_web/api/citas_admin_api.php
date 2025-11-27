<?php
    // Conexión a la base de datos e inicia sesión
    include "../includes/db.php";
    if (session_status() === PHP_SESSION_NONE) session_start();
    header("Content-Type: application/json");

    // Solo administradores pueden usar esta API
    if  (!isset ($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        echo json_encode(["success"=>false, "msg"=>"No autorizado"]);
        exit;
    }

    // Función para manejar errores de SQL
    function sqlError($conn) {
        echo json_encode(["success" => false, "msg" => $conn->error]);
        exit;
    }
    // Acción enviada por GET
    $accion = $_GET['action'] ?? '';

    // Ejecuta la acción correspondiente
    switch ($accion) {

        // Lista todos los usuarios
        case "listarUsuarios":
            $sql = "SELECT idUser, nombre, apellidos FROM users_data";
            $res = $conn->query($sql);
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;

        // Lista citas de un usuario específico
        case "listarCitas":
            $idUser = intval($_GET['idUser']);
            $sql = "SELECT * FROM citas WHERE idUser = $idUser";
            $res = $conn->query($sql);
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;
        
        // Crear nueva cita
        case "crear":
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "INSERT INTO citas (idUser, fecha_cita, motivo) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $data['idUser'], $data['fecha_cita'], $data['motivo']);
            $stmt->execute();
            echo json_encode(["success" => true]);
            break;

        // Actualizar una cita existente
        case "actualizar":
            $data = json_decode(file_get_contents("php://input"), true);
            $sql = "UPDATE citas SET fecha_cita=?, motivo=? WHERE idCita=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $data['fecha_cita'], $data['motivo'], $data['idCita']);
            $stmt->execute();
            echo json_encode(["success" => true]);
            break;

        // Borrar una cita
        case "borrar":
            $id = intval($_GET['idCita']);
            $sql = "DELETE FROM citas WHERE idCita = $id";
            $conn->query($sql);
            echo json_encode(["success" => true]);
            break;

        // Acción no válida
        default:
            echo json_encode(["error" => "Acción no válida"]);
    }
    ?>