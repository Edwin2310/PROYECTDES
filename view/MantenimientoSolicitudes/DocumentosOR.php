<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Siempre usa el SQL y la URL base asociados con el estado 13
    $sql = "SELECT ADJUNTO_OR_DES FROM tbl_opinion_razonada WHERE ID_SOLICITUD = :id";
    $basePath = "../MantenimientoSolicitudes/"; // La URL base para el estado 13

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $relativePath = $row['ADJUNTO_OR_DES'];
        $fileName = basename($relativePath);

        // Construir la URL completa del archivo
        $fullPath = $basePath . $relativePath;

        // Devolver la respuesta en formato JSON
        echo json_encode([
            'name' => $fileName,
            'url' => $fullPath
        ]);
    } else {
        echo json_encode([]);
    }
}
?>
