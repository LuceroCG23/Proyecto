<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "vida_silvestre";

// Recibir datos del formulario
$id_mesa = $_POST['id_mesa'];
$id_persona = $_POST['id_persona'];
$nota = $_POST['nota'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar la consulta para insertar en la tabla "acta"
$sql = "INSERT INTO acta (id_mesa, id_persona, nota) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("iis", $id_mesa, $id_persona, $nota);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Datos insertados correctamente en la tabla acta";
    } else {
        echo "Error al insertar datos en la tabla acta: " . $stmt->error;
    }

    // Cerrar la consulta preparada
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
