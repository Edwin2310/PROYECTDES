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
    $id_modalidad = isset($_POST['id_modalidad']) ? $_POST['id_modalidad'] : '';
    $nombre_modalidad = isset($_POST['nom_modalidad']) ? $_POST['nom_modalidad'] : '';

    if (empty($id_modalidad) || empty($nombre_modalidad)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE tbl_modalidad SET NOM_MODALIDAD = :nombre_modalidad WHERE ID_MODALIDAD = :id_modalidad";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_modalidad', $nombre_modalidad);
    $stmt_update->bindParam(':id_modalidad', $id_modalidad);

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
