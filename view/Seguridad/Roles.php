<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Script/Funciones.php');
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 19; // ID del objeto o módulo correspondiente a esta página

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

    <!DOCTYPE html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Roles</title>
    </head>

    <body>
        <div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">
            <!-- Barra lateral -->
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
                                    <span><?php echo $_SESSION["NombreUsuario"] ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Barra lateral principal -->
            <nav id="sidebar" class="text-warning">
                <div id="sidebar-scroll">
                    <div class="sidebar-content">
                        <?php require_once("../MainSidebar/MainSidebar.php"); ?>
                        <?php require_once("../MainMenu/MainMenu.php"); ?>
                    </div>
                </div>
            </nav>

            <!-- Barra de navegación principal -->
            <nav class="text-warning">
                <?php require_once("../MainHeader/MainHeader.php"); ?>
            </nav>

            <!-- Contenido principal -->
            <div class="content">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Menú Ingreso De Roles</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="text-center mb-2">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addRoleModal">Añadir Rol</button>
                        </div>

                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center">Id Rol</th>
                                    <th>Rol</th>
                                    <th>Descripción</th>
                                    <th>Fecha Creación</th>
                                    <th>Creado Por</th>
                                    <th>Fecha Modificación</th>
                                    <th>Modificado Por</th>
                                    <th class="text-center">Editar</th>
                                    <th class="text-center">Eliminar</th>
                                    <th class="text-center">Permisos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();

                                // Llamada al procedimiento almacenado
                                $stmt = $conn->prepare("CALL `seguridad.splRolesMostrarCreador`(:usuario)");
                                $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($result !== false && count($result) > 0) {
                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdRol']}</td>";
                                        echo "<td>{$row['Rol']}</td>";
                                        echo "<td>{$row['NombreRol']}</td>";
                                        echo "<td>{$row['FechaCreacion']}</td>";
                                        echo "<td>{$row['CreadoPor']}</td>";
                                        echo "<td>{$row['FechaModificacion']}</td>";
                                        echo "<td>{$row['ModificadoPor']}</td>";
                                        echo "<td class='text-center'>
                                                <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editRoleModal' 
                                                        data-id='" . $row["IdRol"] . "' 
                                                        data-rol='" . $row["Rol"] . "' 
                                                        data-descripcion='" . $row["NombreRol"] . "'>
                                                    <i class='si si-note'></i>
                                                </button>
                                            </td>";
                                        echo "<td class='text-center'>
                                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                                        data-id='" . $row["IdRol"] . "'>
                                                    <i class='si si-trash'></i>
                                                </button>
                                            </td>";
                                        echo "<td class='text-center'>
                                            <a href='../Seguridad/Permisos.php?IdRol=" . $row["IdRol"] . "'>
                                            <button type='button' class='btn btn-sm btn-success'>
                                                <i class='si si-settings'></i>
                                            </button>
                                            </a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>No hay datos disponibles</td></tr>";
                                }

                                // Cerrar la conexión
                                $conn = null;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Modal de confirmación para eliminar -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de eliminar este rol?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <form action="../Seguridad/Roles/Eliminar_Rol.php" method="POST">
                                <input type="hidden" name="IdRol" id="delete-rol-id" value="">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar roles -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRoleModalLabel">Añadir Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addRoleForm" method="POST" action="../Seguridad/Roles/Guardar_Rol.php">
                                <!-- Campo oculto para almacenar el IdUsuario de la sesión -->
                                <input type="hidden" id="CreadoPor" name="CreadoPor" value="<?php echo $_SESSION['IdUsuario']; ?>">

                                <div class="form-group">
                                    <label for="Rol">Seleccionar Rol</label>
                                    <select class="form-control" id="Rol" name="Rol">
                                        <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                        <?php echo obtenerRoles($usuario); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="NombreRol">Nombre Rol</label>
                                    <input type="text" class="form-control" id="NombreRol" name="NombreRol" placeholder="Descripción del Rol" maxlength="50" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="btnAddRole">Guardar Rol</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar roles -->
            <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalLabel">Editar Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editRoleForm" method="POST" action="../Seguridad/Roles/Actualizar_Rol.php">
                                <input type="hidden" id="edit-id_rol" name="IdRol">
                                <div class="form-group">
                                    <label for="edit-rol">Seleccionar Nuevo Rol</label>
                                    <select class="form-control" id="edit-rol" name="Rol" required>
                                        <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                        <?php echo obtenerRoles($usuario); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit-descripcion">Descripción</label>
                                    <input type="text" class="form-control" id="edit-descripcion" name="NombreRol" placeholder="Descripción del Rol" maxlength="50">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="btnAddRole">Guardar Rol</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación de eliminación -->
            <div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar Eliminación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="deleteRoleForm">
                                <input type="hidden" id="delete-rol-id" name="IdRol">
                                <!-- Otros campos del formulario -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="eliminarRol()">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de edición de rol -->
            <div id="editRoleModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editRoleForm">
                                <input type="hidden" id="edit-rol-id" name="IdRol">
                                <!-- Otros campos del formulario -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="actualizarRol()">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <script src="./Usuarios/js/roles.js"></script>
    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->