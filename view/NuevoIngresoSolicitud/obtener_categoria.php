<?php
/* session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

$idCategoria = filter_input(INPUT_POST, 'codigo-pago', FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT ID_CATEGORIA, NOM_CATEGORIA FROM tbl_categoria WHERE ID_CATEGORIA = :idCategoria";
$stmt = $conn->prepare($sql);
$stmt->execute(['idCategoria' => $idCategoria]);

$respuesta = "<option value='0'>Seleccionar</option>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $respuesta .= "<option value='" . htmlspecialchars($row['ID_CATEGORIA'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['NOM_CATEGORIA'], ENT_QUOTES, 'UTF-8') . "</option>";
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE); */
session_start();

include("../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

// LLAMADO A NOMBRE
$idCategoria = filter_input(INPUT_POST, 'codigo_pago', FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT ID_CATEGORIA, NOM_CATEGORIA FROM tbl_categoria WHERE ID_CATEGORIA = :idCategoria";
$stmt = $conn->prepare($sql);
$stmt->execute(['idCategoria' => $idCategoria]);

$respuesta = ""; // Elimina la opción "Seleccionar"

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $respuesta .= "<option value='" . htmlspecialchars($row['ID_CATEGORIA'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['NOM_CATEGORIA'], ENT_QUOTES, 'UTF-8') . "</option>";
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
