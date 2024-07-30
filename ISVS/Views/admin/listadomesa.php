<?php
require '../../conn/connection.php';
//-------------BORRADO------------------ 
if(isset($_GET['txtID'])){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    $sentencia=$db->prepare("UPDATE mesa_examen SET estado = 'Inactivo' WHERE id_mesa = :id" );
    $sentencia->bindParam(':id',$txtID);
    $sentencia->execute();
    $mensaje="Registro Eliminado";
    header("Location:listadomesa.php?mensaje=".$mensaje);
  }

// Realiza la consulta para obtener las mesas de examen
$query = "SELECT mesa_examen.*, 
                 materia.nombre AS nombre_materia, 
                 ciclo_lectivo.nombre_ciclo,
                 nombre_tipo AS nombre_tipo
          FROM mesa_examen 
          INNER JOIN materia ON mesa_examen.id_materia = materia.id_materia
          LEFT JOIN ciclo_lectivo ON mesa_examen.id_ciclo = ciclo_lectivo.id_ciclo
          LEFT JOIN tipo ON mesa_examen.id_tipo = tipo.id_tipo WHERE  mesa_examen.estado= 'Activo'";
$result = $db->query($query);
?>
<!-- --------------------------------------------------- -->
<?php require 'navbar.php'; ?>
    <div class="container mt-3">
        <div class="card rounded-2 border-0">
            <di class="card-header pb-0 bg-dark text-white ">
                <h5 class="card-header bg-dark text-white"> Mesas de Examen <a href="../../fpdf/rep-mesa.php" tanget="_blank" class="btn btn-danger  float-right mb-2 "> <i class="fa-solid fa-file-pdf"></i></a>
                    <a href="../../fpdf/excel.php" tanget="_blank" class="btn btn-success float-right mb-2 mr-2"> <i class="fas fa-file-excel"></i></a>
                </h5>
            </di>
            <div class="card-body table-responsive">
                <?php
                if (isset($_GET['mensaje']) && !empty($_GET['mensaje'])) {
                    echo '<div class="alert alert-success"role="alert">' . htmlspecialchars($_GET['mensaje']) . '</div>';
                }
                if (isset($_GET['error']) && !empty($_GET['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                }
                ?>
                <table id="example" class="table table-striped table-bordered  " cellspacing="0" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre de Mesa</th>
                            <th>Materia</th>
                            <th>Hora</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Ciclo Lectivo</th>
                            <th>Tipo</th>
                            <th>Acciones </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row['nombre_mesa'] . "</td>";
                            echo "<td>" . $row['nombre_materia'] . "</td>";
                            echo "<td>" . $row['hora'] . "</td>";
                            echo "<td>" . $row['fecha_i'] . "</td>";
                            echo "<td>" . $row['fecha_fin'] . "</td>";
                            echo "<td>" . $row['nombre_ciclo'] . "</td>";
                            echo "<td>" . $row['nombre_tipo'] . "</td>";    
                        ?>
                        <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="mesa_edit.php?id=<?php echo $row['id_mesa']; ?>" class="btn btn-warning btn-sm" title="Editar" role="button">
                                                            <i class="fas fa-edit"></i>
                                                        </a>                                                         
                                                        <a href="javascript:eliminar5(<?php echo $row['id_mesa'];?>)" class="btn btn-danger btn-sm" title="Borrar" role="button">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>  
                                                </td>

                             <?php
                               echo "</tr>";
                                }
                                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/ocultarMensaje.js"></script>
<?php require 'footer.php'; ?>   