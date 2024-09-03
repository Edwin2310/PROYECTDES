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
    $id_departamento = isset($_POST['id_departamento']) ? $_POST['id_departamento'] : '';
    $nombre_departamento = isset($_POST['nom_departamento']) ? $_POST['nom_departamento'] : '';

    if (empty($id_departamento) || empty($nombre_departamento)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios";
        header("Location: ../../MantenimientoSistema/Departamentos.php?action=edit-error");
        exit();
    }

    $sql_update = "UPDATE tbl_deptos SET NOM_DEPTO = :nombre_departamento WHERE ID_DEPARTAMENTO = :id_departamento";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nombre_departamento', $nombre_departamento);
    $stmt_update->bindParam(':id_departamento', $id_departamento);

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
