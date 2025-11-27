<?php
    // Conexión a la base de datos
    include "../includes/db.php";
    
    $mensaje = "";

    // Iniciamos la sesión si no lo está
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = trim($_POST['usuario']);
        $password = trim($_POST['password']);
        
    // Buscar usuario en la base de datos
    $stmt =$conn->prepare("SELECT l.idLogin, l.idUser, l.usuario, l.password, l.rol, d.nombre, d.apellidos, d.email 
                            FROM users_login l
                            JOIN users_data d ON l.idUser = d.idUSER 
                            WHERE l.usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if ($result->num_rows === 1){
        $row =$result->fetch_assoc();
        $hash = $row['password'];

        // Verificar contraseña
        if(password_verify($password, $hash)) {
            

            // Guarda los datos en la sesión
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['idLogin'] = $row['idLogin'];
            $_SESSION['idUser'] = $row['idUser'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellidos'] = $row['apellidos'];
            $_SESSION['email'] = $row['email'];
            
            $mensaje = " ✅ Login correcto. Redirigiendo...";

            if($row['rol'] === 'admin') {
                header("Location: usuarios_administracion.php");
                exit();
            } else if($row['rol'] === 'user') {
                header("Location: perfil.php");
                exit();
            }
        
        } else {
            $mensaje = " ⚠️ Contraseña incorrecta.";
        }

    } else {
        $mensaje = " ⚠️ Usuario no encontrado.";
    }
}
?>
<?php if (basename($_SERVER['PHP_SELF']) === 'login.php'): ?>
    <script src="../assets/js/login.js" defer></script>
<?php endif; ?>
<?php 
// Conexión a la cabecera que contiene la estructura inicial 
// (<html>, <head>, barra de navegación y apertura del <body>)
include "../includes/header.php";
?>

<section id='login'>
    <h2>Iniciar Seción</h2>
    <p>Introduce tus datos para acceder a tu cuenta:</p>
    <!-- Formulario de inicio de sesión -->
    <form action="login.php" method="post" autocomplete="off">
        <label for="usuario">Usuario</label><br>
        <input type="text" id="usuario" name="usuario" required autocomplete="new-password"><br>

        <label for="usuario">Contraseña</label><br>
        <input type="password" id="password" name="password" required autocomplete="new-password"><br>

        <label for="mostrarPassword" class="mostrar-pass">
        <input type="checkbox" id="mostrarPassword" onclick="togglePassword()">
        Mostrar contraseña
        </label>

        <button type="submit">Iniciar Sesión</button>
    </form>
    <!-- Enlace alternativo para registrarse si no tiene una cuenta -->
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí </a></p>
</section>

<?php
   
    //Conexión al pie de página que cierra el <body> y el <html>
    
    include '../includes/footer.php';
?>
