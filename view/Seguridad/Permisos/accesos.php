<?php
session_start();
require_once("../../../config/conexion.php");

header('Content-Type: application/json');

// Conectar a la base de datos
$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar si se han enviado los datos necesarios
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['idRol']) || !isset($data['idObjeto'])) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$idRol = $data['idRol'];
$idObjeto = $data['idObjeto'];

// Consulta para verificar permisos
$sql = "SELECT * FROM tbl_permisos WHERE ID_ROL = :idRol AND ID_OBJETO = :idObjeto";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':idRol', $idRol);
$stmt->bindParam(':idObjeto', $idObjeto);

if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        echo json_encode(['tienePermiso' => true]);
    } else {
        echo json_encode(['tienePermiso' => false]);
    }
} else {
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => 'Error en la ejecución de la consulta', 'details' => $errorInfo]);
}

?>
