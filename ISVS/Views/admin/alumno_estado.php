<?php require 'navbar.php'; ?>

<?php
// Conexión a la base de datos
require '../../conn/connection.php';

// Obtener el ID del alumno de la URL
$alumno_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($alumno_id) {
    // Obtener el nombre y apellido del alumno
    $sql_alumno = "SELECT nombre, apellido FROM persona WHERE id_persona = $alumno_id";
    $result_alumno = $conexion->query($sql_alumno);

    if ($result_alumno->num_rows > 0) {
        $alumno = $result_alumno->fetch_assoc();
        $nombre_completo = $alumno['nombre'] . ' ' . $alumno['apellido'];
    } else {
        $nombre_completo = "Alumno no encontrado";
    }
    // Obtener las materias disponibles desde la base de datos
    $sql = "SELECT id_materia, Nombre, estado  FROM materia where estado = 'Activo'";
    $result = $conexion->query($sql);
    $materias = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
    } else {
        echo "No se encontraron materias.";
    }
    // Obtener el estado del alumno en las materias
    $sql_estado = "SELECT * FROM alumno_materia WHERE id_persona = $alumno_id";
    $result_estado = $conexion->query($sql_estado);    
    $estado_alumno = [];
    if ($result_estado->num_rows > 0) {
        while ($row_estado = $result_estado->fetch_assoc()) {
            $estado_alumno[$row_estado['id_materia']] = $row_estado['estado'];
        }
    }    
} else {
    echo "ID de alumno no especificado.";
    exit;
}
?>
<!-- ------------------------------------- -->
<section class="content mt-2">
    <div class="row m-auto">
        <div class="col-sm">
            <div class="card rounded-2 border-0">
                <div class="card-header bg-dark text-white pb-0">
                    <h5 class="d-inline-block"><?php echo htmlspecialchars($nombre_completo); ?></h5>            
                </div>
                <div class="card-body table-responsive">
            <!-- ------------------------------------------------------------- -->
            <?php 
             if(!isset($_POST['buscar'])){
                $sql_ciclo = "SELECT id_ciclo , nombre_ciclo FROM ciclo_lectivo WHERE ciclo_actual = 1 LIMIT 1";
                $result_ciclo = $conexion->query($sql_ciclo);
                $ciclo = $result_ciclo->fetch_assoc();
                $select_ciclo = $ciclo['id_ciclo'];
                
                echo "hola".$select_ciclo;
             }
             if(isset($_POST['buscar'])){
                $select_ciclo = $_POST['select_ciclo'];
                // ----------------------------------------------------                
                $sql_ciclo = "SELECT id_ciclo, nombre_ciclo FROM ciclo_lectivo WHERE id_ciclo = $select_ciclo";
                $result_ciclo = $conexion->query($sql_ciclo);
                $ciclo = $result_ciclo->fetch_assoc();
                // --------------------------------------------------------   
                echo "chau".$select_ciclo; 
             }
             ?>            
           <!-- ------------------------------------------------------------- -->
           <p class="border border-primary d-inline ">ciclo lectivo actual: <?php echo $ciclo['nombre_ciclo']; ?></p>
            <!-- ------------------------------------------------------------------ -->
                <form action="" method="post" class="form-group  d-flex flex-row w-25 border border-primary">
                      <select name="select_ciclo" class="form-select" >
                          <option value="" disabled selected class="text-secondary">Seleccione ciclo lectivo</option>
                          <?php                          
                          $stmt = $db->query("SELECT * FROM ciclo_lectivo");                          
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                              echo "<option value='{$row["id_ciclo"]}'>{$row["nombre_ciclo"]}</option>";
                          }
                          ?>
                      </select>                
                    <button type="submit" name="buscar" class="btn btn border  ">
                        <i class="fa-solid fa-magnifying-glass "></i>
                    </button>
                </form>
           <!-- ------------------------------------------------------------- -->
                    <table id="" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                            <th id="notas">#</th>
                                <th id="notas">Materia</th>
                                <th id="notas">Inscribir</th>
                                <th id="notas">Nota 1</th>
                                <th id="notas">Nota 2</th>
                                <th id="notas">Nota 3</th>
                                <th id="notas">Nota 4</th>
                                <th id="notas">Calif. Regular</th>
                                <th id="notas">Calif.1° Ex.Final</th>
                                <th id="notas">Calif.2° Ex.Final</th>
                                <th id="notas">Calif. Final</th>
                                <th id="notas">1°PeR. Ev.Dic</th>
                                <th id="notas">2°PeR. Ev.Dic</th>
                                <th id="notas">1°PeR. Ev.Feb</th>
                                <th id="notas">2°PeR. Ev.Feb</th>
                                <th id="notas">Calif. Final</th>
                                <th id="notas">Guardar</th>
                            </tr>
                        </thead>
                        <!-- __________________________________________________________ -->
                         <?php
                            $sql_estado = "SELECT * FROM alumno_materia WHERE id_persona = $alumno_id AND estado = 'Inscripto' AND id_ciclo = $select_ciclo";
                            $result_estado = $conexion->query($sql_estado);    
                            $estado = $result_estado->fetch_assoc();
                            //$ciclo_estado = $estado['id_ciclo'];
                            
                         ?>

                        <!-- ____________________________________________________________- -->
                        <tbody>
                            
                            <?php foreach ($materias as $index => $materia): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($materia['Nombre']); ?></td>
                                    <td>
                                        <?php if (isset($estado_alumno[$materia['id_materia']]) 
                                                        && $estado_alumno[$materia['id_materia']] == 'Inscripto' 
                                                        
                                                        ): ?>
                                                                         
                                            <div class="bg-success text-white text-center">Inscripto</div>
                                        <?php else: ?>
                                            <form action="alumno_inscripcion.php" method="post">
                                                <input type="hidden" name="alumno_id" value="<?php echo htmlspecialchars($alumno_id); ?>">
                                                <input type="hidden" name="materia_id" value="<?php echo htmlspecialchars($materia['id_materia']); ?>">
                                                <input type="hidden" name="ciclo_lectivo" value="<?php echo htmlspecialchars($select_ciclo); ?>">
                                                <button type="submit" class="btn btn-danger btn-sm btn-block">Inscribir</button>
                                            </form>
                                            
                                        <?php endif; ?>
                                        <?php echo  $select_ciclo  ."_".  $estado_alumno[$materia['id_materia']] ; ?>
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    <td>
                                        <input size="5" type="text" name="" value="" placeholder="nota">
                                    </td>
                                    
                                    <td><?php echo isset($estado_alumno[$materia['id_materia']]) ? htmlspecialchars($estado_alumno[$materia['id_materia']]) : 'libre'; ?></td>
                                    <td>
                                       <button type="button" class="btn btn-primary" >Guardar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>    
            </div> 
        </div>   
    </div>
</section>

<?php require 'footer.php'; ?>
