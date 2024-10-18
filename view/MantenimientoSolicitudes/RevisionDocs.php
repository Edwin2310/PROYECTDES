<?php
require_once("../../config/conexion.php");

if (isset($_SESSION["IdUsuario"])) {
?>
    <?php
    $id = isset($_GET['solicitud_id']) ? htmlspecialchars($_GET['solicitud_id']) : '';
    if ($id) {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        $sql = "SELECT s.IdSolicitud, ts.NomTipoSolicitud, cat.NomCategoria, s.NumReferencia, s.Descripcion, 
                       s.NombreCarrera, g.NomGrado, m.NomModalidad, uc.NomUniversidad, d.NomDepto, 
                       mu.NomMunicipio, u.NombreUsuario, s.NombreCompleto, s.CorreoElectronico, s.FechaIngreso,
                       s.FechaModificacion, cat.CodArbitrios, c.NomCarrera, e.EstadoSolicitud
                FROM `proceso.tblSolicitudes` s
                LEFT JOIN `mantenimiento.tblcategorias` cat ON s.IdCategoria = cat.IdCategoria
                LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON cat.IdTiposolicitud = ts.IdTiposolicitud 
                LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
                LEFT JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
                LEFT JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
                LEFT JOIN `mantenimiento.tbluniversidadescentros` uc ON s.IdUniversidad = uc.IdUniversidad
                LEFT JOIN `mantenimiento.tbldeptos` d ON s.IdDepartamento = d.IdDepartamento
                LEFT JOIN `mantenimiento.tblmunicipios` mu ON s.IdMunicipio = mu.IdMunicipio
                LEFT JOIN `seguridad.tbldatospersonales` u ON s.IdUsuario = u.IdUsuario
                LEFT JOIN `mantenimiento.tblestadossolicitudes` e ON s.IdEstado = e.IdEstado
                WHERE s.IdSolicitud = :solicitud_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':solicitud_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $success = false; // Inicializa la variable de éxito
    $mensaje = ''; // Inicializa la variable del mensaje

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado_id'])) {
        $estado_id = $_POST['estado_id'];

        if (isset($_POST['confirmar_solicitud']) && $_POST['confirmar_solicitud'] === 'yes') {
            $query = "CALL `proceso.splEstadoSolicitudActualizar`(:solicitud_id, :estado_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':solicitud_id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado_id', $estado_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success = true;
                $mensaje = 'Solicitud confirmada exitosamente'; // Mensaje para actualización de estado
            }
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_solicitud'])) {
        // Captura los datos del formulario
        $tipo_solicitud = $_POST['tipo_solicitud'];
        $categoria = $_POST['categoria'];
        $carrera = $_POST['carrera'];
        $grado_academico = $_POST['grado_academico'];
        $modalidad = $_POST['modalidad'];
        $universidad = $_POST['universidad'];
        $departamento = $_POST['departamento'];
        $municipio = $_POST['municipio'];
        $descripcion = $_POST['descripcion'];

        // Llamar al procedimiento almacenado
        $update_query = "CALL `proceso.splRevisionDocsActualizar`(:solicitud_id, :tipo_solicitud, :categoria, :carrera, :grado_academico, :modalidad, :universidad, :departamento, :municipio, :descripcion)";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':solicitud_id', $id, PDO::PARAM_INT);
        $update_stmt->bindParam(':tipo_solicitud', $tipo_solicitud, PDO::PARAM_INT);
        $update_stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $update_stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $update_stmt->bindParam(':grado_academico', $grado_academico, PDO::PARAM_INT);
        $update_stmt->bindParam(':modalidad', $modalidad, PDO::PARAM_INT);
        $update_stmt->bindParam(':universidad', $universidad, PDO::PARAM_INT);
        $update_stmt->bindParam(':departamento', $departamento, PDO::PARAM_INT);
        $update_stmt->bindParam(':municipio', $municipio, PDO::PARAM_INT);
        $update_stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

        if ($update_stmt->execute()) {
            $success = true; // Indica que la actualización fue exitosa
            $mensaje = 'Solicitud actualizada exitosamente'; // Mensaje para actualización de solicitud
        }
    }
    ?>

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Revisión de Documentación</title>
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
                    <h2 class="content-heading">Revisión de Documentación <small>Dirección de Educación Superior</small></h2>
                    <div class="row">
                        <div class="block-content">
                            <div class="js-wizard-simple block">
                                <form id="solicitudForm" action="" method="post">
                                    <input type="hidden" name="solicitud_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="estado_id" value="4"> <!-- Estado que deseas actualizar -->
                                    <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['IdUsuario']; ?>">

                                    <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                                        <div class="tab-pane active" id="wizard-simple-step2" role="tabpanel">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="tipo_solicitud">Tipo de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="tipo_solicitud" name="tipo_solicitud">
                                                                <?php
                                                                // Consulta para obtener los tipos de solicitud
                                                                $query_tipo = "SELECT TipoSolicitud FROM `proceso.tblsolicitudes`";
                                                                $stmt_tipo = $conn->prepare($query_tipo);
                                                                $stmt_tipo->execute();

                                                                // Recorre cada tipo de solicitud y crea las opciones del select
                                                                while ($tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($tipo['IdTiposolicitud'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    // Verifica que $row['TipoSolicitud'] esté definido y sea igual al IdTiposolicitud
                                                                    if (isset($row['TipoSolicitud']) && $tipo['IdTiposolicitud'] == $row['TipoSolicitud']) {
                                                                        echo ' selected'; // Marca como seleccionado si coinciden
                                                                    }
                                                                    echo '>' . htmlspecialchars($tipo['TipoSolicitud'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="categoria">Categoría de Solicitud</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="categoria" name="categoria">
                                                                <?php
                                                                $query_categoria = "SELECT IdCategoria, NomCategoria FROM `mantenimiento.tblcategorias`";
                                                                $stmt_categoria = $conn->prepare($query_categoria);
                                                                $stmt_categoria->execute();
                                                                while ($categoria = $stmt_categoria->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($categoria['IdCategoria'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($categoria['NomCategoria'] == $row['NomCategoria']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($categoria['NomCategoria'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 justify-content-center">
                                                        <label class="col-12" for="codigo_pago">Código de Pago</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="codigo_pago" name="codigo_pago">
                                                                <?php
                                                                $query_codigo = "SELECT IdCategoria, CodArbitrios FROM `mantenimiento.tblcategorias`";
                                                                $stmt_codigo = $conn->prepare($query_codigo);
                                                                $stmt_codigo->execute();
                                                                while ($codigo = $stmt_codigo->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($codigo['IdCategoria'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($codigo['CodArbitrios'] == $row['CodArbitrios']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($codigo['CodArbitrios'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="carrera">Nombre de la Carrera</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo htmlspecialchars($row['NomCarrera'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="grado_academico">Grado Académico</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="grado_academico" name="grado_academico">
                                                                <?php
                                                                $query_grado = "SELECT idGrado, NomGrado FROM `mantenimiento.tblgradosacademicos`";
                                                                $stmt_grado = $conn->prepare($query_grado);
                                                                $stmt_grado->execute();
                                                                while ($grado = $stmt_grado->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($grado['idGrado'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($grado['NomGrado'] == $row['NomGrado']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($grado['NomGrado'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="modalidad">Modalidad</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="modalidad" name="modalidad">
                                                                <?php
                                                                $query_modalidad = "SELECT IdModalidad, NomModalidad FROM `mantenimiento.tblmodalidades`";
                                                                $stmt_modalidad = $conn->prepare($query_modalidad);
                                                                $stmt_modalidad->execute();
                                                                while ($modalidad = $stmt_modalidad->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($modalidad['IdModalidad'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($modalidad['NomModalidad'] == $row['NomModalidad']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($modalidad['NomModalidad'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="universidad">Universidad/Centro/Institución</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="universidad" name="universidad">
                                                                <?php
                                                                $query_universidad = "SELECT IdUniversidad, NomUniversidad FROM `mantenimiento.tbluniversidadescentros`";
                                                                $stmt_universidad = $conn->prepare($query_universidad);
                                                                $stmt_universidad->execute();
                                                                while ($universidad = $stmt_universidad->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($universidad['IdUniversidad'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($universidad['NomUniversidad'] == $row['NomUniversidad']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($universidad['NomUniversidad'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="departamento">Departamento</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="departamento" name="departamento">
                                                                <?php
                                                                $query_departamento = "SELECT IdDepartamento, NomDepto FROM `mantenimiento.tbldeptos`";
                                                                $stmt_departamento = $conn->prepare($query_departamento);
                                                                $stmt_departamento->execute();
                                                                while ($departamento = $stmt_departamento->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($departamento['IdDepartamento'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($departamento['NomDepto'] == $row['NomDepto']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($departamento['NomDepto'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 justify-content-center">
                                                        <label class="col-12" for="municipio">Municipio</label>
                                                        <div class="col-12">
                                                            <select class="form-control" id="municipio" name="municipio">
                                                                <?php
                                                                $query_municipio = "SELECT IdMunicipio, NomMunicipio FROM `mantenimiento.tblmunicipios`";
                                                                $stmt_municipio = $conn->prepare($query_municipio);
                                                                $stmt_municipio->execute();
                                                                while ($municipio = $stmt_municipio->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo '<option value="' . htmlspecialchars($municipio['IdMunicipio'], ENT_QUOTES, 'UTF-8') . '"';
                                                                    if ($municipio['NomMunicipio'] == $row['NomMunicipio']) echo ' selected';
                                                                    echo '>' . htmlspecialchars($municipio['NomMunicipio'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <label class="col-12" for="descripciones">Descripcion de la Solicitud</label>
                                                    <div class="col-12">
                                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($row['Descripcion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-alt-info" title="Regresar a revisión de documentación" style="width: 200px;" onclick="window.history.back()"><i class="si si-action-undo mr-5"></i>Regresar</button>
                                                <a href="RevisionSubsanacion.php?id=<?php echo htmlspecialchars($row['IdSolicitud'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <button type="button" class="btn btn-alt-info" title="Solicitar subsanación de documentos incompletos o incorrectos" style="width: 200px;"><i class="si si-docs mr-5"></i>Subsanación</button>
                                                </a>
                                                <a id="descargarBtn" href="#" class="btn btn-alt-info" title="Descargar adjuntos relacionados a la solicitud" style="width: 200px;"><i class="si si-folder-alt mr-5"></i>Descargar Adjuntos</a>
                                                <button type="button" id="docsFisicosBtn" class="btn btn-alt-info" title="Solicitar documentos en físico" style="width: 200px;"><i class="si si-paper-clip mr-5"></i>Docs. Físicos</button>
                                                <button type="submit" name="editar_solicitud" class="btn btn-alt-info" title="Editar campos incorrectos" style="width: 200px;"><i class="fa fa-edit mr-5"></i>Editar</button>
                                                <div class="row justify-content-between px-4">
                                                    <div class="col-md-6 text-left">
                                                        <span class="small text-secondary">Nombre del solicitante: <?php echo htmlspecialchars($row['NombreCompleto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <span class="small text-secondary">Fecha de Ingreso: <?php echo htmlspecialchars($row['FechaIngreso'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end px-4">
                                                    <div class="col-md-6 text-left">
                                                        <span class="small text-secondary">Correo: <?php echo htmlspecialchars($row['CorreoElectronico'], ENT_QUOTES, 'UTF-8');; ?></span>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <span class="small text-secondary">Fecha de Ultima Modificación: <?php echo htmlspecialchars($row['FechaModificacion'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </div>
                                                </div>
                                </form>
                                <?php if ($success) : ?>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '<?php echo $mensaje; ?>',
                                                showConfirmButton: true,
                                                confirmButtonText: 'Aceptar'
                                            }).then(function() {
                                                window.location.href = 'RevisionDocument.php';
                                            });
                                        });
                                    </script>
                                <?php endif; ?>
                            </div>
                            <?php require_once("../MainJs/MainJs.php"); ?>
                            <script>
                                document.getElementById('docsFisicosBtn').addEventListener('click', function() {
                                    Swal.fire({
                                        title: 'Confirmar Solicitud',
                                        text: '¿Desea solicitar los documentos en físico para esta solicitud, una vez aceptada no se mostrara dentro de la tabla de revisión de documentos?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Aceptar',
                                        cancelButtonText: 'Cancelar',
                                        customClass: {
                                            confirmButton: 'btn btn-primary', // Clase para botón azul
                                            cancelButton: 'btn btn-danger' // Clase para botón rojo
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Agrega un campo oculto al formulario para confirmar
                                            var form = document.getElementById('solicitudForm');
                                            var input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = 'confirmar_solicitud';
                                            input.value = 'yes';
                                            form.appendChild(input);

                                            form.submit(); // Envía el formulario
                                        }
                                    });
                                });
                            </script>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtén el valor del ID de solicitud del input
            var idSolicitud = document.querySelector('input[name="solicitud_id"]').value;

            // Construye la URL de descarga
            var downloadUrl = 'descargar_archivo.php?solicitud_id=' + encodeURIComponent(idSolicitud);

            // Asigna la URL al atributo href del botón
            document.getElementById('descargarBtn').href = downloadUrl;
        });
    </script>


    </html>
<?php
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}
?>