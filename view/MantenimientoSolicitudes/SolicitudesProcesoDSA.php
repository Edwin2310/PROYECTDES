<?php
require_once("../../config/conexion.php");


if (isset($_SESSION["IdUsuario"])) {

?>
    <?php
    require_once("../../config/conexion.php");

    $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

    if ($id) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        $sql = "SELECT a.ID_SOLICITUD, b.NOM_CARRERA, c.NOM_CATEGORIA, d.NOM_UNIVERSIDAD, e.NOM_GRADO, f.ESTADO_sOLICITUD, g.NOM_MODALIDAD
                FROM tbl_solicitudes a 
                LEFT JOIN tbl_carrera b ON a.ID_CARRERA = b.ID_CARRERA
                LEFT JOIN tbl_categoria c ON a.ID_CATEGORIA = c.ID_CATEGORIA
                LEFT JOIN tbl_universidad_centro d ON a.ID_UNIVERSIDAD = d.ID_UNIVERSIDAD
                LEFT JOIN tbl_grado_academico e ON a.ID_GRADO = e.ID_GRADO
                LEFT JOIN tbl_estado_solicitud f ON a.ID_ESTADO = f.ID_ESTADO
                LEFT JOIN tbl_modalidad g ON a.ID_MODALIDAD = g.ID_MODALIDAD
                WHERE a.ID_SOLICITUD = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="dropzone-styles.css"> <!-- Enlace al archivo CSS -->
        
        

        <title>Seguimiento </title>

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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><?php echo $_SESSION["NOMBRE_USUARIO"] ?></a>
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
                            <h3 class="block-title">Emision de Opinion Razonada <small>Direccion de Educacion Superior</small></h3>
                        </div>
                        <div class="block-content">

                            <div class="row items-push">
                                <div class="col-md-4">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Detalles de la Solicitud</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-book-open"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form id="formulario" action="EmisionORFinal.php" method="post" enctype="multipart/form-data">
                                                <div class="form-group row">
                                                    <label class="col-12" for="nombre-carrera">Nombre de la Carrera</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="nombre-carrera" name="nombre_carrera" placeholder="Coord. Curricular de la Carrera" value="<?php echo htmlspecialchars($row['NOM_CARRERA']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="modalidad">Modalidad</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="modalidad" name="modalidad" placeholder="Descripción de la Modalidad" value="<?php echo htmlspecialchars($row['NOM_MODALIDAD']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="universidad">Universidad/Centro</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="universidad" name="universidad" placeholder="Nombre de la Universidad" value="<?php echo htmlspecialchars($row['NOM_UNIVERSIDAD']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="grado">Grado Académico</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="grado" name="grado" placeholder="Grado Académico" value="<?php echo htmlspecialchars($row['NOM_GRADO']); ?>" readonly required>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Equipo Encargado</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form action="be_forms_elements_bootstrap.html" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="form-group row">
                                                    <label class="col-12" for="example-text-input">Integrante #1</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="example-text-input" name="example-text-input" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="example-text-input">Integrante #2</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="example-text-input" name="example-text-input" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="example-email-input">Integrante #3</label>
                                                    <div class="col-12">
                                                        <input type="email" class="form-control" id="example-email-input" name="example-email-input" placeholder="" required>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-8">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Opinion Razonada Emitida</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-file"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">

                                            <div class="row">
                                                <div class="col-12">

                                                    <div id="RA-dropzone" class="dropzone">
                                                        <div class="dz-message"></div>
                                                        <div id="file-list">
                                                            <!-- Los archivos se cargarán aquí con JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="block-content block-content-sm block-content-full bg-body-light">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-alt-danger" id="backButton">
                                            <i class="si si-action-undo mr-5"></i> Regresar
                                        </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-alt-warning" id="btn-rechazar-DSA">
                                            Rechazar <i class="fa fa-times-rectangle ml-5"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-6">
                                        <button hidden type="button" class="btn btn-alt-danger" id="backButton">
                                            <i class="si si-action-undo mr-5"></i> Regresar
                                        </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-alt-primary" id="btn-aprobado-DSA">
                                            Aprobar y Enviar <i class="fa fa-check-square-o ml-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </main>



            <?php require_once("../MainFooter/MainFooter.php"); ?>

        </div>

        <?php require_once("../MainJs/MainJs.php"); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="DSA.js"></script>


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