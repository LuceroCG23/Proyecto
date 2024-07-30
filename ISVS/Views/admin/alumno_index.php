<?php
require '../../conn/connection.php';
//-------------BORRADO------------------ 
if(isset($_GET['txtID'])){
  $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
  $sentencia=$db->prepare("UPDATE persona SET estado = 'Inactivo' WHERE id_persona = :id" );
  $sentencia->bindParam(':id',$txtID);
  $sentencia->execute();
  $mensaje="Registro Alumno Eliminado";
  header("Location:alumno_index.php?mensaje=".$mensaje);
}
?>
<!-- ------------------------------------------ -->
<?php require 'navbar.php'; ?>
    <section class="content mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <div class="card-header pb-0 bg-dark text-white ">
                        <h5 class="d-inline-block ">Listado de Alumnos</h5>
                        <a class="btn btn-primary float-right mb-2" href="alumno_crea.php">Agregar Alumno</a>
                    </div>                    
                    <!-- -------------------- -->
                    <div class="card-body table-responsive">
                        <form id="inscripcionForm" action="" method="post">
                            <table id="example" class="table table-striped table-sm" style="width:100%">
                                <thead class="thead-dark">
                                    <th>#</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Genero</th>
                                    <th>DNI</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Celular</th>
                                    <th>Departamento</th>
                                    <!-- <th>Historial</th> -->
                                    <th>Inscripciones</th> 
                                    <th>Notas</th> 
                                    <th>Acciones</th>               
                                </thead>
                                <tbody>
                                    <?php                                    
                                    try {
                                        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
                                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $query = "SELECT * FROM persona WHERE id_rol = 1 AND estado = 'Activo'";
                                        $stmt = $db->prepare($query);
                                        $stmt->execute();
                                        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($alumnos as $alumno) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $alumno['id_persona'] ?></th>
                                                <td><?php echo $alumno['apellido'] ?></td>
                                                <td><?php echo $alumno['nombre'] ?></td>
                                                <td><?php echo $alumno['genero'] ?></td>
                                                <td><?php echo $alumno['DNI'] ?></td>
                                                <td><?php echo $alumno['fecha_ingreso'] ?></td>
                                                <td><?php echo $alumno['fecha_nacimiento'] ?></td>
                                                <td><?php echo $alumno['celular'] ?></td> 
                                                <td><?php echo $alumno['ciudad'] ?></td>  
                                                <td><a href="alumno_inscripcion.php?id=<?php echo $alumno['id_persona'];?>" class="btn btn-info btn-sm" type="button">Inscripciones</a> </td>
                                                <td><a href="alumno_estado.php?id=<?php echo $alumno['id_persona'];?>" class="btn btn-info btn-sm" type="button">Notas</a></td>                                                     
                                                <td class="text-center">
                                                    <div class="btn-group">

                                                        <a href="alumno_info.php?id=<?php echo $alumno['id_persona'];?>" class="btn btn-info btn-sm" type="button">Info.</a>  

                                                        <a href="alumno_edit.php?id=<?php echo $alumno['id_persona'];?>"class="btn btn-warning btn-sm" type="button" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:eliminar1(<?php echo $alumno['id_persona'];?>)" class="btn btn-danger btn-sm" type="button" title="Borrar">                                                            
                                                            <i class="fas fa-trash"></i>
                                                        </a> 
                                                    </div>  
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error de conexión: " . $e->getMessage();
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </form>
                        <br>
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- <script src="js/ocultarMensaje.js"></script>     -->
<?php require 'footer.php'; ?>   