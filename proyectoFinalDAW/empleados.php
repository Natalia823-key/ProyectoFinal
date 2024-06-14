<?php
require_once 'conf.inc.php';
require_once 'gestion.php';
session_start();
$database = new Database();
$conn = $database->getConnection();
$employeeManager = new Employee($conn);

$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
// Manejar la inserción o actualización de empleados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
    if ($user_role != 'member') {
       // Recoger los datos del formulario
       $dni = isset($_POST["dni"]) ? $_POST["dni"] : null;
       $name = isset($_POST["name"]) ? $_POST["name"] : null;
       $email = isset($_POST["email"]) ? $_POST["email"] : null;
       $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : null;
       $rol = isset($_POST["rol"]) ? $_POST["rol"] : null;
       $horas = isset($_POST["horas"]) ? $_POST["horas"] : null;

         // Verificar si todos los datos necesarios están presentes
       if (!$dni || !$name || !$email || !$telefono || !$rol || !$horas) {
           echo "Todos los campos son obligatorios.";
           exit();
        }
        // Depuración: Verificar los datos
        echo "DNI: $dni, Name: $name, Email: $email, Telefono: $telefono, Rol: $rol, Horas: $horas";

        // Llamar a la función manageEmployee para insertar o actualizar el empleado
         $result = $employeeManager->manageEmployee($dni, $name, $email, $telefono, $rol, $horas);
           echo $result;
    } else {
    echo "No tiene permisos para realizar esta acción.";
    exit();
    }
}


// Manejar la eliminación de empleados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar"])) {
    if ($user_role != 'member') {
       $dni = isset($_POST["dni"]) ? $_POST["dni"] : null;

       // Verificar si el DNI está presente
       if (!$dni) {
          echo "El DNI es obligatorio para eliminar un empleado.";
          exit();
        }

        // Llamar a la función deleteEmployee para eliminar el empleado
        $result = $employeeManager->deleteEmployee($dni);
          echo $result;
    } else {
            echo "No tiene permisos para realizar esta acción.";
            exit();
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Empleados</title>
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
                <h1><span>Empleados</span></h1>
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

                    <li><a href="gastos.php">Gastos</a></li>

                </ul>

            </nav>
        </div>
        <div class="tabla-usuarios">
            <table class="table table-hover">
                <thead class="table-bordered font-weight-bold">
                    <tr>
                        <th scope="col">DNI</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Horas</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.dni, u.name, u.email, e.telefono, e.rol, e.horas FROM empleados e INNER JOIN users u ON e.user_id = u.id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($stmt->rowCount() > 0) {
                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["dni"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["telefono"] . "</td>";
                            echo "<td>" . $row["rol"] . "</td>";
                            echo "<td>" . $row["horas"] . "</td>";
                            echo "<td>
                                     <button class='btn btn-primary btn-sm  select-user' data-toggle='modal' data-target='#editarModal'
                                        data-dni='" . $row["dni"] . "' 
                                        data-name='" . $row["name"] . "' 
                                        data-email='" . $row["email"] . "' 
                                        data-telefono='" . $row["telefono"] . "' 
                                        data-rol='" . $row["rol"] . "' 
                                        data-horas='" . $row["horas"] . "'>Editar</button>
                                    <form method='post' action=''>
                                        <input type='hidden' name='dni' value='" . $row["dni"] . "'>
                                        <button type='submit' name='eliminar' class='btn btn-danger btn-sm'>Eliminar</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay usuarios registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php if ($user_role != 'member') { ?>
              <div class="mb-3">
                   <button class="btn btn-success" id="agregarEmpleadoBtn" data-toggle="modal" data-target="#editarModal">Agregar Empleado</button>
              </div>
            <?php } ?>
        </div>
    </div>
    <!-- Modal para editar empleado -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Agregar/Editar Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarEmpleadoForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" value="">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="">
                        </div>
                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <input type="text" class="form-control" id="rol" name="rol" value="">
                        </div>
                        <div class="form-group">
                            <label for="horas">Horas</label>
                            <input type="text" class="form-control" id="horas" name="horas" value="">
                        </div>
                        <button type="submit" class="btn btn-primary" name="guardar">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="scriptsJs/recuperarData.js"></script>
    <?php
    $conn = null;
    ?>
</body>

</html>