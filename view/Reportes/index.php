<?php
session_start();
require_once("../../config/conexion.php");



$id_rol = $_SESSION['IdRol'] ?? null;
$id_objeto = 9; // ID del objeto o módulo correspondiente a esta página

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
    $idUniversidad = isset($_GET['id_universidad']) ? $_GET['id_universidad'] : '';
    $idCarrera = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : '';
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
                s.ID_SOLICITUD,
                u.NOM_UNIVERSIDAD,
                c.NOM_CARRERA,
                m.NOM_MODALIDAD,
                g.NOM_GRADO,
                s.FECHA_INGRESO,
                a.ACUERDO_ADMISION,
                ap.FECHA_CREACION AS FECHA_APROBACION,
                ap.ACUERDO_APROBACION
            FROM
                tbl_solicitudes s
            JOIN tbl_modalidad m ON s.ID_MODALIDAD = m.ID_MODALIDAD
            JOIN tbl_grado_academico g ON s.ID_GRADO = g.ID_GRADO
            LEFT JOIN tbl_acuerdo_ces_admin a ON s.ID_SOLICITUD = a.ID_SOLICITUD
            LEFT JOIN tbl_acuerdo_ces_aprob ap ON s.ID_SOLICITUD = ap.ID_SOLICITUD
            LEFT JOIN tbl_carrera c ON s.ID_CARRERA = c.ID_CARRERA
            LEFT JOIN tbl_universidad_centro u ON s.ID_UNIVERSIDAD = u.ID_UNIVERSIDAD
            WHERE s.ID_UNIVERSIDAD = :idUniversidad 
            AND c.ID_CARRERA = :idCarrera";

            // Agregar filtros de fecha si están presentes
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
                                    <span><?php echo $_SESSION["NOMBRE_USUARIO"] ?></span>
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
                                    <label for="id_universidad">Universidad:</label>
                                    <select class="form-control" id="id_universidad" name="id_universidad" required>
                                        <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                        <?php
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();
                                        $sql_universidad = "SELECT ID_UNIVERSIDAD, NOM_UNIVERSIDAD FROM tbl_universidad_centro";
                                        $result_universidad = $conn->query($sql_universidad);
                                        if ($result_universidad !== false && $result_universidad->rowCount() > 0) {
                                            while ($row = $result_universidad->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row["ID_UNIVERSIDAD"] . "'>" . $row["NOM_UNIVERSIDAD"] . "</option>";
                                            }
                                        }
                                        $conn = null;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_carrera">Carrera:</label>
                                    <select class="form-control" id="id_carrera" name="id_carrera" required>
                                        <option value="" disabled selected style="display:none;">Seleccionar Carrera</option>
                                        <?php
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();
                                        $sql_carrera = "SELECT ID_CARRERA, NOM_CARRERA FROM tbl_carrera";
                                        $result_carrera = $conn->query($sql_carrera);
                                        if ($result_carrera !== false && $result_carrera->rowCount() > 0) {
                                            while ($row = $result_carrera->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row["ID_CARRERA"] . "'>" . $row["NOM_CARRERA"] . "</option>";
                                            }
                                        }
                                        $conn = null;
                                        ?>
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
                                            <td><?php echo htmlspecialchars($row['ID_SOLICITUD']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NOM_UNIVERSIDAD']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NOM_CARRERA']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NOM_MODALIDAD']); ?></td>
                                            <td><?php echo htmlspecialchars($row['NOM_GRADO']); ?></td>
                                            <td><?php echo htmlspecialchars($row['FECHA_INGRESO']); ?></td>
                                            <td><?php echo htmlspecialchars($row['ACUERDO_ADMISION']); ?></td>
                                            <td><?php echo htmlspecialchars($row['FECHA_APROBACION']); ?></td>
                                            <td><?php echo htmlspecialchars($row['ACUERDO_APROBACION']); ?></td>
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
                    window.location.href = "exportar.php?id_universidad=" + universidad + "&id_carrera=" + carrera + "&fecha_inicio=" + fechaInicio + "&fecha_fin=" + fechaFin + "&type=excel";
                });

                $('#export-pdf').on('click', function() {
                    var universidad = $(this).data('universidad');
                    var carrera = $(this).data('carrera');
                    var fechaInicio = $('#fecha_inicio').val();
                    var fechaFin = $('#fecha_fin').val();
                    window.location.href = "exportar.php?id_universidad=" + universidad + "&id_carrera=" + carrera + "&fecha_inicio=" + fechaInicio + "&fecha_fin=" + fechaFin + "&type=pdf";
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