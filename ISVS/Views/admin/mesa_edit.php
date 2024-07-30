
<?php 
require_once('../../conn/connection.php');
$mensaje = '';
$error = '';

$id = $_GET['id'];
$sqli = "SELECT * FROM mesa_examen WHERE id_mesa=?";
$stmt = $conexion->prepare($sqli);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar datos del formulario
    $id = $_POST['Id'];
    $nombre = $_POST['nombre_mesa'];
    $materia = $_POST['materia'];
    $fecha_i = $_POST['fecha_i'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora= $_POST['hora'];
    $tipo = $_POST['id_tipo'];
    $ciclo= $_POST['ciclo_lectivo'];
    // Consulta preparada para evitar inyección SQL
    $sql = "UPDATE mesa_examen SET nombre_mesa=?, id_materia=?, fecha_i=?, fecha_fin=?, hora=?, id_ciclo=?, id_tipo=? WHERE id_mesa=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("siisiiii", $nombre, $materia, $fecha_i, $fecha_fin, $hora, $tipo, $ciclo, $id);
    if ($stmt->execute()) {
        $mensaje = 'Registro actualizado correctamente.';
    } else {
        $error = 'Error al actualizar el registro: '. $stmt->error;
    }
    header("Location: listadomesa.php?mensaje=". urlencode($mensaje). "&error=". urlencode($error));
    exit();

}

?>
<?php require 'navbar.php'; ?>
    <div class="container mt-3 " style="width: 40rem">
        <div class="row d-flex justify-content-center ">
            <div class="col ">
                <div class="card rounded-2 border-0">
                    <h5 class="card-header bg-dark text-white">Editar Mesa de Examen</h5>
                    <div class="card-body bg-light">
                        <form action="" method="post">
                        <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id_mesa']?>">
                          
                            <div class="form-group">
                                <label for="nombre_mesa">Nombre de Mesa:</label>
                                <input type="text" name="nombre_mesa" autocomplete="off" class="form-control" value="<?php echo $row['nombre_mesa']?>">
                            </div>
                         <!-- ----------------------------- -->
                            <div class="form-group">
                                <label for="materia">Materia:</label>
                                <select name="materia" id="materia" class="form-control" autocomplete="off" required>
                                    <?php
                                    $sqlM = "SELECT * FROM materia WHERE  estado = 'Activo' AND id_materia=" . $row['id_materia'];
                                    $resultadoM = $conexion->query($sqlM);
                                    $rowM = $resultadoM->fetch_assoc();
                                    echo "<option hidden value=" . $rowM["id_materia"] . ">" . $rowM["Nombre"] . " </option>";


                                    $query = "SELECT id_materia, Nombre FROM materia WHERE estado = 'Activo'";
                                    $result_materias = $db->query($query);
                                    while ($row1 = $result_materias->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row1['id_materia'] . "'>" . $row1['Nombre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- ---------------------------- -->
                            <div class="form-group">
                                <label for="fecha_i">Fecha Inicio:</label>
                                <input type="date" name="fecha_i" autocomplete="off" class="form-control" value="<?php echo $row['fecha_i']?>">
                            </div>
                            <!-- ---------------------------- -->
                            <div class="form-group">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" name="fecha_fin" autocomplete="off" class="form-control" value="<?php echo $row['fecha_fin']?>">
                            </div>
                            <!-- ---------------------------- -->

                            <div class="form-group">
                                <label for="hora">Hora:</label>
                                <input type="time" name="hora" autocomplete="off" class="form-control" value="<?php echo $row['hora']?>">
                            </div>
                            <!-- ---------------------------- -->
                            <div class="form-group">
                                <label for="id_tipo">Tipo de Materia:</label>
                                <select name="id_tipo" class="form-control" autocomplete="off" required>
                                    <?php
                                $sqlt = "SELECT * FROM tipo WHERE id_tipo= " . $row['id_tipo'];
                                    $resultadoM = $conexion->query($sqlt);
                                    $rowt = $resultadoM->fetch_assoc();
                                    echo "<option hidden value=" . $rowt["id_tipo"] . ">" . $rowt["nombre_tipo"] . " </option>";
                                    ?>
                                    <option value="1">Regular</option>
                                    <option value="2">Promocional</option>
                                    <option value="3">Libre</option>
                                </select>
                            </div>
                            <!-- --------------- habría q cambiarlo los 1ro, 2do.. año------------- -->
                            <div class="form-group">
                                <label for="ciclo_lectivo">Ciclo Lectivo:</label>
                                <select name="ciclo_lectivo" id="ciclo_lectivo" class="form-control" autocomplete="off" required>
                                    <?php
                        
                                    $query_ciclos = "SELECT id_ciclo, nombre_ciclo FROM ciclo_lectivo";
                                    $result_ciclos = $db->query($query_ciclos);
                                    while ($row_ciclo = $result_ciclos->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row_ciclo['id_ciclo'] . "'>" . $row_ciclo['nombre_ciclo'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <br>
                            <div class="form-group">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a class="btn btn-warning" href="listadomesa.php">Volver</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require 'footer.php'; ?>