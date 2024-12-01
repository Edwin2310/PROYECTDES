<?php
session_start();
include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(" ", $conn->errorInfo()));
}

// Verificar si se ha enviado la solicitud para eliminar la modalidad
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdModalidad = isset($_POST['IdModalidad']) ? $_POST['IdModalidad'] : '';

    if (empty($IdModalidad)) {
        $_SESSION['error_message'] = "El ID de la modalidad es obligatorio";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=delete-error");
        exit();
    }

    // Actualizar el IdVisibilidad para desactivar la modalidad (por ejemplo, cambiar a 0)
    $sql_delete = "UPDATE `mantenimiento.tblmodalidades` SET IdVisibilidad = 2 WHERE IdModalidad = :IdModalidad"; // Asumimos que 0 es el valor para desactivar
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':IdModalidad', $IdModalidad);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Modalidad eliminada exitosamente";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar modalidad: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Modalidades.php?action=delete-error");
    exit();
}
