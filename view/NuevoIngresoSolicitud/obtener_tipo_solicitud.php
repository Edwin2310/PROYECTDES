<?php
session_start();

include("../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

$idCategoria = filter_input(INPUT_POST, 'codigo_pago', FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT ts.ID_TIPO_SOLICITUD, ts.NOM_TIPO 
        FROM tbl_categoria c 
        JOIN tbl_tipo_solicitud ts ON c.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD 
        WHERE c.ID_CATEGORIA = :idCategoria;";

$stmt = $conn->prepare($sql);
$stmt->execute(['idCategoria' => $idCategoria]);

$respuesta = ""; // Elimina la opción "Selecciona el tipo de solicitud"

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $respuesta .= "<option value='" . htmlspecialchars($row['ID_TIPO_SOLICITUD'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['NOM_TIPO'], ENT_QUOTES, 'UTF-8') . "</option>";
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
