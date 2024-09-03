<?php
require_once("../../config/conexion.php");

$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

if ($id) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Primero, obtén el estado de la solicitud
    $estadoSql = "SELECT ID_ESTADO FROM tbl_solicitudes WHERE ID_SOLICITUD = :id";
    $estadoStmt = $conn->prepare($estadoSql);
    $estadoStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $estadoStmt->execute();
    $estadoRow = $estadoStmt->fetch(PDO::FETCH_ASSOC);

    if ($estadoRow && $estadoRow['ID_ESTADO'] == 11) {
        // Si el estado es 11, obtén el último PLAN_ESTUDIOS de tbl_opinion_razonada
        $sql = "SELECT PLAN_ESTUDIOS FROM tbl_opinion_razonada WHERE ID_SOLICITUD = :id";
        $basePath = "../ConsultarSolicitudes/"; // La URL base cuando el estado es 11
    } else {
        // Si el estado no es 11, obtén el PLAN_ESTUDIOS de tbl_archivos_adjuntos
        $sql = "SELECT PLAN_ESTUDIOS FROM tbl_archivos_adjuntos WHERE ID_SOLICITUD = :id";
        $basePath = "../NuevoIngresoSolicitud/"; // La URL base en otros casos
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $relativePath = $row['PLAN_ESTUDIOS'];
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

