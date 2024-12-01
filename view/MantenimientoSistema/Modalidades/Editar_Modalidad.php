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
    $IdModalidad = isset($_POST['IdModalidad']) ? $_POST['IdModalidad'] : '';
    $nombre_modalidad = isset($_POST['NomModalidad']) ? $_POST['NomModalidad'] : '';

    if (empty($IdModalidad) || empty($nombre_modalidad)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE `mantenimiento.tblmodalidades` SET NomModalidad = :nombre_modalidad WHERE IdModalidad = :IdModalidad";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_modalidad', $nombre_modalidad);
    $stmt_update->bindParam(':IdModalidad', $IdModalidad);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Modalidad editada exitosamente";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=edit-success");
    } else {
        $_SESSION['error_message'] = "Error al editar modalidad: " . implode(" ", $stmt_update->errorInfo());
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=edit-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Modalidades.php?action=edit-error");
    exit();
}
?>
