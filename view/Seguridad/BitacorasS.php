<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 46; // ID del objeto o módulo correspondiente a esta página

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
                <h2 class="content-heading">Acuerdo de Admisión</h2>

                <div class="row justify-content-center">
                    <div class="col-md-4" data-toggle="appear">
                        <a href="../Seguridad/Bitacorasoli.php" data-id-objeto="45" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                            <div class="block-content block-content-full">
                                <div class="py-30 text-center">
                                    <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                        <i class="fa fa-pencil-square text-default"></i>
                                    </div>
                                </div>
                                <div class="block-content bg-body-light">
                                    <p class="h5 font-w600">Bitácora solicitudes</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4" data-toggle="appear">
                        <a href="../Seguridad/Bitacora.php" data-id-objeto="21" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                            <div class="block-content block-content-full">
                                <div class="py-30 text-center">
                                    <div class="item item-2x item-circle bg-default text-white mx-auto">
                                        <i class="si si-magnifier text-warning"></i>
                                    </div>
                                </div>
                                <div class="block-content bg-body-light">
                                    <p class="h5 font-w600">Bitácora eventos</p>
                                </div>
                            </div>
                        </a>
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
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="../Seguridad/Permisos/acceso.js" defer></script>
<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->