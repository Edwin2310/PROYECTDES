<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["IdUsuario"])) {
    $id = isset($_GET['solicitud_id']) ? htmlspecialchars($_GET['solicitud_id']) : '';
    if ($id) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        $sql = "SELECT s.IdSolicitud, ts.NomTipoSolicitud, cat.NomCategoria, s.NumReferencia, s.Descripcion, s.NombreCarrera, g.NomGrado, m.NomModalidad,
                       uc.NomUniversidad, d.NomDepto, mu.NomMunicipio, u.NombreUsuario, s.NombreCompleto, s.CorreoElectronico, s.FechaIngreso, 
                       s.FechaModificacion, cat.CodArbitrios, c.NomCarrera, e.EstadoSolicitud
                FROM `proceso.tblSolicitudes` s
                LEFT JOIN `mantenimiento.tblcategorias` cat ON s.IdCategoria = cat.IdCategoria
                LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
                LEFT JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
                LEFT JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
                LEFT JOIN `mantenimiento.tbluniversidades` uc ON s.IdUniversidad = uc.IdUniversidad
                LEFT JOIN `mantenimiento.tbldeptos` d ON s.IdDepartamento = d.IdDepartamento
                LEFT JOIN `mantenimiento.tblmunicipios` mu ON s.IdMunicipio = mu.IdMunicipio
                LEFT JOIN `seguridad.tbldatospersonales` u ON s.IdUsuario = u.IdUsuario
                LEFT JOIN `mantenimiento.tblestadossolicitudes` e ON s.IdEstado = e.IdEstado
                LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON cat.IdTipoSolicitud = ts.IdTipoSolicitud
                WHERE s.IdSolicitud = :solicitud_id";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
$(document).ready(function() {
    $('#asignar-sesion-form').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío estándar del formulario

        if (!validateForm()) {
            return;
        }

        var formData = $(this).serialize(); // Obtener los datos del formulario
        console.log('Enviando datos:', formData); // Registro de datos enviados

        $.ajax({
            url: 'Guardar_Acuerdo.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Registro de respuesta
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Redirigir a la página deseada después de guardar
                        window.location.href = '../ConsultarSolicitudes/index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error de AJAX:', error); // Registro de error de AJAX
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
        var acuerdo = $('#acuerdo-admision').val();
        var pattern = /^\d{4}-\d{3}-\d{4}$/;

        if (!pattern.test(acuerdo) || acuerdo.length !== 13) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'El acuerdo de admisión debe seguir el formato: 1024-388-2024 y tener exactamente 13 caracteres.',
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
                                                <input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo htmlspecialchars($row['NombreCarrera'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="modalidad">Modalidad</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="modalidad" name="modalidad" value="<?php echo htmlspecialchars($row['NomModalidad'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-12" for="universidad">Universidad</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="universidad" name="universidad" value="<?php echo htmlspecialchars($row['NomUniversidad'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12" for="grado">Grado Académico</label>
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="grado" name="grado" value="<?php echo htmlspecialchars($row['NomGrado'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
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
                                                    <input type="text" class="form-control" id="acuerdo-admision" name="acuerdo_admision" maxlength="13">
                                                </div>
                                            </div>

                                            <input type="hidden" name="solicitud_id" value="<?php echo htmlspecialchars($row['IdSolicitud'], ENT_QUOTES, 'UTF-8'); ?>">

                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                            
                                            <div id="mensaje"></div>
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
                                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($row['NombreCompleto'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-12" for="email">Correo Electrónico</label>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['CorreoElectronico'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
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



