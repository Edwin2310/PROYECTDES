<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["IdUsuario"])) {

    $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

    if ($id) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Consulta para obtener los detalles de la solicitud y la ruta del archivo adjunto
        $sql = "SELECT a.IdSolicitud, a.NombreCompleto, a.CorreoElectronico, b.NomCarrera, c.NomCategoria, 
                       d.NomUniversidad, e.NomGrado, f.EstadoSolicitud, g.NomModalidad,
                       adj.PlanEstudios, adj.Solicitud, adj.PlantaDocente, adj.Diagnostico
                FROM `proceso.tblsolicitudes` a 
                LEFT JOIN `mantenimiento.tblcarreras` b ON a.IdCarrera = b.IdCarrera
                LEFT JOIN `mantenimiento.tblcategorias` c ON a.IdCategoria = c.IdCategoria
                LEFT JOIN `mantenimiento.tbluniversidades` d ON a.IdUniversidad = d.IdUniversidad
                LEFT JOIN `mantenimiento.tblgradosacademicos` e ON a.IdGrado = e.IdGrado
                LEFT JOIN `mantenimiento.tblestadossolicitudes` f ON a.IdEstado = f.IdEstado
                LEFT JOIN `mantenimiento.tblmodalidades` g ON a.IdModalidad = g.IdModalidad
                LEFT JOIN `documentos.tblarchivosadjuntos` adj ON a.IdSolicitud = adj.IdSolicitud
                WHERE a.IdSolicitud = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el archivo, almacenar la ruta en variables distintas
        $filePathPlanEstudios = $row['PlanEstudios'] ?? null;
        $filePathSolicitud = $row['SOLICITUD'] ?? null;
        $filePathPlantaDocente = $row['PlantaDocente'] ?? null;
        $filePathDiagnostico = $row['Diagnostico'] ?? null;

        // Consulta para obtener la última observación de la solicitud
        $obsSql = "SELECT Observacion
                   FROM `documentos.tblobservaciones`
                   WHERE IdSolicitud = :id 
                   ORDER BY FechaObservacion DESC 
                   LIMIT 1";

        $obsStmt = $conn->prepare($obsSql);
        $obsStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $obsStmt->execute();
        $obsRow = $obsStmt->fetch(PDO::FETCH_ASSOC);

        // Obtener la última observación
        $observaciones = $obsRow['Observacion'] ?? '';
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
        <script src="SubsanarDocumentos.js"></script>


        <title>Subsanación de Documentos | SIGAES </title>

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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><?php echo $_SESSION["NombreUsuario"] ?></a>
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
                            <h3 class="block-title">Subsanación de Documentos <small>Direccion de Educacion Superior</small></h3>
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

                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Observaciones</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-binoculars"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="row">
                                                <div class="col-md-6 justify-content-center">
                                                    <div class="col-12">
                                                        <textarea class="form-control" id="example-textarea-input" name="example-textarea-input" style="text-transform:uppercase" rows="16" placeholder="" readonly required><?php echo htmlspecialchars($observaciones); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Dropzone para descargar archivos -->
                                                    <div id="download-dropzone" class="dropzone">
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

                            <div class="row items-push">
                                <div class="col-md-4">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Detalles del Remitente</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form action="be_forms_elements_bootstrap.html" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="form-group row">
                                                    <label class="col-12" for="example-text-input">Nombre Completo</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="nombre-completo" name="NombreCompleto" placeholder="" value="<?php echo htmlspecialchars($row['NombreCompleto']); ?>" readonly required>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-12" for="example-CorreoElectronico-input">Correo Electronico</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="" value="<?php echo htmlspecialchars($row['CorreoElectronico']); ?>" readonly required>
                                                    </div>
                                                </div>
                                            </form>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Subir nueva version o versiones de documentos</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-cloud-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form action="be_forms_elements_bootstrap.html" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">

                                                            <div id="dropzone-documents" class="dropzone"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                            </form>
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
                                        <button type="button" class="btn btn-alt-primary" id="btn-EnviarArchivos">
                                            Enviar <i class="fa fa-save ml-5"></i>
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
        <script src="SubsanarDocumentos.js"></script>


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