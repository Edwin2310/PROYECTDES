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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $IdDepartamento = isset($_POST['IdDepartamento']) ? $_POST['IdDepartamento'] : '';

    if (empty($IdDepartamento)) {
        $_SESSION['error_message'] = "El ID del departamento es obligatorio";
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=delete-error");
        exit();
    }

    $sql_delete = "UPDATE `mantenimiento.tbldeptos` SET IdVisibilidad = 2 WHERE IdDepartamento = :IdDepartamento";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':IdDepartamento', $IdDepartamento);

    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = "Departamento eliminado exitosamente";
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=delete-success");
    } else {
        $_SESSION['error_message'] = "Error al eliminar departamento: " . implode(" ", $stmt_delete->errorInfo());
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=delete-error");
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Departamentos.php?action=delete-error");
    exit();
}
?>
