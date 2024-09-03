<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["ID_USUARIO"])) {
    $conexion = new Conectar();
    $conexion = $conexion->Conexion();

    if (isset($_GET['solicitud_id'])) {
        $solicitud_id = $_GET['solicitud_id'];
        $tipo_solicitud = $_GET['tipo_solicitud'];
        $categoria = $_GET['categoria'];
        $descripcion = $_GET['descripcion'];
        $carrera = $_GET['carrera'];
        $grado_academico = $_GET['grado_academico'];
        $nombre_completo = $_GET['nombre_completo'];
        $modalidad = $_GET['modalidad'];
        $universidad = $_GET['universidad'];
        $departamento = $_GET['departamento'];
        $municipio = $_GET['municipio'];
        $usuario = $_GET['usuario'];
        $email = $_GET['email'];
        $fecha_ingreso = $_GET['fecha_ingreso'];
        $fecha_modificacion = $_GET['fecha_modificacion'];
        $cod_pago = $_GET['codigo_pago'];
        $estado = $_GET['estado'];

        // Validación de campos vacíos
        $campos_obligatorios = [
            'tipo_solicitud' => $tipo_solicitud,
            'categoria' => $categoria,
            'descripcion' => $descripcion,
            'carrera' => $carrera,
            'grado_academico' => $grado_academico,
            'nombre_completo' => $nombre_completo,
            'modalidad' => $modalidad,
            'universidad' => $universidad,
            'departamento' => $departamento,
            'municipio' => $municipio,
            'usuario' => $usuario,
            'email' => $email,
            'fecha_ingreso' => $fecha_ingreso,
            'fecha_modificacion' => $fecha_modificacion,
            'cod_pago' => $cod_pago
        ];

        $campos_vacios = [];
        foreach ($campos_obligatorios as $campo => $valor) {
            if (empty($valor)) {
                $campos_vacios[] = $campo;
            }
        }

        if (!empty($campos_vacios)) {
            $_SESSION['error'] = 'Los siguientes campos son obligatorios y están vacíos: ' . implode(', ', $campos_vacios);
            header("Location: formulario.php");
            exit();
        }

        // Verifica si se ha enviado una solicitud para actualizar el estado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado_id'])) {
            $estado_id = $_POST['estado_id']; // Estado a actualizar (en este caso, 4)

            // Actualiza el estado en la base de datos
            $query = "UPDATE tbl_solicitudes SET ID_ESTADO = :estado_id WHERE ID_SOLICITUD = :solicitud_id";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':estado_id', $estado_id, PDO::PARAM_INT);
            $stmt->bindParam(':solicitud_id', $solicitud_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $success = false;
            }
        }
    } else {
        header("Location: index.php");
        exit();
    }


    // Obtener el ID_SESION basado en el ID_SOLICITUD
    $id_solicitud = $_GET['solicitud_id']; // O puedes usar $_SESSION si corresponde
    $sql = "SELECT ID_SESION FROM tbl_sesionctc WHERE ID_SOLICITUD = :id_solicitud";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_solicitud', $id_solicitud);
    $stmt->execute();
    $id_sesion = $stmt->fetchColumn();

    $conn = null;



