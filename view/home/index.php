<?php
session_start();
require_once("../../config/conexion.php");
if (isset($_SESSION["ID_USUARIO"])) {

?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>

        <?php require_once("../MainHead/MainHead.php"); ?>

        <title>Home / Bienvenido </title>

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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html">
                                    <span><?php echo $_SESSION["NOMBRE_USUARIO"] ?></span>
                                    <!-- Mostrar el ID_USUARIO -->
                                    <span>ID Usuario: <?php echo $_SESSION["ID_USUARIO"]; ?></span>
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



            <!-- Inicio de la entrada de la universidad -->

            <main id="main-container">

                <div class="content">
                    <h2 class="content-heading">Bienvenido, <?php echo $_SESSION["NOMBRE_USUARIO"]; ?></h2>




                    <div class="row gutters-tiny">
                        <div class="col-md-4 col-lg-4 ml-auto " data-toggle="appear">
                            <a href="../NuevoIngresoSolicitud/index.php" class="modulo-link block block-link-shadow text-center" data-id-objeto="3" data-accion="accedio al modulo" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Ingreso de Solicitudes</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class="col-sm-4 col-lg-4 mr-auto" data-toggle="appear">
                            <a href="../ConsultarSolicitudes/index.php" class="modulo-link block block-link-shadow text-center" data-id-objeto="4" data-accion="accedio al modulo" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-magnifier text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Consultar Solicitudes</p>
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
        <script type="text/javascript" src="home.js"></script>
        <!-- Incluir el script acceso.js -->
        <script src="../Seguridad/Permisos/acceso.js" defer></script>
        <script src="../Seguridad/Bitacora/Bitacora.js"></script>

    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>