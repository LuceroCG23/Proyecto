<?php
require '../../conn/connection.php';

function insertarMateria($db, $nombre, $descripcion, $horas, $num_resolucion, $plan_estudio, $año_cursado, $id_tipo, &$mensaje, &$error)
{
    try {
        $stmt = $db->prepare("INSERT INTO materia (Nombre, descripcion, horas, num_resolucion, plan_estudio, año_cursado, id_tipo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'Activo')");
        $stmt->execute([$nombre, $descripcion, $horas, $num_resolucion, $plan_estudio, $año_cursado, $id_tipo]);
        $materia_id = $db->lastInsertId();

        if (isset($_POST['correlativas'])) {
            $correlativas = $_POST['correlativas'];
            foreach ($correlativas as $correlativa) {
                $stmt = $db->prepare("INSERT INTO correlativa (id_materia, id_correlativa) VALUES (?, ?)");
                $stmt->execute([$materia_id, $correlativa]);
            }
            $mensaje = "Materia ingresada con éxito. Correlativas guardadas.";
        } else {
            $mensaje = "Materia ingresada con éxito. No se agregaron correlativas.";
        }
    } catch (PDOException $e) {
        $error = "Error al ingresar Materia: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    $mensaje = null;
    $error = null;

    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $horas = (int)$_POST["horas"];
    $num_resolucion = substr($_POST["num_resolucion"], 0, 15); // Limitar a 15 caracteres
    $plan_estudio = $_POST["plan_estudio"];
    $año_cursado = $_POST["año"];
    $id_tipo = (int)$_POST["id_tipo"];

    insertarMateria($db, $nombre, $descripcion, $horas, $num_resolucion, $plan_estudio, $año_cursado, $id_tipo, $mensaje, $error);

    header("Location: materia_index.php?mensaje=" . urlencode($mensaje) . "&error=" . urlencode($error));
    exit();
}
?>
<?php require 'navbar.php'; ?>

<div class="container mt-2">
    <div class="card rounded-2 border-0">
        <h5 class="card-header bg-dark text-white">Registro de Materias</h5>
        <div class="card-body">
            <form method="post" action="" id="formulario-materia">
                <div id="formulario-ingreso">
                    <div class="row">
                        <div class="col">
                            <br>
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el Nombre" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <input type="text" class="form-control" name="descripcion" placeholder="Ingrese Descripcion" id="descripcion" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="horas">Horas de cursada:</label>
                                <input type="number" class="form-control" name="horas" id="horas" placeholder="Ingrese las horas" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="num_resolucion">Número de Resolución:</label>
                                <input type="text" class="form-control" id="num_resolucion" name="num_resolucion" placeholder="Ingrese N° de Resolucion (Máx. 11 caracteres)" maxlength="15" autocomplete="off" required>
                                <small class="form-text text-muted">Máximo 15 caracteres.</small>
                            </div>

                            <div class="form-group">
                                <label for="año">Año de Cursado:</label>
                                <select name="año" id="año" class="form-control" autocomplete="off" required>
                                    <option value="" disabled selected>Seleccione Año de Cursado</option>
                                    <option value="1">1° Año</option>
                                    <option value="2">2° Año</option>
                                    <option value="3">3° Año</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="plan_estudio">Cuatrimestre</label>
                                <select name="plan_estudio" id="plan_estudio" class="form-control" autocomplete="off" required>
                                    <option value="" disabled selected>Seleccione el Cuatrimestre</option>
                                    <option value="1">1° Cuatrimestre</option>
                                    <option value="2">2° Cuatrimestre</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_tipo">Tipo de Materia:</label>
                                <select name="id_tipo" id="id_tipo" class="form-control" autocomplete="off" required>
                                    <option value="" disabled selected>Seleccione su Tipo</option>
                                    <option value="1">Promocional</option>
                                    <option value="2">Regular</option>
                                    <option value="3">Libre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card-body table-responsive pt-0">
                                <table id="" class="table table-striped table-sm" style="width:100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Materia</th>
                                            <th>Seleccione</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = $db->query("SELECT * FROM materia WHERE estado = 'Activo'");
                                        while ($resultado = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $resultado["id_materia"] ?></td>
                                                <td><?php echo $resultado["Nombre"] ?></td>
                                                <td>
                                                    <div class="form-check checkbox-xl d-flex justify-content-center">
                                                        <input type="checkbox" class="form-check-input" name="correlativas[]" value="<?php echo $resultado["id_materia"]; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                         <!-- Opción para no agregar correlativa -->
                                            <tr>
                                                <td colspan="3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="noCorrelativa" value="no">
                                                        <label class="form-check-label" for="noCorrelativa">No agregar correlativa</label>
                                                    </div>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary float-right" id="continuarBtn">Continuar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="confirmacion" style="display: none;">
                    <h5>Confirmar datos ingresados</h5>
                    <p><strong>Nombre:</strong> <span id="confirmarNombre"></span></p>
                    <p><strong>Descripción:</strong> <span id="confirmarDescripcion"></span></p>
                    <p><strong>Horas de cursada:</strong> <span id="confirmarHoras"></span></p>
                    <p><strong>Número de Resolución:</strong> <span id="confirmarResolucion"></span></p>
                    <p><strong>Año de Cursado:</strong> <span id="confirmarAño"></span></p>
                    <p><strong>Cuatrimestre:</strong> <span id="confirmarPlan"></span></p>
                    <p><strong>Tipo de Materia:</strong> <span id="confirmarTipo"></span></p>
                    <p><strong>Correlativas:</strong> <span id="confirmarCorrelativas"></span></p>
                    <input type="hidden" name="confirmar" value="true">
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-danger" id="cancelarBtn">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
  <script>
    document.getElementById('continuarBtn').addEventListener('click', function() {
    document.getElementById('formulario-ingreso').style.display = 'none';
    document.getElementById('confirmacion').style.display = 'block';

    document.getElementById('confirmarNombre').textContent = document.getElementById('nombre').value;
    document.getElementById('confirmarDescripcion').textContent = document.getElementById('descripcion').value;
    document.getElementById('confirmarHoras').textContent = document.getElementById('horas').value;
    document.getElementById('confirmarResolucion').textContent = document.getElementById('num_resolucion').value;
    document.getElementById('confirmarAño').textContent = document.getElementById('año').value;
    document.getElementById('confirmarPlan').textContent = document.getElementById('plan_estudio').value;

    // Obtener texto del select de Tipo de Materia
    var tipoSelect = document.getElementById('id_tipo');
    var tipoText = tipoSelect.options[tipoSelect.selectedIndex].text;
    document.getElementById('confirmarTipo').textContent = tipoText;

    let correlativasSeleccionadas = Array.from(document.querySelectorAll('input[name="correlativas[]"]:checked'))
        .map(el => el.parentElement.parentElement.previousElementSibling.textContent)
        .join(', ');
    document.getElementById('confirmarCorrelativas').textContent = correlativasSeleccionadas || 'Sin correlativas';

    document.querySelector('input[name="nombre"]').value = document.getElementById('nombre').value;
    document.querySelector('input[name="descripcion"]').value = document.getElementById('descripcion').value;
    document.querySelector('input[name="horas"]').value = document.getElementById('horas').value;
    document.querySelector('input[name="num_resolucion"]').value = document.getElementById('num_resolucion').value;
    document.querySelector('input[name="año"]').value = document.getElementById('año').value;
    document.querySelector('input[name="plan_estudio"]').value = document.getElementById('plan_estudio').value;
    document.querySelector('input[name="id_tipo"]').value = document.getElementById('id_tipo').value;
    
    // Actualizar correlativas ocultas
    document.querySelectorAll('.form-check.d-none input[name="correlativas[]"]').forEach(el => el.parentElement.remove());
    document.querySelectorAll('input[name="correlativas[]"]:checked').forEach(el => {
        let hiddenCheckbox = document.createElement('input');
        hiddenCheckbox.type = 'checkbox';
        hiddenCheckbox.className = 'form-check-input d-none';
        hiddenCheckbox.name = 'correlativas[]';
        hiddenCheckbox.value = el.value;
        hiddenCheckbox.checked = true;
        document.querySelector('.form-check.d-none').appendChild(hiddenCheckbox);
    });
});

document.getElementById('cancelarBtn').addEventListener('click', function() {
    document.getElementById('confirmacion').style.display = 'none';
    document.getElementById('formulario-ingreso').style.display = 'block';
});
  </script>
</div>
<?php require 'footer.php'; ?>