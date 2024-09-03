<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["ID_USUARIO"])) {
    $id = isset($_GET['solicitud_id']) ? htmlspecialchars($_GET['solicitud_id']) : '';
    if ($id) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        $sql = "SELECT s.ID_SOLICITUD, tp.NOM_TIPO, cat.NOM_CATEGORIA, s.NUM_REFERENCIA, s.DESCRIPCION, s.NOMBRE_CARRERA, g.NOM_GRADO, m.NOM_MODALIDAD,
                           uc.NOM_UNIVERSIDAD, d.NOM_DEPTO, mu.NOM_MUNICIPIO, u.NOMBRE_USUARIO, s.NOMBRE_COMPLETO, s.EMAIL, s.FECHA_INGRESO, 
                           s.FECHA_MODIFICACION, cat.COD_ARBITRIOS, c.NOM_CARRERA, e.ESTADO_SOLICITUD
                    FROM tbl_solicitudes s
                    LEFT JOIN tbl_tipo_solicitud tp ON s.ID_TIPO_SOLICITUD = tp.ID_TIPO_SOLICITUD
                    LEFT JOIN tbl_categoria cat ON s.ID_CATEGORIA = cat.ID_CATEGORIA
                    LEFT JOIN tbl_carrera c ON s.ID_CARRERA = c.ID_CARRERA
                    LEFT JOIN tbl_grado_academico g ON s.ID_GRADO = g.ID_GRADO
                    LEFT JOIN tbl_modalidad m ON s.ID_MODALIDAD = m.ID_MODALIDAD
                    LEFT JOIN tbl_universidad_centro uc ON s.ID_UNIVERSIDAD = uc.ID_UNIVERSIDAD
                    LEFT JOIN tbl_deptos d ON s.ID_DEPARTAMENTO = d.ID_DEPARTAMENTO
                    LEFT JOIN tbl_municipios mu ON s.ID_MUNICIPIO = mu.ID_MUNICIPIO
                    LEFT JOIN tbl_ms_usuario u ON s.ID_USUARIO = u.ID_USUARIO
                    LEFT JOIN tbl_estado_solicitud e ON s.ID_ESTADO = e.ID_ESTADO
                    WHERE s.ID_SOLICITUD = :solicitud_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':solicitud_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once("../MainHead/MainHead.php"); ?>
    <title>Asignar Sesión a Solicitud</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>

<script>
$(document).ready(function() {
    $('#asignar-sesion-form').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío estándar del formulario

        if (!validateForm()) {
            return;
        }

        var formData = $(this).serialize(); // Obtener los datos del formulario

        $.ajax({
            url: 'Guardar_Acuerdoaprob.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#mensaje').html('<p style="color: green;">' + response.message + '</p>');
                    
                    // Reemplazar la alerta estándar con SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Acuerdo guardado y estado actualizado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Redirigir después de aceptar
                        window.location.href = '../ConsultarSolicitudes/index.php';
                    });

                } else {
                    $('#mensaje').html('<p style="color: red;">' + response.message + '</p>');
                    
                    // Reemplazar la alerta estándar con SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                $('#mensaje').html('<p style="color: red;">Hubo un error al procesar la solicitud.</p>');
                
                // Reemplazar la alerta estándar con SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    function validateForm() {
        var acuerdo = $('#acuerdo-aprobacion').val();
        var pattern = /^\d{4}-\d{3}-\d{4}$/;

        if (!pattern.test(acuerdo) || acuerdo.length !== 13) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'El acuerdo de aprobación debe seguir el formato: 1024-388-2024 y tener exactamente 13 caracteres.',
                confirmButtonText: 'Aceptar'
            });
            return false;
        }
        return true;
    }
});
</script>



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
                        <h3 class="block-title">Asignar Acuerdo <small>Dirección de Educación Superior</small></h3>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-md-6">
                                <div class="block block-header-default">
                                    <div class="block-header mb-0 bg-body-dark" style="color: blue;">
                                        <h3 class="block-title">Detalles de la Solicitud</h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="form-group row">
                                            <label class="col-12" for="carrera">Nombre de la Carrera</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo htmlspecialchars($row['NOMBRE_CARRERA'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="modalidad">Modalidad</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="modalidad" name="modalidad" value="<?php echo htmlspecialchars($row['NOM_MODALIDAD'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-12" for="universidad">Universidad</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="universidad" name="universidad" value="<?php echo htmlspecialchars($row['NOM_UNIVERSIDAD'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="grado">Grado Académico</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="grado" name="grado" value="<?php echo htmlspecialchars($row['NOM_GRADO'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Asignar Acuerdo Form -->
                            <div class="col-md-6">
                                <div class="block block-header-default">
                                    <div class="block-header mb-0 bg-body-dark" style="color: blue;">
                                        <h3 class="block-title">Asignar Número de Acuerdo</h3>
                                    </div>
                                    <div class="block-content">
                                        <form id="asignar-sesion-form">
                                            <div class="form-group row">
                                                <label class="col-12" for="acuerdo-admision">Número de Acuerdo de Admisión</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="acuerdo-aprobacion" name="acuerdo_aprobacion" maxlength="13">
                                                </div>
                                            </div>

                                            <input type="hidden" name="solicitud_id" value="<?php echo htmlspecialchars($row['ID_SOLICITUD'], ENT_QUOTES, 'UTF-8'); ?>">

                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Remitente -->
                        <div class="row items-push">
                            <div class="col-md-6">
                                <div class="block block-header-default">
                                    <div class="block-header mb-0 bg-body-dark" style="color: blue;">
                                        <h3 class="block-title">Detalles del Remitente</h3>
                                    </div>
                                    <div class="block-content">
                                        <form action="be_forms_elements_bootstrap.html" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                            <div class="form-group row">
                                                <label class="col-12" for="nombre-completo">Nombre Completo</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($row['NOMBRE_COMPLETO'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-12" for="email">Correo Electrónico</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['EMAIL'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

    <?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>


