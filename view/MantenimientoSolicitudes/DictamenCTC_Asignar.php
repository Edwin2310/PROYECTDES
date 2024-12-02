<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 40; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 3) {
        // Verificar permisos
        if (verificarPermiso($id_rol, $id_objeto)) {
            $accion = "Accedió al módulo.";

            // Registrar en la bitácora
            registrobitaevent($id_usuario, $id_objeto, $accion);
        } else {
            $accion = "acceso denegado.";

            // Registrar en bitácora antes de redirigir
            registrobitaevent($id_usuario, $id_objeto, $accion);

            // Redirigir a la página de denegación
            header("Location: ../Seguridad/Permisos/denegado.php");
            exit();
        }

        // Actualizar la marca temporal en la sesión
        $_SESSION['last_access_time'][$current_page] = $current_time;
    }


?>

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Consultar Solicitudes</title>
        <style>
            .table-container {
                overflow-x: auto;
            }

            .hidden-column {
                display: none;
            }

            .show-more-btn {
                margin-top: 10px;
            }
        </style>
    </head>

    <body>
        <div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">
            <aside id="side-overlay">
                <div id="side-overlay-scroll">
                    <div class="content-header content-header-fullrow">
                        <div class="content-header-section align-parent">
                            <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            <div class="content-header-item">
                                <a class="img-link mr-5" href="be_pages_generic_profile.html">
                                    <img class="img-avatar img-avatar32" src="../../public/assets/img/avatars/avatar15.jpg" alt="">
                                </a>
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html">John Smith</a>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <nav id="sidebar" class="text-warning">
                <div id="sidebar-scroll">
                    <div class="sidebar-content">
                        <?php require_once("../MainSidebar/MainSidebar.php"); ?>
                        <?php require_once("../MainMenu/MainMenu.php"); ?>
                    </div>
                </div>
            </nav>
            <nav class="text-warning">
                <?php require_once("../MainHeader/MainHeader.php"); ?>
            </nav>
            <main id="main-container">
                <div class="content">
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Revisión de Documentación <small></small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="table-container">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID_SOLICITUD</th>
                                            <th class="text-center">TIPO DE SOLICITUD</th>
                                            <th class="text-center">CATEGORIA</th>
                                            <th class="text-center">N° DE REFERENCIA</th>
                                            <th class="text-center">DESCRIPCION</th>
                                            <th class="text-center hidden-column">CARRERA</th>
                                            <th class="text-center hidden-column">GRADO ACADÉMICO</th>
                                            <th class="text-center hidden-column">MODALIDAD</th>
                                            <th class="text-center hidden-column">UNIVERSIDAD</th>
                                            <th class="text-center hidden-column">DEPARTAMENTO</th>
                                            <th class="text-center hidden-column">MUNICIPIO</th>
                                            <th class="text-center hidden-column">USUARIO</th>
                                            <th class="text-center hidden-column">NOMBRE COMPLETO</th>
                                            <th class="text-center hidden-column">EMAIL</th>
                                            <th class="text-center hidden-column">FECHA DE INGRESO</th>
                                            <th class="text-center hidden-column">FECHA DE MODIFICACION</th>
                                            <th class="text-center hidden-column">CODIGO DE PAGO</th>
                                            <th class="text-center hidden-column">NOMBRE DE CARRERA</th>
                                            <th class="text-center" style="width: 15%;">ESTADO</th>
                                            <th class="text-center" style="width: 15%;">PROCESO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();
                                        $sql = "SELECT
                                        s.IdSolicitud,
                                        s.NumReferencia,
                                        s.Descripcion,
                                        s.NombreCompleto,
                                        s.FechaIngreso,
                                        s.FechaModificacion,
                                        ts.NomTipoSolicitud,
                                        cat.NomCategoria,
                                        c.NomCarrera,
                                        g.NomGrado,
                                        m.NomModalidad,
                                        uc.NomUniversidad,
                                        d.NomDepto,
                                        mu.NomMunicipio,
                                        u.NombreUsuario,
                                        s.CorreoElectronico,
                                        cat.CodArbitrios,
                                        s.NombreCarrera,
                                        e.EstadoSolicitud
                                    FROM
                                        `proceso.tblSolicitudes` s
                                    LEFT JOIN `mantenimiento.tblcategorias` cat ON s.IdCategoria = cat.IdCategoria
                                    LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
                                    LEFT JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
                                    LEFT JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
                                    LEFT JOIN `mantenimiento.tbluniversidades` uc ON s.IdUniversidad = uc.IdUniversidad
                                    LEFT JOIN `mantenimiento.tbldeptos` d ON s.IdDepartamento = d.IdDepartamento
                                    LEFT JOIN `mantenimiento.tblmunicipios` mu ON s.IdMunicipio = mu.IdMunicipio
                                    LEFT JOIN `seguridad.tbldatospersonales` u ON s.IdUsuario = u.IdUsuario
                                    LEFT JOIN `mantenimiento.tblestadossolicitudes` e ON s.IdEstado = e.IdEstado
                                    LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON cat.IdTipoSolicitud = ts.IdTipoSolicitud -- Relación con tipos de solicitud
                                    WHERE e.IdEstado IN (7)
                                    ORDER BY e.IdEstado;";

                                        $result = $conn->query($sql);
                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['NomTipoSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['NomCategoria']}</td>";
                                                echo "<td class='text-center'>{$row['NumReferencia']}</td>";
                                                echo "<td class='text-center'>{$row['Descripcion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomCarrera']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomGrado']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomModalidad']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomUniversidad']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomDepto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomMunicipio']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreUsuario']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreCompleto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CorreoElectronico']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaIngreso']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaModificacion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CodArbitrios']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreCarrera']}</td>";
                                                echo "<td class='text-center'>{$row['EstadoSolicitud']}</td>";
                                                echo "<td class='text-center'>
                                                        <a href='../MantenimientoSolicitudes/detalle_dictamenctc.php?solicitud_id={$row['IdSolicitud']}
                                                                 &tipo_solicitud={$row['NomTipoSolicitud']}
                                                                 &categoria={$row['NomCategoria']}
                                                                 &referencia={$row['NumReferencia']}
                                                                 &descripcion={$row['Descripcion']}
                                                                 &carrera={$row['NomCarrera']}
                                                                 &grado_academico={$row['NomGrado']}
                                                                 &modalidad={$row['NomModalidad']}
                                                                 &universidad={$row['NomUniversidad']}
                                                                 &departamento={$row['NomDepto']}
                                                                 &municipio={$row['NomMunicipio']}
                                                                 &usuario={$row['NombreUsuario']}
                                                                 &nombre_completo={$row['NombreCompleto']}
                                                                 &email={$row['CorreoElectronico']}
                                                                 &fecha_ingreso={$row['FechaIngreso']}
                                                                 &fecha_modificacion={$row['FechaModificacion']}
                                                                 &codigo_pago={$row['CodArbitrios']}
                                                                 &nombre_carrera={$row['NombreCarrera']}
                                                                 &estado={$row['EstadoSolicitud']}' class='btn btn-sm btn-primary' >
                                                            <i class='si si-note'>ASIGNAR DICTAMEN</i>
                                                        </a>
                                                     </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='18' class='text-center'>No hay datos disponibles</td></tr>";
                                        }                                        
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <button class="btn btn-primary show-more-btn" id="showMoreBtn">Mostrar más</button>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once("../MainFooter/MainFooter.php"); ?>
        </div>
        <?php require_once("../MainJs/MainJs.php"); ?>

        <!-- Modal Principal -->
        <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mainModalLabel">Revisión de Solicitud</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Aquí puedes agregar el contenido del modal principal -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const showMoreBtn = document.getElementById("showMoreBtn");
                const hiddenColumns = document.querySelectorAll(".hidden-column");

                showMoreBtn.addEventListener("click", function() {
                    const isShowingMore = showMoreBtn.textContent === "Mostrar más";
                    hiddenColumns.forEach(function(column) {
                        column.style.display = isShowingMore ? "table-cell" : "none";
                    });
                    showMoreBtn.textContent = isShowingMore ? "Mostrar menos" : "Mostrar más";
                });
            });
        </script>
    </body>

    </html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script src="../Seguridad/Permisos/acceso.js" defer></script>
<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->