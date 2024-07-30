<?php require 'navbar.php'; ?>

<div class="container mt-2 " style="width: 40rem">
    <div class="row m-auto">
        <div class="col">
            <div class="card rounded-2 border-0">
                <div class="card-header bg-dark text-white">
                Formulario Acta
                </div>
                <div class="card-body">
                    <form action="procesar_acta.php" method="post">
                        <div class="form-group">
                            <label for="id_mesa">Mesa de Examen:</label>
                            <select name="id_mesa" id="id_mesa">
                                <?php
                                // Conexión a la base de datos
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $database = "vidasilvestre";
                                // Crear conexión
                                $conn = new mysqli($servername, $username, $password, $database);
                                // Verificar la conexión
                                if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                                }
                                // Consulta para obtener las mesas de examen disponibles
                                $sql_mesas = "SELECT id_mesa, nombre_mesa, tipo FROM mesa_examen";
                                $result_mesas = $conn->query($sql_mesas);
                                if ($result_mesas->num_rows > 0) {
                                    while ($row = $result_mesas->fetch_assoc()) {
                                        echo "<option value='" . $row['id_mesa'] . "'>" . $row['nombre_mesa'] . $row['tipo']  . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>  
                        <div class="form-group">
                            <label for="id_persona">Alumno:</label>
                            <select name="id_persona" id="id_persona">
                                <?php
                                // Consulta para obtener personas con id_rol 1
                                $sql_personas = "SELECT id_persona, nombre FROM persona WHERE id_rol = 1";
                                $result_personas = $conn->query($sql_personas);
                                if ($result_personas->num_rows > 0) {
                                    while ($row = $result_personas->fetch_assoc()) {
                                        echo "<option value='" . $row['id_persona'] . "'>" . $row['nombre'] . "</option>";
                                    }
                                }
                                // Cerrar la conexión a la base de datos
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="nota" class="d-inline-block " >Nota:</label>
                            <select name="nota" id="nota" class="form-group " autocomplete="off" required>
                                <option value="">seleccione una nota</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>             
                        <input class="btn btn-primary" type="submit" value="Enviar">
                    </form>                        
                </div>    
            </div>
        </div>    
    </div>
</div>

