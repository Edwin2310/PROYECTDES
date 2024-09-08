<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 10; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

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
                    <h2 class="content-heading">Bienvenido, UNITEC </h2>



                    <div class="row row-deck">
                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/Departamentos.php" data-id-objeto="22" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Departamentos</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/Municipios.php" data-id-objeto="23" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-magnifier text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Municipios</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/Universidades.php" data-id-objeto="24" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Universidades</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class="col-md-6 col-xl-3 " data-toggle="appear">
                            <a href="../MantenimientoSistema/Carreras.php" data-id-objeto="25" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-magnifier text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Carreras</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row row-deck">
                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/Modalidades.php" data-id-objeto="26" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Modalidades</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/GradosAcademicos.php" data-id-objeto="27" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-magnifier text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">GradosAcademicos</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class=" col-md-6 col-xl-3" data-toggle="appear">
                            <a href="../MantenimientoSistema/TiposDeSolicitudes.php" data-id-objeto="28" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Tipos De Solicitudes</p>
                                    </div>
                                </div>
                            </a>
                        </div>



                        <div class="col-md-6 col-xl-3 " data-toggle="appear">
                            <a href="../MantenimientoSistema/CategoriaDeSolicitudes.php" data-id-objeto="29" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-magnifier text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Categorias De Solicitudes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="row row-deck">
                        <div class="col-md-6 col-xl-3 ml-auto" data-toggle="appear">
                            <a href="../MantenimientoSistema/EstadoDeSolicitudes.php" data-id-objeto="30" data-accion="accedio al modulo" class=" modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-book-open text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class=" h5 font-w600">Estado De Solicitudes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- HTML: Enlace con diseño personalizado -->
                        <div class="col-md-6 col-xl-3 mr-auto" data-toggle="appear">
                            <a onclick="document.getElementById('exportForm').submit();" data-id-objeto="53" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center" href="javascript:void(0)">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="fa fa-database text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Descargar Base de Datos</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Formulario oculto -->
                        <form id="exportForm" method="post" action="export_db.php" style="display:none;">
                            <input type="hidden" name="download_db" value="1">
                        </form>




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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Seguridad/Bitacora/Bitacora.js"></script>