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

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_grado = isset($_POST['id_grado']) ? $_POST['id_grado'] : '';
    $nombre_grado = isset($_POST['nom_grado']) ? $_POST['nom_grado'] : '';

    if (empty($id_grado) || empty($nombre_grado)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE tbl_grado_academico SET NOM_GRADO = :nombre_grado WHERE ID_GRADO = :id_grado";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_grado', $nombre_grado);
    $stmt_update->bindParam(':id_grado', $id_grado);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Grado Académico editado exitosamente";
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=edit-success");
    } else {
        $_SESSION['error_message'] = "Error al editar Grado Académico: " . implode(" ", $stmt_update->errorInfo());
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=edit-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=edit-error");
    exit();
}
?>
