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
    $id_modalidad = isset($_POST['id_modalidad']) ? $_POST['id_modalidad'] : '';

    if (empty($id_modalidad)) {
        $_SESSION['error_message'] = "El ID de la modalidad es obligatorio";
        header("Location: ../../MantenimientoSistema/Modalidades.php?action=delete-error");
        exit();
    }

    $sql_delete = "DELETE FROM tbl_modalidad WHERE ID_MODALIDAD = :id_modalidad";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id_modalidad', $id_modalidad);

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
?>
