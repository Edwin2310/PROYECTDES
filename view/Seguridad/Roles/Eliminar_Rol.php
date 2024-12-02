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
$IdRol = $_POST['IdRol'];

// Preparar la consulta SQL para eliminar el rol
$sql = "DELETE FROM `seguridad.tblmsroles` WHERE IdRol = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($stmt->execute([$IdRol])) {
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
