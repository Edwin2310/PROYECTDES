<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $files = $_FILES['file'];
        $idSolicitud = isset($_POST['idSolicitud']) ? htmlspecialchars($_POST['idSolicitud']) : '';
        $uploadedFiles = [];

        // Definir directorios base
        $directories = [
            'SUBPLAN' => 'documentos/Solicitudes/' . $idSolicitud . '/PlanDeEstudios/',
            'SUBSOLI' => 'documentos/Solicitudes/' . $idSolicitud . '/Solicitud/',
            'SUBPDOC' => 'documentos/Solicitudes/' . $idSolicitud . '/PlantaDocente/',
            'SUBDIAG' => 'documentos/Solicitudes/' . $idSolicitud . '/Diagnostico/'
        ];

        // Asegurarse de que los directorios existen
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        foreach ($files['name'] as $index => $fileName) {
            $fileTmpName = $files['tmp_name'][$index];
            $fileError = $files['error'][$index];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $prefix = '';

            if ($fileError !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Error en la carga del archivo: ' . $fileName . '. Error code: ' . $fileError]);
                exit;
            }

            foreach ($directories as $key => $baseDir) {
                if (strpos($fileName, $key) === 0) {
                    $prefix = $key;
                    break;
                }
            }

            if ($prefix === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Prefijo de archivo desconocido: ' . $fileName]);
                exit;
            }

            $baseDir = $directories[$prefix];
            $newFileName = $prefix . '_' . date('Ymd') . '_' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT) . '.' . $fileExtension;
            $filePath = $baseDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $filePath)) {
                $uploadedFiles[] = [
                    'fileName' => $newFileName,
                    'filePath' => $filePath,
                    'fileType' => $prefix
                ];
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al subir el archivo: ' . $fileName]);
                exit;
            }
        }

        echo json_encode(['success' => true, 'uploadedFiles' => $uploadedFiles]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se han enviado archivos.']);
    }
}
?>
