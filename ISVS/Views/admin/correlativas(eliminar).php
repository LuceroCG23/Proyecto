<?php require 'navbar.php'; ?>
    <section class="content mt-2">
        <div class="row m-auto ">
            <div class="col-sm">
                <div class="card rounded-2 border-0">
                    <div class="card-header bg-dark text-white pb-0">
                        <h5 class="d-inline-block ">Listado de Materias y Correlativas</h5>
                        <!-- <a class="btn btn-primary float-right mb-2" href="registromateria.php">Registro de Materia</a> -->
                    </div>
                    <div class="card-body table-responsive">
                        <form action="correlativa1.php" method="post">
                            <?php
                                // Conexión a la base de datos (reemplaza estos valores con los tuyos)
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "vidasilvestre";
                                // Crear conexión
                                $conn = new mysqli($servername, $username, $password, $dbname);

                                // Verificar la conexión
                                if ($conn->connect_error) {
                                    die("Conexión fallida: " . $conn->connect_error);
                                }                                
                                ?>
                                <div class="row">
                                    <div class="col">                                    
                                        <?php
                                        // Mostrar los datos de materia seleccionada
                                        echo "<h3>Materia Seleccionada:</h3>";
                                        echo "<ul class='list-group'>";                                        
                                            // Query para obtener datos del alumno                                            
                                            $query = "SELECT Nombre FROM materia WHERE id_materia =". $_GET['id'];
                                            $result = $conn->query($query);

                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                echo "<li class='list-group-item'>{$row['Nombre']}</li>";
                                            }
                                        echo "</ul>";
                                        ?>
                                    </div>                                    
                                    <div class="col ">
                                        <?php
                                        // Mostrar la lista de materias disponibles
                                        echo "<h3>Selección de Materias:</h3>";
                                        echo "<table  id='example2' class='table table-striped' style='width:100%'>";
                                        echo 
                                        "<tr>
                                        <th>Seleccionar</th>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        </tr>";
                                        ?>                                
                                    </div>
                                </div>
                                <?php
                                        // Query para obtener materias con estado activo
                                            $query_materias = "SELECT id_materia, Nombre, descripcion FROM materia WHERE estado = 'Activo'";
                                            $result_materias = $conn->query($query_materias);
                                            while ($row = $result_materias->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>
                                                <div class='form-check checkbox-xl d-flex justify-content-center'>
                                                <input type='checkbox' class='form-check-input ' name='id_materia[]' value='{$row['id_materia']}'>
                                                </div>
                                                </td>";
                                                echo "<td>{$row['id_materia']}</td>";
                                                echo "<td>{$row['Nombre']}</td>";
                                                echo "<td>{$row['descripcion']}</td>";
                                                echo "</tr>";
                                            };
                                            echo "</table>";
                                            // -----------------------------------
                                            echo "<a href='listado_materia.php' class='btn btn-danger mt-3 mr-2  px-4'>Cancelar</a>";
                                            echo "<input type='submit' class='btn btn-primary mt-3 px-4' value='Agregar'>";
                                            // -----------------------------------
                                           // Cierre del formulario
                                            // Cerrar la conexión
                                            $conn->close();
                            ?>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
<?php require 'footer.php'; ?>