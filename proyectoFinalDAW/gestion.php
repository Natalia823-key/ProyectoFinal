<?php
require_once 'conf.inc.php';

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['id'];
                $_SESSION['name_user'] = $user['name'];
                header("Location: index.php");
                exit();
            } else {
                return 'Credenciales incorrectas.';
            }
        } else {
            return 'No se encontró ningún usuario con el correo electrónico proporcionado.';
        }
    }

    public function register($name, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        try {
            if($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                return 'Error al registrar el usuario.';
            }
        } catch (PDOException $e) {
            error_log('Error en la ejecución de la consulta: ' . $e->getMessage());
            return 'Error al registrar el usuario.';
        }
    }
}

class Employee {
    private $conn;
    private $defaultInvernaderoId;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->setDefaultInvernaderoId();
    }

    private function setDefaultInvernaderoId() {
        $sqlDefaultInvernadero = "SELECT id_inver FROM invernadero LIMIT 1";
        $stmtDefaultInvernadero = $this->conn->prepare($sqlDefaultInvernadero);
        $stmtDefaultInvernadero->execute();
        $defaultInvernadero = $stmtDefaultInvernadero->fetch(PDO::FETCH_ASSOC);
        if (!$defaultInvernadero) {
            die("No hay invernaderos disponibles. Por favor, crea un invernadero primero.");
        }
        $this->defaultInvernaderoId = $defaultInvernadero['id_inver'];
    }

    public function manageEmployee($dni, $name, $email, $telefono, $rol, $horas) {
        $sqlCheckEmployee = "SELECT COUNT(*) AS count FROM empleados WHERE dni = :dni";
        $stmtCheckEmployee = $this->conn->prepare($sqlCheckEmployee);
        $stmtCheckEmployee->bindParam(':dni', $dni);
        $stmtCheckEmployee->execute();
        $resultCheckEmployee = $stmtCheckEmployee->fetch(PDO::FETCH_ASSOC);

        if ($resultCheckEmployee['count'] > 0) {
            return $this->updateEmployee($dni, $name, $email, $telefono, $rol, $horas);
        } else {
            return $this->insertEmployee($dni, $name, $email, $telefono, $rol, $horas);
        }
    }

    private function updateEmployee($dni, $name, $email, $telefono, $rol, $horas) {
        $sqlUpdateEmpleado = "UPDATE empleados SET telefono = :telefono, rol = :rol, horas = :horas, invernadero_id = :invernadero_id WHERE dni = :dni";
        $stmtUpdateEmpleado = $this->conn->prepare($sqlUpdateEmpleado);
        $stmtUpdateEmpleado->bindParam(':dni', $dni);
        $stmtUpdateEmpleado->bindParam(':telefono', $telefono);
        $stmtUpdateEmpleado->bindParam(':rol', $rol);
        $stmtUpdateEmpleado->bindParam(':horas', $horas);
        $stmtUpdateEmpleado->bindParam(':invernadero_id', $this->defaultInvernaderoId);

        $sqlUpdateUser = "UPDATE users SET name = :name, email = :email WHERE id = (SELECT user_id FROM empleados WHERE dni = :dni)";
        $stmtUpdateUser = $this->conn->prepare($sqlUpdateUser);
        $stmtUpdateUser->bindParam(':name', $name);
        $stmtUpdateUser->bindParam(':email', $email);
        $stmtUpdateUser->bindParam(':dni', $dni);

        if ($stmtUpdateEmpleado->execute() && $stmtUpdateUser->execute()) {
            return "Los datos del empleado se han actualizado correctamente.";
        } else {
            return "Ha ocurrido un error al intentar actualizar los datos del empleado.";
        }
    }

    private function insertEmployee($dni, $name, $email, $telefono, $rol, $horas) {
        $sqlUserId = "SELECT id FROM users WHERE email = :email";
        $stmtUserId = $this->conn->prepare($sqlUserId);
        $stmtUserId->bindParam(':email', $email);
        $stmtUserId->execute();
        $user = $stmtUserId->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return "El usuario con el correo electrónico $email no existe.";
        }
        $user_id = $user['id'];

        $sqlInsertEmpleado = "INSERT INTO empleados (dni, horas, rol, telefono, invernadero_id, user_id) VALUES (:dni, :horas, :rol, :telefono, :invernadero_id, :user_id)";
        $stmtInsertEmpleado = $this->conn->prepare($sqlInsertEmpleado);
        $stmtInsertEmpleado->bindParam(':dni', $dni);
        $stmtInsertEmpleado->bindParam(':horas', $horas);
        $stmtInsertEmpleado->bindParam(':rol', $rol);
        $stmtInsertEmpleado->bindParam(':telefono', $telefono);
        $stmtInsertEmpleado->bindParam(':invernadero_id', $this->defaultInvernaderoId);
        $stmtInsertEmpleado->bindParam(':user_id', $user_id);

        try {
            if ($stmtInsertEmpleado->execute()) {
                return "Se ha agregado un nuevo empleado correctamente.";
            } else {
                return "Ha ocurrido un error al intentar agregar un nuevo empleado.";
            }
            } catch (PDOException $e) {
                return "Error: " . $e->getMessage();
        }
    }

    public function deleteEmployee($dni) {
        $sqlDeleteEmployee = "DELETE FROM empleados WHERE dni = :dni";
        $stmtDeleteEmployee = $this->conn->prepare($sqlDeleteEmployee);
        $stmtDeleteEmployee->bindParam(':dni', $dni);
        $stmtDeleteEmployee->execute();

        if ($stmtDeleteEmployee->rowCount() > 0) {
            $sqlDeleteUser = "DELETE FROM users WHERE id = (SELECT user_id FROM empleados WHERE dni = :dni)";
            $stmtDeleteUser = $this->conn->prepare($sqlDeleteUser);
            $stmtDeleteUser->bindParam(':dni', $dni);
            $stmtDeleteUser->execute();
            return "El empleado y el usuario asociado han sido eliminados correctamente.";
        } else {
            return "No se pudo encontrar al empleado para eliminar.";
        }
    }
}

