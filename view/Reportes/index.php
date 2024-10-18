<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../NuevoIngresoSolicitud/Funciones_Solicitud.php');
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
// Obtener los valores necesarios para la verificación
$id_rol = $_SESSION['IdRol'] ?? null;
$id_objeto = 9; // ID del objeto o módulo correspondiente a esta página
// Llama a la función para verificar los permisos
verificarPermiso($id_rol, $id_objeto);

if (!$id_rol) {
    header("Location: ../Seguridad/Permisos/denegado.php");
    exit();
}

// Conectar a la base de datos
$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar permiso en la base de datos
$sql = "SELECT * FROM `seguridad.tblpermisos` WHERE IdRol = :idRol AND IdObjeto = :idObjeto";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':idRol', $id_rol);
$stmt->bindParam(':idObjeto', $id_objeto);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    // Usuario tiene permiso, continuar con el contenido de la página
} else {
    header("Location: ../Seguridad/Permisos/denegado.php");
    exit();
}


if (isset($_SESSION["IdUsuario"])) {
    $idUniversidad = isset($_GET['IdUniversidad']) ? $_GET['IdUniversidad'] : '';
    $idCarrera = isset($_GET['IdCarrera']) ? $_GET['IdCarrera'] : '';
    $fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
    $fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

    try {
        // Crear instancia de Conectar y obtener la conexión
        $db = new Conectar();
        $pdo = $db->Conexion();

        // Inicializar variable para almacenar resultados
        $result = [];

        // Solo ejecutar la consulta si ambos filtros están seleccionados
        if ($idUniversidad && $idCarrera) {
            $query = "SELECT
                s.IdSolicitud,
                u.NomUniversidad,
                c.NomCarrera,
                m.NomModalidad,
                g.NomGrado,
                s.FechaIngreso,
                a.AcuerdoAdmision,
                ap.FechaCreacion AS FechaAprobacion,
                ap.AcuerdoAprobacion
            FROM
                `proceso.tblsolicitudes` s
            JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
            JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
            LEFT JOIN `proceso.tblacuerdoscesadmin` a ON s.IdSolicitud = a.IdSolicitud
            LEFT JOIN `proceso.tblacuerdoscesaprob` ap ON s.IdSolicitud = ap.IdSolicitud
            LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
            LEFT JOIN `mantenimiento.tbluniversidadescentros` u ON s.IdUniversidad = u.IdUniversidad
            WHERE
                s.IdUniversidad = : IdUniversidad
            AND c.IdCarrera = : IdCarrera";

            // Agregar filtros de fecha si están presentes
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
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
        echo $error; // Para depuración
    }
?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Reporteria</title>
        <link rel="stylesheet" href="estilos.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>

    <body>
        <div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">
            <aside id="side-overlay">
                <div id="side-overlay-scroll">
                    <div class="content-header content-header-fullrow">
                        <div class="content-header-section align-parent">
                            <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout"
                                data-action="side_overlay_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            <div class="content-header-item">
                                <a class="img-link mr-5" href="be_pages_generic_profile.html">
                                    <img class="img-avatar img-avatar32" src="../../public/assets/img/avatars/avatar15.jpg"
                                        alt="">
                                </a>
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html">
                                    <span><?php echo $_SESSION["NombreUsuario"] ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <nav id="sidebar">
                <div id="sidebar-scroll">
                    <div class="sidebar-content">
                        <?php require_once("../MainSidebar/MainSidebar.php"); ?>
                        <?php require_once("../MainMenu/MainMenu.php"); ?>
                    </div>
                </div>
            </nav>

            <?php require_once("../MainHeader/MainHeader.php"); ?>

            <main id="main-container">
                <div class="content">
                    <div class="container">
                        <!-- Formulario de Filtrado -->
                        <form method="GET" action="">
                            <div class="filters">
                                <div class="form-group">
                                    <label for="IdUniversidad">Universidad:</label>
                                    <select class="form-control" id="IdUniversidad" name="IdUniversidad" required>
                                        <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                        <?php echo obtenerUniversidades($usuario); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="IdCarrera">Carrera:</label>
                                    <select class="form-control" id="IdCarrera" name="IdCarrera" required>
                                        <option value="" disabled selected style="display:none;">Seleccionar Carrera</option>
                                        <?php echo obtenerCarreras($usuario); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio:</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fechaInicio); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin:</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fechaFin); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Generar</button>
                            </div>
                        </form>

                        <!-- Mostrar Datos -->
                        <div class="data-table">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th># Solicitud</th>
                                        <th>Universidad</th>
                                        <th>Carrera</th>
                                        <th>Modalidad</th>
                                        <th>Grado</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acuerdo Admisión</th>
                                        <th>Fecha Aprobación</th>
                                        <th>Acuerdo Aprobación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $row) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['IdSolicitud']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NomUniversidad']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NomCarrera']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NomModalidad']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NomGrado']); ?></td>
                                            <td><?php echo htmlspecialchars($row['FechaIngreso']); ?></td>
                                            <td><?php echo htmlspecialchars($row['AcuerdoAdmision']); ?></td>
                                            <td><?php echo htmlspecialchars($row['FechaAprobacion']); ?></td>
                                            <td><?php echo htmlspecialchars($row['AcuerdoAprobacion']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Botones de Exportación -->
                        <?php if ($idUniversidad && $idCarrera) : ?>
                            <div class="export-options">
                                <span>EXPORTAR COMO:</span>
                                <div class="buttons">
                                    <button id="export-excel" class="excel-btn"
                                        data-universidad="<?= htmlspecialchars($idUniversidad) ?>"
                                        data-carrera="<?= htmlspecialchars($idCarrera) ?>">
                                        <i class="fas fa-file-excel"></i>
                                    </button>
                                    <button id="export-pdf" class="pdf-btn"
                                        data-universidad="<?= htmlspecialchars($idUniversidad) ?>"
                                        data-carrera="<?= htmlspecialchars($idCarrera) ?>">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </main>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.8.0/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="../Seguridad/Bitacora/Bitacora.js"></script>

        <script>
            $(document).ready(function() {
                $('#export-excel').on('click', function() {
                    var universidad = $(this).data('universidad');
                    var carrera = $(this).data('carrera');
                    var fechaInicio = $('#fecha_inicio').val();
                    var fechaFin = $('#fecha_fin').val();
                    window.location.href = "exportar.php?IdUniversidad=" + universidad + "&IdCarrera=" + carrera + "&fecha_inicio=" + fechaInicio + "&fecha_fin=" + fechaFin + "&type=excel";
                });

                $('#export-pdf').on('click', function() {
                    var universidad = $(this).data('universidad');
                    var carrera = $(this).data('carrera');
                    var fechaInicio = $('#fecha_inicio').val();
                    var fechaFin = $('#fecha_fin').val();
                    window.location.href = "exportar.php?IdUniversidad=" + universidad + "&IdCarrera=" + carrera + "&fecha_inicio=" + fechaInicio + "&fecha_fin=" + fechaFin + "&type=pdf";
                });
            });
        </script>

    </body>

    </html>
<?php
} else {
    echo "Usuario no autenticado.";
}
?>