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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $IdRol = $_POST["IdRol"];
    $Rol = $_POST["Rol"];
    $NombreRol = $_POST["NombreRol"];

    // Obtener el ID del usuario que está realizando la modificación
    if (isset($_SESSION["IdUsuario"])) {
        $id_usuario_modificador = $_SESSION["IdUsuario"];
    } else {
        // Manejar el caso en que no se ha iniciado sesión o no se tiene el ID de usuario
        echo "No se ha iniciado sesión o no se encontró el ID del usuario.";
        exit();
    }

    // Preparar la consulta SQL
    $sql = "UPDATE `seguridad.tblmsroles` SET Rol = ?, NombreRol = ?, FechaModificacion = NOW(), ModificadoPor = ? WHERE IdRol = ?";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular los parámetros
        if ($stmt->execute([$Rol, $NombreRol, $id_usuario_modificador, $IdRol])) {
            // Éxito al actualizar el rol
            header("Location: ../../Seguridad/Roles.php");
            exit();
        } else {
            // Error al ejecutar la declaración SQL
            echo "Error al actualizar el rol: " . $stmt->errorInfo()[2];
        }
    } else {
        // Error en la preparación de la declaración SQL
        echo "Error en la preparación de la consulta: " . $conn->errorInfo()[2];
    }
} else {
    // Redireccionar si se intenta acceder directamente a este script sin un POST
    exit();
}
