<?php

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 42; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

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
                    <h2 class="content-heading">Análisis Curricular</h2>


                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-alt-primary" id="btn-aprobar-enviar">
                            Aprobar o rechazar una vez <i class="fa fa-check-circle"></i>
                        </button>
                    </div>

                    <br>

                    <div class="row justify-content-center">
                        <div class="col-md-4" data-toggle="appear">
                            <a href="../MantenimientoSolicitudes/AnalisisCurricular_DGAC.php" cdata-id-objeto="50" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <p class="h5">DGAC</p>
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-note text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Revisar y Aprobar Opinión Razonada</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4" data-toggle="appear">
                            <a href="../MantenimientoSolicitudes/AnalisisCurricular_DirecE.php" data-id-objeto="51" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <p class="h5">DIRECCIÓN EJECUTIVA</p>
                                        <div class="item item-2x item-circle bg-default text-white mx-auto">
                                            <i class="si si-note text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Revisar y Aprobar Opinión Razonada</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4" data-toggle="appear">
                            <a href="../MantenimientoSolicitudes/AnalisisCurricular_DSA.php" data-id-objeto="52" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                                <div class="block-content block-content-full">
                                    <div class="py-30 text-center">
                                        <p class="h5">DSA</p>
                                        <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                            <i class="si si-note text-default"></i>
                                        </div>
                                    </div>
                                    <div class="block-content bg-body-light">
                                        <p class="h5 font-w600">Revisar y Aprobar Opinión Razonada</p>
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
        <script>
            document.getElementById('btn-aprobar-enviar').addEventListener('click', function() {
                window.location.href = 'AnalisisCurricularTodasS.php';
            });

        </script>


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