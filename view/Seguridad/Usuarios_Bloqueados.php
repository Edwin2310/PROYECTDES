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
    $id_objeto = 15; // ID del objeto o módulo correspondiente a esta página

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
        <title>Usuarios Bloqueados</title>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <script src="../Seguridad/Bitacora/Bitacora.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            .table-container {
                overflow-x: auto;
            }

            .hidden-column {
                display: none;
            }

            .show-more-btn {
                margin-top: 10px;
            }
        </style>
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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"></a>
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
                            <h3 class="block-title">Menú Ingreso De Usuario Bloqueados<small></small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <td class="text-center">
                                <a href="../Seguridad/Usuarios.php">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Usuarios Activos">
                                        <i class="fa fa-user"></i>
                                    </button>
                                </a>
                            </td>
                            <br>
                            <br>
                            <div class="block-content block-content-full">
                                <div class="table-container">
                                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id Usuario</th>
                                                <th class="d-none d-sm-table-cell"># Identidad</th>
                                                <th class="d-none d-sm-table-cell">Dirección</th>
                                                <th class="d-none d-sm-table-cell">Usuario</th>
                                                <th class="d-none d-sm-table-cell">Correo</th>
                                                <th class="d-none d-sm-table-cell">Nombre Usuario</th>
                                                <th class="d-none d-sm-table-cell">Número Empleado</th>
                                                <th class="d-none d-sm-table-cell">Estado Usuario</th>
                                                <th class="d-none d-sm-table-cell">Nombre Rol</th>
                                                <th class="text-center hidden-column">Fecha Creación</th>
                                                <th class="text-center hidden-column">Universidad</th>
                                                <th class="text-center" style="width: 15%;">Editar Usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once("../../config/conexion.php");
                                            $conexion = new Conectar();
                                            $conn = $conexion->Conexion();

                                            // Llamada al procedimiento almacenado
                                            $stmt = $conn->prepare("CALL `seguridad.splUsuariosMostrarBloqueados`(:usuario)");
                                            $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                            $stmt->execute();
                                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($result !== false && count($result) > 0) {
                                                foreach ($result as $row) {
                                                    echo "<tr>";
                                                    echo "<td class='text-center'>{$row['IdUsuario']}</td>";
                                                    echo "<td>{$row['NumIdentidad']}</td>";
                                                    echo "<td>{$row['Direccion']}</td>";
                                                    echo "<td>{$row['Usuario']}</td>";
                                                    echo "<td>{$row['CorreoElectronico']}</td>";
                                                    echo "<td>{$row['NombreUsuario']}</td>";
                                                    echo "<td>{$row['NumEmpleado']}</td>";
                                                    echo "<td>{$row['IdEstado']}</td>";
                                                    echo "<td>{$row['IdRol']}</td>";
                                                    echo "<td class='text-center hidden-column'>{$row['FechaCreacion']}</td>";
                                                    echo "<td class='text-center hidden-column'>{$row['IdUniversidad']}</td>";
                                                    echo "<td class='text-center'> 
                                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editUserModal' 
                                                                data-IdUsuario='" . $row["IdUsuario"] . "' 
                                                                data-NumIdentidad='" . $row["NumIdentidad"] . "' 
                                                                data-Direccion='" . $row["Direccion"] . "' 
                                                                data-Usuario='" . $row["Usuario"] . "' 
                                                                data-CorreoElectronico='" . $row["CorreoElectronico"] . "' 
                                                                data-NombreUsuario='" . $row["NombreUsuario"] . "' 
                                                                data-NumEmpleado='" . $row["NumEmpleado"] . "'
                                                                data-IdEstado='" . $row["IdEstado"] . "' 
                                                                data-IdRol='" . $row["IdRol"] . "'>
                                                                <i class='si si-note'></i>
                                                           </button>
                                                        </td>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='12' class='text-center'>No hay datos disponibles</td></tr>";
                                            }
                                            $conn = null;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-primary show-more-btn" id="showMoreBtn">Mostrar más</button>
                            </div>
                        </div>
                    </div>
            </main>
            <?php require_once("../MainFooter/MainFooter.php"); ?>
        </div>
        <?php require_once("../MainJs/MainJs.php"); ?>

        <!-- Modal para agregar usuarios -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm" method="POST">
                            <div class="form-group">
                                <label for="NumIdentidad">Número de Identidad</label>
                                <input type="text" class="form-control" id="NumIdentidad" name="NumIdentidad" placeholder="0801199923672" required>
                            </div>
                            <div class="form-group">
                                <label for="Direccion">Dirección</label>
                                <input type="text" class="form-control" id="Direccion" name="Direccion" required>
                            </div>
                            <div class="form-group">
                                <label for="Usuario">Usuario</label>
                                <input type="text" class="form-control" id="Usuario" name="Usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="CorreoElectronico">Correo Electrónico</label>
                                <input type="email" class="form-control" id="CorreoElectronico" name="CorreoElectronico" required>
                            </div>
                            <div class="form-group">
                                <label for="NombreUsuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" required>
                            </div>
                            <div class="form-group">
                                <label for="NumEmpleado">Número de Empleado</label>
                                <input type="number" class="form-control" id="NumEmpleado" name="NumEmpleado" required>
                            </div>
                            <div class="form-group" id="EmpleadoDes">
                                <label for="EmpleadoDes">Empleado DES</label>
                                <select class="form-control" id="EmpleadoDes_select" name="EmpleadoDes" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Opción</option>
                                    <option value="1">Sí</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <input type="hidden" id="IdEstado" name="IdEstado" value="1">
                            <div class="form-group">
                                <label for="IdRol">Rol</label>
                                <select class="form-control" id="IdRol" name="IdRol" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                    <?php echo obtenerRoles($usuario); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="IdUniversidad">Universidad</label>
                                <select class="form-control" id="IdUniversidad" name="IdUniversidad" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                    <?php echo obtenerUniversidades($usuario); ?>
                                </select>
                            </div>
                            <input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $_SESSION['IdUsuario']; ?>">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" id="guardarBtn">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar usuarios -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" method="POST" action="../Seguridad/Usuarios/Editar_Usuario.php">
                            <input type="hidden" id="edit_IdUsuario" name="IdUsuario">
                            <!-- Otros campos del formulario -->
                            <div class="form-group">
                                <label for="edit_NumIdentidad">Número de Identidad</label>
                                <input type="text" class="form-control" id="edit_NumIdentidad" name="NumIdentidad" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_Direccion">Dirección</label>
                                <input type="text" class="form-control" id="edit_Direccion" name="Direccion" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_Usuario">Usuario</label>
                                <input type="text" class="form-control" id="edit_Usuario" name="Usuario" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_CorreoElectronico">Correo Electrónico</label>
                                <input type="email" class="form-control" id="edit_CorreoElectronico" name="CorreoElectronico" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_NombreUsuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="edit_NombreUsuario" name="NombreUsuario" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_NumEmpleado">Número de Empleado</label>
                                <input type="text" class="form-control" id="edit_NumEmpleado" name="NumEmpleado">
                            </div>
                            <div class="form-group">
                                <label for="edit_IdEstado">Estado Usuario</label>
                                <input type="text" class="form-control" id="edit_IdEstado" name="IdEstado" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_IdEstado_nuevo">Seleccionar Nuevo Estado</label>
                                <select class="form-control" id="edit_IdEstado_nuevo" name="IdEstado" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Estado</option>
                                    <?php echo editarEstadosInactivos($usuario); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_IdRol">Rol</label>
                                <input type="text" class="form-control" id="edit_IdRol" name="IdRol" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_rol">Seleccionar Nuevo Rol</label>
                                <select class="form-control" id="edit_rol" name="rol" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                    <?php echo obtenerRoles($usuario); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_universidad_nueva">Seleccionar Nueva Universidad</label>
                                <select class="form-control" id="edit_universidad_nueva" name="IdUniversidad" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                    <?php echo obtenerUniversidades($usuario); ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include("./Script/scripts_Usuario.php"); ?>


    </html>

<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>