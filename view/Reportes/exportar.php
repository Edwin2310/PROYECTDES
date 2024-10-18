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
$idUniversidad = isset($_GET['IdUniversidad']) ? $_GET['IdUniversidad'] : '';
$idCarrera = isset($_GET['IdCarrera']) ? $_GET['IdCarrera'] : '';
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
            s.IdSolicitud,
            c.NomCarrera,
            s.Descripcion,
            ts.NomTipoSolicitud,
            ct.NomCategoria,
            u.NomUniversidad,
            g.NomGrado,
            m.NomModalidad,
            dt.NomDepto,
            mc.NomMunicipio,
            s.FechaIngreso,
            a.AcuerdoAdmision,
            ap.FechaCreacion AS FechaAprobacion,
            ap.AcuerdoAprobacion,
            ur.Usuario
        FROM
            `proceso.tblsolicitudes` s
        JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
        JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
        LEFT JOIN `proceso.tblacuerdoscesadmin` a ON s.IdSolicitud = a.IdSolicitud
        LEFT JOIN `proceso.tblacuerdoscesaprob` ap ON s.IdSolicitud = ap.IdSolicitud
        LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
        LEFT JOIN `mantenimiento.tbluniversidadescentros` u ON s.IdUniversidad = u.IdUniversidad
        LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON s.IdTiposolicitud = ts.IdTiposolicitud
        LEFT JOIN `mantenimiento.tblcategorias` ct ON s.IdCategoria = ct.IdCategoria
        LEFT JOIN `mantenimiento.tbldeptos` dt ON s.IdDepartamento = dt.IdDepartamento
        LEFT JOIN `mantenimiento.tblmunicipios` mc ON s.IdMunicipio = mc.IdMunicipio
        LEFT JOIN `seguridad.tblusuarios_personal` ur ON s.IdUsuario = ur.IdUsuario
        WHERE
            s.IdUniversidad = : idUniversidad
        AND c.IdCarrera = : idCarrera";

    if ($fechaInicio && $fechaFin) {
        $query .= " AND s.FechaIngreso BETWEEN :fechaInicio AND :fechaFin";
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
        $sheet->setCellValue('A' . $row, $data['IdSolicitud']);
        $sheet->setCellValue('B' . $row, $data['NomUniversidad']);
        $sheet->setCellValue('C' . $row, $data['NomCarrera']);
        $sheet->setCellValue('D' . $row, $data['NomModalidad']);
        $sheet->setCellValue('E' . $row, $data['NomGrado']);
        $sheet->setCellValue('F' . $row, $data['FechaIngreso']);
        $sheet->setCellValue('G' . $row, $data['AcuerdoAdmision']);
        $sheet->setCellValue('H' . $row, $data['FechaAprobacion']);
        $sheet->setCellValue('I' . $row, $data['AcuerdoAprobacion']);
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
            <td width="30%">' . $data['NomCarrera'] . '</td>
            <td class="table-title" width="20%">N° Solicitud</td>
            <td width="30%">' . $data['IdSolicitud'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Descripción</td>
            <td colspan="3">' . $data['Descripcion'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Tipo de Solicitud</td>
            <td>' . $data['NomTipoSolicitud'] . '</td>
            <td class="table-title">Categoría</td>
            <td>' . $data['NomCategoria'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Centro de Educación Superior</td>
            <td>' . $data['NomUniversidad'] . '</td>
            <td class="table-title">Grado Académico</td>
            <td>' . $data['NomGrado'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Modalidad</td>
            <td>' . $data['NomModalidad'] . '</td>
            <td class="table-title">Departamento</td>
            <td>' . $data['NomDepto'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Municipio</td>
            <td>' . $data['NomMunicipio'] . '</td>
            <td class="table-title">Fecha de Ingreso</td>
            <td>' . $data['FechaIngreso'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Acuerdo de Admisión</td>
            <td>' . $data['AcuerdoAdmision'] . '</td>
            <td class="table-title">Fecha de Aprobación</td>
            <td>' . $data['FechaAprobacion'] . '</td>
        </tr>
        <tr>
            <td class="table-title">Acuerdo de Aprobación</td>
            <td>' . $data['AcuerdoAprobacion'] . '</td>
            <td class="table-title">Usuario Responsable</td>
            <td>' . $data['Usuario'] . '</td>
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
