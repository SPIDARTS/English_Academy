<?php
include __DIR__ . "/../includes/db.php";

session_start();

// Forzar JSON siempre
header("Content-Type: application/json; charset=UTF-8");

// Mostrar errores en el log, no en pantalla (evita HTML)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Solo administradores pueden usar esta API
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(["success" => false, "msg" => "No autorizado"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$idUser = $_SESSION['idUser'] ?? null;

// Verifica conexión DB
if (!$conn) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    // Listar todas la noticias
    if ($method === 'GET') {
        if (isset($_GET['accion']) && $_GET['accion'] === 'listar') {
            $sql = "SELECT n.*, u.nombre AS autor
                    FROM noticias n
                    JOIN users_data u ON n.idUser = u.idUser
                    ORDER BY fecha DESC";
            $stmt = $conn->query($sql);
            if (!$stmt) {
                throw new Exception($conn->error);
            }
            $noticias = $stmt->fetch_all(MYSQLI_ASSOC);
            echo json_encode($noticias);
            exit;
        }
    }

    // Crear, editar o borrar noticias

    elseif ($method === 'POST') {
        $data = $_POST;
        $file = $_FILES['imagen'] ?? null;
        $accion = $data['accion'] ?? '';
        $upload_dir = __DIR__ . "/../assets/images/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0775, true);
        }

        $upload_image = function ($file, $upload_dir) {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $nombre_archivo = uniqid() . '-' . basename($file['name']);
                $ruta_destino = $upload_dir . $nombre_archivo;
                if (move_uploaded_file($file['tmp_name'], $ruta_destino)) {
                    return $nombre_archivo;
                } else {
                    throw new Exception("Error al mover la imagen al directorio destino");
                }
            }
            return null;
        };

        switch ($accion) {

            // Crear una nueva noticia
            case 'crear':
                $nombre_imagen = $upload_image($file, $upload_dir);
                $sql = "INSERT INTO noticias (titulo, texto, fecha, idUser, imagen)
                        VALUES (?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssis", $data['titulo'], $data['texto'], $data['fecha'], $idUser, $nombre_imagen);
                $stmt->execute();
                echo json_encode(['mensaje' => 'Noticia creada correctamente']);
                break;

            // Editar una noticia existente
            case 'editar':
                $imagen_actual = $data['imagen_actual'] ?? null;
                $nombre_imagen = $imagen_actual;
                if ($file && $file['error'] === UPLOAD_ERR_OK) {
                    $nuevo = $upload_image($file, $upload_dir);
                    if ($imagen_actual && file_exists($upload_dir . $imagen_actual)) {
                        unlink($upload_dir . $imagen_actual);
                    }
                    $nombre_imagen = $nuevo;
                }
                $sql = "UPDATE noticias SET titulo=?, texto=?, fecha=?, imagen=? WHERE idNoticia=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $data['titulo'], $data['texto'], $data['fecha'], $nombre_imagen, $data['idNoticia']);
                $stmt->execute();
                echo json_encode(['mensaje' => 'Noticia actualizada correctamente']);
                break;

            // Borrar una noticia
            case 'borrar':
                $idNoticia = $data['idNoticia'];
                $stmt = $conn->prepare("SELECT imagen FROM noticias WHERE idNoticia=?");
                $stmt->bind_param("i", $idNoticia);
                $stmt->execute();
                $stmt->bind_result($imagen_a_borrar);
                $stmt->fetch();
                $stmt->close();

                $stmt = $conn->prepare("DELETE FROM noticias WHERE idNoticia=?");
                $stmt->bind_param("i", $idNoticia);
                $stmt->execute();

                if ($imagen_a_borrar && file_exists($upload_dir . $imagen_a_borrar)) {
                    unlink($upload_dir . $imagen_a_borrar);
                }
                echo json_encode(['mensaje' => 'Noticia eliminada']);
                break;

            default:
                echo json_encode(['error' => 'Acción no válida']);
        }
    } else {
        echo json_encode(['error' => 'Método no permitido']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
