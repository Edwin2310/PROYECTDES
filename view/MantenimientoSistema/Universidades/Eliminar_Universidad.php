<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(" ", $conn->errorInfo()));
}

// Verificar si se ha enviado la solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $IdUniversidad = isset($_POST['IdUniversidad']) ? $_POST['IdUniversidad'] : '';

    // Validar datos
    if (empty($IdUniversidad)) {
        $_SESSION['error_message'] = "El ID de la Universidad es obligatorio";
        header("Location: ../../MantenimientoSistema/Universidades.php?action=delete-error");
        exit();
    }

    // Preparar y ejecutar la consulta de eliminación
    $sql_delete = "UPDATE `mantenimiento.tbluniversidades` SET IdVisibilidad = 2 WHERE IdUniversidad = :IdUniversidad";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':IdUniversidad', $IdUniversidad);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Universidad eliminada exitosamente";
        header("Location: ../../MantenimientoSistema/Universidades.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar Universidad: " . urlencode(implode(" ", $stmt_delete->errorInfo()));
        header("Location: ../../MantenimientoSistema/Universidades.php?action=delete-error");
    }

    // Cerrar la conexión
    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Universidades.php?action=delete-error");
    exit();
}
?>
