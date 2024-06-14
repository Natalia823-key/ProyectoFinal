<?php  
 require_once 'conf.inc.php';
 session_start();              
$database = new Database();
$conn = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Vales</title>
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
                <h1><span>Vales</span></h1>
            </header>
        </div>

        <div class="nav">
            <nav class="nav" aria-label="menu navegación">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="invernadero.php">Invernadero</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="gastos.php">Gastos</a></li>
                    <li><a href="empleados.php">Empleados</a></li>

                </ul>

            </nav>
        </div>
        <div class="list-group">
            <button type="button" class="list-group-item list-group-item-action secondary" id="centrar">Listado de Vales</button>
        <?php 
            // Manejo de la solicitud POST para agregar un nuevo vale
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $numero_vale = $_POST['num_vale'];
                $precio_vale = $_POST['precio_vale'];
                $invernadero_id = $_POST['invernadero_id'];

                // Validar los datos recibidos
                if (!empty($numero_vale) && !empty($precio_vale) && !empty($invernadero_id)) {
                    try {
                        // Insertar el nuevo vale en la base de datos
                        $insert_query = "INSERT INTO vales (num_vale, precio_vale, invernadero_id) VALUES (:num_vale, :precio_vale, :invernadero_id)";
                        $insert_stmt = $conn->prepare($insert_query);
                        $insert_stmt->bindParam(':num_vale', $numero_vale, PDO::PARAM_INT);
                        $insert_stmt->bindParam(':precio_vale', $precio_vale, PDO::PARAM_STR);
                        $insert_stmt->bindParam(':invernadero_id', $invernadero_id, PDO::PARAM_INT);

                        if ($insert_stmt->execute()) {
                            echo "<p>Vale guardado exitosamente.</p>";
                        } else {
                            echo "<p>Error al guardar el vale.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "<p>Por favor, complete todos los campos.</p>";
                }
            }

            // Consulta para obtener los vales guardados
            $query = "SELECT num_vale, precio_vale FROM vales";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $num_vales = $stmt->rowCount();

            if ($num_vales > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<button type='button' class='vales'>Número: $num_vale - Precio: $precio_vale</button>";
                }
            } else {
                echo "<p>No se encontraron vales guardados.</p>";
            }
        ?>
        </div>
        <button type="button" class="vales" id="addValeBtn">Añadir vale</button>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form id="addValeForm" method="post" action="">
                    <label for="numero_vale">Número de Vale:</label>
                    <input type="text" id="numero_vale" name="num_vale" required><br><br>
                    <label for="precio_vale">Precio del Vale:</label>
                    <input type="text" id="precio_vale" name="precio_vale" required><br><br>
                    <label for="invernadero_id">Invernadero:</label>
                    <select id="invernadero_id" name="invernadero_id" required>
                        <?php
                        // Obtener la lista de invernaderos
                        $invernadero_query = "SELECT id_inver, nom_inver FROM invernadero";
                        $invernadero_stmt = $conn->prepare($invernadero_query);
                        $invernadero_stmt->execute();

                        while ($row = $invernadero_stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id_inver']}'>{$row['nom_inver']}</option>";
                        }
                        ?>
                    </select>
                    <button class="guardar" type="submit">Guardar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="scriptsJs/vales.js"></script>
</body>

</html>