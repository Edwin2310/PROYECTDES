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
    $id_grado = isset($_POST['id_grado']) ? $_POST['id_grado'] : '';

    if (empty($id_grado)) {
        $_SESSION['error_message'] = "El ID deL Grado Académico es obligatorio";
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=delete-error");
        exit();
    }

    $sql_delete = "DELETE FROM tbl_grado_academico WHERE ID_GRADO = :id_grado";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id_grado', $id_grado);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Grado Académico eliminado exitosamente";
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar Grado Académico: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=delete-error");
    exit();
}
?>
