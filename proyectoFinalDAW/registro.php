<?php
require_once 'gestion.php';
session_start();
$database = new Database();
$conn = $database->getConnection();
$user = new User($conn);  // Crear una instancia de la clase User
// Registro de usuarios
if(isset($_POST['register'])) {
    echo "Formulario enviado"; 
    $name = $_POST['name'];
    $email = $_POST['email'];
    // Llamar al método register de la clase User
    $result = $user->register($name, $email, $password);

    // Mostrar el resultado
    if ($result === true) {
        echo '<div id="success">Usuario registrado correctamente.</div>';
        header("Location: login.php");
        exit();
    } else {
        echo '<div id="error">' . $result . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>AppAgricultorRegistro</title>
    <?php include 'header.html' ?>
</head>

<body>
    <div class="principal">
        <div class="cabecera">
            <header>
                <img class="logo" src="imagenes/logoN.png" alt="logo">
                <h1><span>App Agricultor</span></h1>
            </header>
        </div>
            <main>
                    <header>
                            <h2><p>Crear cuenta</p> 
                            </h2>
                    </header>
                       <form action="" method="POST" class="form">
                          <ul>
                            <li class="icon">
                                <label class="name" for="name">Nombre y Apellidos</label>
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="name" name="name" autocomplete="name" required>
                            </li>
                            <li class="icon">
                                <label class="email" for="email">Dirección de email</label>
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required autocomplete="email">
                            </li>
                            <li class="icon">
                                <label class="pas" for="password">Contraseña</label>
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" required autocomplete="off">
                            </li>
                            <li>
                                <button  class="boton" type="submit" name="register" value="Registrarse">Registrarse</button>
                            </li>
                          </ul>
                       </form>
            </main>
        <footer>
            <div class="copyright">
                <p>Derechos reservados &copy; 2024-25</p>
            </div>
        </footer>
    </div>
    <script src="scriptsJs/icono.js"></script>
</body>

</html>