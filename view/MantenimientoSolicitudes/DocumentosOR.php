<?php

require_once("../../config/conexion.php");

// Mostrar errores (solo para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Siempre usa el SQL y la URL base asociados con el estado 13
    $sql = "SELECT AdjuntoOrDes FROM `documentos.tblopinionesrazonadas` WHERE IdSolicitud = :id";
    $basePath = "../MantenimientoSolicitudes/"; // La URL base para el estado 13

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Aquí para depuración
    if (!$row) {
        echo json_encode(['error' => 'No se encontró el archivo o el ID es incorrecto.']);
        exit;
    }

    $relativePath = $row['AdjuntoOrDes']; // Asegúrate de que esto esté correcto
    $fileName = basename($relativePath);

    // Construir la URL completa del archivo
    $fullPath = $basePath . $relativePath;

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode([
        'name' => $fileName,
        'url' => $fullPath
    ]);
}


