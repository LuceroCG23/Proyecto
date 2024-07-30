<?php
require_once('../../conn/connection.php');

$mensj1 = '';
$error1 = '';

if (isset($_POST['profesor']) && isset($_POST['materia'])) {
    $profesor = $_POST['profesor'];
    $materia = $_POST['materia'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $estado = "Activo";
    $id = $_POST['id'];

    $sql = "INSERT INTO asignar (id_persona, id_materia, fecha_i, Estado) VALUES (:profesor, :materia, :fecha_ingreso, :estado)";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":profesor", $profesor, PDO::PARAM_INT);
    $stmt->bindParam(":materia", $materia, PDO::PARAM_INT);
    $stmt->bindParam(":fecha_ingreso", $fecha_ingreso);
    $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $mensj1 = 'Registro cargado correctamente.';
    } catch (PDOException $e) {
        $error1 = 'Error al cargar el registro: ' . $e->getMessage();
    }
}

if ($mensj1 || $error1) {
    header("Location: asigna_index.php?mensaje1=" . urlencode($mensj1) . "&error1=" . urlencode($error1));
    exit();
}
?>
<?php require 'navbar.php'; ?>


    <div class="container mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <h5 class="card-header bg-dark text-white">Asignar materia</h5>
                    <div class="card-body bg-light">
                        <form method="post" class="form" action="">

  <!-- ---------------El get trae el id del profesor q quiere asignar la materia------------------ -->
                           <div class="form-group">
                                <label  for="profesor">Profesor:
                                <select  name="profesor" class="form-control" >
                           
                                    <?php
                                    $sql = $conexion->query("SELECT * FROM persona WHERE id_rol = 2 AND estado = 'Activo' AND id_persona=" . $_GET['id']);
                                    while ($resultado = $sql->fetch_assoc()) {
                                        echo "<option value='" . $resultado["id_persona"] . "'>" . $resultado["nombre"] . " " . $resultado["apellido"] . "</option>";
                                    }
                                    ?>
                                </select></label>
                            </div>

                                <!-- --------------------------------- -->
                                <div class="row">
                               <div class="col">
                            <div class="form-group">
                                <label for="fecha_ingreso">Fecha de Ingreso:</label>
                                <input type="date" class="form-control" name="fecha_ingreso" required>
                            </div>
                        </div>
                        </div>
                            <!-- --------------------------------- -->
                            <div class="form-group">
                                <label for="materia">Materia:</label>
                                <select name="materia" class="form-control" required>
                                    <option disabled selected hidden>Seleccione la materia</option>
                                    <?php
                                    $sqlm = $conexion->query("SELECT * FROM materia WHERE  estado = 'Activo'");
                                    while ($resultadom = $sqlm->fetch_assoc()) {
                                        echo "<option value='" . $resultadom["id_materia"] . "'>" . $resultadom["Nombre"] . " </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                        
                            <!-- --------------------------------- -->
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="Estado" value="Activo" disabled>
                            </div>
                            <!-------------------------------------------------------------->
                            <div class="mt-3 mb-2">
                                <button type="submit" class="btn btn-primary">Guardar </button>
                                <a class="btn btn-warning" href="asigna_index.php">Ver Listado</a>
                            </div>
                            <?php
                            if (!empty($infoMessage)) {
                                echo '<div class="alert alert-success" role="alert">' . $infoMessage . '</div>';
                            }
                            if (!empty($errorMessage)) {
                                echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>