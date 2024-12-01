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

        try {
            // Llamada al procedimiento almacenado para obtener los detalles y el número de registro
            $sql = "CALL `proceso.splEntregaPlanEstudios`(:id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el primer resultado (detalles de la solicitud)
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si se encuentra el archivo, almacenar la ruta
            $filePath = $row['PlanEstudios'] ?? null;

            // Avanzar a la segunda consulta (número de registro)
            $stmt->nextRowset();
            $obsRow = $stmt->fetch(PDO::FETCH_ASSOC);

            // Obtener el número de registro
            $NUM = $obsRow['NumRegistro'] ?? '';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
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
        <script src="SubsanacionObservaciones.js"></script>


        <title>Analisis Curricular </title>

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
                            <h3 class="block-title">Analisis Curricular <small>Direccion de Educacion Superior</small></h3>
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
                                                    <label class="col-12" for="nombre-carrera">Numero de Referencia</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="nombre-carrera" name="nombre_carrera" placeholder="" value="<?php echo htmlspecialchars($NUM); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="nombre-carrera">Nombre de la Carrera</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="nombre-carrera" name="nombre_carrera" placeholder="" value="<?php echo htmlspecialchars($row['NomCarrera']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="modalidad">Modalidad</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="modalidad" name="modalidad" placeholder="" value="<?php echo htmlspecialchars($row['NomModalidad']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="universidad">Universidad/Centro</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="universidad" name="universidad" placeholder="" value="<?php echo htmlspecialchars($row['NomUniversidad']); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="grado">Grado Académico</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="grado" name="grado" placeholder="" value="<?php echo htmlspecialchars($row['NomGrado']); ?>" readonly required>
                                                    </div>
                                                </div>

                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>

                                            </form>
                                        </div>
                                    </div>

                                </div>


                                <div class="col-md-8">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Plan de estudio Registrado Firmado y Foliado</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-book"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Dropzone para descargar archivos -->
                                                    <div id="entrega-download" class="dropzone">
                                                        <div id="file-list">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <br>
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
        <script src="entregaplan.js"></script>


    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>


<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->