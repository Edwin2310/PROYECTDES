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

$IdObjeto = $_POST['IdObjeto'];

$sql = "DELETE FROM `seguridad.tblobjetos` WHERE IdObjeto = :IdObjeto";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bindValue(':IdObjeto', $IdObjeto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../../Seguridad/Objetos.php?mensaje=eliminado");
    } else {
        header("Location: ../../Seguridad/Objetos.php?mensaje=error");
    }

    $stmt->closeCursor();
}

$conn = null;
