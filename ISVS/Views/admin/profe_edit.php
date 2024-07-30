<?php
require '../../conn/connection.php';

$infoMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id_profe = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dni = $_POST['dni'];
        $email = $_POST['email'];
        $celular = $_POST['celular'];
        $contrasena = $_POST['contrasena']; // Nuevo campo de contraseña-----

        $consulta_actualizar = $db->prepare("UPDATE persona SET nombre = :nombre, apellido = :apellido, DNI = :dni, email_correo = :email, celular = :celular, contraseña = :contrasena WHERE id_persona = :id");

        $consulta_actualizar->bindParam(':id', $id_profe, PDO::PARAM_INT);
        $consulta_actualizar->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta_actualizar->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $consulta_actualizar->bindParam(':dni', $dni, PDO::PARAM_STR);
        $consulta_actualizar->bindParam(':email', $email, PDO::PARAM_STR);
        $consulta_actualizar->bindParam(':celular', $celular, PDO::PARAM_STR);
        $consulta_actualizar->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);

        if ($consulta_actualizar->execute()) {
            $infoMessage = 'Registro modificado correctamente';
        } else {
            $errorMessage = 'Error al editar el registro: ' . implode(', '. $consulta_actualizar->errorInfo());
        }
    } else {
        $errorMessage = 'Falta el ID del profesor en el formulario.';
    }
}

if (isset($_GET['id'])) {
    $id_profe = $_GET['id'];
    $consulta_profe = $db->prepare("SELECT * FROM persona WHERE id_persona = :id");
    $consulta_profe->bindParam(':id', $id_profe, PDO::PARAM_INT);
    $consulta_profe->execute();
    $profesor = $consulta_profe->fetch();

    if (!$profesor) {
        die('No se encontró el profesor con el ID proporcionado.');
    }
} else {
    die('Ha ocurrido un error');
}

// Redirigir solo si hay mensajes para enviar
if ($infoMessage || $errorMessage) {
    header("Location: profe_index.php?mensaje=" . urlencode($infoMessage) . "&error=" . urlencode($errorMessage));
    exit();
}
?>
<!-- -------------------------------- -->
<?php require 'navbar.php'; ?>
    <div class="container mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <h5 class="card-header bg-dark text-white">Edición de Profesor</h5>
                    <div class="card-body bg-light">
                        <form method="post" class="form" action="">
                            <input type="hidden" class="form-control" name="id" value="<?php echo htmlspecialchars($profesor['id_persona']); ?>">
                            <!-------------------------------------------------------------->
                            <label>Nombres:</label>
                            <input type="text" class="form-control" required name="nombre" autocomplete="off" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" maxlength="45">
                            <!-------------------------------------------------------------->
                            <label>Apellidos:</label>
                            <input type="text" class="form-control" required name="apellido" autocomplete="off" value="<?php echo htmlspecialchars($profesor['apellido']); ?>" maxlength="45">
                            <!-------------------------------------------------------------->
                            <label>DNI:</label>
                            <input type="text" class="form-control" required name="dni" id="dni" autocomplete="off" value="<?php echo htmlspecialchars($profesor['DNI']); ?>" maxlength="8">
                            <span id="dniOK"></span>
                            <br>
                            <!-------------------------------------------------------------->
                            <label>Correo:</label>
                            <input type="email" class="form-control" required name="email" id="email" autocomplete="off" value="<?php echo htmlspecialchars($profesor['email_correo']); ?>" maxlength="45">
                            <span id="emailOK"></span>
                            <br>
                            <!-------------------------------------------------------------->
                            <label>Celular:</label>
                            <input type="tel" class="form-control" required name="celular" id="celular" autocomplete="off" value="<?php echo htmlspecialchars($profesor['celular']); ?>" maxlength="10">
                            <span id="celularOK"></span>
                            <br>
                            <!-------------------------------------------------------------->                           
                            <label>Contraseña:</label>
                            <div class="input-group">
                                <input class="form-control bg-light" type="password" name="contrasena" id="password" required value="<?php echo htmlspecialchars($profesor['contraseña']); ?>" maxlength="10">
                                <button type="button" class="btn btn-outline-primary" name="toggle-eye" id="toggle-eye" onclick="togglePasswordVisibility()">
                                    <i class="fas fa-eye p-1"></i>
                                </button>
                            </div>                          
                            <!-------------------------------------------------------------->                            
                            <div class="mt-3 mb-2">
                                <button type="submit" class="btn btn-primary" name="modificar" onclick="return confirm('¿Estás seguro de guardar los cambios?')">Guardar Cambios</button>
                                <a class="btn btn-warning" href="profe_index.php">Ver Listado</a>
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
    <script src="js/contraseña.js"></script>
    <script src="js/validacion.js"></script>
    <script src="js/validacion2.js"></script>
<?php require 'footer.php'; ?>