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
    $id_carrera = isset($_POST['id_carrera']) ? $_POST['id_carrera'] : '';

    if (empty($id_carrera)) {
        $_SESSION['error_message'] = "El ID de la carrera es obligatorio";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
        exit();
    }

    $sql_delete = "DELETE FROM tbl_carrera WHERE ID_CARRERA = :id_carrera";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id_carrera', $id_carrera);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Carrera eliminada exitosamente";
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar carrera: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Carreras.php?action=delete-error");
    exit();
}
?>
