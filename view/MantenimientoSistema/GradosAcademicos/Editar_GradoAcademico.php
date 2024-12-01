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
    $IdGrado = isset($_POST['IdGrado']) ? $_POST['IdGrado'] : '';
    $nombre_grado = isset($_POST['NomGrado']) ? $_POST['NomGrado'] : '';

    if (empty($IdGrado) || empty($nombre_grado)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/GradosAcademicos.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE `mantenimiento.tblgradosacademicos` SET NomGrado = :nombre_grado WHERE IdGrado = :IdGrado";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_grado', $nombre_grado);
    $stmt_update->bindParam(':IdGrado', $IdGrado);

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
