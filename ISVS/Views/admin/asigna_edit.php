<?php
require_once('../../conn/connection.php');

$mensaje = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar datos del formulario
    $id = $_POST['Id'];
    $profesor = $_POST['profesor'];
    $materia = $_POST['materia'];
    $fecha = $_POST['fecha'];

    // Consulta preparada para evitar inyección SQL
    $sql = "UPDATE asignar SET id_persona=?, id_materia=?, fecha_i=? WHERE id_asignar=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iisi", $profesor, $materia, $fecha, $id);

    if ($stmt->execute()) {
        $mensaje = 'Registro actualizado correctamente.';
    } else {
        $error = 'Error al actualizar el registro: '. $stmt->error;
    }

    // Redirigir con mensajes de éxito/error
    header("Location: asigna_index.php?mensaje=". urlencode($mensaje). "&error=". urlencode($error));
    exit();
}



require 'navbar.php';

// Obtener el registro a editar
$id = $_GET['id'];
$sql = "SELECT * FROM asignar WHERE id_asignar=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();

?>

<!-- Formulario de edición -->
<div class="container mt-3">
    <div class="row m-auto">
        <div class="col-sm">
            <div class="card rounded-2 border-0">
                <h5 class="card-header bg-dark text-white">Editar</h5>
                <div class="card-body bg-light">
                    <form method="post" class="form" action="">
                        <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id_asignar']?>">

                        <!-- Profesor -->
                        <div class="form-group">
                            <label for="profesor">Profesor:</label>
                            <select  name="profesor" class="form-control card-header bg-white text-dark">
                                <?php
                                $sql = $conexion->query("SELECT * FROM persona WHERE id_rol = 2 AND estado = 'Activo' AND id_persona=" . $row['id_persona']);
                                while ($resultado3 = $sql->fetch_assoc()) {
                                    echo "<option value='" . $resultado3["id_persona"] . "'>" . $resultado3["nombre"] . " " . $resultado3["apellido"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Fecha de Ingreso actual -->
                        <div class="form-group">
                            <label >Fecha de Ingreso actual:</label>
                            <input disabled class="form-control"  value="<?php echo $row['fecha_i']?>" >
                        </div>

                        <!-- Actualizar Fecha de Ingreso -->
                        <div class="form-group">
                            <label for="fecha">Actualizar Fecha de Ingreso :</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $row['fecha_i'] ?>" required>
                        </div>

                        <!-- Materia -->
                        <div class="form-group">
                            <label for="materia">Materia:</label>
                            <select name="materia" class="form-control" required>
                                <?php
                               $sqlM = "SELECT * FROM materia WHERE  estado = 'Activo' AND id_materia=" . $row['id_materia'];
                               $resultadoM = $conexion->query($sqlM);
                               $rowM = $resultadoM->fetch_assoc();
                               echo "<option hidden value=" . $rowM["id_materia"] . ">" . $rowM["Nombre"] . " </option>";
                               
                               $sqlM1 = "SELECT * FROM materia WHERE  estado = 'Activo'";
                               $resultadoM1 = $conexion->query($sqlM1);

                               while ($fila = $resultadoM1->fetch_array()) {
                                   echo "<option value=" . $fila["id_materia"] . ">" . $fila["Nombre"] . " </option>";
                               }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" class="form-control" name="Estado" value="Activo" disabled>

                        <!-- Botones de Actualizar y Volver -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a class="btn btn-warning" href="asigna_index.php">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php';?>
