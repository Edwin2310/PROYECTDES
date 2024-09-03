<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Script/Funciones.php');
if (isset($_SESSION["ID_USUARIO"])) {

?>

<?php
    $id_rol = $_SESSION['ID_ROL'] ?? null;
    $id_objeto = 15; // ID del objeto o módulo correspondiente a esta página

    if (!$id_rol) {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Verificar permiso en la base de datos
    $sql = "SELECT * FROM tbl_permisos WHERE ID_ROL = :idRol AND ID_OBJETO = :idObjeto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idRol', $id_rol);
    $stmt->bindParam(':idObjeto', $id_objeto);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        // Usuario tiene permiso, continuar con el contenido de la página
    } else {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }
    ?>

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <title>Usuarios</title>
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
                            <h3 class="block-title">Menú Ingreso De Usuario <small></small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <td class="text-center">
                                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">Añadir Usuario</button>
                            </td>
                            <td class="text-center">
                                <a href="../Seguridad/Usuarios_Inactivos.php">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Usuarios Inactivos">
                                        <i class="fa fa-user-times"></i>
                                    </button>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="../Seguridad/Usuarios_Bloqueados.php">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Usuarios Bloqueados">
                                        <i class="fa fa-lock"></i>
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
                                                <th class="text-center hidden-column">Creado Por</th>
                                                <th class="text-center hidden-column">Universidad</th>
                                                <th class="text-center" style="width: 15%;">Editar Usuario</th>
                                                <th class="text-center" style="width: 15%;">Eliminar Usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once("../../config/conexion.php");
                                            $conexion = new Conectar();
                                            $conn = $conexion->Conexion();

                                            // Llamada al procedimiento almacenado
                                            $stmt = $conn->prepare("CALL splUsuariosMostrar(:usuario)");
                                            $stmt->bindValue(':usuario', $_SESSION["ID_USUARIO"], PDO::PARAM_STR);
                                            $stmt->execute();
                                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($result !== false && count($result) > 0) {
                                                foreach ($result as $row) {
                                                    echo "<tr>";
                                                    echo "<td class='text-center'>{$row['ID_USUARIO']}</td>";
                                                    echo "<td>{$row['NUM_IDENTIDAD']}</td>";
                                                    echo "<td>{$row['DIRECCION_1']}</td>";
                                                    echo "<td>{$row['USUARIO']}</td>";
                                                    echo "<td>{$row['CORREO_ELECTRONICO']}</td>";
                                                    echo "<td>{$row['NOMBRE_USUARIO']}</td>";
                                                    echo "<td>{$row['NUM_EMPLEADO']}</td>";
                                                    echo "<td>{$row['ESTADO_USUARIO']}</td>";
                                                    echo "<td>{$row['ID_ROL']}</td>";
                                                    echo "<td class='text-center hidden-column'>{$row['FECHA_CREACION']}</td>";
                                                    echo "<td class='text-center hidden-column'>{$row['CREADO_POR']}</td>";
                                                    echo "<td class='text-center hidden-column'>{$row['ID_UNIVERSIDAD']}</td>";
                                                    echo "<td class='text-center'> 
                                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editUserModal' 
                                                                data-id='" . $row["ID_USUARIO"] . "' 
                                                                data-num_identidad='" . $row["NUM_IDENTIDAD"] . "' 
                                                                data-direccion_1='" . $row["DIRECCION_1"] . "' 
                                                                data-usuario='" . $row["USUARIO"] . "' 
                                                                data-correo_electronico='" . $row["CORREO_ELECTRONICO"] . "' 
                                                                data-nombre_usuario='" . $row["NOMBRE_USUARIO"] . "' 
                                                                data-num_empleado='" . $row["NUM_EMPLEADO"] . "'
                                                                data-estado_usuario='" . $row["ESTADO_USUARIO"] . "' 
                                                                data-id_rol='" . $row["ID_ROL"] . "' 
                                                                data-creado_por='" . $row["CREADO_POR"] . "'>
                                                                <i class='si si-note'></i>
                                                           </button>
                                                        </td>";
                                                    echo "<td class='text-center'> 
                                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                                                data-id='" . $row["ID_USUARIO"] . "'>
                                                            <i class='si si-trash'></i>
                                                        </button>
                                                        </td>";
                                                    echo "</tr>";
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
                                <label for="num_identidad">Número de Identidad</label>
                                <input type="text" class="form-control" id="num_identidad" name="num_identidad" placeholder="0801199923672" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion_1">Dirección</label>
                                <input type="text" class="form-control" id="direccion_1" name="direccion_1" required>
                            </div>
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="correo_electronico">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre_usuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="num_empleado">Número de Empleado</label>
                                <input type="number" class="form-control" id="num_empleado" name="num_empleado" required>
                            </div>
                            <div class="form-group" id="empleado_des">
                                <label for="empleado_des">Empleado DES</label>
                                <select class="form-control" id="empleado_des_select" name="empleado_des" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Opción</option>
                                    <option value="1">Sí</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <input type="hidden" id="estado_usuario" name="estado_usuario" value="1">
                            <div class="form-group">
                                <label for="id_rol">Rol</label>
                                <select class="form-control" id="id_rol" name="id_rol" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                    <?php echo obtenerRoles($usuario); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_universidad">Universidad</label>
                                <select class="form-control" id="id_universidad" name="id_universidad" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                    <?php echo obtenerUniversidades($usuario); ?>
                                </select>
                            </div>
                            <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['ID_USUARIO']; ?>">
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
                            <input type="hidden" id="edit_id_usuario" name="id_usuario">
                            <!-- Otros campos del formulario -->
                            <div class="form-group">
                                <label for="edit_num_identidad">Número de Identidad</label>
                                <input type="text" class="form-control" id="edit_num_identidad" name="num_identidad" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_direccion_1">Dirección</label>
                                <input type="text" class="form-control" id="edit_direccion_1" name="direccion_1" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_usuario">Usuario</label>
                                <input type="text" class="form-control" id="edit_usuario" name="usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_correo_electronico">Correo Electrónico</label>
                                <input type="email" class="form-control" id="edit_correo_electronico" name="correo_electronico" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_nombre_usuario">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="edit_nombre_usuario" name="nombre_usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_num_empleado">Número de Empleado</label>
                                <input type="text" class="form-control" id="edit_num_empleado" name="num_empleado">
                            </div>
                            <div class="form-group">
                                <label for="edit_estado_usuario">Estado Usuario</label>
                                <input type="text" class="form-control" id="edit_estado_usuario" name="estado_usuario" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_estado_usuario_nuevo">Seleccionar Nuevo Estado</label>
                                <select class="form-control" id="edit_estado_usuario_nuevo" name="estado_usuario" required>
                                    <option value="" disabled selected style="display:none;">Seleccionar Estado</option>
                                    <?php echo editarEstados($usuario); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_rol">Rol</label>
                                <input type="text" class="form-control" id="edit_id_rol" name="id_rol" readonly>
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
                                <select class="form-control" id="edit_universidad_nueva" name="id_universidad" required>
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

        <!-- Modal de confirmación para eliminar usuarios -->
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
                        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteUserForm" method="POST" action="../Seguridad/Usuarios/Eliminar_Usuario.php">
                            <input type="hidden" id="delete_id_usuario" name="id_usuario">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
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