<?php
// Conexión a la base de datos
require '../../conn/connection.php'; 

//-------------BORRADO------------------ 
if (isset($_GET['txtID'])) {
    $txtID = isset($_GET['txtID']) ? $_GET['txtID'] : "";
    $sentencia = $db->prepare("UPDATE materia SET estado = 'Inactivo' WHERE id_materia = :id");
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();
    $mensaje = "Registro Materia Eliminado";
    header("Location:materia_index.php?mensaje=" . $mensaje);
    exit;
}

// Requerir la barra de navegación
require 'navbar.php';
?>

<!-- Sección de contenido -->
<section class="content mt-2">
    <div class="row m-auto">
        <div class="col-sm">
            <div class="card rounded-2 border-0">
                <div class="card-header bg-dark text-white pb-0">
                    <h5 class="d-inline-block">Listado de Materias y Correlativas</h5>
                    <a class="btn btn-primary float-right mb-2" href="materia_crea.php">Registro de Materia</a>
                </div>

                <!-- Tabla de materias -->
                <div class="card-body table-responsive">
                    <table id="example" class="table table-striped table-sm" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Materia</th>
                                <th>Correlativas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $query = "SELECT m.id_materia, m.Nombre AS Materia,
                                                 GROUP_CONCAT(cor.Nombre ORDER BY cor.Nombre ASC SEPARATOR ', ') AS Correlativas
                                          FROM materia m
                                          LEFT JOIN correlativa co ON m.id_materia = co.id_materia
                                          LEFT JOIN materia cor ON co.id_correlativa = cor.id_materia
                                          WHERE m.estado = 'Activo'
                                          GROUP BY m.id_materia, m.Nombre
                                          ORDER BY m.id_materia ASC";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($materias as $materia) {
                            ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($materia['id_materia'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($materia['Materia'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($materia['Correlativas'] ? $materia['Correlativas'] : 'Sin correlativas', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="materia_edit.php?id=<?php echo htmlspecialchars($materia['id_materia'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning btn-sm" role="button"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:eliminar3(<?php echo $materia['id_materia']; ?>)" class="btn btn-danger btn-sm" title="Borrar" role="button">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } catch (PDOException $e) {
                                error_log("Error al obtener las materias: " . $e->getMessage());
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="js/ocultarMensaje.js"></script>
<?php require 'footer.php'; ?>