class Expenses {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getGastos() {
        $sql_gastos = "SELECT i.nom_inver AS nombre_invernadero, SUM(p.precio_prod * p.num_prod) AS gastos FROM productos p INNER JOIN invernadero i ON p.invernadero_id = i.id_inver GROUP BY p.invernadero_id";
        $stmt_gastos = $this->conn->prepare($sql_gastos);
        $stmt_gastos->execute();
        return $stmt_gastos->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGanancias() {
        $sql_ganancias = "SELECT i.nom_inver AS nombre_invernadero, SUM(precio_vale) AS ganancias FROM vales v INNER JOIN invernadero i ON v.invernadero_id = i.id_inver GROUP BY v.invernadero_id";
        $stmt_ganancias = $this->conn->prepare($sql_ganancias);
        $stmt_ganancias->execute();
        return $stmt_ganancias->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalGastos($gastos) {
        return array_sum(array_column($gastos, 'gastos'));
    }

    public function getTotalGanancias($ganancias) {
        return array_sum(array_column($ganancias, 'ganancias'));
    }
}

class Products {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function addProduct($nombre_producto, $cantidad, $precio, $invernadero_id) {
        $sql = "INSERT INTO productos (nom_prod, num_prod, precio_prod, invernadero_id) VALUES (:nombre_producto, :cantidad, :precio, :invernadero_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':invernadero_id', $invernadero_id);

        if ($stmt->execute()) {
            return "Producto añadido correctamente.";
        } else {
            return "Error al añadir producto.";
        }
    }

    public function deleteProduct($id_producto) {
        $sql = "DELETE FROM productos WHERE id_prod = :id_producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);

        if ($stmt->execute()) {
            return "Producto eliminado correctamente.";
        } else {
            return "Error al eliminar producto.";
        }
    }
}
