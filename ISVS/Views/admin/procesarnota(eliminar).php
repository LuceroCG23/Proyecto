<?php
require '../../conn/connection.php';

if (isset($_POST['guardar_notas'])) {
    var_dump($_POST);
    $errores = [];
    $notas_actualizadas = 0;
    $notas_insertadas = 0;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'nota') !== false && strpos($key, 'id_persona') !== false) {
            $index = substr($key, strpos($key, '_') + 1);
            $id_persona = $_POST['id_persona_' . $index];
            $nota1 = floatval($_POST['nota1_' . $index]); // Convertir a flotante
            $nota2 = floatval($_POST['nota2_' . $index]); // Convertir a flotante
            $nota3 = floatval($_POST['nota3_' . $index]); // Convertir a flotante
            $nota4 = floatval($_POST['nota4_' . $index]); // Convertir a flotante

            // Calcular el promedio como un flotante
            $promedio = ($nota1 + $nota2 + $nota3 + $nota4) / 4.0;

            // Actualizar o insertar notas en la base de datos
            try {
                $query = "SELECT id_nota FROM nota WHERE id_persona = :id_persona";
                $statement = $db->prepare($query);
                $statement->bindParam(':id_persona', $id_persona);
                $statement->execute();
                $existing_note = $statement->fetch(PDO::FETCH_ASSOC);

                if ($existing_note) {
                    // Actualizar las notas si ya existen registros
                    $query = "UPDATE nota SET nota1 = :nota1, nota2 = :nota2, nota3 = :nota3, nota4 = :nota4, promedio = :promedio WHERE id_persona = :id_persona";
                    $statement = $db->prepare($query);
                    $statement->bindParam(':id_persona', $id_persona);
                    $statement->bindParam(':nota1', $nota1);
                    $statement->bindParam(':nota2', $nota2);
                    $statement->bindParam(':nota3', $nota3);
                    $statement->bindParam(':nota4', $nota4);
                    $statement->bindParam(':promedio', $promedio);
                    if ($statement->execute()) {
                        $notas_actualizadas++;
                    } else {
                        $errores[] = "Error al actualizar las notas del estudiante con ID: $id_persona";
                    }
                } else {
                    // Insertar nuevas notas si no hay registros
                    $query = "INSERT INTO nota (id_persona, nota1, nota2, nota3, nota4, promedio) VALUES (:id_persona, :nota1, :nota2, :nota3, :nota4, :promedio)";
                    $statement = $db->prepare($query);
                    $statement->bindParam(':id_persona', $id_persona);
                    $statement->bindParam(':nota1', $nota1);
                    $statement->bindParam(':nota2', $nota2);
                    $statement->bindParam(':nota3', $nota3);
                    $statement->bindParam(':nota4', $nota4);
                    $statement->bindParam(':promedio', $promedio);
                    if ($statement->execute()) {
                        $notas_insertadas++;
                    } else {
                        $errores[] = "Error al insertar las notas del estudiante con ID: $id_persona";
                    }
                }
            } catch (PDOException $e) {
                $errores[] = 'Error al ejecutar la consulta: ' . $e->getMessage();
            }
        }
    }

    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo $error . "<br>";
        }
    } else {
        echo "Se han actualizado $notas_actualizadas registros y se han insertado $notas_insertadas registros nuevos correctamente.";
    }
} else {
    echo "No se han recibido datos para guardar.";
}
