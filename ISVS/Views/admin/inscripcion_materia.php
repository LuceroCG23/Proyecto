
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
                        <form action="insertar.php" method="post">
                            <?php
                            // Verificar si se enviaron datos por el formulario de la página anterior
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_persona'])) {
                                // Obtener los ID de los alumnos seleccionados
                                $id_personas = $_POST['id_persona'];
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
                                        // Mostrar los datos de los alumnos seleccionados
                                        echo "<h3>Alumnos Seleccionados:</h3>";
                                        echo "<ul class='list-group'>";
                                        foreach ($id_personas as $id_persona) {
                                            // Query para obtener datos del alumno
                                            $query_alumno = "SELECT nombre, apellido FROM persona WHERE id_persona = '$id_persona'";
                                            $result_alumno = $conn->query($query_alumno);

                                            if ($result_alumno->num_rows > 0) {
                                                $row = $result_alumno->fetch_assoc();
                                                echo "<li class='list-group-item'>{$row['nombre']} {$row['apellido']}</li>";
                                            }
                                        }
                                        echo "</ul>";
                                        ?>
                                    </div>
                                    <div class="col">
                                        <h3>Seleccionar Ciclo</h3>
                                        <select name="ciclo_lectivo" id="ciclo_lectivo" class="form-control" autocomplete="off" required>
                                            <option value="" disabled selected>Seleccione el ciclo lectivo</option>
                                            <?php
                                            require 'conn/connection.php';
                                            $query_ciclos = "SELECT id_ciclo, nombre_ciclo FROM ciclo_lectivo";
                                            $result_ciclos = $db->query($query_ciclos);
                                            while ($row_ciclo = $result_ciclos->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row_ciclo['id_ciclo'] . "'>" . $row_ciclo['nombre_ciclo'] . "</option>";
                                            }
                                            ?>
                                        </select>
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
                                        <th>Estado</th>
                                        <th>Descripción</th>
                                        </tr>";
                                        ?>
                                    </div>
                                </div>
                            <?php
                                // Query para obtener materias con estado activo
                                $query_materias = "SELECT id_materia, Nombre, estado, descripcion FROM materia WHERE estado = 'Activo'";
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
                                    echo "<td>{$row['estado']}</td>";
                                    echo "<td>{$row['descripcion']}</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                                // -----------------------------------
                                echo "<input type='hidden' name='id_persona' value='" . implode(",", $id_personas) . "'>";

                                echo "<a href='seleccionar_alumnos.php' class='btn btn-danger mt-3 mr-2  px-4'>Cancelar</a>";
                                echo "<input type='submit' class='btn btn-primary mt-3 px-4' value='Inscribir'>";
                                // -----------------------------------
                                echo "</form>";  // Cierre del formulario
                                // Cerrar la conexión
                                $conn->close();
                            } else {
                                // Si alguien intenta acceder a este archivo directamente sin enviar datos por el formulario
                                echo "Acceso no permitido.";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php require 'footer.php'; ?>