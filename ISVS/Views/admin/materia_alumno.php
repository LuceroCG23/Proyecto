<?php
require '../../conn/connection.php';
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $materia_id = $_GET['id'];
    try {
        // Establecer conexión con la base de datos
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener el nombre de la materia
        $nombre_materia_query = "SELECT Nombre FROM materia WHERE id_materia = :materia_id";
        $stmt_nombre_materia = $db->prepare($nombre_materia_query);
        $stmt_nombre_materia->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmt_nombre_materia->execute();
        $nombre_materia_result = $stmt_nombre_materia->fetch(PDO::FETCH_ASSOC);
        // Obtener el nombre de la materia
        $nombre_materia = $nombre_materia_result['Nombre'];
        // Consulta para obtener los datos de los alumnos inscritos en la materia específica
        $alumnos_query = "SELECT persona.nombre, persona.apellido, persona.email_correo
                          FROM alumno_materia
                          INNER JOIN persona ON alumno_materia.id_persona = persona.id_persona
                          WHERE alumno_materia.id_materia = :materia_id";
        // Preparar y ejecutar la consulta
        $stmt_alumnos = $db->prepare($alumnos_query);
        $stmt_alumnos->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
        $stmt_alumnos->execute();
        // Obtener los resultados de la consulta
        $alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);
        // Mostrar los datos de los alumnos
        echo '<h2>Alumnos inscritos en la materia: ' . $nombre_materia . '</h2>';
        if(count($alumnos) > 0) {
            echo '<ul>';
            foreach($alumnos as $alumno) {
                echo '<li>';
                echo 'Nombre: ' . $alumno['nombre'] . ' ' . $alumno['apellido'] . '<br>';
                echo 'Email: ' . $alumno['email_correo'] . '<br>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No hay alumnos inscritos en esta materia.';
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
} else {
    echo "No se ha proporcionado un ID de materia.";
}
?>
