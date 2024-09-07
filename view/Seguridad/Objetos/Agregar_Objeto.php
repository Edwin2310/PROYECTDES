<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(":", $conn->errorInfo()));
}

// Obtener los datos del formulario
$objeto = $_POST['objeto'];
$tipo_objeto = $_POST['tipo_objeto'];
$descripcion = $_POST['descripcion'];
$creado_por = $_SESSION['IdUsuario']; // Obtener el ID del usuario de la sesión
$fecha_creacion = date('Y-m-d H:i:s'); // Fecha y hora actual

// Insertar los datos en la base de datos
$sql = "INSERT INTO tbl_ms_objetos (objeto, tipo_objeto, descripcion, fecha_creacion, creado_por) VALUES (:objeto, :tipo_objeto, :descripcion, :fecha_creacion, :creado_por)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':objeto', $objeto);
$stmt->bindParam(':tipo_objeto', $tipo_objeto);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->bindParam(':fecha_creacion', $fecha_creacion);
$stmt->bindParam(':creado_por', $creado_por);

if ($stmt->execute()) {
    echo "Nuevo objeto añadido correctamente";
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Error: " . $sql . "<br>" . $errorInfo[2];
}

// Cerrar la conexión
$conn = null;

// Redirigir de vuelta a la página principal
header("Location: ../../Seguridad/Objetos.php");

exit();
?>
