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

$IdParametro = $_POST['IdParametro'];

$sql = "DELETE FROM `seguridad.tblparametros` WHERE IdParametro = :IdParametro";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bindValue(':IdParametro', $IdParametro, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Parametros.php?mensaje=eliminado");
    } else {
        header("Location: ../../Seguridad/Parametros.php?mensaje=error");
    }

    $stmt->closeCursor();
}

$conn = null;
