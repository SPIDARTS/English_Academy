<?php
// Valida que no falten coampos obligatorios
    function camposVacios($datos, $camposObligatorios) {
        foreach ($camposObligatorios as $campo) {
            if (empty(trim($datos[$campo] ?? ""))) {
                return true;
            }
        }
        return false;
    }
// Validar email correcto
    function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
// Validar teléfono (7-15 dígitos numéricos)
    function validarTelefono($telefono) {
        return preg_match("/^[0-9]{7,15}$/", $telefono);
    }

// Validar usuario (mínimos 4 caracteres)
    function validarUsuario($usuario) {
        return strlen($usuario) >= 4;
    }
// Validar contraseña 
    function validarPassword($password) {
        return strlen($password) >= 8 &&
            preg_match("/[A-Z]/", $password)&&
            preg_match("/[a-z]/", $password)&&
            preg_match("/[0-9]/", $password)&&
            preg_match("/[\W]/", $password);
    }
// Función para registrar usuario
function registrarUsuario($conn, $datos) {
    $stmt = $conn->prepare("INSERT INTO users_data
        (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo)
        VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",
        $datos['nombre'],
        $datos['apellidos'],
        $datos['email'],
        $datos['telefono'],
        $datos['fecha_nacimiento'],
        $datos['direccion'],
        $datos['sexo']
    );
    $stmt->execute();
    $idUser = $stmt->insert_id;

    $hash = password_hash($datos['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users_login (idUser, usuario, password, rol) VALUES (?,?,?, 'user')");
    $stmt->bind_param("iss", $idUser, $datos['usuario'], $hash);
    $stmt->execute();

    return true;
}
?>