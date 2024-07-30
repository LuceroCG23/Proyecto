<?php
require '../../conn/connection.php';
// $inMessage = '';
// $errMessage = '';
// if (isset($_GET['id'])) {
//     $id_materia = $_GET['id'];

//     if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
//         // El usuario confirmó la desactivación, proceder con la actualización del estado
//         $consulta_desactivar = $db->prepare("UPDATE materia SET estado = 'Inactivo' WHERE id_materia = :id");
//         $consulta_desactivar->bindParam(':id', $id_materia, PDO::PARAM_INT);

//         if ($consulta_desactivar->execute()) {
//             $inMessage = 'Registro desactivado correctamente';
//         } else {
//             $errMessage = 'Error al desactivar el registro: ' . implode(', '. $consulta_desactivar->errorInfo());
//         }
//     }
// } else {
//     $errMessage = 'Ha ocurrido un error: Falta el ID de la materia en la URL.';
// }

// // Redirigir solo si hay mensajes para enviar
// if ($inMessage || $errMessage) {
//     header("Location: materia_index.php?mensaje=" . urlencode($inMessage) . "&error=" . urlencode($errMessage));
//     exit();
// }
// ?>
// <!-- ------------------------------------------------- -->
// <?php require 'navbar.php'; ?>
//     <div class="body">
        <div class="panel">
            <h4>Desactivar Materia</h4>
            <!-- Muestra mensajes de éxito o error ------->
            <?php
            if (!empty($infoMessage)) {
                echo '<div class="alert alert-primary" role="alert">' . $infoMessage . '</div>';
            }
            if (!empty($errorMessage)) {
                echo '<div class="alert alert-primary" role="alert">' . $errorMessage . '</div>';
            }
            ?>
            <br><br>
            <?php if (empty($errMessage)) { // Mostrar confirmación solo si no hay un error ?>
                <!-- <p>¿Está seguro de que desea desactivar este registro?</p>
                <a class="btn btn-danger" href="?id=<?php echo $id_materia; ?>&confirm=yes">Si</a>
                <a class="btn btn-primary" href="materia_index.php">No</a> -->
            <?php } else { ?>
                <a class="btn btn-warning" href="materia_index.php">Volver al Listado</a>
            <?php } ?>
        </div>
    </div>
<?php require 'footer.php'; ?>
