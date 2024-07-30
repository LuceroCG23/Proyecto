<?php
include '../../conn/connection.php';

// Verifica si se envió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolecta datos del formulario
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $dni = trim($_POST["dni"]);
    $celular = trim($_POST["celular"]);
    $email = trim($_POST["email"]);
    $direccion = trim($_POST["direccion"]);
    $ciudad = trim($_POST["ciudad"]);
    $genero = trim($_POST["genero"]);
    $pais = "Argentina";
    $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
    $fecha_ingreso = trim($_POST["fecha_ingreso"]);
    $passwordd = trim($_POST["passwordd"]);
    $legajo =  trim($_POST["legajo"]);
    $titulo = trim($_POST["titulo"]);
    $estado = "Activo"; // Valor predeterminado para estado
    $id_rol = "2"; // Valor predeterminado para alumno es 1.

    $error = "";

    // Validación de la contraseña
    if (strlen($passwordd) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Validación de la mayoría de edad
    $fecha_actual = new DateTime();
    $fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
    $edad = $fecha_actual->diff($fecha_nacimiento_dt)->y;

    if ($edad < 18) {
        $error = "Debe ser mayor de edad para registrarse.";
    }

    try {
        // Verificar si el correo electrónico ya existe
        $sql_check_email = "SELECT COUNT(*) FROM persona WHERE email_correo = :email";
        $stmt_check_email = $db->prepare($sql_check_email);
        $stmt_check_email->bindParam(':email', $email);
        $stmt_check_email->execute();
        $count_email = $stmt_check_email->fetchColumn();

        // Verificar si el DNI ya existe
        $sql_check_dni = "SELECT COUNT(*) FROM persona WHERE DNI = :dni";
        $stmt_check_dni = $db->prepare($sql_check_dni);
        $stmt_check_dni->bindParam(':dni', $dni);
        $stmt_check_dni->execute();
        $count_dni = $stmt_check_dni->fetchColumn();

        if ($count_email > 0) {
            $error = "El correo electrónico ya está registrado. Por favor, use uno diferente.";
        } elseif ($count_dni > 0) {
            $error = "El DNI ya está registrado. Por favor, use uno diferente.";
        }

        if ($error) {
            $redirect_url = "profe_crea.php?error=" . urlencode($error)
                . "&nombre=" . urlencode($nombre)
                . "&apellido=" . urlencode($apellido)
                . "&dni=" . urlencode($dni)
                . "&celular=" . urlencode($celular)
                . "&email=" . urlencode($email)
                . "&direccion=" . urlencode($direccion)
                . "&ciudad=" . urlencode($ciudad)
                . "&genero=" . urlencode($genero)
                . "&fecha_nacimiento=" . urlencode($fecha_nacimiento)
                . "&fecha_ingreso=" . urlencode($fecha_ingreso)
                . "&legajo=". urlencode($legajo)
                . "&titulo=". urlencode($titulo);

            header("Location: " . $redirect_url);
            exit();
        } else {
            // Inserta datos en la base de datos
            $sql = "INSERT INTO persona (nombre, apellido, fecha_nacimiento, DNI, celular, email_correo, direccion, fecha_ingreso, pais, ciudad, contraseña, id_rol, genero,legajo , titulo, estado) 
                    VALUES (:nombre, :apellido, :fecha_nacimiento, :dni, :celular, :email, :direccion, :fecha_ingreso, :pais, :ciudad, :passwordd , :id_rol, :genero, :legajo,:titulo, :estado)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':celular', $celular);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':passwordd', $passwordd);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':legajo', $legajo);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':estado', $estado);

            if ($stmt->execute()) {
                header("Location: profe_index.php?mensaje=" . urlencode("Persona ingresada con éxito."));
                exit();
            } else {
                $error = "Error al ingresar Persona.";
            }
        }
    } catch (PDOException $e) {
        $error = "Error en la base de datos: " . $e->getMessage();
        $redirect_url = "profe_crea.php?error=" . urlencode($error)
            . "&nombre=" . urlencode($nombre)
            . "&apellido=" . urlencode($apellido)
            . "&dni=" . urlencode($dni)
            . "&celular=" . urlencode($celular)
            . "&email=" . urlencode($email)
            . "&direccion=" . urlencode($direccion)
            . "&ciudad=" . urlencode($ciudad)
            . "&genero=" . urlencode($genero)
            . "&legajo=" . urlencode($legajo)
            . "&titulo=" . urlencode($titulo)
            . "&fecha_nacimiento=" . urlencode($fecha_nacimiento)
            . "&fecha_ingreso=" . urlencode($fecha_ingreso);

        header("Location: " . $redirect_url);
        exit();
    }
}
?>
<!-- ---------------------------------------------------- -->
<?php require 'navbar.php'; ?>
<div class="container mt-3">
    <div class="card rounded-2 border-0">
        <h5 class="card-header bg-dark text-white">Formulario de Inscripción de Profesor</h5>
        <div class="card-body bg-light">
            <?php
            // Recupera el mensaje de error y los datos del formulario desde la URL
            $error = isset($_GET["error"]) ? $_GET["error"] : "";
            $nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : "";
            $apellido = isset($_GET["apellido"]) ? $_GET["apellido"] : "";
            $dni = isset($_GET["dni"]) ? $_GET["dni"] : "";
            $celular = isset($_GET["celular"]) ? $_GET["celular"] : "";
            $email = isset($_GET["email"]) ? $_GET["email"] : "";
            $direccion = isset($_GET["direccion"]) ? $_GET["direccion"] : "";
            $ciudad = isset($_GET["ciudad"]) ? $_GET["ciudad"] : "";
            $genero = isset($_GET["genero"]) ? $_GET["genero"] : "";
            $fecha_nacimiento = isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : "";
            $fecha_ingreso = isset($_GET["fecha_ingreso"]) ? $_GET["fecha_ingreso"] : "";
            $legajo = isset($_GET["legajo"]) ? $_GET["legajo"] : "";
            $titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : "";
            ?>
            <form id="formulario" method="post" action="">
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>" autocomplete="off" placeholder="Ingrese Nombre(s)" required>
                        </div>
                    </div>
                    <!-- --------------------------------- -->
                    <div class="col">
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo htmlspecialchars($apellido); ?>" autocomplete="off" placeholder="Ingrese Apellido(s)" required>
                        </div>
                    </div>
                </div>
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="text" class="form-control" name="dni" id="dni" value="<?php echo htmlspecialchars($dni); ?>" autocomplete="off" placeholder="Ingrese su DNI" required>
                            <span id="dniOK"></span>
                        </div>
                    </div>
                    <div class="col">

                        <div class="form-group">
                            <label for="celular">Celular:</label>
                            <input type="tel" class="form-control" name="celular" id="celular" value="<?php echo htmlspecialchars($celular); ?>" autocomplete="off" placeholder="Ingrese Telefono" required>
                            <span id="celularOK"></span>
                        </div>

                    </div>
                </div>
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="ciudad">Ciudad:</label>
                            <select name="ciudad" id="ciudad" class="form-control" autocomplete="off" required>
                                <option value="" disabled <?php echo ($ciudad == "") ? "selected" : ""; ?>>Seleccione un departamento de San Juan</option>
                                <option value="Albardón" <?php echo ($ciudad == "Albardon") ? "selected" : ""; ?>>Albardón</option>
                                <option value="Angaco" <?php echo ($ciudad == "Angaco") ? "selected" : ""; ?>>Angaco</option>
                                <option value="Calingasta" <?php echo ($ciudad == "Calingasta") ? "selected" : ""; ?>>Calingasta</option>
                                <option value="Caucete" <?php echo ($ciudad == "Caucete") ? "selected" : ""; ?>>Caucete</option>
                                <option value="Chimbas" <?php echo ($ciudad == "Chimbas") ? "selected" : ""; ?>>Chimbas</option>
                                <option value="Capital" <?php echo ($ciudad == "Capital") ? "selected" : ""; ?>>Capital</option>
                                <option value="Iglesia" <?php echo ($ciudad == "Iglesia") ? "selected" : ""; ?>>Iglesia</option>
                                <option value="Jáchal" <?php echo ($ciudad == "Jáchal") ? "selected" : ""; ?>>Jáchal</option>
                                <option value="9 de Julio" <?php echo ($ciudad == "9 de Julio") ? "selected" : ""; ?>>9 de Julio</option>
                                <option value="Pocito" <?php echo ($ciudad == "Pocito") ? "selected" : ""; ?>>Pocito</option>
                                <option value="Rawson" <?php echo ($ciudad == "Rawson") ? "selected" : ""; ?>>Rawson</option>
                                <option value="Rivadavia" <?php echo ($ciudad == "Rivadavia") ? "selected" : ""; ?>>Rivadavia</option>
                                <option value="San Martín" <?php echo ($ciudad == "San Martín") ? "selected" : ""; ?>>San Martín</option>
                                <option value="Santa Lucía" <?php echo ($ciudad == "Santa Lucía") ? "selected" : ""; ?>>Santa Lucía</option>
                                <option value="Sarmiento" <?php echo ($ciudad == "Sarmiento") ? "selected" : ""; ?>>Sarmiento</option>
                                <option value="Ullum" <?php echo ($ciudad == "Ullum") ? "selected" : ""; ?>>Ullum</option>
                                <option value="Valle Fértil" <?php echo ($ciudad == "Valle Fértil") ? "selected" : ""; ?>>Valle Fértil</option>
                                <option value="Zonda" <?php echo ($ciudad == "Zonda") ? "selected" : ""; ?>>Zonda</option>
                                <option value="25 de Mayo" <?php echo ($ciudad == "25 de Mayo") ? "selected" : ""; ?>>25 de Mayo</option>
                                <!-- Agrega otros departamentos de San Juan aquí -->
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo htmlspecialchars($direccion); ?>" autocomplete="off" placeholder="Ingrese Direcion" required>
                        </div>
                    </div>
                </div>
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" autocomplete="off" required>
                            <span id="edadError" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select name="genero" id="genero" autocomplete="off" class="form-control" required>
                                <option value="" disabled <?php echo ($genero == "") ? "selected" : ""; ?>>Seleccione su Género</option>
                                <option value="Masculino" <?php echo ($genero == "Masculino") ? "selected" : ""; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($genero == "Femenino") ? "selected" : ""; ?>>Femenino</option>
                                <option value="Otros" <?php echo ($genero == "Otros") ? "selected" : ""; ?>>Otros</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="titulo">Titulo:</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese Titulo" autocomplete="off" value="<?php echo htmlspecialchars($titulo); ?> " required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="legajo">Legajo:</label>
                            <input type="text" class="form-control" name="legajo" id="legajo" placeholder="Ingrese el n° de legajo" value="<?php echo htmlspecialchars($legajo); ?>" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <!-- --------------------------------- -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input id="email" class="form-control" name="email" id="email"  placeholder="Ingrese Email" autocomplete="off" value="<?php echo htmlspecialchars($email); ?>" required>
                            <span id="emailOK"></span>

                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="passwordd">Contraseña:</label>
                            <div class="input-group">
                                <input class="form-control bg-light" type="password" placeholder="Contraseña" name="passwordd" id="passwordd" autocomplete="off" required />
                                <button type="button" class="btn btn-outline-primary" name="toggle-eye" id="toggle-eye" onclick="togglePasswordVisibility()">
                                    <i class="fas fa-eye p-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- --------------------------------- -->
                <!-- Agregamos un botón para guardar con un evento JavaScript -->
                <button type="button" class="btn btn-primary float-right" id="guardarBtn" onclick="validarFormulario()">Guardar</button>
                <!-- Agregamos un div para mostrar un mensaje de confirmación -->
                <div id="confirmacion" style="display: none;">
                    <p>¿Estás seguro de que deseas guardar los datos?</p>
                    <button type="button" class="btn btn-success" id="confirmarBtn">Sí</button>
                    <button type="button" class="btn btn-danger" id="cancelarBtn">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../../js/contraseña.js"></script>
<script src="../../js/validacion.js"></script>
<script src="../../js/validacion2.js"></script>
<?php require 'footer.php'; ?>