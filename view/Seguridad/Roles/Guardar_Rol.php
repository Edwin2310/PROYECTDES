<?php
// Iniciar la sesión
session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo()[2]);
}

$rol = $_POST["rol"];
$descripcion = $_POST["descripcion"];
$creado_por = $_SESSION['ID_USUARIO']; // Obtener el ID_USUARIO de la sesión

// Consulta SQL para insertar el nuevo rol
$sql = "INSERT INTO tbl_ms_roles (ROL, DESCRIPCION, CREADO_POR) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($stmt->execute([$rol, $descripcion, $creado_por])) {
        echo "Usuario agregado exitosamente";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Error en la preparación de la consulta: " . $conn->errorInfo()[2];
}

// Cerrar la conexión
$stmt = null;
$conn = null;

// Redirigir de vuelta a la página principal
header("Location: ../../Seguridad/Roles.php");
exit();
?>
