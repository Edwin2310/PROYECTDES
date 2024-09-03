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

$id_consejal = $_POST['id_consejal'];

$sql = "DELETE FROM tbl_consejales WHERE id_consejal = :id_consejal";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bindValue(':id_consejal', $id_consejal, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Consejales.php?mensaje=eliminado");
    } else {
        header("Location: ../../Seguridad/Consejales.php?mensaje=error");
    }

    $stmt->closeCursor();
}

$conn = null;
?>
