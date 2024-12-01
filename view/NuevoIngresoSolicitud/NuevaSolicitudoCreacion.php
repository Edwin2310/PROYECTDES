<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {

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
                    <h2 class="content-heading">Dirección de Educación Superior</h2>

                    <div class="row gutters-tiny">

                        <div class="col-lg-4 ml-auto" data-toggle="appear">
                            <a href="index.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-note text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Creación de Solicitud</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 mr-auto" data-toggle="appear">
                            <a href="../ConsultarSolicitudes/index.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="fa fa-pencil-square-o text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Consultar solicitudes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- <div class="col-lg-4 mr-auto" data-toggle="appear">
                            <a href="CreacionCarrera1.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="fa fa-pencil-square-o text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Creación de Carrera</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 mr-auto" data-toggle="appear">
                            <a href="CreacionReforma1.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-note text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Creación de Reforma</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 ml-auto" data-toggle="appear">
                            <a href="CreacionCentro1.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="fa fa-plus-square-o text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Creación de Centro</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 mr-auto" data-toggle="appear">
                            <a href="CreacionOtros1.php" class="block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="fa fa-plus-square-o text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Creación de Otros</p>
                                    </div>
                                </div>
                            </a>
                        </div> -->

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