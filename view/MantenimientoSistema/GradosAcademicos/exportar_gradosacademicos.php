<?php
/* require_once("../../../config/conexion.php");
require_once ("../../../vendor/autoload.php"); // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_SESSION["IdUsuario"])) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();
    $sql = "SELECT b.ID_BITACORA, b.FECHA_HORA, 
                   b.IdUsuario, u.USUARIO AS NOMBRE_USUARIO, o.IdObjeto, 
                   b.ACCION, b.DESCRIPCION
            FROM tbl_ms_bitacora b
            INNER JOIN tbl_ms_usuario u ON b.IdUsuario = u.IdUsuario
            INNER JOIN tbl_ms_objetos o ON b.IdObjeto = o.IdObjeto
            ORDER BY b.ID_BITACORA ASC;";
    $result = $conn->query($sql);

    if ($result !== false && $result->rowCount() > 0) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agregar encabezados de columna
        $sheet->setCellValue('A1', 'Id Bitacora');
        $sheet->setCellValue('B1', 'Fecha y Hora');
        $sheet->setCellValue('C1', 'Id Usuario');
        $sheet->setCellValue('D1', 'Usuario');
        $sheet->setCellValue('E1', 'Id Objeto');
        $sheet->setCellValue('F1', 'Acción');
        $sheet->setCellValue('G1', 'Descripción');

        // Agregar datos
        $rowNum = 2;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sheet->setCellValue('A' . $rowNum, $row['ID_BITACORA']);
            $sheet->setCellValue('B' . $rowNum, $row['FECHA_HORA']);
            $sheet->setCellValue('C' . $rowNum, $row['IdUsuario']);
            $sheet->setCellValue('D' . $rowNum, $row['NOMBRE_USUARIO']);
            $sheet->setCellValue('E' . $rowNum, $row['IdObjeto']);
            $sheet->setCellValue('F' . $rowNum, $row['ACCION']);
            $sheet->setCellValue('G' . $rowNum, $row['DESCRIPCION']);
            $rowNum++;
        }

        // Escribir el archivo Excel y forzar la descarga
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="bitacora.xlsx"');
        $writer->save('php://output');
        exit();
    } else {
        echo "No hay datos disponibles para exportar.";
    }
} else {
    header("Location:" . Conectar::ruta() . "index.php");
} */


session_start();
require_once("../../../config/conexion.php");
require_once("../../../vendor/autoload.php"); // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

if (isset($_SESSION["IdUsuario"])) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Adjusted the query to fetch ID_GRADO and NOM_GRADO from tbl_grado_academico
    $sql = "SELECT ID_GRADO, NOM_GRADO FROM tbl_grado_academico ORDER BY ID_GRADO";
    $result = $conn->query($sql);

    if ($result !== false && $result->rowCount() > 0) {
        // Clear the output buffer
        if (ob_get_length()) {
            ob_end_clean();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header styles
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ];

        // Center alignment style for all cells
        $centerAlignment = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Column headers
        $headers = [
            'A1' => 'ID GRADO',
            'B1' => 'NOMBRE GRADO',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->applyFromArray($headerStyle);
            $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true); // Auto adjust column width
        }

        // Add data
        $rowNum = 2;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sheet->setCellValue('A' . $rowNum, $row['ID_GRADO']);
            $sheet->setCellValue('B' . $rowNum, $row['NOM_GRADO']);
            
            // Apply center alignment to all data cells
            $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->applyFromArray($centerAlignment);
            $rowNum++;
        }

        // Write Excel file and force download
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="grado_academico.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    } else {
        echo "No hay datos disponibles para exportar.";
    }
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}