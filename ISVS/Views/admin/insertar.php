<?php
require 'conn/connection.php';
// Verificar si se enviaron datos por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la Base de Datos
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Verificar si se seleccionó al menos una materia y un alumno
    if (!empty($_POST['id_materia']) && isset($_POST['id_persona']) && isset($_POST['ciclo_lectivo'])) {
        // Recibir datos del formulario
        $id_personas = explode(",", $_POST['id_persona']);
        $id_materias = $_POST['id_materia'];
        $id_ciclo = $_POST['ciclo_lectivo'];
        // Insertar datos en la base de datos
        foreach ($id_personas as $id_persona) {
            foreach ($id_materias as $id_materia) {
                $stmt = $db->prepare("INSERT INTO alumno_materia (id_persona, id_materia, id_ciclo) VALUES (?, ?, ?)");
                // Bind parameters
                $stmt->bindParam(1, $id_persona);
                $stmt->bindParam(2, $id_materia);
                $stmt->bindParam(3, $id_ciclo);
                // Execute the statement
                if ($stmt->execute()) {
                    $mensaje = 'Registro cargado correctamente.';
                } else {
                    $error = 'Error al cargar el registro: ' . implode(', ', $stmt->errorInfo());
                }
            }
        }
    } else {
        $error = "No se seleccionó al menos una materia, un alumno o un ciclo lectivo.";
    }
    if (isset($mensaje) || isset($error)) {
        header("Location:seleccionar_alumnos.php?mensaje=" . urlencode($mensaje) . "&error=" . urlencode($error));
        exit();
    }
}