?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>ASIGNAR DICTAMEN CTC</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .section-title {
                background-color: #003399;
                color: white;
                padding: 10px;
                font-weight: bold;
            }

            .form-section {
                border: 1px solid #003399;
                padding: 15px;
                margin-bottom: 20px;
            }

            .btn-submit {
                background-color: #003399;
                color: white;
                font-weight: bold;
            }

            .btn-submit:hover {
                background-color: #002080;
            }
        </style>
        <!-- Incluir SweetAlert2 desde un CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form id="formulario" action="EmisionORFinal.php" method="post" enctype="multipart/form-data">
                                                <div class="form-group row">
                                                    <label class="col-12" for="nombre-carrera">Nombre de la Carrera</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="nombre-carrera" name="nombre_carrera" value="<?php echo htmlspecialchars($carrera, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="modalidad">Modalidad</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="modalidad" name="modalidad" value="<?php echo htmlspecialchars($modalidad, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="universidad">Universidad/Centro</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="universidad" name="universidad" value="<?php echo htmlspecialchars($universidad, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="grado">Grado Académico</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="grado" name="grado" value="<?php echo htmlspecialchars($grado_academico, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Datos del Remitente</h3>
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
                                                        <input type="text" class="form-control" id="example-text-input" name="example-text-input" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="example-email-input">Correo Electronico</label>
                                                    <div class="col-12">
                                                        <input type="email" class="form-control" id="example-email-input" name="example-email-input" placeholder="" required>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Asignar Dictamen a Solicitud</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form id="dictamen-form" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="row items-push">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-md-12" for="example-text-input">Dictamen CTC</label>
                                                            <br>
                                                            <br>

                                                            <label class="col-md-12" for="example-email-input">Dictaminado como</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="form-group row">

                                                            <div class="col-12">
                                                                <input type="hidden" id="idSesion" value="<?php echo htmlspecialchars($id_sesion); ?>">
                                                                <!--   <p id="mostrarIdSesion"></p> -->
                                                                <input type="text" class="form-control" id="example-text-input" name="dictamen" placeholder="1024-386-2024" required>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <div class="col-12">
                                                                <select class="form-control" id="example-select" name="estado">
                                                                    <option value="0">Seleccione</option>
                                                                    <?php
                                                                    session_start();
                                                                    include("../../../config/conexion.php");
                                                                    $conexion = new Conectar();
                                                                    $conn = $conexion->Conexion();
                                                                    if (!$conn) {
                                                                        die("Conexión fallida: " . $conn->errorInfo()[2]);
                                                                    }
                                                                    $sql = "SELECT ID_ESTADO_DICTAMEN, ESTADO_CTC FROM tbl_dictamen_estados";
                                                                    $result = $conn->query($sql);
                                                                    if ($result) {
                                                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value='" . htmlspecialchars($row['ID_ESTADO_DICTAMEN'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['ESTADO_CTC'], ENT_QUOTES, 'UTF-8') . "</option>";
                                                                        }
                                                                    } else {
                                                                        echo "Error: " . $conn->errorInfo()[2];
                                                                    }
                                                                    $conn = null;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Comision</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form id="comision-form" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="row items-push">
                                                    <div class="col-md-5 text-center">
                                                        <br>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-md-6 justify-content-center " for="example-text-input">COORDINADOR DE LA COMISIÓN</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">

                                                            <div class="col-12">
                                                                <select class="form-control" id="example-select" name="centro">
                                                                    <option value="0">Seleccione Una</option>
                                                                    <option value="14">UNITEC</option>
                                                                    <option value="20">UTH</option>
                                                                    <option value="1">UNAH</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="block block-header-default">
                                        <div class="block-header mb-0 bg-body-dark">
                                            <h3 class="block-title">Observaciones de la comision</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-user"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <form id="observaciones-form" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <textarea class="form-control" id="example-textarea-input" name="observaciones" rows="3" placeholder="Obeservaciones de la comision" required></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <div class="block block-header-default">
                                        <div class="block-content">
                                            <form action="uploadCTC.php" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                                <div class="form-group row justify-content-center">
                                                    <div class="col-md-8">
                                                        <!-- aqui pones el dropzone de edwin -->

                                                        <div class="tab-pane" id="wizard-simple-step3" role="tabpanel">
                                                            <div class="form-group ">

                                                                <!-- Campo oculto para el ID del usuario -->
                                                                <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['ID_USUARIO']; ?>">
                                                                <!-- Área de Dropzone para subir documentos -->
                                                                <div class="form-group">
                                                                    <b>
                                                                        <center>
                                                                            <h4 style="background-color: #f4f4f4; padding: 10px; border-radius: 5px;">Adjuntar Dictamen del CTC</h4>
                                                                        </center>
                                                                    </b>
                                                                    <div id="dropzone-documents" class="dropzone"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="block-content block-content-sm block-content-full bg-body-light">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-alt-info" onclick="window.location.href='../MantenimientoSolicitudes/DictamenCTC_Asignar.php';" id="backButton">
                                            <i class="si si-action-undo mr-5"></i> Regresar
                                        </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-alt-primary" id="btnEnviar">
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
        <script src="../../public/assets/js/codebase.core.min.js"></script>
        <script src="../../public/assets/js/codebase.app.min.js"></script>
        <script src="../../public/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../../public/assets/js/pages/be_forms_validation.min.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: ../../index.php");
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluye Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<!-- Incluye Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>



