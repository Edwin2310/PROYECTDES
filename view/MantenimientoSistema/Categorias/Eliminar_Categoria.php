<?php
session_start();
include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . implode(" ", $conn->errorInfo()));
}

// Verificar si se ha enviado la solicitud para eliminar la Categoria
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdCategoria = isset($_POST['IdCategoria']) ? $_POST['IdCategoria'] : '';

    if (empty($IdCategoria)) {
        $_SESSION['error_message'] = "El ID de la categoria es obligatorio";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-error");
        exit();
    }

    // Actualizar el IdVisibilidad para desactivar la Categoria (por ejemplo, cambiar a 0)
    $sql_delete = "UPDATE `mantenimiento.tblcategorias` SET IdVisibilidad = 2 WHERE IdCategoria= :IdCategoria"; // Asumimos que 0 es el valor para desactivar
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':IdCategoria', $IdCategoria);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Categoria eliminada exitosamente";
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar Categoria: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/CategoriaDeSolicitudes.php?action=delete-error");
    exit();
}

