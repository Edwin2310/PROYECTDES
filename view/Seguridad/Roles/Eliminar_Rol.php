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

// Obtener el ID del rol desde el formulario
$id_rol = $_POST['id_rol'];

// Preparar la consulta SQL para eliminar el rol
$sql = "DELETE FROM tbl_ms_roles WHERE IdRol = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($stmt->execute([$id_rol])) {
        // Redirigir a la página de roles con un mensaje de éxito
        header("Location: ../../Seguridad/Roles.php?mensaje=eliminado");
        exit();
    } else {
        // Redirigir a la página de roles con un mensaje de error
        header("Location: ../../Seguridad/Roles.php?mensaje=error");
        exit();
    }
} else {
    echo "Error en la preparación de la consulta: " . $conn->errorInfo()[2];
}

?>
