<?php
require '../../conn/connection.php';

// Inicializar variables de mensaje
$mensj3 = '';
$error3 = '';

if (isset($_GET['id'])) {
    $id_asignar = $_GET['id'];

    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // El usuario confirmó la desactivación, proceder con la actualización del estado
        if (isset($_POST['fecha_baja'])) {
            $fecha_baja = $_POST['fecha_baja'];

            $consulta_desactivar = $db->prepare("UPDATE asignar SET Estado = 'Inactivo', fecha_b = :fecha_baja WHERE id_asignar = :id");
            $consulta_desactivar->bindParam(':id', $id_asignar, PDO::PARAM_INT);
            $consulta_desactivar->bindParam(':fecha_baja', $fecha_baja, PDO::PARAM_STR);

            if ($consulta_desactivar->execute()) {
                $mensj3 = 'Registro desactivado correctamente';
            } else {
                $error3 = 'Error al desactivar el registro: ' . implode(', ', $consulta_desactivar->errorInfo());
            }
        } else {
            $error3 = 'Debe proporcionar una fecha de baja.';
        }
    }
}

// Redirigir solo si hay mensajes para enviar
if ($mensj3 || $error3) {
    header("Location: asigna_index.php?mensaje3=" . urlencode($mensj3) . "&error3=" . urlencode($error3));
    exit();
}
?>

<!-- -------------------------------------------- -->

<?php require 'navbar.php'; ?>
<div class="body">
    <div class="panel">
        <?php if (empty($errMessage)) { // Mostrar confirmación solo si no hay un error 
        ?>
            <!-- --------------------------------- -->
            <div class="container mt-3">
                <div class="row m-auto">
                    <div class="col-sm">
                        <div class="card rounded-2 border-0">
                            <h5 class="card-header bg-dark text-white">¿Está seguro de que desea dar de baja al profesor?</h5>
                            <div class="card-body bg-light">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="fecha_baja">Fecha de salida:</label>
                                                <input type="date" class="form-control" name="fecha_baja" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-danger" name="confirm" value="yes">Sí</button>
                                    <a class="btn btn-primary" href="asigna_index.php">No</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <a class="btn btn-warning" href="asigna_index.php">Volver al Listado</a>
        <?php } ?>
    </div>
</div>
</div>
<?php require 'footer.php'; ?>
