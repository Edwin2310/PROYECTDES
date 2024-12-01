<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 24; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 3) {
        // Verificar permisos
        if (verificarPermiso($id_rol, $id_objeto)) {
            $accion = "Accedió al módulo.";

            // Registrar en la bitácora
            registrobitaevent($id_usuario, $id_objeto, $accion);
        } else {
            $accion = "acceso denegado.";

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
        <title>Gestión de Universidades</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="Categoria/Script_Categoria.js"></script>
    </head>
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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html">
                                    <span><?php echo $_SESSION["NOMBRE_USUARIO"] ?></span>
                                </a>
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

            <div class="content">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Gestión de Universidades</h3>
                        <a href="./Universidades/exportar_universidad.php" class="btn btn-success mx-2">Descargar Excel</a>

                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Universidades_Bloqueadas.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Universidades Bloqueadas">
                                    <i class="fa fa-lock"></i>
                                </button>
                            </a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUniversidadModal">Añadir Universidad</button>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID UNIVERSIDAD</th>
                                    <th class="text-center">UNIVERSIDAD</th>
                                    <th class="text-center" style="width: 15%;">EDITAR UNIVERSIDAD</th>
                                    <th class="text-center" style="width: 15%;">BLOQUEAR UNIVERSIDAD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT 
                                        u.IdUniversidad, 
                                        u.NomUniversidad
                                        FROM 
                                        `mantenimiento.tbluniversidades` u
                                        LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON u.IdVisibilidad = e.IdVisibilidad
                                        WHERE e.IdVisibilidad IN (1)
                                        ORDER BY
                                        u.IdUniversidad";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdUniversidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editUniversidadModal' 
                                        data-id='" . $row["IdUniversidad"] . "' 
                                        data-NomUniversidad='" . $row["NomUniversidad"] . "' 
                                        >
                                    <i class='si si-note'></i>
                                </button>
                            </td>";
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteUniversidadModal' 
                                        data-id='" . $row["IdUniversidad"] . "'>
                                    <i class='si si-trash'></i>
                                </button>
                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No hay datos disponibles</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <?php require_once("../MainFooter/MainFooter.php"); ?>

            <!-- Modal para agregar universidades -->
            <div class="modal fade" id="addUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="addUniversidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUniversidadodalLabel">Añadir Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Agregar_Universidad.php">
                                <div class="form-group">
                                    <label for="NomUniversidad">Nombre Universidad</label>
                                    <input type="text" class="form-control" id="NomUniversidad" name="NomUniversidad" maxlength="30" required oninput="validarNombreUniversidad(this)" style="text-transform:uppercase;">
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Universidad</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar univeridades -->
            <div class="modal fade" id="editUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="editUniversidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUniversidadModalLabel">Editar Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Editar_Universidad.php">
                                <input type="hidden" id="edit_IdUniversidad" name="IdUniversidad">
                                <div class="form-group">
                                    <label for="NomUniversidad">Nombre Universidad</label>
                                    <input type="text" class="form-control" id="NomUniversidad" name="NomUniversidad" maxlength="30" required oninput="validarNombreUniversidad(this)" style="text-transform:uppercase;">
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de Universidad -->
            <div class="modal fade" id="confirmDeleteUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUniversidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteUniversidadModalLabel">Bloquear Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres bloquear esta universidad?</p>
                            <form id="deleteUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Eliminar_Universidad.php">
                                <input type="hidden" id="delete_IdUniversidad" name="IdUniversidad">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-danger">Bloquear</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("../MainJs/MainJs.php"); ?>

            <!-- Incluye el script de JavaScript -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- Bootstrap JS y dependencias -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                // Código JavaScript para manejar los modales de editar y eliminar Universidads
                $('#editUniversidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var NomUniversidad = button.data('NomUniversidad');
                    var departamento = button.data('nom_depto');
                    var municipio = button.data('nom_municipio');


                    var modal = $(this);
                    modal.find('#edit_IdUniversidad').val(id);
                    modal.find('#edit_NomUniversidad').val(NomUniversidad);
                    modal.find('#edit_nom_depto').val(departamento);
                    modal.find('#edit_nom_municipio').val(municipio);

                });

                $('#confirmDeleteUniversidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_IdUniversidad').val(id);
                });
            </script>
            <script src="Universidades/Script_Universidad.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>