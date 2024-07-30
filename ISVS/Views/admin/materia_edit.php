<?php
require '../../conn/connection.php';
$infoMessage = '';
$errorMessage = '';
// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el campo 'id' está presente en el formulario
    if (isset($_POST['id'])) {
        // Obtener los datos del formulario
        $id_materia = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $horas = $_POST['horas'];
        $num_resolucion = $_POST['num_resolucion'];
        $plan_estudio = $_POST['plan_estudio'];
        $año = $_POST['año'];
        $id_tipo = $_POST['id_tipo'];
        $consulta_actualizar = $db->prepare("UPDATE materia SET Nombre = ?, descripcion = ?, horas = ?, num_resolucion = ?, plan_estudio = ?,año_cursado=?, id_tipo = ? WHERE id_materia = ?");
        // Ejecutar la consulta
        if ($consulta_actualizar->execute([$nombre, $descripcion, $horas, $num_resolucion, $plan_estudio, $año, $id_tipo, $id_materia])) {
            $infoMessage = 'Registro modificado correctamente';
        } else {
            $errorMessage = 'Error al editar el registro: ' . implode(', ' . $consulta_actualizar->errorInfo());
        }
    } else {
        // Si el campo 'id' no está presente en el formulario
        $errorMessage = 'Falta el ID de la materia en el formulario.';
    }
}
// Verificar si se proporciona un ID válido en la URL
if (isset($_GET['id'])) {
    $id_materia = $_GET['id'];
    // Consultar la materia con el ID proporcionado
    $consulta_materia = $db->prepare("SELECT * FROM materia WHERE id_materia = ?");
    $consulta_materia->execute([$id_materia]);
    $materia = $consulta_materia->fetch();
    // Verificar si se encontró la materia
    if (!$materia) {
        die('No se encontró la materia con el ID proporcionado.');
    }
} else {
    // Si no se proporciona un ID válido en la URL
    die('Ha ocurrido un error');
}
// Redirigir solo si hay mensajes para enviar
if ($infoMessage || $errorMessage) {
    header("Location: materia_index.php?mensaje=" . urlencode($infoMessage) . "&error=" . urlencode($errorMessage));
    exit();
}
?>
<!-- -------------------------------------------- -->
<?php require 'navbar.php'; ?>
    <div class="container mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <h5 class="card-header bg-dark text-white">Edición de Materia</h5>
                    <div class="card-body bg-light">
                        <form method="post" class="form" action="">
                            <input type="hidden" class="form-control" name="id" value="<?php echo htmlspecialchars($materia['id_materia']); ?>">
                            <!-------------------------------------------------------------->
                            <label>Nombres:</label>
                            <input type="text" class="form-control" required name="nombre" autocomplete="off" value="<?php echo htmlspecialchars($materia['Nombre']); ?>" maxlength="45">
                            <!-------------------------------------------------------------->
                            <label>Descripción:</label>
                            <input type text="text" class="form-control" required name="descripcion" autocomplete="off" value="<?php echo htmlspecialchars($materia['descripcion']); ?>" maxlength="45">
                            <!-------------------------------------------------------------->
                            <label>Horas de cursada:</label>
                            <input type="text" class="form-control" required name="horas" id="horas" autocomplete="off" value="<?php echo htmlspecialchars($materia['horas']); ?>" maxlength="8">
                            <span id="horasOK"></span>
                            <br>
                          <label>Número de resolución:</label>  
                          <input type="text" class="form-control" required name="num_resolucion" id="num_resolucion" autocomplete="off" value="<?php echo htmlspecialchars($materia['num_resolucion']); ?>" maxlength="10">
                            <span id="num_resolucionOK"></span>
                            <br>
                             <!-------------------------------------------------------------->
                             <label>Año de Cursado:</label>
                             <select  name="año" id="año" class="form-control" autocomplete="off" required>
                                    <option value="" disabled>Seleccione su Tipo</option>
                                    <option value="1" <?php echo ($materia['año_cursado'] == 1) ? 'selected' : ''; ?>>1° Año</option>
                                    <option value="2" <?php echo ($materia['año_cursado'] == 2) ? 'selected' : ''; ?>>2° Año</option>
                                    <option value="3" <?php echo ($materia['año_cursado'] == 3) ? 'selected' : ''; ?>>3° Año</option>
                                </select>
                            <span id="añoOK"></span>
                            <br>
                            <!-------------------------------------------------------------->
                            <label for="plan_estudio">Cuatrimestre</label>
                             <select name="plan_estudio"   id="plan_estudio" class="form-control" autocomplete="off" required>
                                    <option value="" disabled>Seleccione el Cuatrimestre</option>
                                    <option value="1" <?php echo ($materia['plan_estudio'] == 1) ? 'selected' : ''; ?>>1° Cuatrimestre</option>
                                    <option value="2" <?php echo ($materia['plan_estudio'] == 2) ? 'selected' : ''; ?>>2° Cuatrimestre</option>
                                </select>                           
                            <!-------------------------------------------------------------->
                            <div class="form-group">
                                <label for="id_tipo">Tipo de Materia:</label>
                                <select name="id_tipo" class="form-control" autocomplete="off" required>
                                    <option value="" disabled>Seleccione su Tipo</option>
                                    <option value="1" <?php echo ($materia['id_tipo'] == 1) ? 'selected' : ''; ?>>Regular</option>
                                    <option value="2" <?php echo ($materia['id_tipo'] == 2) ? 'selected' : ''; ?>>Promocional</option>
                                    <option value="3" <?php echo ($materia['id_tipo'] == 3) ? 'selected' : ''; ?>>Libre</option>
                                </select>
                            </div>
                            <!-------------------------------------------------------------->
                            <div class="mt-3 mb-2">
                                <button type="submit" class="btn btn-primary" name="modificar" onclick="return confirm('¿Estás seguro de guardar los cambios?')">Guardar Cambios</button>
                                <a class="btn btn-warning" href="materia_index.php">Ver Listado</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require 'footer.php'; ?>   