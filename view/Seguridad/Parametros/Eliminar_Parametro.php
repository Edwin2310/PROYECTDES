<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conexion->Conexion()->errorInfo());
}

$id_parametro = $_POST['id_parametro'];

$sql = "DELETE FROM tbl_ms_parametros WHERE id_parametro = :id_parametro";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bindValue(':id_parametro', $id_parametro, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Parametros.php?mensaje=eliminado");
    } else {
        header("Location: ../../Seguridad/Parametros.php?mensaje=error");
    }

    $stmt->closeCursor();
}

$conn = null;
?>
