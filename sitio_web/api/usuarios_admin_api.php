<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    // Conexión a la base de datos e inicia sesión
    include "../includes/db.php";
    session_start();
    header("Content-Type: application/json");

    // Evitar caché del navegador
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Solo administradores pueden usar esta API
    if  (!isset ($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        echo json_encode(["success"=>false, "msg"=>"No autorizado"]);
        exit;
    }

    // Acción recibida por POST O GET
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    // Controlador de acciones: listar, guardar, borrarsi
    switch ($action) {
        
        case "listar":
            $res = mysqli_query($conn, 
                "SELECT d.idUSER, d.nombre, d.email, l.usuario, l.rol
                FROM users_data d
                JOIN users_login l ON d.idUSER = l.idUser
            ");
            $usuarios = [];
            while ($u = mysqli_fetch_assoc($res)) $usuarios[] = $u;
            echo json_encode(["success" =>true, "usuarios"=>$usuarios]);
            break;

        case "guardar":
            $id = $_POST['idUser'] ?? '';
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $usuario = $_POST['usuario'];
            $rol = $_POST['rol'];
            $pass = $_POST['password'];

            if ($id == '') {
                mysqli_query($conn, "INSERT INTO users_data (nombre, email, apellidos, telefono, fecha_nacimiento) VALUES ('$nombre','$email','','','2000-01-01')");
                $nuevoId = mysqli_insert_id($conn);
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                mysqli_query($conn, "INSERT INTO users_login (idUser, usuario, password, rol) VALUES ('$nuevoId','$usuario','$hash','$rol')");
                echo json_encode(["success"=>true, "msg"=>"Usuario creado"]);
            } else {
                mysqli_query($conn, "UPDATE users_data SET nombre='$nombre', email='$email' WHERE idUSER='$id'");
                if (!empty($pass)) {
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    mysqli_query($conn, "UPDATE users_login SET usuario='$usuario', password='$hash', rol='$rol' WHERE idUser='$id'");
            } else {
                mysqli_query($conn, "UPDATE users_login SET usuario='$usuario', rol='$rol' WHERE idUser='$id'");
            }
                echo json_encode(["success"=>true, "msg"=>"Usuarrio actualizado"]);
            }
            break;

        case "borrar":
            $id = $_POST['idUser'];
            mysqli_query($conn, "DELETE FROM users_login WHERE idUser='$id'");
            mysqli_query($conn, "DELETE FROM users_data WHERE idUSER='$id'");
            echo json_encode(["success"=>true, "msg"=>"Usuario eliminado"]);
            break;
    }
?>