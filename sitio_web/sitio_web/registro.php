<?php 
    // Conexión a la base de datos, a las funciones y a la
    // cabecera que contiene la estructura inicial 
    // (<html>, <head>, barra de navegación y apertura del <body>)
    include "../includes/db.php";
    include "../includes/header.php";
    include "../includes/funciones.php";

    // Variable para mostrar mensaje al usuario
    $mensaje ="";

    // Verifica si el formulario fue enviado usando el método POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recibimos y limpiamos los datos del formulario
        $datos = [
            "nombre"    => trim($_POST['nombre']),
            "apellidos" => trim($_POST['apellidos']),
            "email"     => trim($_POST['email']),
            "telefono"  => trim($_POST['telefono']),
            "fecha_nacimiento" => trim($_POST['fecha_nacimiento']),
            "direccion" => trim($_POST['direccion']),
            "sexo"      => trim($_POST['sexo']),
            "usuario"   => trim($_POST['usuario']),
            "password"  => trim($_POST['password']),
        ];
    // Validaciones
    if (camposVacios($_POST,['nombre', 'apellidos','email','telefono','fecha_nacimiento','usuario','password'])) {
        $mensaje = " ⚠️ Todos los campos obligatorios deben completarse.";    
    } elseif (!validarEmail($datos['email'])) {
        $mensaje = " ⚠️ El correo electrónico no es válido.";
    } elseif (!validarUsuario($datos['usuario'])){
        $mensaje = " ⚠️ El usuario debe tener al menos 4 caracteres.";
    } elseif (!validarPassword($datos['password'])) {
        $mensaje = " ⚠️ La caontraseña debe tener al menos 8 caracteres, inlcuyendo mayúscula, minúscula, número y símbolo.";
    } else {
    // Verificar si ya existe email o usuario
        $sql = "SELECT * FROM users_data u
                JOIN users_login l ON u.idUSER = l.idUser
                WHERE u.email=? OR l.usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $datos['email'], $datos['usuario']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $mensaje = " ⚠️ El usuario o correo ya están registrados.";
        } else {
            registrarUsuario($conn, $datos);
            $mensaje = " ✅ Registro correcto. Redirigiendo a login...";
            header("refresh:2;url=login.php");
        }
    }
}?>

<!-- HTML -->
<h2>Registro</h2>
<?php if ($mensaje) echo "<p>$mensaje</p>"; ?>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required
        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"><br>
    <input type="text" name="apellidos" placeholder="Apellidos" required
        value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"><br>
    <input type="email" name="email" placeholder="Email" required
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br>
    <input type="text" name="telefono" placeholder="Teléfono" required
        value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"><br>
    <input type="date" name="fecha_nacimiento" required
        value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>"><br>
    <input type="text" name="direccion" placeholder="Dirección" required
        value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>"><br>


    <select name="sexo">
        <option value="masculino" <?= (($_POST['sexo'] ?? '') == 'masculino') ? 'selected' : '' ?>>Masculino</option>
        <option value="femenino" <?= (($_POST['sexo'] ?? '') == 'femenino') ? 'selected' : '' ?>>Femenino</option>
        <option value="otro" <?= (($_POST['sexo'] ?? '') == 'otro') ? 'selected' : '' ?>>Otro</option>
    </select><br>

    <input type= "text" name="usuario" placeholder="Usuario" required
        value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>

    <button type="submit">Registrarse</button>
</form>

<!-- Enlace a login -->
<a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>


<?php
//Conexión al pie de página que cierra el <body> y el <html>
    include "../includes/footer.php";
?>

