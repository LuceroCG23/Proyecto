<?php
require '../../conn/connection.php';
// Inicializar la variable para evitar advertencias
$alumnos = [];
// Consulta de materias y ciclos activos
$materias = $db->prepare("SELECT * FROM materia WHERE estado = 'Activo'");
$materias->execute();
$materias = $materias->fetchAll();
$ciclos = $db->prepare("SELECT * FROM ciclo_lectivo WHERE estado = 'Activo'");
$ciclos->execute();
$ciclos = $ciclos->fetchAll();
// Procesamiento del formulario
if (isset($_GET['revisar'])) {
    $id_materia = $_GET['materia'];
    // Validar datos del formulario
    if (!is_numeric($id_materia)) {
        die('Error: Los datos del formulario no son válidos.');
    }
    try {
        $sqlAlumnos = $db->prepare("
            SELECT
                p.id_persona, 
                CONCAT(p.apellido, ', ', p.nombre) AS nombre,
                m.nombre AS materia,
                n.Nota_1,
                n.Nota_2,
                n.Nota_3,
                n.Nota_4,
                AVG((n.Nota_1 + n.Nota_2 + n.Nota_3 + n.Nota_4) / 4) AS promedio
            FROM
                persona p
            JOIN
                alumno_materia am ON p.id_persona = am.id_persona
            JOIN
                materia m ON am.id_materia = m.id_materia
            LEFT JOIN
                nota n ON am.id_nota = n.id_nota
            WHERE
                am.id_materia = :id_materia
            GROUP BY
                p.id_persona
        ");
        // Ligar parámetros y ejecutar la consulta
        $sqlAlumnos->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $sqlAlumnos->execute();
        // Guardar resultados en la variable
        $alumnos = $sqlAlumnos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    }
}
?>
<!-- -------------------------------------------- -->
<?php require 'navbar.php'; ?>
    <div class="content mt-3">
        <div class="row m-auto justify-content-center">
            <div class="col-auto">
                <div class="card rounded-2 border-0">
                    <div class="card-header bg-dark text-white">
                        <h5>Registro y Modificación Notas</h5>
                    </div>
                    <div class="card-body table-responsive-xl">
                        <!-- Formulario para seleccionar materia -->
                        <?php if (!isset($_GET['revisar'])) : ?>
                            <form method="get" action="">
                                <label class="font-weight-bold">Seleccione la Materia</label>
                                <select class="form-select" name="materia" required>
                                    <option value="" disabled selected>Seleccione la Materia</option>
                                    <?php foreach ($materias as $materia) : ?>
                                        <option value="<?php echo $materia['id_materia']; ?>"><?php echo $materia['Nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label class="font-weight-bold">Seleccione Ciclo</label>
                                <select name="ciclo_lectivo" class="form-control" required>
                                    <option value="" disabled selected>Seleccione el ciclo lectivo</option>
                                    <?php foreach ($ciclos as $ciclo) : ?>
                                        <option value="<?php echo $ciclo['id_ciclo']; ?>"><?php echo $ciclo['nombre_ciclo']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" name="revisar" class="btn btn-primary">Ingresar Notas</button>
                                    <a class="btn btn-warning ml-3" href="listadonotas.view.php">Consultar Notas</a>
                                </div>
                            </form>
                        <?php endif; ?>
                        <?php if (isset($_GET['revisar'])) : ?>
                            <form action="guardar_notas.php" method="post">
                                <div class="table-responsive">
                                    <table id="example" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Alumno</th>
                                                <!-- Campos para ingresar las notas -->
                                                <th>Nota 1</th>
                                                <th>Nota 2</th>
                                                <th>Nota 3</th>
                                                <th>Nota 4</th>
                                                <th>Calif. Regular</th>
                                                <th>Calif.1° Ex.Final</th>
                                                <th>Calif.2° Ex.Final</th>
                                                <th>Calif. Final</th>
                                                <th>1°PeR. Ev.Dic</th>
                                                <th>2°PeR. Ev.Dic</th>
                                                <th>1°PeR. Ev.Feb</th>
                                                <th>2°PeR. Ev.Feb</th>
                                                <th>Calif. Final</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Verificar que hay datos de alumnos -->
                                            <?php if (isset($alumnos) && count($alumnos) > 0) : ?>
                                                <!-- Iterar sobre los alumnos para crear las filas -->
                                                <?php foreach ($alumnos as $alumno) : ?>
                                                    <tr>
                                                        <td><?php echo $alumno['id_persona']; ?></td>
                                                        <td><?php echo $alumno['nombre']; ?></td>
                                                        <!-- Campos de entrada para las notas -->
                                                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                                                            <td>
                                                                <input size="5" type="text" name="nota<?php echo $i; ?>_<?php echo $alumno['id_persona']; ?>" value="<?php echo isset($alumno['nota' . $i]) ? $alumno['nota' . $i] : ''; ?>">
                                                            </td>
                                                        <?php endfor; ?>
                                                        <!-- Otros tipos de notas -->
                                                        <?php for ($i = 5; $i <= 12; $i++) : ?>
                                                            <td>
                                                                <input size="5" type="text" name="nota<?php echo $i; ?>_<?php echo $alumno['id_persona']; ?>" value="<?php echo isset($alumno['nota' . $i]) ? $alumno['nota' . $i] : ''; ?>">
                                                            </td>
                                                        <?php endfor; ?>
                                                        <td><?php echo number_format($alumno['promedio'], 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="12">No se encontraron datos</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Botones de acción -->
                                <div class="content mt-3">
                                    <a class="btn btn-danger mb-2" href="notas.view.php"><strong>&lt;&lt; Volver</strong></a>
                                    <div class="ml-3" style="float: right;">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a class="btn btn-warning" href="listadonotas.view.php">Consultar Notas</a>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require 'footer.php'; ?>
