
<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
        $baseDir = 'documentos/' .'Solicitudes/'. $idSolicitud . '/PlanEstudiosFinal/';

        // Crear el directorio si no existe
        if (!is_dir($baseDir)) {
            if (!mkdir($baseDir, 0755, true)) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio.']);
                exit;
            }
        }

        // Generar el nombre base del archivo
        $now = new DateTime();
        $dateStr = $now->format('Ymd');
        $baseFileName = "PLANFINAL_" . $dateStr;
        $fileName = $file['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Buscar archivos existentes en el directorio
        $files = glob($baseDir . $baseFileName . '_*.' . $fileExtension);
        $fileCount = count($files);
        $newFileName = $baseFileName . '_' . str_pad($fileCount + 1, 3, '0', STR_PAD_LEFT) . '.' . $fileExtension;

        $uploadFile = $baseDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo json_encode([
                'success' => true,
                'fileName' => $newFileName,
                'filePath' => $uploadFile
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se ha enviado ningún archivo.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
