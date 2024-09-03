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
    $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';

    if (empty($id_categoria)) {
        $_SESSION['error_message'] = "El ID de la categoría es obligatorio";
        header("Location: ../../MantenimientoSistema/Categorias/CategoriaDeSolicitudes.php?action=delete-error");
        exit();
    }

    $sql_delete = "DELETE FROM tbl_categoria WHERE ID_CATEGORIA = :id_categoria";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id_categoria', $id_categoria);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Categoría eliminada exitosamente";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar categoría: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-error");
    exit();
}
?>
