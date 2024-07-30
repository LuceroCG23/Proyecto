<?php
require 'conn/connection.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Iniciar una transacción para mayor seguridad
        $db->beginTransaction();

        // Validar que el formulario tiene los datos necesarios
        if (!isset($_POST['id_persona']) || !isset($_POST['id_materia'])) {
            throw new Exception('Datos del formulario incompletos.');
        }

        // Asignar valores a las variables
        $id_persona = $_POST['id_persona'];
        $id_materia = $_POST['id_materia'];

        // Prepara la declaración de inserción
        $sqlInsert = $db->prepare("
            INSERT INTO nota 
            (id_persona, id_materia, nota1, nota2, nota3, nota4, calif_regularidad, calif_1_ex_final, calif_2_ex_final, calif_final, ev_dic_1, ev_dic_2, ev_feb_1, ev_feb_2, calificacion_definitiva)
            VALUES 
            (:id_persona, :id_materia, :nota1, :nota2, :nota3, :nota4, :calif_regularidad, :calif_1_ex_final, :calif_2_ex_final, :calif_final, :ev_dic_1, :ev_dic_2, :ev_feb_1, :ev_feb_2, :calificacion_definitiva)
        ");
        // Ligar parámetros con valores del formulario
        $sqlInsert->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
        $sqlInsert->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $sqlInsert->bindParam(':nota1', $_POST['nota1'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':nota2', $_POST['nota2'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':nota3', $_POST['nota3'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':nota4', $_POST['nota4'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':calif_regularidad', $_POST['calif_regularidad'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':calif_1_ex_final', $_POST['calif_1_ex_final'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':calif_2_ex_final', $_POST['calif_2_ex_final'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':calif_final', $_POST['calif_final'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':ev_dic_1', $_POST['ev_dic_1'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':ev_dic_2', $_POST['ev_dic_2'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':ev_feb_1', $_POST['ev_feb_1'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':ev_feb_2', $_POST['ev_feb_2'], PDO::PARAM_STR);
        $sqlInsert->bindParam(':calificacion_definitiva', $_POST['calificacion_definitiva'], PDO::PARAM_STR);
        // Ejecutar la declaración
        $sqlInsert->execute();
        // Confirmar la transacción después de operaciones exitosas
        $db->commit();
        echo "Las notas se insertaron con éxito.";
    } catch (Exception $e) {
        // Revertir la transacción y mostrar el mensaje de error
        $db->rollBack();
        echo "Error al insertar las notas: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
