<?php
require_once 'conf.inc.php';
require_once 'gestion.php';
session_start();
$database = new Database();
$conn = $database->getConnection();
$products = new Products($conn);

// Manejar el caso de agregar un nuevo producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_producto"]) && isset($_POST["cantidad"]) && isset($_POST["precio"]) && isset($_POST["invernadero_id"])) {
    // Recuperar los datos del formulario
    $nombre_producto = $_POST["nombre_producto"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $invernadero_id = $_POST["invernadero_id"];

    // Llamar al método addProduct de la instancia $products
    echo $products->addProduct($nombre_producto, $cantidad, $precio, $invernadero_id);
}
// Manejar el caso de eliminar un producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_producto"]) && isset($_POST["id_producto"])) {
    // Recuperar el ID del producto a eliminar
    $id_producto = $_POST["id_producto"];

    // Llamar al método deleteProduct de la instancia $products
    echo $products->deleteProduct($id_producto);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Productos</title>
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
                <h1><span>Productos</span></h1>
            </header>
        </div>

        <div class="nav">
            <nav class="nav" aria-label="menu navegación">
                <ul>
                    <li><a href="index.php">Inicio</a>

                    </li>
                    <li><a href="invernadero.php">Invernadero</a></li>

                    <li><a href="valesIni.php">Vales</a></li>

                    <li><a href="gastos.php">Gastos</a></li>

                    <li><a href="empleados.php">Empleados</a></li>
                </ul>
            </nav>
        </div>
        <div>
        </div>
        <div class="tabla">
            <table class="table table-hover" aria-describedby="tabla de productos">
                <thead class="table-bordered font-weight-bold">
                    <tr>
                        <th scope="col">Productos</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Opcion</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Obtener la lista de productos de la base de datos
                    $sql = "SELECT id_prod, nom_prod, num_prod, precio_prod FROM productos";
                    $resultado = $conn->query($sql);

                    if ($resultado->rowCount() > 0) {
                        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($fila["nom_prod"]) . "</td>";
                            echo "<td>" . htmlspecialchars($fila["num_prod"]) . "</td>";
                            echo "<td>" . htmlspecialchars($fila["precio_prod"]) . "</td>";
                            echo "<td><form method='post'><input type='hidden' name='eliminar_producto' value='true'><input type='hidden' name='id_producto' value='" . htmlspecialchars($fila["id_prod"]) . "'><button type='submit' class='eliminar'>Eliminar</button></form></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay productos</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="new">
            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modalAgregarProducto">Añadir producto</button>
        </div>    
         <!-- Modal para agregar producto -->
         <div class="modal" id="modalAgregarProducto" tabindex="-1" role="dialog" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarProductoLabel">Añadir Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para añadir productos -->
                        <form method="post">
                           <div class="form-group">
                               <label for="nombre_producto">Nombre del Producto:</label>
                               <input type="text"  class="form-control" id="nombre_producto" name="nombre_producto" required>
                           </div>
                           <div class="form-group">
                               <label for="cantidad">Cantidad:</label>
                               <input type="number"  class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                            <div class="form-group">
                               <label for="precio">Precio:</label>
                                <input type="text"  class="form-control" id="precio" name="precio" required>
                            </div>
                            <div class="form-group">
                               <!-- Agregar un campo para seleccionar el invernadero -->
                               <label for="invernadero_id">Invernadero:</label>
                               <select  class="form-control" id="invernadero_id" name="invernadero_id">
                                <?php
                                  // Consulta para obtener la lista de invernaderos
                                   $sql_invernaderos = "SELECT id_inver, nom_inver FROM invernadero";
                                  $resultado_invernaderos = $conn->query($sql_invernaderos);

                                   // Mostrar opciones de invernaderos en el select
                                   while ($fila_invernadero = $resultado_invernaderos->fetch(PDO::FETCH_ASSOC)) {
                                       echo "<option value='" . htmlspecialchars($fila_invernadero["id_inver"]) . "'>" . htmlspecialchars($fila_invernadero["nom_inver"]) . "</option>";
                                    }
                                ?>
                               </select>
                            </div>
                            <button type="submit" role="button" class="btn btn-outline-secondary">Añadir producto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>