<?php
require '../../conn/connection.php';

// Variables para mensajes
$mensaje = "";
$error = "";

try {
    // Procesar el formulario cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $nombre = $db->quote($_POST["nombre"]);
        $descripcion = $db->quote($_POST["descripcion"]);
        $horas = (int)$_POST["horas"];
        $num_resolucion = (int)$_POST["num_resolucion"];
        $plan_estudio = $db->quote($_POST["plan_estudio"]);
        $año_cursado = $db->quote($_POST["año"]);
        $id_tipo = (int)$_POST["id_tipo"];
        $estado = 'Activo'; // Valor predeterminado para el estado, estado es si esta activo o inactivo.

        // Inserción de datos en la tabla 'materia' (usando sentencia preparada)
        $sql = "INSERT INTO materia (Nombre, descripcion, horas, num_resolucion, plan_estudio,año_cursado , id_tipo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $descripcion);
        $stmt->bindParam(3, $horas, PDO::PARAM_INT);
        $stmt->bindParam(4, $num_resolucion, PDO::PARAM_INT);
        $stmt->bindParam(5, $plan_estudio);
        $stmt->bindParam(6, $año_cursado);
        $stmt->bindParam(7, $id_tipo, PDO::PARAM_INT);
        $stmt->bindParam(8, $estado);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = "Materia ingresada con éxito.";
        } else {
            $error = "Error al ingresar Materia: " . $stmt->errorInfo()[2];
        }

        // Cerrar la conexión y la declaración preparada
        $stmt->closeCursor();
        $db = null;
    }
} catch (PDOException $e) {
    $error = "Error en la conexión o consulta: " . $e->getMessage();
}

// Redirigir a la página "listado_materia.php" con los mensajes en la URL
header("Location:materia_index.php?mensaje=" . urlencode($mensaje) . "&error=" . urlencode($error));
exit();