<script>
    // Configuración de Dropzone
    Dropzone.autoDiscover = false; // Desactiva la autodetección de Dropzone

    document.addEventListener('DOMContentLoaded', function() {
        var myDropzone = new Dropzone("#dropzone-documents", {
            url: "uploadCTC.php", // URL a la que se envían los archivos
            method: "post",
            paramName: "dictamen", // Nombre del parámetro que recibe el archivo en PHP
            maxFilesize: 10, // Tamaño máximo del archivo en MB
            acceptedFiles: ".pdf,.doc,.docx", // Tipos de archivos aceptados
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra los archivos aquí para subirlos",
            success: function(file, response) {
                console.log("Archivo subido con éxito:", response);
            },
            error: function(file, response) {
                console.log("Error al subir el archivo:", response);
            }
        });

        // Maneja el envío del formulario
        document.getElementById('btnEnviar').addEventListener('click', function() {
            var id_usuario = document.getElementById('id_usuario').value;
            var id_solicitud = "<?php echo htmlspecialchars($solicitud_id, ENT_QUOTES, 'UTF-8'); ?>"; // Valor del ID de solicitud desde PHP

            // Añade el ID_USUARIO y el ID_SOLICITUD a los archivos que se están enviando
            myDropzone.getAcceptedFiles().forEach(function(file) {
                var formData = new FormData();
                formData.append("dictamen", file);
                formData.append("id_usuario", id_usuario);
                formData.append("id_solicitud", id_solicitud);

                // Envía los datos adicionales a través de AJAX
                fetch('uploadCTC.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                    .then(data => {
                        console.log('Éxito:', data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnEnviar').addEventListener('click', function() {
            const dictamenForm = document.getElementById('dictamen-form');
            const comisionForm = document.getElementById('comision-form');
            const observacionesForm = document.getElementById('observaciones-form');

            // Recolectar datos de los formularios
            const idSesion = dictamenForm.querySelector('#idSesion').value;
            const dictamen = dictamenForm.querySelector('input[name="dictamen"]').value;
            const estado = dictamenForm.querySelector('select[name="estado"]').value;
            const centro = comisionForm.querySelector('select[name="centro"]').value;
            const observaciones = observacionesForm.querySelector('textarea[name="observaciones"]').value;
            const idUsuario = document.getElementById('id_usuario').value;

            // Validar datos
            if (!idSesion || !dictamen || !estado || !centro || !observaciones) {
                alert('Todos los campos deben estar llenos.');
                return;
            }

            // Enviar datos al servidor
            fetch('inser_dictamenctc.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'idSesion': idSesion,
                        'dictamen': dictamen,
                        'estado': estado,
                        'centro': centro,
                        'observaciones': observaciones,
                        'id_usuario': idUsuario
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Dictamen asignado correctamente.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Redirigir después de cerrar SweetAlert
                            window.location.href = '../MantenimientoSolicitudes/DictamenCTC_Asignar.php';
                        });
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al enviar los datos.');
                });
        });
    });
</script>


<!-- <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Obtén el valor del campo oculto
    var idSesion = document.getElementById("idSesion").value;

    // Muestra el valor en el elemento deseado
    document.getElementById("mostrarIdSesion").textContent = "ID de Sesión: " + idSesion;
  });
</script>
 -->