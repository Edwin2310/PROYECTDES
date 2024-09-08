<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {
?>


<?php
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 38; // ID del objeto o módulo correspondiente a esta página

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
                                            <th class="text-center">ID_SOLICITUD</th>
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
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();
                                        $sql = "SELECT
                                                    s.ID_SOLICITUD,
                                                    c.NOM_CARRERA,
                                                    cat.NOM_CATEGORIA,
                                                    uc.NOM_UNIVERSIDAD,
                                                    g.NOM_GRADO,
                                                    e.ESTADO_SOLICITUD,
                                                    tp.NOM_TIPO,
                                                    s.NUM_REFERENCIA,
                                                    s.DESCRIPCION,
                                                    d.NOM_DEPTO,
                                                    mu.NOM_MUNICIPIO,
                                                    u.NOMBRE_USUARIO,
                                                    s.NOMBRE_COMPLETO,
                                                    s.EMAIL,
                                                    s.FECHA_INGRESO,
                                                    s.FECHA_MODIFICACION,
                                                    cat.COD_ARBITRIOS
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
                                                WHERE e.ID_ESTADO IN (5)
                                                ORDER BY e.ID_ESTADO";

                                        $result = $conn->query($sql);
                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['ID_SOLICITUD']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_CARRERA']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_CATEGORIA']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_UNIVERSIDAD']}</td>";
                                                echo "<td class='text-center'>{$row['NOM_GRADO']}</td>";

                                                echo "<td class='text-center'>{$row['ESTADO_SOLICITUD']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_TIPO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NUM_REFERENCIA']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['DESCRIPCION']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_DEPTO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOM_MUNICIPIO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOMBRE_USUARIO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NOMBRE_COMPLETO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['EMAIL']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FECHA_INGRESO']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FECHA_MODIFICACION']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['COD_ARBITRIOS']}</td>";

                                                echo "<td class='text-center'>
                                                        <a href='Asignar_AcuerdoAdmin.php ?solicitud_id={$row['ID_SOLICITUD']}
                                                                                  &tipo_solicitud={$row['NOM_TIPO']}
                                                                                  &categoria={$row['NOM_CATEGORIA']}
                                                                                  &referencia={$row['NUM_REFERENCIA']}
                                                                                  &descripcion={$row['DESCRIPCION']}
                                                                                  &carrera={$row['NOM_CARRERA']}
                                                                                  &grado_academico={$row['NOM_GRADO']}
                                                                                 
                                                                                  &universidad={$row['NOM_UNIVERSIDAD']}
                                                                                  &departamento={$row['NOM_DEPTO']}
                                                                                  &municipio={$row['NOM_MUNICIPIO']}
                                                                                  &usuario={$row['NOMBRE_USUARIO']}
                                                                                  &nombre_completo={$row['NOMBRE_COMPLETO']}
                                                                                  &email={$row['EMAIL']}
                                                                                  &fecha_ingreso={$row['FECHA_INGRESO']}
                                                                                  &fecha_modificacion={$row['FECHA_MODIFICACION']}
                                                                                  &codigo_pago={$row['COD_ARBITRIOS']}
                                                                                  &nombre_carrera={$row['NOM_CARRERA']}
                                                                                  &estado={$row['ESTADO_SOLICITUD']}' class='btn btn-sm btn-secondary' title='Asignar Acuerdo'>
                                                                                 Asignar Acuerdo
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