<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {

?>


<?php
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 33; // ID del objeto o módulo correspondiente a esta página

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
                <h2 class="content-heading">Dictamen CTC</h2>

                <div class="row justify-content-center">
                    <div class="col-md-4" data-toggle="appear">
                        <a href="../MantenimientoSolicitudes/DictamenCTC_Agenda.php" data-id-objeto="39" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                            <div class="block-content block-content-full">
                                <div class="py-30 text-center">
                                    <div class="item item-2x item-circle bg-warning text-white mx-auto">
                                        <i class="si si-book-open text-default"></i>
                                    </div>
                                </div>
                                <div class="block-content bg-body-light">
                                    <p class="h5 font-w600">Agenda Solicitudes al CTC</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4" data-toggle="appear">
                        <a href="../MantenimientoSolicitudes/DictamenCTC_Asignar.php" data-id-objeto="40" data-accion="accedio al modulo" class="modulo-link block block-link-shadow text-center">
                            <div class="block-content block-content-full">
                                <div class="py-30 text-center">
                                    <div class="item item-2x item-circle bg-default text-white mx-auto">
                                        <i class="si si-magnifier text-warning"></i>
                                    </div>
                                </div>
                                <div class="block-content bg-body-light">
                                    <p class="h5 font-w600">Asignar Dictamen CTC</p>
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
<script src="../Seguridad/Permisos/acceso.js" defer></script>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>