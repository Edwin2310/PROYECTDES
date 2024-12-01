<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Funciones_Solicitud.php');
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {
    // Obtener el ID del usuario de la sesión
    $idUsuario = $_SESSION['IdUsuario'] ?? null;
    $idUniversidadUsuario = obtenerIdUniversidadUsuario($idUsuario); // Llamar a la nueva función para obtener el idUniversidad

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 58; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 7) {
        // Verificar permisos
        if (verificarPermiso($id_rol, $id_objeto)) {
            $accion = "Accedió al módulo.";

            // Registrar en la bitácora
            registrobitaevent($id_usuario, $id_objeto, $accion);
        } else {
            $accion = "Acceso denegado.";

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
        <title>Nuevo ingreso de Solicitud </title>

        <!-- Incluye Dropzone CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

        <!-- Incluye Dropzone JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

        <!-- Incluye JQUERY -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- SweetAlert CSS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Incluye los Estilos -->
        <link rel="stylesheet" href="./css/estilos.css">

        <!-- Incluye libreria Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Incluye libreria Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

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
                    <h2 class="content-heading">Creacion de Centro <small>Dirección de Educación Superior</small></h2>
                    <div class="row">
                        <div class="block-content">
                            <!-- Simple Wizard -->
                            <div class="js-wizard-simple block">
                                <!-- Step Tabs -->
                                <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="step1-tab" href="#wizard-simple-step1" data-toggle="tab" aria-expanded="true">1. Datos Personales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link disabled" id="step2-tab" href="#wizard-simple-step2" data-toggle="tab" aria-expanded="false">2. Datos del Documento</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link disabled" id="step3-tab" href="#wizard-simple-step3" data-toggle="tab" aria-expanded="false">3. Confirmación</a>
                                    </li>
                                </ul>
                                <!-- END Step Tabs -->

                                <!-- Form -->
                                <form id="wizard-form" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>">
                                    <input type="hidden" id="IdUniversidadUsuario" value="<?php echo htmlspecialchars($idUniversidadUsuario, ENT_QUOTES, 'UTF-8'); ?>">

                                    <!-- Steps Content -->
                                    <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                                        <!-- Step 1 -->
                                        <div class="tab-pane active" id="wizard-simple-step1" role="tabpanel">
                                            <div class="form-group row">
                                                <label class="col-12" for="NombreCompleto">Nombre Completo</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="NombreCompleto" name="NombreCompleto" maxlength="50" placeholder="Coord. Curricular de la Carrera + Nombre completo" required oninput="validateForm(event)">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-12" for="CorreoElectronico">Correo Electrónico</label>
                                                <div class="col-12">
                                                    <input type="CorreoElectronico" class="form-control" id="CorreoElectronico" name="CorreoElectronico" maxlength="50" placeholder="correoinstitucional@unitec.com" required oninput="validateForm(event)">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-control-plaintext">Nota: Al correo previamente escrito se le estará enviando las notificaciones de cambio de estados con respecto a la solicitud presentada.</div>
                                            </div>
                                        </div>
                                        <!-- END Step 1 -->

                                        <!-- Step 2 -->
                                        <div class="tab-pane" id="wizard-simple-step2" role="tabpanel">
                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="IdTiposolicitud">Tipo de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="IdTiposolicitud" name="IdTiposolicitud" required readonly>
                                                                <?php echo obtenerTipoSolicitud($usuario); ?>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="IdCategoria">Categoría de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="IdCategoria" name="IdCategoria" required>
                                                                <option value="0">Seleccionar Categoría</option>
                                                                <?php echo obtenerCategoriaSolicitud($usuario); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">

                                                    <div class="col-md-3">
                                                        <label class="col-12" for="CodigoPago">Codigo de Pago</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="CodigoPago" name="CodigoPago" readonly required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="col-12" for="CodigoReferencia">Codigo de Referencia</label>
                                                        <div class="col-12">
                                                            <input type="number" class="form-control" id="CodigoReferencia" name="CodigoReferencia" placeholder="73352" required>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="NombreCentro">Nombre Centro</label>
                                                        <div class="col-12">
                                                            <input class="form-control" type="text" id="NombreCentro" name="NombreCentro" placeholder="Centro" maxlength="70" required>
                                                            </input>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="IdUniversidad">Universidad</label>
                                                        <div class="col-12">
                                                            <select class="form-control readonly-select" id="IdUniversidad" name="IdUniversidad" readonly>
                                                                <option value="0" disabled selected style="display:none;">Seleccionar Universidad</option>
                                                                <?php echo obtenerUniversidades($usuario); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="IdTipoCentro">Tipo de Centro</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="IdTipoCentro" name="IdTipoCentro">
                                                                <option value="0">Seleccionar Centro</option>
                                                                <?php echo obtenerTipoCentro($usuario); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6" id="div-departamento1">
                                                        <label class="col-12" for="IdDepartamento">Departamento</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="IdDepartamento" name="IdDepartamento" onchange="cargarMunicipios(this.value)" required>
                                                                <option value="0">Seleccionar Departamento</option>
                                                                <?php echo obtenerDepartamentos($usuario); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6" id="div-municipio1">
                                                        <label class="col-12" for="IdMunicipio">Municipio</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="IdMunicipio" name="IdMunicipio" required>
                                                                <option value="0">Seleccionar Municipio</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="col-12" for="DescripcionSolicitud">Descripción de la Solicitud</label>
                                                    <div class="col-12">
                                                        <textarea class="form-control" id="DescripcionSolicitud" name="DescripcionSolicitud" rows="6" maxlength="230" placeholder="PRESENTACIÓN DE SOLICITUD DE REFORMA AL PLAN DE ESTUDIOS DE LA CARRERA DE INGENIERIA EN MECATRONICA, EN EL GRADO DE LICENCIATURA DE LA UNIVERSIDAD TECNOLOGICA CENTROAMERICANA, UNITEC." required oninput="validateTextarea(event)"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 2 -->


                                        <!-- Step 3 -->
                                        <div class="tab-pane" id="wizard-simple-step3" role="tabpanel">
                                            <div class="form-group">

                                                <!-- Campo oculto para el ID del usuario -->
                                                <input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>">
                                                <!-- Área de Dropzone para subir documentos -->
                                                <div class="form-group">
                                                    <b>
                                                        <center>
                                                            <h4 style="background-color: #f4f4f4; padding: 10px; border-radius: 5px;">Adjuntar Documentos</h4>
                                                        </center>
                                                    </b>
                                                    <div id="dropzone-documents" class="dropzone"></div>
                                                </div>

                                                <!-- Nota de archivo -->
                                                <div id="formato-nombre-archivo">
                                                    <center>
                                                        <h4>Formato de Nombre de Archivo</h4>
                                                    </center>
                                                    <p>Se le asignará automaticamente la correspondiente nomenclatura al subir tus archivos, con el siguiente formato general:</p>
                                                    <p><strong>NOMBRE_YYYYMMDD_NNN</strong></p>
                                                    <ul>
                                                        <li><strong>NOMBRE</strong>: Es un prefijo que indica el tipo de archivo. Los cuáles son los siguientes:
                                                            <ul>
                                                                <li><code>DIAG</code> para Diagnóstico</li>
                                                                <li><code>PLAN</code> para Plan de Estudios</li>
                                                                <li><code>PDOC</code> para Planta Docente</li>
                                                                <li><code>SOLI</code> para Solicitud</li>
                                                            </ul>
                                                        </li>
                                                        <li><strong>YYYYMMDD</strong>: La fecha en formato año (YYYY), mes (MM) y día (DD) cuando se carga el archivo.</li>
                                                        <li><strong>NNN</strong>: Un número secuencial de tres dígitos para diferenciar entre archivos cargados en la misma fecha.</li>
                                                    </ul>
                                                    <p><strong>Ejemplos:</strong></p>
                                                    <ul>
                                                        <li><code>DIAG_20240802_001</code></li>
                                                        <li><code>PLAN_20240802_002</code></li>
                                                        <li><code>PDOC_20240802_003</code></li>
                                                        <li><code>SOLI_20240802_004</code></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 3 -->
                                    </div>
                                    <!-- END Steps Content -->

                                    <!-- Steps Navigation -->
                                    <div class="block-content block-content-sm block-content-full bg-body-light">
                                        <div class="row">
                                            <div class="col-6 text-left">
                                                <!-- Botón de "Atrás" -->
                                                <button type="button" class="btn btn-alt-secondary" id="btn-back" style="display: none;">
                                                    <i class="fa fa-chevron-left mr-5"></i> Atrás
                                                </button>
                                            </div>
                                            <div class="col-6 text-right">
                                                <!-- Botón de "Siguiente" -->
                                                <button type="button" class="btn btn-alt-primary" id="btn-next">
                                                    <i class="fa fa-chevron-right mr-5"></i> Siguiente
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Steps Navigation -->

                                </form>
                                <!-- END Form -->

                            </div>
                            <!-- END Simple Wizard -->
                        </div>
                    </div>
                    <!-- END Simple Wizards -->
                </div>
            </main>

            <?php require_once("../MainFooter/MainFooter.php"); ?>

        </div>

        <?php require_once("../MainJs/MainJs.php"); ?>


    </body>

    </html>
<?php
    //Validacion de segurida de inicio de sesion
    //Si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<!-- Incluye los JS -->
<script src="../NuevoIngresoSolicitud/js/obtener_municipio.js"></script>
<script src="../NuevoIngresoSolicitud/js/Guardar_Adjuntos.js"></script>
<script src="../Seguridad//Bitacora/Bitacora.js"></script>
<script src="../NuevoIngresoSolicitud/js/obtener_categoria.js"></script>
<script src="../NuevoIngresoSolicitud/js/obtener_codarbitrio.js"></script>
<script src="../NuevoIngresoSolicitud/js/obtener_idUniversidad.js"></script>
<script src="../NuevoIngresoSolicitud/js/vali.js"></script>
<script src="../NuevoIngresoSolicitud/js/ValidacionesInputs.js"></script>
<script src="../NuevoIngresoSolicitud/js/municipios.js"></script>