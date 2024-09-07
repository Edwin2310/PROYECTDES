<?php
session_start();
require_once("../../config/conexion.php");
require '../../vendor/autoload.php'; // Autoload Composer
require '../../vendor/tcpdf/tcpdf.php'; // Carga el archivo TCPDF

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (!isset($_SESSION["IdUsuario"])) {
    header("Location:" . Conectar::ruta() . "index.php");
    exit();
}

// Obtener y verificar los parámetros
$idUniversidad = isset($_GET['id_universidad']) ? $_GET['id_universidad'] : '';
$idCarrera = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : '';
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

if (!$idUniversidad || !$idCarrera) {
    echo "Filtros incompletos. Asegúrate de que ambos filtros 'idUniversidad' y 'idCarrera' estén proporcionados.";
    exit();
}

try {
    $db = new Conectar();
    $pdo = $db->Conexion();

    // Consulta SQL actualizada
    $query = "SELECT
                s.ID_SOLICITUD,
                c.NOM_CARRERA,
                s.DESCRIPCION,
                ts.NOM_TIPO,
                ct.NOM_CATEGORIA,
                u.NOM_UNIVERSIDAD,
                g.NOM_GRADO,
                m.NOM_MODALIDAD,
                dt.NOM_DEPTO,
                mc.NOM_MUNICIPIO,
                s.FECHA_INGRESO,
                a.ACUERDO_ADMISION,
                ap.FECHA_CREACION AS FECHA_APROBACION,
                ap.ACUERDO_APROBACION,
                ur.USUARIO
            FROM
                tbl_solicitudes s
            JOIN tbl_modalidad m ON s.ID_MODALIDAD = m.ID_MODALIDAD
            JOIN tbl_grado_academico g ON s.ID_GRADO = g.ID_GRADO
            LEFT JOIN tbl_acuerdo_ces_admin a ON s.ID_SOLICITUD = a.ID_SOLICITUD
            LEFT JOIN tbl_acuerdo_ces_aprob ap ON s.ID_SOLICITUD = ap.ID_SOLICITUD
            LEFT JOIN tbl_carrera c ON s.ID_CARRERA = c.ID_CARRERA
            LEFT JOIN tbl_universidad_centro u ON s.ID_UNIVERSIDAD = u.ID_UNIVERSIDAD
            LEFT JOIN tbl_tipo_solicitud ts ON s.ID_TIPO_SOLICITUD = ts.ID_TIPO_SOLICITUD
            LEFT JOIN tbl_categoria ct ON s.ID_CATEGORIA = ct.ID_CATEGORIA
            LEFT JOIN tbl_deptos dt ON s.ID_DEPARTAMENTO = dt.ID_DEPARTAMENTO
            LEFT JOIN tbl_municipios mc ON s.ID_MUNICIPIO = mc.ID_MUNICIPIO
            LEFT JOIN tbl_ms_usuario ur ON s.IdUsuario = ur.IdUsuario
            WHERE s.ID_UNIVERSIDAD = :idUniversidad 
            AND c.ID_CARRERA = :idCarrera";

    if ($fechaInicio && $fechaFin) {
        $query .= " AND s.FECHA_INGRESO BETWEEN :fechaInicio AND :fechaFin";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idUniversidad', $idUniversidad, PDO::PARAM_INT);
    $stmt->bindParam(':idCarrera', $idCarrera, PDO::PARAM_INT);

    if ($fechaInicio && $fechaFin) {
        $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($result)) {
        echo "<script>
            alert('No se encontraron resultados.');
            window.location.href = 'index.php';
        </script>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}


if ($type === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', '# Solicitud');
    $sheet->setCellValue('B1', 'Universidad');
    $sheet->setCellValue('C1', 'Carrera');
    $sheet->setCellValue('D1', 'Modalidad');
    $sheet->setCellValue('E1', 'Grado');
    $sheet->setCellValue('F1', 'Fecha Ingreso');
    $sheet->setCellValue('G1', 'Acuerdo Admisión');
    $sheet->setCellValue('H1', 'Fecha Aprobación');
    $sheet->setCellValue('I1', 'Acuerdo Aprobación');

    $row = 2;
    foreach ($result as $data) {
        $sheet->setCellValue('A' . $row, $data['ID_SOLICITUD']);
        $sheet->setCellValue('B' . $row, $data['NOM_UNIVERSIDAD']);
        $sheet->setCellValue('C' . $row, $data['NOM_CARRERA']);
        $sheet->setCellValue('D' . $row, $data['NOM_MODALIDAD']);
        $sheet->setCellValue('E' . $row, $data['NOM_GRADO']);
        $sheet->setCellValue('F' . $row, $data['FECHA_INGRESO']);
        $sheet->setCellValue('G' . $row, $data['ACUERDO_ADMISION']);
        $sheet->setCellValue('H' . $row, $data['FECHA_APROBACION']);
        $sheet->setCellValue('I' . $row, $data['ACUERDO_APROBACION']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'ReporteSolicitud.xlsx';

    ob_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
} elseif ($type === 'pdf') {
    ob_clean();
    // Crear una instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Configuración del documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nombre de Autor');
    $pdf->SetTitle('Detalles Solicitud');
    $pdf->SetSubject('Detalles de la Solicitud');
    $pdf->SetKeywords('TCPDF, PDF, solicitud, detalles');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Establece los márgenes
    $pdf->SetMargins(10, 10, 10, true);

    // Añadir una página
    $pdf->AddPage();

    // Ruta de la imagen
    $imagePath = '../../public/assets/img/Logo/Correo.jpeg';

    // Obtener el ancho de la página
    $pageWidth = $pdf->getPageWidth();

    // Agregar imagen en la parte superior
    $pdf->Image($imagePath, 0, 0, $pageWidth, 40, 'JPEG', '', 'T', true, 150, '', false, false, 0, false, false, false);

    // Espacio debajo de la imagen
    $pdf->Ln(50);

    // Estilo para el título y el contenido
    $titulo_style = '<style>
        h1 {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: #003366;
            margin-bottom: 20px;
        }
    </style>';

    $contenido_style = '<style>
        .section-title {
            font-weight: bold;
            background-color: #003366;
            color: #FFFFFF;
            padding: 5px;
            margin-bottom: 5px;
            font-size: 12px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }
        .table-title {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>';

    // Generar contenido del PDF
    $html = $titulo_style . '
<h1>DETALLES SOLICITUD</h1>';

    // Añadir el título al PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Inicializar contador para las tablas
    $tabla_count = 0;

    // Iterar sobre los resultados y agregar tablas
    foreach ($result as $data) {
        // Verificar si se han agregado 3 tablas ya
        if ($tabla_count >= 3) {
            break;
        }

        // Generar contenido para la tabla
        $html = $contenido_style . '
    <table>
        <tr>
            <td class="table-title" width="20%">Nombre Carrera</td>
            <td width="30%">' . $data['NOM_CARRERA'] . '</td>
            <td class="table-title" width="20%">N° Solicitud</td>
            <td width="30%">' . $data['ID_SOLICITUD'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Descripción</td>
            <td colspan="3">' . $data['DESCRIPCION'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Tipo de Solicitud</td>
            <td>' . $data['NOM_TIPO'] . '</td>
            <td class="table-title">Categoría</td>
            <td>' . $data['NOM_CATEGORIA'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Centro de Educación Superior</td>
            <td>' . $data['NOM_UNIVERSIDAD'] . '</td>
            <td class="table-title">Grado Académico</td>
            <td>' . $data['NOM_GRADO'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Modalidad</td>
            <td>' . $data['NOM_MODALIDAD'] . '</td>
            <td class="table-title">Departamento</td>
            <td>' . $data['NOM_DEPTO'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Municipio</td>
            <td>' . $data['NOM_MUNICIPIO'] . '</td>
            <td class="table-title">Fecha de Ingreso</td>
            <td>' . $data['FECHA_INGRESO'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Acuerdo de Admisión</td>
            <td>' . $data['ACUERDO_ADMISION'] . '</td>
            <td class="table-title">Fecha de Aprobación</td>
            <td>' . $data['FECHA_APROBACION'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Acuerdo de Aprobación</td>
            <td>' . $data['ACUERDO_APROBACION'] . '</td>
            <td class="table-title">Usuario Responsable</td>
            <td>' . $data['USUARIO'] . '</td>
        </tr>
    </table>';

        // Añadir el contenido principal al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Incrementar el contador de tablas
        $tabla_count++;
    }


    // Agregar la tabla de historial al final del PDF
    $historial_html = $contenido_style . '
    <h2 class="section-title">Historial de la Solicitud</h2>
    <table>
        <tr>
            <td class="table-title">Fecha</td>
            <td class="table-title">Estado</td>
            <td class="table-title">Usuario</td>
        </tr>
        <tr>
            <td>16/08/2024</td>
            <td>PETICIÓN</td>
            <td>UNITEC</td>
        </tr>
        <tr>
            <td>16/08/2024</td>
            <td>REVISIÓN DE DOCUMENTO</td>
            <td>EMPLEADO ENCARGADO</td>
        </tr>
        <tr>
            <td>16/08/2024</td>
            <td>SUBSANACIÓN DE DOCUMENTO</td>
            <td>EMPLEADO ENCARGADO</td>
        </tr>
        <tr>
            <td>16/08/2024</td>
            <td>DOCUMENTOS SUBSANADO</td>
            <td>EMPLEADO ENCARGADO</td>
        </tr>
        <tr>
            <td>16/08/2024</td>
            <td>ANÁLISIS</td>
            <td>EMPLEADO ENCARGADO</td>
        </tr>
    </table>';

    // Añadir la tabla de historial al PDF
    $pdf->writeHTML($historial_html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('DetalleSolicitud.pdf', 'D');
}
