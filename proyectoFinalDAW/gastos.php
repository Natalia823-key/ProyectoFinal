<?php
require_once 'conf.inc.php';
require_once 'gestion.php';
session_start();
$database = new Database();
$conn = $database->getConnection();
$expenses = new Expenses($conn);

// Crear una instancia de la clase Expenses
$expenses = new Expenses($conn);

// Obtener los gastos y ganancias utilizando los métodos de la clase Expenses
$gastos_por_invernadero = $expenses->getGastos();
$ganancias_por_invernadero = $expenses->getGanancias();

// Calcular el total de gastos y ganancias
$total_gastos = $expenses->getTotalGastos($gastos_por_invernadero);
$total_ganancias = $expenses->getTotalGanancias($ganancias_por_invernadero);          

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gastos</title>
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
                <h1><span>Gastos</span></h1>
            </header>
        </div>

        <div class="nav">
            <nav class="nav" aria-label="menu navegación">
                <ul>
                    <li><a href="index.php">Inicio</a>

                    </li>
                    <li><a href="invernadero.php">Invernadero</a></li>

                    <li><a href="valesIni.php">Vales</a></li>

                    <li><a href="productos.php">Productos</a></li>

                    <li><a href="empleados.php">Empleados</a></li>

                </ul>

            </nav>
        </div>
        <div class="tabla">
          <h2>Datos de Gastos y Ganancias</h2>
          <table class="table table-hover" aria-describedby="tabla de gastos y ganancias">
            <thead class="table-bordered font-weight-bold">
                    <tr>
                        <th scope="col">Invernadero</th>
                        <th scope="col">Gastos (€)</th>
                        <th scope="col">Ganancias (€)</th>
                    </tr>
            </thead>
            <tbody>
            <?php foreach ($gastos_por_invernadero as $key => $gasto): ?>
                <tr>
                    <td><?php echo $gasto['nombre_invernadero']; ?></td>
                    <td><?php echo $gasto['gastos']; ?></td>
                    <td><?php echo isset($ganancias_por_invernadero[$key]) ?$ganancias_por_invernadero[$key]['ganancias'] : 0; ?></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td class="font-weight-bold">Total</td>
                    <td><?php echo $total_gastos; ?></td>
                    <td><?php echo $total_ganancias; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="font-weight-bold">Saldo</td>
                    <td class="font-weight-bold"><?php echo $total_ganancias - $total_gastos; ?></td>
                </tr>
            </tbody>
          </table>
        </div>
    </div>
</body>
</html>