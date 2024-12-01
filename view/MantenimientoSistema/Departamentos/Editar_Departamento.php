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
    $nombre_departamento = isset($_POST['nom_departamento']) ? $_POST['nom_departamento'] : '';

    if (empty($IdDepartamento) || empty($nombre_departamento)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE `mantenimiento.tbldeptos` SET NomDepto = :nombre_departamento WHERE IdDepartamento = :IdDepartamento";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_departamento', $nombre_departamento);
    $stmt_update->bindParam(':IdDepartamento', $IdDepartamento);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Departamento editado exitosamente";
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=edit-success");
    } else {
        // Añadir el mensaje del error para depurar
        $errorInfo = $stmt_update->errorInfo();
        $_SESSION['error_message'] = "Error al editar departamento: " . implode(" ", $errorInfo);
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=edit-error&message=" . urlencode(implode(" ", $errorInfo)));
    }

    $conn = null;
    exit();
} else {
    $_SESSION['error_message'] = "Solicitud no válida";
    header("Location: ../../MantenimientoSistema/Departamentos.php?action=edit-error");
    exit();
}
?>
