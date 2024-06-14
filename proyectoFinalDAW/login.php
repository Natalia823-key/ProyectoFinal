<?php
include_once 'gestion.php';
session_start();

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$conn = $database->getConnection();

// Verificar si el formulario de inicio de sesión ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Crear una instancia de la clase User
    $user = new User($conn);

    // Llamar al método login e imprimir el resultado si hay un error
    $login_result = $user->login($email, $password);
    if ($login_result !== true) {
        echo $login_result;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>AppAgricultorLogin</title>
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
                <h2>
                    <p>Bienvenido,</p>
                    <p>inicia sesión y empieza usar la APP</p>
                </h2>
            </header>
            <form action="" method="POST" class="form">
                <fieldset>
                    <legend class="legend">Datos personales</legend>
                    <ul>
                        <li class="icon">
                            <label class="email" for="email">Dirección de email</label>
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email">
                        </li>
                        <li class="icon">
                            <label class="pas" for="password">Contraseña</label>
                            <i class="fas fa-lock"></i> 
                            <input type="password" id="password" name="password">
                        </li>
                        <li>
                            <button class="boton" type="submit" name="login" value="Iniciar Sesión"><a href="index.php">Iniciar Sesión</a></button>
                        </li>
                        <li>
                            <p class="a">¿Aún no tienes cuenta? <a href="registro.php">Crear una cuenta gratuita</a></p>
                        </li>
                    </ul>
                </fieldset>
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
