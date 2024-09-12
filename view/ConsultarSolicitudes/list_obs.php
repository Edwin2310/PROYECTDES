<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Consulta para obtener la última observación de la solicitud
        $sql = "SELECT DocObservacion 
                FROM `documentos.tblobservaciones` 
                WHERE IdSolicitud = :id
                ORDER BY ID_OBSERVACION DESC
                LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['DocObservacion']) {
            $relativePath = $row['DocObservacion'];
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
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó un ID válido.']);
}
