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
                                        s.ID_SOLICITUD,
                                        tp.NOM_TIPO,
                                        cat.NOM_CATEGORIA,
                                        s.NUM_REFERENCIA,
                                        s.DESCRIPCION,
                                        c.NOM_CARRERA,
                                        g.NOM_GRADO,
                                        m.NOM_MODALIDAD,
                                        uc.NOM_UNIVERSIDAD,
                                        d.NOM_DEPTO,
                                        mu.NOM_MUNICIPIO,
                                        u.NOMBRE_USUARIO,
                                        s.NOMBRE_COMPLETO,
                                        s.EMAIL,
                                        s.FECHA_INGRESO,
                                        s.FECHA_MODIFICACION,
                                        cat.COD_ARBITRIOS,
                                        s.NOMBRE_CARRERA,
                                        e.ESTADO_SOLICITUD
                                        FROM
                                        tbl_solicitudes s
                                        LEFT JOIN tbl_tipo_solicitud tp ON s.ID_TIPO_SOLICITUD = tp.ID_TIPO_SOLICITUD
                                        LEFT JOIN tbl_categoria cat ON s.ID_CATEGORIA = cat.ID_CATEGORIA
                                        LEFT JOIN tbl_carrera c ON s.ID_CARRERA = c.ID_CARRERA
                                        LEFT JOIN tbl_grado_academico g ON s.ID_GRADO = g.ID_GRADO
                                        LEFT JOIN tbl_modalidad m ON s.ID_MODALIDAD = m.ID_MODALIDAD
                                        LEFT JOIN tbl_universidad_centro uc ON s.ID_UNIVERSIDAD = uc.ID_UNIVERSIDAD
                                        LEFT JOIN tbl_deptos d ON s.ID_DEPARTAMENTO = d.ID_DEPARTAMENTO
                                        LEFT JOIN tbl_municipios mu ON s.ID_MUNICIPIO = mu.ID_MUNICIPIO
                                        LEFT JOIN tbl_ms_usuario u ON s.IdUsuario = u.IdUsuario
                                        LEFT JOIN tbl_estado_solicitud e ON s.ID_ESTADO = e.ID_ESTADO
                                        WHERE e.ID_ESTADO IN (7)
                                        ORDER BY e.ID_ESTADO";

                                        $result = $conn->query($sql);
                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['ID_SOLICITUD']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_TIPO']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_CATEGORIA']}</td>";
                                                echo "<td class='text-center'>{$row['NUM_REFERENCIA']}</td>";
                                                echo "<td class='text-center'>{$row['DESCRIPCION']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_CARRERA']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_GRADO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_MODALIDAD']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_UNIVERSIDAD']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_DEPTO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_MUNICIPIO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOMBRE_USUARIO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOMBRE_COMPLETO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['EMAIL']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FECHA_INGRESO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FECHA_MODIFICACION']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['COD_ARBITRIOS']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOMBRE_CARRERA']}</td>";
                                                echo "<td class='text-center'>{$row['ESTADO_SOLICITUD']}</td>";
                                                echo "<td class='text-center'>
                                                        <a href='../MantenimientoSolicitudes/detalle_dictamenctc.php?solicitud_id={$row['ID_SOLICITUD']}
                                                                                  &tipo_solicitud={$row['NOM_TIPO']}
                                                                                  &categoria={$row['NOM_CATEGORIA']}
                                                                                  &referencia={$row['NUM_REFERENCIA']}
                                                                                  &descripcion={$row['DESCRIPCION']}
                                                                                  &carrera={$row['NOM_CARRERA']}
                                                                                  &grado_academico={$row['NOM_GRADO']}
                                                                                  &modalidad={$row['NOM_MODALIDAD']}
                                                                                  &universidad={$row['NOM_UNIVERSIDAD']}
                                                                                  &departamento={$row['NOM_DEPTO']}
                                                                                  &municipio={$row['NOM_MUNICIPIO']}
                                                                                  &usuario={$row['NOMBRE_USUARIO']}
                                                                                  &nombre_completo={$row['NOMBRE_COMPLETO']}
                                                                                  &email={$row['EMAIL']}
                                                                                  &fecha_ingreso={$row['FECHA_INGRESO']}
                                                                                  &fecha_modificacion={$row['FECHA_MODIFICACION']}
                                                                                  &codigo_pago={$row['COD_ARBITRIOS']}
                                                                                  &nombre_carrera={$row['NOMBRE_CARRERA']}
                                                                                  &estado={$row['ESTADO_SOLICITUD']}' class='btn btn-sm btn-primary' >
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