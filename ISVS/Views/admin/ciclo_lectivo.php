<?php
// Inicia la sesión para usar variables de sesión para mensajes
// Conexión a la base de datos
require '../../conn/connection.php';
// Obtener el ciclo lectivo actual
$ciclo_actual = null;
try {
    // Consulta para obtener el ciclo actual
    $stmt = $db->prepare("SELECT nombre_ciclo FROM ciclo_lectivo WHERE ciclo_actual = 1 LIMIT 1");
    $stmt->execute();
    $ciclo_actual = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores y registro en el log
    error_log("Error al obtener el ciclo actual: " . $e->getMessage());
}
// Manejo de formularios con método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar_ciclo':
                $ciclo = trim($_POST['ciclo']);  // Sanitiza la entrada del usuario
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];

                if (empty($ciclo) || empty($fecha_inicio) || empty($fecha_fin)) {
                    $_SESSION['mensaje'] = "Error: Todos los campos son requeridos.";
                } else {
                    try {
                        // Verificar si el ciclo lectivo ya existe
                        $stmt = $db->prepare("SELECT COUNT(*) AS count FROM ciclo_lectivo WHERE nombre_ciclo = ?");
                        $stmt->bindParam(1, $ciclo);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result['count'] > 0) {
                            $_SESSION['mensaje'] = "Error: El ciclo lectivo ya está agregado.";
                        } else {
                            // Insertar el nuevo ciclo lectivo
                            $stmt = $db->prepare("INSERT INTO ciclo_lectivo (nombre_ciclo, fecha_inicio, fecha_fin, ciclo_actual, Estado) VALUES (?, ?, ?, 0, 'Activo')");
                            $stmt->bindParam(1, $ciclo);
                            $stmt->bindParam(2, $fecha_inicio);
                            $stmt->bindParam(3, $fecha_fin);
                            $stmt->execute();
                            $_SESSION['mensaje'] = "Ciclo lectivo agregado exitosamente.";
                        }
                    } catch (PDOException $e) {
                        $_SESSION['mensaje'] = "Error al agregar el ciclo lectivo: " . $e->getMessage();
                    }
                }
                // Redireccionar después de agregar
                
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
                break;

            case 'actualizar_ciclo':
                $nuevo_ciclo = $_POST['nuevo_ciclo'];
                if (empty($nuevo_ciclo)) {
                    $_SESSION['mensaje'] = "Error: No se seleccionó un nuevo ciclo lectivo.";
                } else {
                    try {
                        // Usar transacciones para mantener la consistencia
                        $db->beginTransaction();
                        // Desactivar el ciclo actual existente
                        $stmt = $db->prepare("UPDATE ciclo_lectivo SET ciclo_actual = 0 WHERE ciclo_actual = 1");
                        $stmt->execute();

                        // Establecer el nuevo ciclo como el actual
                        $stmt = $db->prepare("UPDATE ciclo_lectivo SET ciclo_actual = 1 WHERE id_ciclo = ?");
                        $stmt->bindParam(1, $nuevo_ciclo);
                        $stmt->execute();

                        $db->commit();  // Confirmar la transacción
                        $_SESSION['mensaje'] = "Ciclo lectivo actualizado exitosamente.";
                    } catch (PDOException $e) {
                        $db->rollBack();  // Revertir cambios en caso de error
                        $_SESSION['mensaje'] = "Error al actualizar el ciclo lectivo: " . $e->getMessage();
                    }
                }
                // Redireccionar después de actualizar
                header("Location:ciclo_lectivo.php");
                // header("Location: " . $_SERVER['PHP_SELF']);
                exit();
                break;
            default:
                $_SESSION['mensaje'] = "Acción no reconocida.";
                header("Location:ciclo_lectivo.php");
                // header("Location: " . $_SERVER['PHP_SELF']);  // Redireccionar para evitar estado incorrecto
                exit();
        }
    }
}
?>
<!-- ------------------------------------------ -->
<?php require 'navbar.php'; ?>
<!-- ------------------------------------------ -->
<div class="container mt-3">
    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_SESSION['mensaje'])) : ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensaje']; ?></div>
        <?php unset($_SESSION['mensaje']); // Eliminar el mensaje después de mostrarlo 
        ?>
    <?php endif; ?>
<!-- ---------------------- -->
    <div class="row">
        <div class="col">
            <div class="card rounded-2 border-0">
                <h5 class="card-header bg-dark text-white">Agregar Ciclo Lectivo</h5>
                <div class="card-body bg-light">
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Ciclo Lectivo:</label>
                            <input type="text" class="form-control" name="ciclo" placeholder="Ingrese Ciclo Lectivo" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Inicio:</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Fin:</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                        <input type="hidden" name="accion" value="agregar_ciclo">
                        <input type="submit" class="btn btn-primary" value="Agregar">
                    </form>
                </div>
            </div>
        </div>
        <!-- Formulario para actualizar el ciclo lectivo actual -->
        <div class="col">
            <div class="card rounded-2 border-0">
                <h5 class="card-header bg-dark text-white">Ciclo Lectivo</h5>
                <div class="card-body bg-light">                    
                <!-- ----------------------------------------- -->                
                <div class="card border-dark mb-3">
                    <h5 class="card-header bg-dark text-white">Ciclo Lectivo Actual</h5>
                        <div class="card-body ">
                            <?php if ($ciclo_actual) : ?>
                            <p>El ciclo lectivo actual es: <h2 class="text-success"><?php echo htmlspecialchars($ciclo_actual['nombre_ciclo'], ENT_QUOTES, 'UTF-8'); ?></h2></p>
                            <?php else : ?>
                            <p class="text-danger">No hay un ciclo lectivo actual definido.</p>
                            <?php endif; ?> 
                        </div>
                </div>           
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Seleccione Ciclo Lectivo para Actualizar:</label>                            
                            <select name="nuevo_ciclo" class="form-control" required>
                                <option value="" disabled selected>Seleccione Ciclo Lectivo</option>
                                <?php
                                // Consulta SQL para obtener ciclos que no sean el actual
                                $stmt = $db->query("SELECT id_ciclo, nombre_ciclo FROM ciclo_lectivo WHERE ciclo_actual = 0 ORDER BY id_ciclo DESC");
                                // Recorrer los resultados para llenar el select
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row["id_ciclo"]}'>{$row["nombre_ciclo"]}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="accion" value="actualizar_ciclo">
                        <input type="submit" class="btn btn-success" value="Actualizar">
                    </form>                                  
                </div>                
            </div>
        </div>
    </div>
</div>
<!-- Incluir el pie de página -->
<?php require 'footer.php'; ?>