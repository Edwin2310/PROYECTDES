<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {

?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <!-- Bootstrap CSS -->

        <style>
            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #e4e4e4;
                color: black;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: black;
            }

            #div-centralized {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 20px;
            }

            .select2-selection--multiple {
                height: auto !important;
            }

            .basic-multiple {
                width: 100%;
                max-width: 600px;
                /* Ajusta esto según tus necesidades */
                min-width: 300px;
            }

            .select2-container {
                width: 100% !important;
            }
        </style>


        <title>Nuevo ingreso de Solicitud </title>

        <!-- Incluye Dropzone CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

        <!-- Incluye Dropzone JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

        <!-- Incluye JQUERY -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- SweetAlert CSS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="../NuevoIngresoSolicitud/obtener_municipio.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <h2 class="content-heading">Creación de Otros <small>Dirección de Educación Superior</small></h2>
                    <div class="row">
                        <div class="block-content">
                            <!-- Simple Wizard -->
                            <div class="js-wizard-simple block">
                                <!-- Step Tabs -->
                                <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab">1. Datos Personales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab">2. Datos del Documento</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab">3. Confirmación</a>
                                    </li>
                                </ul>
                                <!-- END Step Tabs -->

                                <!-- Form -->
                                <form id="wizard-form" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>">

                                    <!-- Steps Content -->
                                    <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                                        <!-- Step 1 -->

                                        <div class="tab-pane active" id="wizard-simple-step1" role="tabpanel">

                                            <div class="form-group row">
                                                <label class="col-12" for="NOMBRE_COMPLETO">Nombre Completo</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="NOMBRE_COMPLETO" name="NOMBRE_COMPLETO" placeholder="Coord. Curricular de la Carrera + Nombre completo" required oninput="validateForm(event)">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-12" for="EMAIL">Correo Electrónico</label>
                                                <div class="col-12">
                                                    <input type="email" class="form-control" id="EMAIL" name="EMAIL" placeholder="correoinstitucional@unitec.com" required oninput="validateForm(event)">
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
                                                        <label class="col-12" for="codigo-pago">Tipo de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="codigo-pago" name="codigo-pago">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="id_categoria">Categoría de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="id_categoria" name="id_categoria" required></select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label class="col-12" for="id_tipo_solicitud">Codigo de Pago</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="NOMBRE_COMPLETO" name="NOMBRE_COMPLETO" placeholder="830">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="col-12" for="Num_referencia">Cdigo de Referencia</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="Num_referencia" name="Num_referencia" placeholder="73352" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-12" for="id_carrera">Nombre de la Solicitud</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="Num_referencia" name="Num_referencia" placeholder="Ingenieria Mecatronica" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="id_grado_acad">Nombre de la Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="id_grado_acad" name="id_grado_acad">
                                                                <option value="0">UNITEC</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label class="col-12" for="id_grado_acad">Grado Académico</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="id_grado_acad" name="id_grado_acad">
                                                                <option value="0">Seleccione un grado académico</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6" id="div-departamento1">
                                                        <label class="col-12" for="Departamento1">Modalidad</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="Departamento1" name="Departamento[]" required>
                                                                <option value="0">Seleccione un Departamento</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6" id="div-departamento1">
                                                        <label class="col-12" for="Departamento1">SEDE</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="Departamento1" name="Departamento[]" required>
                                                                <option value="0">Seleccione un Departamento</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <label class="col-12" for="Descripcion_solicitud">Descripción de la Solicitud</label>
                                                <div class="col-12">
                                                    <textarea class="form-control" id="Descripcion_solicitud" name="Descripcion_solicitud" rows="6" placeholder="PRESENTACIÓN DE SOLICITUD DE REFORMA AL PLAN DE ESTUDIOS DE LA CARRERA DE INGENIERIA EN MECATRONICA, EN EL GRADO DE LICENCIATURA DE LA UNIVERSIDAD TECNOLOGICA CENTROAMERICANA, UNITEC." required oninput="validateTextarea(event)"></textarea>
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
                                                        <h4>Formato de Nombre de Archivo Requerido</h4>
                                                    </center>
                                                    <p>Por favor, asegúrate de seguir la siguiente nomenclatura al nombrar tus archivos. El nombre del archivo debe comenzar con uno de los prefijos especificados y luego incluir la fecha y un número secuencial. Aquí está el formato general:</p>
                                                    <p><strong>NOMBRE_YYYYMMDD_NNN</strong></p>
                                                    <ul>
                                                        <li><strong>NOMBRE</strong>: Es un prefijo que indica el tipo de archivo. Debe ser uno de los siguientes:
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
                                                    <p><strong>Ejemplos Permitidos:</strong></p>
                                                    <ul>
                                                        <li><code>DIAG_20240802_001</code></li>
                                                        <li><code>PLAN_20240802_002</code></li>
                                                        <li><code>PDOC_20240802_003</code></li>
                                                        <li><code>SOLI_20240802_004</code></li>
                                                    </ul>
                                                    <p>Si el nombre del archivo no sigue este formato, se considerará inválido y no se procesará correctamente.</p>
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
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script>
    Dropzone.autoDiscover = false; // Desactivar el auto-descubrimiento para personalizar

    // Crear un nuevo Dropzone
    var dropzoneDocuments = new Dropzone("#dropzone-documents", {
        url: "http://localhost/PROYECTDES1.1V/view/NuevoIngresoSolicitud/upload.php", // Asegúrate de que esta ruta sea correcta
        paramName: "file", // Nombre del archivo enviado al servidor
        maxFilesize: 10, // Tamaño máximo del archivo en MB
        uploadMultiple: true, // Permitir múltiples archivos
        parallelUploads: 10, // Número de subidas simultáneas
        addRemoveLinks: true, // Permitir eliminar archivos de la lista de carga
        dictRemoveFile: "Eliminar archivo", // Texto del enlace para eliminar
        dictDefaultMessage: "Arrastra los archivos aquí para subirlos o haz clic para seleccionarlos", // Texto para el área de Dropzone
        acceptedFiles: ".pdf,.doc,.docx,.xls", // Tipos de archivo permitidos
        autoProcessQueue: false, // Desactivar la subida automática
        init: function() {
            var totalFiles = 0; // Total de archivos a subir
            var uploadedFiles = 0; // Archivos subidos exitosamente

            this.on("sending", function(file, xhr, formData) {
                // Añadir campos adicionales al enviar
                formData.append("IdUsuario", document.querySelector("#IdUsuario").value);
                totalFiles++; // Incrementar el contador de archivos al comenzar la subida
            });

            this.on("addedfile", function(file) {
                var fileName = file.name;
                var now = new Date();
                var dateStr =
                    now.getFullYear() +
                    (now.getMonth() + 1).toString().padStart(2, "0") +
                    now.getDate().toString().padStart(2, "0"); // YYYYMMDD

                console.log("Fecha actual esperada: " + dateStr);

                // Expresiones regulares para cada tipo de documento
                var regexList = [
                    new RegExp(`^DIAG_${dateStr}_\\d+`), // Diagnóstico
                    new RegExp(`^PLAN_${dateStr}_\\d+`), // Plan de Estudios
                    new RegExp(`^PDOC_${dateStr}_\\d+`), // Planta Docente
                    new RegExp(`^SOLI_${dateStr}_\\d+`), // Solicitud
                ];

                var valid = regexList.some(function(regex) {
                    return regex.test(fileName);
                });

                if (!valid) {
                    this.removeFile(file);
                    alert(
                        "Formato de nombre de archivo no válido. Ejemplos permitidos: DIAG_" +
                        dateStr +
                        "_001, PLAN_" +
                        dateStr +
                        "_002, PDOC_" +
                        dateStr +
                        "_003, SOLI_" +
                        dateStr +
                        "_004."
                    );
                }
            });

            this.on("success", function(file, response) {
                console.log("Archivo subido con éxito:", response);
                uploadedFiles++; // Incrementar el contador de archivos subidos exitosamente
                if (uploadedFiles === totalFiles) {
                    // Todos los archivos se han subido
                    Swal.fire({
                        icon: "success",
                        title: "¡Solicitud Finalizada!",
                        text: "Archivos Subidos Exitosamente",
                        timer: 2000, // 2 segundos
                        timerProgressBar: true,
                        showConfirmButton: false, // Oculta el botón de confirmación
                    }).then(() => {
                        // Redirige a la URL después de que se cierra la alerta
                        window.location.href =
                            "http://localhost/PROYECTDES1.1V/view/ConsultarSolicitudes/index.php";
                    });
                }
            });

            this.on("error", function(file, response) {
                console.log("Error al subir el archivo:", response);
            });
        },
    });

    // Inicializa el wizard y botones
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('wizard-form');
        var btnNext = document.getElementById('btn-next');
        var btnBack = document.getElementById('btn-back'); // Botón "Atrás"
        var btnSubmit = document.getElementById('btn-submit');

        btnNext.addEventListener('click', function() {
            var activeTab = document.querySelector('.tab-pane.active');
            var stepIndex = Array.prototype.indexOf.call(activeTab.parentElement.children, activeTab);

            if (validateStep(stepIndex)) {
                if (stepIndex === 0) {
                    // Paso 1 - Enviar datos a save_combined.php
                    var data = new FormData();
                    var step1Inputs = document.querySelectorAll('#wizard-simple-step1 input, #wizard-simple-step1 select');
                    step1Inputs.forEach(function(input) {
                        data.append(input.name, input.value);
                    });
                    data.append('step', 'step1');

                    fetch('save_combined.php', {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result === 'step1_success') {
                                // Mover al siguiente paso (step 2)
                                switchTab('#wizard-simple-step2');
                                btnBack.style.display = 'none'; // Ocultar botón "Atrás" en el paso 2
                                btnSubmit.classList.add('d-none'); // Ocultar botón "Guardar Adjuntos"
                            } else {
                                // Manejar error
                                alert('Error al guardar datos del paso 1: ' + result);
                            }
                        })
                        .catch(error => console.error('Error:', error));

                } else if (stepIndex === 1) {
                    // Paso 2 - Enviar datos a save_combined.php
                    var data = new FormData();
                    var step2Inputs = document.querySelectorAll('#wizard-simple-step2 input, #wizard-simple-step2 select, #wizard-simple-step2 textarea');
                    step2Inputs.forEach(function(input) {
                        data.append(input.name, input.value);
                    });
                    data.append('step', 'step2');

                    fetch('save_combined.php', {
                            method: 'POST',
                            body: data
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result === 'step2_success' || result === 'success') {
                                // Mover al siguiente paso (step 3)
                                switchTab('#wizard-simple-step3');
                                btnBack.style.display = 'inline-block'; // Mostrar botón "Atrás" en el paso 3
                                btnSubmit.classList.remove('d-none'); // Mostrar botón "Guardar Adjuntos"
                            } else {
                                // Manejar error
                                alert('Error al guardar datos del paso 2: ' + result);
                            }
                        })
                        .catch(error => console.error('Error:', error));

                } else if (stepIndex === 2) {
                    // Paso 3 - Enviar archivos adjuntos
                    if (validateStep(stepIndex)) {
                        // Solo procesar la cola de archivos si la validación es exitosa
                        uploadFiles(); // Función para procesar la cola de archivos en Dropzone.js
                    } else {
                        // Mostrar alerta si hay campos vacíos
                        Swal.fire({
                            icon: 'error',
                            title: 'Campos Vacíos',
                            text: 'Por favor, completa todos los campos obligatorios antes de continuar.',
                        });
                    }
                }
            } else {
                // Mostrar alerta si hay campos vacíos
                Swal.fire({
                    icon: 'error',
                    title: 'Campos Vacíos',
                    text: 'Por favor, completa todos los campos obligatorios antes de continuar.',
                });
            }
        });

        btnBack.addEventListener('click', function() {
            var activeTab = document.querySelector('.tab-pane.active');
            var stepIndex = Array.prototype.indexOf.call(activeTab.parentElement.children, activeTab);

            if (stepIndex > 0) {
                // Volver al paso anterior
                var prevTabId = `#wizard-simple-step${stepIndex}`;
                switchTab(prevTabId);

                // Ocultar el botón "Atrás" si estamos en el primer paso
                if (stepIndex === 1) {
                    btnBack.style.display = 'none';
                }
                // Ocultar el botón "Guardar Adjuntos" si no estamos en el último paso
                btnSubmit.classList.add('d-none');
            }
        });

        function switchTab(targetTabId) {
            var currentActiveTab = document.querySelector('.nav-link.active');
            if (currentActiveTab) {
                currentActiveTab.classList.remove('active');
            }

            var targetTab = document.querySelector(`a[href="${targetTabId}"]`);
            if (targetTab) {
                targetTab.classList.add('active');
                $(targetTab).tab('show'); // Utiliza Bootstrap para mostrar el tab
            }

            var currentActivePane = document.querySelector('.tab-pane.active');
            if (currentActivePane) {
                currentActivePane.classList.remove('active');
                currentActivePane.classList.remove('show');
            }

            var targetPane = document.querySelector(targetTabId);
            if (targetPane) {
                targetPane.classList.add('active');
                targetPane.classList.add('show');
            }
        }

        function validateStep(stepIndex) {
            var isValid = true;

            if (stepIndex === 0) {
                // Validar campos del Paso 1
                var step1Inputs = document.querySelectorAll('#wizard-simple-step1 input, #wizard-simple-step1 select');
                step1Inputs.forEach(function(input) {
                    if (input.required && !input.value.trim()) {
                        isValid = false;
                    }
                });

            } else if (stepIndex === 1) {
                // Validar campos del Paso 2
                var step2Inputs = document.querySelectorAll('#wizard-simple-step2 input, #wizard-simple-step2 select, #wizard-simple-step2 textarea');
                step2Inputs.forEach(function(input) {
                    if (input.required && !input.value.trim()) {
                        isValid = false;
                    }
                });
            }

            return isValid;
        }
    });

    // Función para enviar archivos manualmente
    function uploadFiles() {
        dropzoneDocuments.processQueue(); // Procesar la cola de archivos
    }
</script>



<script src="../Seguridad//Bitacora/Bitacora.js"></script>
<script src="../NuevoIngresoSolicitud/obtener_categoria.js"></script>
<script src="../NuevoIngresoSolicitud/vali.js"></script>
<script src="../NuevoIngresoSolicitud/ValidacionesInputs.js"></script>