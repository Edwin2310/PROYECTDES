<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 50; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">


    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>

        <title>Consultar Solicitudes</title>

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

                    <div class="sidebar-content ">


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
                            <h3 class="block-title">Solicitudes en proceso <small>DGAC</small></h3>
                        </div>
                        <div class="block-content block-content-full">

                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-center">Codigo de Tramite</th>
                                        <th>Carrera</th>
                                        <th class="d-none d-sm-table-cell">Categoria</th>
                                        <th class="d-none d-sm-table-cell">Institucion</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Grado Academico</th>
                                        <th class="text-center" style="width: 15%;">Estado</th>
                                        <th class="text-center" style="width: 15%;">Funciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    require_once("../../config/conexion.php");

                                    $conexion = new Conectar();
                                    $conn = $conexion->Conexion();

                                    try {
                                        // Preparar la llamada al procedimiento almacenado
                                        $sql = "CALL `proceso.splSolicitudesDGCA`()";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();

                                        // Verificar si hay resultados
                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td>{$row['NomCarrera']}</td>";
                                                echo "<td>{$row['NomCategoria']}</td>";
                                                echo "<td>{$row['NomUniversidad']}</td>";
                                                echo "<td>{$row['NomGrado']}</td>";

                                                // Condición para mostrar el badge correcto según el estado
                                                if ($row['IdEstado'] == 19) {
                                                    echo "<td><span class='badge badge-info'>{$row['EstadoSolicitud']}</span></td>";
                                                } elseif ($row['IdEstado'] == 13) {
                                                    echo "<td><span class='badge badge-success'>{$row['EstadoSolicitud']}</span></td>";
                                                }

                                                echo "<td class='text-center'>
                                                <a href='SolicitudesProcesoDGAC.php?id={$row['IdSolicitud']}'>
                                                    <button type='button' class='btn btn-sm btn-secondary'>
                                                        Revisar OR DES
                                                    </button>
                                                </a>
                                            </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No hay datos disponibles</td></tr>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='7' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>


            <?php require_once("../MainFooter/MainFooter.php"); ?>


        </div>

        <?php require_once("../MainJs/MainJs.php"); ?>

    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>