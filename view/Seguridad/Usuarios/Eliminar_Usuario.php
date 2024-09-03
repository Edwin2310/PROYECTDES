<?php
// Iniciar la sesión
session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
}

// Obtener el ID del usuario desde el formulario
$id_usuario = $_POST['id_usuario'];

// Preparar la consulta SQL para actualizar el estado del usuario
$sql = "UPDATE tbl_ms_usuario SET estado_usuario = 2 WHERE id_usuario = :id_usuario";

$stmt = $conn->prepare($sql);

// Bind de parámetros
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Redirigir a la página de usuarios con un mensaje de éxito
    header("Location: ../../Seguridad/Usuarios.php?mensaje=actualizado");
} else {
    // Redirigir a la página de usuarios con un mensaje de error
    header("Location: ../../Seguridad/Usuarios.php?mensaje=error");
}

// Cerrar la conexión
$conn = null;
?>
