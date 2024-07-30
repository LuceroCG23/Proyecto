<?php require 'navbar.php'; ?>
    <section class="content mt-3">
        <div class="row m-auto">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <div class="card-header bg-dark text-white pb-0">
                        <h5 class="d-inline-block">Listado de Profesores</h5>

                        <a class="btn btn-primary float-right mb-2" href="profe_index.php">Volver</a>                

                    </div>
                    <!-- Mensaje de asignar exitosamente o error  -->
                    <?php
                    if (isset($_GET['mensaje1']) && !empty($_GET['mensaje1'])) {
                        echo '<div class="alert alert-success"role="alert">' . htmlspecialchars($_GET['mensaje1']) . '</div>';
                    }
                    if (isset($_GET['error1']) && !empty($_GET['error1'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error1']) . '</div>';
                    }
                    ?>
                    <!-- Mensaje de desactivar un asignar -->
                    <?php
                    if (isset($_GET['mensaje3']) && !empty($_GET['mensaje3'])) {
                        echo '<div class="alert alert-success"role="alert">' . htmlspecialchars($_GET['mensaje3']) . '</div>';
                    }
                    if (isset($_GET['error3']) && !empty($_GET['error3'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error3']) . '</div>';
                    }
                    ?>
                    <!-- Mensaje de editar asignar -->
                    <?php
                    if (isset($_GET['mensaje2']) && !empty($_GET['mensaje2'])) {
                        echo '<div class="alert alert-success"role="alert">' . htmlspecialchars($_GET['mensaje2']) . '</div>';
                    }
                    if (isset($_GET['error2']) && !empty($_GET['error2'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error2']) . '</div>';
                    }
                    ?>
                    <div class="card-body table-responsive">
                        <table id="example" class="table table-striped table_id">
                            <thead class="thead-dark">
                                <th>#</th>
                                <th>Profesor</th>
                                <th>Materia</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                     
                                <?php
                                require ("../../conn/connection.php");

                                $sql = $conexion->query("SELECT * FROM asignar 
                                INNER JOIN persona ON asignar.id_persona = persona.id_persona AND persona.estado = 'Activo'
                                INNER JOIN materia ON asignar.id_materia = materia.id_materia AND materia.estado = 'Activo'
                                WHERE asignar.Estado = 'Activo' ");
                               
                                while ($resultado = $sql->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $resultado['id_asignar'] ?></th>
                                        <td scope="row"><?php echo $resultado['nombre'] ?> <?php echo $resultado['apellido'] ?> </td>
                                        <td scope="row"><?php echo $resultado['Nombre'] ?></td> <!--Lo cambie en la BD materia-->
                                        <td scope="row"><?php echo $resultado['fecha_i'] ?></td> 
                                        <!--Lo cambie en la BD asignar----->
                                        <!--cambie los nombre en BD x q al tener el mismo nombre se mezclan las conexiones-->
                                        <!-------BOTONES--->
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="asigna_edit.php?id=<?php echo $resultado['id_asignar'] ?>" class="btn btn-warning" role="button">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="asigna_borra.php?id=<?php echo $resultado['id_asignar'] ?>" class="btn btn-danger" role="button">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>  
                                        </td>
                                        <!-- ------------------------- -->
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <br><br>
                        <!-- Mostrar mensajes que se reciben a través de los parámetros en la URL -->
                        <?php
                        if (isset($_GET['err'])) {
                            echo '<span class="error">Error al almacenar el registro</span>';
                        }
                        if (isset($_GET['info'])) {
                            echo '<span class="success">Registro almacenado correctamente!</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        </section>    
    <script src="js/buscador.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/ocultarMensaje.js"></script>
<?php require 'footer.php'; ?>
