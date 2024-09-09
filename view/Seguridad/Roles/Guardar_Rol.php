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

$Rol = $_POST["Rol"];
$NombreRol = $_POST["NombreRol"];
$CreadoPor = $_SESSION['IdUsuario']; // Obtener el IdUsuario de la sesión

// Consulta SQL para insertar el nuevo Rol
$sql = "INSERT INTO `seguridad.tblmsRoles` (Rol, NombreRol, IdUsuario) VALUES (?, ?, ?)";


$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($stmt->execute([$Rol, $NombreRol, $CreadoPor])) {
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
