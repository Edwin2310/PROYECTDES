<?php
require_once("../../config/conexion.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamada al procedimiento almacenado
        $sql = "CALL `proceso.splObtenerArchivoPlanEstudio`(:id)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $relativePath = $row['AdjuntoPlan'];
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
    echo json_encode(['error' => 'No se proporcionó el ID de solicitud']);
}
