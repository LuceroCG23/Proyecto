<?php
include_once('../../conn/connection.php');
// Inicializar variables de mensaje
$mensj2 = '';
$error2 = '';
if (isset($_POST['profesor']) && isset($_POST['materia']) && isset($_POST['Id'])) {
    $id = $_POST['Id'];
    $profesor = $_POST['profesor'];
    $materia = $_POST['materia'];
    $fecha= $_POST['fecha'];

    $sql = "UPDATE asignar SET id_persona=:profesor, id_materia=:materia, fecha_i=:fecha WHERE id_asignar=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":profesor", $profesor, PDO::PARAM_INT);
    $stmt->bindParam(":materia", $materia, PDO::PARAM_INT);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    
    if ($stmt->execute()) {
        $mensj2 = 'Registro actualizado correctamente.';
    } else {
        $error2 = 'Error al a actualizar el registro: ' . implode(', ', $consulta_desactivar->errorInfo());
    }
}
// Redirigir solo si hay mensajes para enviar
if ($mensj2|| $error2) {
header("Location: asigna_index.php?mensaje2=" . urlencode($mensj2) . "&error2=" . urlencode($error2));
exit();
}
?>
<!-- ------------------------------------------------- -->
<?php require 'navbar.php'; ?>
    <div class="container mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <h5 class="card-header bg-dark text-white">Editar</h5>
                    <div class="card-body bg-light">
                        <form method="post" class="form" action="">
                        <?php
                            include('../../conn/connection.php');
                            $sql = "SELECT * FROM asignar WHERE id_asignar =" . $_GET['id'];

                            $resultado = $conexion->query($sql);

                            $row = $resultado->fetch_assoc();

                            ?>
                            <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id_asignar'] ?>"> 
                            <!-- ---------------El get trae el id del profesor q quiere asignar la materia------------------ -->
                            <div class="form-group">
                                    <?php
                                    include('../../conn/connection.php');
                                    $sql = $conexion->query("SELECT * FROM persona WHERE id_rol = 2 AND estado = 'Activo' AND id_persona=" . $row['id_persona']);
                                    while ($resultado3 = $sql->fetch_assoc()) {
                                        echo "<label  name='profesor'  value='" . $resultado3["id_persona"] . "'> Profesor: " . $resultado3["nombre"] . " " . $resultado3["apellido"] . "</label>";
                                    }
                                    ?>
                            </div>
                                    <!-- --------------------------------- -->
                            <div class="form-group">
                                <label for="fec">Fecha de Ingreso actual: <?php echo $row['fecha_i'] ?></label>
                            </div>
                               <!-- ----------------------------------->
                               <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="fecha">Actualizar Fecha de Ingreso :</label>
                                <?php
                            include('../../conn/connection.php');
                            $sqlf = "SELECT * FROM asignar WHERE id_asignar =" . $_GET['id'];
                            $resultado2 = $conexion->query($sqlf);
                            $row1 = $resultado2->fetch_assoc();
                            ?>
                                <input type="date" class="form-control" name="fecha" value="<?php echo $row1['fecha_i'] ?>"required>
                            </div>
                        </div>
                        </div> 
                            <!-- --------------------------------- -->
                            <div class="form-group">
                                <label for="materia">Materia:</label>
                                <select name="materia" class="form-control" required>
                                    <?php
                                    include('../../conn/connection.php');
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
                            <!-- --------------------------------- -->
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="Estado" value="Activo" disabled>
                            </div>
                            <!-------------------------------------------------------------->
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
<?php require 'footer.php'; ?>
