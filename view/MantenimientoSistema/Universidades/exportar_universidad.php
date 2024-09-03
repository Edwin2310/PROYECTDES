<?php
/* require_once("../../../config/conexion.php");
require_once ("../../../vendor/autoload.php"); // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_SESSION["ID_USUARIO"])) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();
    $sql = "SELECT b.ID_BITACORA, b.FECHA_HORA, 
                   b.ID_USUARIO, u.USUARIO AS NOMBRE_USUARIO, o.ID_OBJETO, 
                   b.ACCION, b.DESCRIPCION
            FROM tbl_ms_bitacora b
            INNER JOIN tbl_ms_usuario u ON b.ID_USUARIO = u.ID_USUARIO
            INNER JOIN tbl_ms_objetos o ON b.ID_OBJETO = o.ID_OBJETO
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
            $sheet->setCellValue('C' . $rowNum, $row['ID_USUARIO']);
            $sheet->setCellValue('D' . $rowNum, $row['NOMBRE_USUARIO']);
            $sheet->setCellValue('E' . $rowNum, $row['ID_OBJETO']);
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

if (isset($_SESSION["ID_USUARIO"])) {
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    $sql = "SELECT 
         u.ID_UNIVERSIDAD, 
         u.NOM_UNIVERSIDAD, 
         d.ID_DEPARTAMENTO, 
         d.NOM_DEPTO, 
         m.ID_MUNICIPIO, 
         m.NOM_MUNICIPIO
         FROM 
         tbl_universidad_centro u
         JOIN 
         tbl_deptos d ON u.ID_DEPARTAMENTO = d.ID_DEPARTAMENTO
         JOIN 
         tbl_municipios m ON u.ID_MUNICIPIO = m.ID_MUNICIPIO
         ORDER BY
         u.ID_UNIVERSIDAD";
    $result = $conn->query($sql);

    if ($result !== false && $result->rowCount() > 0) {
        // Limpia el buffer de salida
        if (ob_get_length()) {
            ob_end_clean();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Estilos para los encabezados
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ];

        // Estilo de alineación centrada para todas las celdas
        $centerAlignment = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Encabezados de columna
        $headers = [
            'A1' => 'ID UNIVERSIDAD',
            'B1' => 'NOMBRE UNIVERSIDAD',
            'C1' => 'NOMBRE DEPARTAMENTO (SEDE PRINCIPAL)',
            'D1' => 'NOMBRE MUNICIPIO (SEDE PRINCIPAL)',
         
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->applyFromArray($headerStyle);
            $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true); // Ajuste automático de columna
        }

        // Agregar datos
        $rowNum = 2;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sheet->setCellValue('A' . $rowNum, $row['ID_UNIVERSIDAD']);
            $sheet->setCellValue('B' . $rowNum, $row['NOM_UNIVERSIDAD']);
            $sheet->setCellValue('C' . $rowNum, $row['NOM_DEPTO']);
            $sheet->setCellValue('D' . $rowNum, $row['NOM_MUNICIPIO']);
            
           

            // Aplicar alineación centrada a todas las celdas de datos
            $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->applyFromArray($centerAlignment);
            $rowNum++;
        }

        // Escribir el archivo Excel y forzar la descarga
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="universidades.xlsx"');
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