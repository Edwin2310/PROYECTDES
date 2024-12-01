<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 38; // ID del objeto o módulo correspondiente a esta página
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

            .text-left {
                text-align: left;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .d-flex {
                display: flex;
            }

            .justify-content-between {
                justify-content: space-between;
            }

            .align-items-center {
                align-items: center;
            }

            .button-group {
                display: flex;
                gap: 10px;
            }

            .show-more-btn {
                margin-left: auto;
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
                            <h3 class="block-title">Agendar acuerdo <small></small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="table-container">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center">IdSolicitud</th>
                                            <th class="text-center">CARRERA</th>
                                            <th class="text-center">CATEGORÍA</th>
                                            <th class="text-center">UNIVERSIDAD</th>
                                            <th class="text-center">GRADO ACADÉMICO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center hidden-column">TIPO DE SOLICITUD</th>
                                            <th class="text-center hidden-column">N° DE REFERENCIA</th>
                                            <th class="text-center hidden-column">DESCRIPCIÓN</th>
                                            <th class="text-center hidden-column">DEPARTAMENTO</th>
                                            <th class="text-center hidden-column">MUNICIPIO</th>
                                            <th class="text-center hidden-column">USUARIO</th>
                                            <th class="text-center hidden-column">NOMBRE COMPLETO</th>
                                            <th class="text-center hidden-column">EMAIL</th>
                                            <th class="text-center hidden-column">FECHA DE INGRESO</th>
                                            <th class="text-center hidden-column">FECHA DE MODIFICACIÓN</th>
                                            <th class="text-center hidden-column">CÓDIGO DE PAGO</th>
                                            <th class="text-center">Función</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Establecer la conexión con la base de datos
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Consulta SQL
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
                                                LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON cat.IdTipoSolicitud = ts.IdTipoSolicitud
                                                WHERE e.IdEstado IN (5)
                                                ORDER BY e.IdEstado;";

                                        // Ejecutar la consulta
                                        $result = $conn->query($sql);

                                        // Verificar si hay resultados
                                        if ($result !== false && $result->rowCount() > 0) {
                                            // Recorrer los resultados
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['NomCarrera']}</td>";
                                                echo "<td class='text-center'>{$row['NomCategoria']}</td>";
                                                echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                                echo "<td class='text-center'>{$row['NomGrado']}</td>";
                                                echo "<td class='text-center'>{$row['EstadoSolicitud']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomTipoSolicitud']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NumReferencia']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['Descripcion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomDepto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomMunicipio']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreUsuario']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreCompleto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CorreoElectronico']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaIngreso']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaModificacion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CodArbitrios']}</td>";

                                                // Enlace para asignar acuerdo
                                                echo "<td class='text-center'>
                                                        <a href='Asignar_AcuerdoAdmin.php?solicitud_id={$row['IdSolicitud']}
                                                            &tipo_solicitud={$row['NomTipoSolicitud']}
                                                            &categoria={$row['NomCategoria']}
                                                            &referencia={$row['NumReferencia']}
                                                            &descripcion={$row['Descripcion']}
                                                            &carrera={$row['NomCarrera']}
                                                            &grado_academico={$row['NomGrado']}
                                                            &universidad={$row['NomUniversidad']}
                                                            &departamento={$row['NomDepto']}
                                                            &municipio={$row['NomMunicipio']}
                                                            &usuario={$row['NombreUsuario']}
                                                            &nombre_completo={$row['NombreCompleto']}
                                                            &email={$row['CorreoElectronico']}
                                                            &fecha_ingreso={$row['FechaIngreso']}
                                                            &fecha_modificacion={$row['FechaModificacion']}
                                                            &codigo_pago={$row['CodArbitrios']}
                                                            &nombre_carrera={$row['NomCarrera']}
                                                            &estado={$row['EstadoSolicitud']}' 
                                                            class='btn btn-sm btn-secondary' title='Asignar Acuerdo'>
                                                            Asignar Acuerdo
                                                        </a>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            // Si no hay resultados
                                            echo "<tr><td colspan='18' class='text-center'>No hay datos disponibles</td></tr>";
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                            <div class="text-left mt-3">
                                <button class="btn btn-primary show-more-btn" id="showMoreBtn">Mostrar más</button>
                            </div>
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
                    hiddenColumns.forEach(col => {
                        col.classList.toggle("hidden-column");
                    });

                    if (showMoreBtn.textContent === "Mostrar más") {
                        showMoreBtn.textContent = "Mostrar menos";
                    } else {
                        showMoreBtn.textContent = "Mostrar más";
                    }
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
<script src="../Seguridad//Bitacora//Bitacora.js"></script>