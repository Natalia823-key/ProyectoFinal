<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Invernadero</title>
    <?php include 'header.html' ?>
</head>

<body>
    <div class="contenido">
        <div class="head">
            <header>
            <?php  if (isset($_SESSION['name_user'])) {
                    echo '<p class="bienvenido">Usuario: ' . htmlspecialchars($_SESSION['name_user']) . '</p>';
                } ?>
                <img class="logo" src="imagenes/logoN.png" alt="logo">
                <h1><span>Invernadero</span></h1>
            </header>
        </div>

        <div class="nav">
            <nav class="nav" aria-label="menu navegación">
                <ul>
                    <li><a href="index.php">Inicio</a>

                    </li>
                    <li><a href="valesIni.php">Vales</a></li>

                    <li><a href="productos.php">Productos</a></li>

                    <li><a href="gastos.php">Gastos</a></li>

                    <li><a href="empleados.php">Empleados</a></li>

                </ul>

            </nav>
        </div>

        <div class="container">
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prevBtn" class="btn btn-outline-success">&lt;</button>
                    <span id="currentMonth" class="mx-4"></span>
                    <button id="nextBtn" class="btn btn-outline-success">&gt;</button>
                </div>
                <table id="calendar" class="table table-bordered table-responsive-sm"></table>
            </div>
            <div id="eventForm" class="event-form">
                <h2>Agregar Evento</h2>
                <input type="text" id="eventDescription" class="form-control mb-2" placeholder="Descripción del Evento">
            </div>
            <div id="eventList" class="event-list"><h3>Eventos programados</h3></div>
        </div>

        <script src="scriptsJs/calendario.js"></script>

    </div>
</body>
</html>
    