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
$IdUsuario = $_POST['IdUsuario'];

// Preparar la consulta SQL para actualizar el estado del usuario
$sql = "UPDATE `seguridad.tblusuarios` SET EstadoUsuario = 2 WHERE IdUsuario = :IdUsuario";

$stmt = $conn->prepare($sql);

// Bind de parámetros
$stmt->bindParam(':IdUsuario', $IdUsuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Redirigir a la página de usuarios con un mensaje de éxito
    header("Location: ../../Seguridad/Usuarios.php?mensaje=actualizado");
} else {
    // Redirigir a la página de usuarios con un mensaje de error
    header("Location: ../../Seguridad/Usuarios.php?mensaje=error");
}

// Cerrar la conexión
$conn = null;
