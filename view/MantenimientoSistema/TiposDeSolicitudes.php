<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 28; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>

        <?php require_once("../MainHead/MainHead.php"); ?>

        <title>Tipos de Solicitudes </title>

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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><!-- llamada del usuario   -->
                                    <span><?php echo $_SESSION["USUARIO"] ?> <?php echo $_SESSION["NOMBRE_USUARIO"] ?></span></a>
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



            <!-- Inicio de la entrada de la universidad -->

            <main id="main-container">

                <div class="content">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Gestión de Municipios</h3>
                    </div>

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-center">Id Tipo Solicitud</th>
                                <th class="text-center">Tipo de Solicitud</th>
                                <th class="text-center">Editar</th>
                                <th class="text-center">Bloquear</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../../config/conexion.php");
                            $conexion = new Conectar();
                            $conn = $conexion->Conexion();

                            // Consulta a la base de datos para obtener los tipos de solicitud
                            $sql = "SELECT IdTiposolicitud, NomTipoSolicitud FROM `mantenimiento.tbltiposolicitudes`";
                            $result = $conn->query($sql);

                            // Verificar si hay resultados
                            if ($result !== false && $result->rowCount() > 0) {
                                // Iterar sobre los resultados
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$row['IdTiposolicitud']}</td>";
                                    echo "<td class='text-center'>{$row['NomTipoSolicitud']}</td>";
                                    echo "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editTipoSolicitudModal' 
                                data-id='{$row['IdTiposolicitud']}' 
                                data-tipo='{$row['NomTipoSolicitud']}'>
                            <i class='si si-note'></i>
                        </button>
                      </td>";
                                    echo "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                data-id='{$row['IdTiposolicitud']}'>
                            <i class='si si-trash'></i>
                        </button>
                      </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No hay datos disponibles</td></tr>";
                            }

                            // Cerrar la conexión
                            $conn = null;
                            ?>
                        </tbody>
                    </table>


                    </script>

    </body>

    </html>

    </html>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>