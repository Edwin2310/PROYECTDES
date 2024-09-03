<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Consulta para obtener la última observación de la solicitud
    $sql = "SELECT ADJUNTO_PLAN 
            FROM tbl_plan_estudio
            WHERE ID_SOLICITUD = :id
            ORDER BY ID_PLAN_ESTUDIO DESC
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $relativePath = $row['ADJUNTO_PLAN'];
        $fileName = basename($relativePath);

        // Construir la URL completa del archivo
        $basePath = "../MantenimientoSolicitudes/";
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



