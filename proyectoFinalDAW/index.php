<?php
require_once 'conf.inc.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Inicio</title>
    <?php include 'header.html' ?>
</head>

<body>
    <div class="contenido">
        <header class="head">
            <?php if (isset($_SESSION['name_user'])) : ?>
                <p class="bienvenido">Bienvenido, <?= htmlspecialchars($_SESSION['name_user']); ?></p>
            <!--<?php //else : ?>
                <p class="bienvenido">Bienvenido, invitado</p>-->
            <?php endif; ?>
            <img class="logo" src="imagenes/logoN.png" alt="logo">
            <h1><span>App Agricultor</span></h1>
        </header>

        <nav class="nav" aria-label="menu de navegación">
                <ul>
                    <li><a href="invernadero.php">Invernadero</a></li>
                    <li><a href="valesIni.php">Vales</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="gastos.php">Gastos</a></li>
                    <li><a href="empleados.php">Empleados</a></li>
                </ul>
        </nav>

        <section class="info">
            <div class="column">
                <div class="element"><a href="https://www.fhalmeria.com/pizarra-de-precios/almeria">Pizarra de precios</a></div>
                <div class="element"><a href="https://agroinformacion.com/">Noticias</a></div>
                <div class="element"><a href="https://www.aemet.es/es/portada">Tiempo</a></div>
            </div>
            <div class="column">
                <div class="element"><a href="#">Contactar</a></div>
                <div class="element"><a href="#">Acerca de nosotros</a></div>
                <div class="element"><a href="https://www.craviottoyfernandez.com/">Contacto con almacén</a></div>
            </div>
        </section>

        <footer class="footer">
            <div class="copyright">
                <p>Derechos reservados &copy; 2024-25</p>
            </div>
            <div class="redes">
                <p>Redes sociales: </p>
                <a href="https://www.facebook.com/?locale=es_ES">
                    <img src="imagenes/fac.PNG" alt="enlace a facebook">
                </a>
                <a href="https://www.instagram.com/">
                    <img src="imagenes/inst.PNG" alt="enlace a instagram">
                </a>
                <a href="https://www.youtube.com/">
                    <img src="imagenes/yout.PNG" alt="enlace a youtube">
                </a>
            </div>
        </footer>
    </div>
</body>

</html>