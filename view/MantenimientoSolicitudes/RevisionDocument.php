<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 31; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

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
                                            <th class="text-center">ID SOLICITUD</th>
                                            <th class="text-center">TIPO DE SOLICITUD</th>
                                            <th class="text-center">CATEGORIA</th>
                                            <th class="text-center">NOMBRE DE CARRERA</th>
                                            <th class="text-center">GRADO ACADÉMICO</th>
                                            <th class="text-center">MODALIDAD</th>
                                            <th class="text-center">UNIVERSIDAD</th>
                                            <th class="text-center hidden-column">DESCRIPCION</th>
                                            <th class="text-center hidden-column">N° DE REFERENCIA</th>
                                            <th class="text-center hidden-column">DEPARTAMENTO</th>
                                            <th class="text-center hidden-column">MUNICIPIO</th>
                                            <th class="text-center hidden-column">USUARIO</th>
                                            <th class="text-center hidden-column">NOMBRE COMPLETO</th>
                                            <th class="text-center hidden-column">EMAIL</th>
                                            <th class="text-center hidden-column">FECHA DE INGRESO</th>
                                            <th class="text-center hidden-column">FECHA DE MODIFICACION</th>
                                            <th class="text-center hidden-column">CODIGO DE PAGO</th>
                                            <th class="text-center hidden-column">CARRERA</th>
                                            <th class="text-center" style="width: 15%;">ESTADO</th>
                                            <th class="text-center" style="width: 15%;">PROCESO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Llamar al procedimiento almacenado
                                        $sql = "CALL `proceso.sqlRevisionDocsMostrar`()";

                                        $result = $conn->query($sql);
                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['TipoSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['NomCategoria']}</td>";
                                                echo "<td class='text-center'>{$row['NomCarrera']}</td>";
                                                echo "<td class='text-center'>{$row['NomGrado']}</td>";
                                                echo "<td class='text-center'>{$row['NomModalidad']}</td>";
                                                echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['Descripcion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NumReferencia']}</td>";
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
                                                        <a href='RevisionDocs.php?solicitud_id={$row['IdSolicitud']}
                                                                            &estado={$row['EstadoSolicitud']}' class='btn btn-sm btn-secondary' title='Proceso'>
                                                            <i class='si si-note'></i>
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
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="../Seguridad/Permisos/acceso.js" defer></script>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>