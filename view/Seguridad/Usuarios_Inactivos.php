<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Script/Funciones.php');
if (isset($_SESSION["IdUsuario"])) {

?>
    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <?php include("./Script/scripts_Usuario.php"); ?>
        <script src="../Seguridad//Bitacora//Bitacora.js"></script>
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
                        <h3 class="block-title">Menú Ingreso De Usuarios Inactivos<small></small></h3>
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
                                        <th class="d-none d-sm-table-cell">Fecha Creación</th>
                                        <th class="d-none d-sm-table-cell">Creado Por</th>
                                        <th class="text-center hidden-column">Universidad</th>
                                        <th class="text-center hidden-column">Fecha Modificación</th>
                                        <th class="text-center hidden-column">Modificado Por</th>
                                        <th class="text-center" style="width: 15%;">Editar Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once("../../config/conexion.php");

                                    try {
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Llamada al procedimiento almacenado
                                        $stmt = $conn->prepare("CALL splUsuariosMostrarInactivos(:usuario)");
                                        $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($result !== false && count($result) > 0) {
                                            foreach ($result as $row) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdUsuario']}</td>";
                                                echo "<td>{$row['NUM_IDENTIDAD']}</td>";
                                                echo "<td>{$row['DIRECCION_1']}</td>";
                                                echo "<td>{$row['USUARIO']}</td>";
                                                echo "<td>{$row['CORREO_ELECTRONICO']}</td>";
                                                echo "<td>{$row['NOMBRE_USUARIO']}</td>";
                                                echo "<td>{$row['NUM_EMPLEADO']}</td>";
                                                echo "<td>{$row['ESTADO_USUARIO']}</td>";
                                                echo "<td>{$row['IdRol']}</td>";
                                                echo "<td>{$row['FECHA_CREACION']}</td>";
                                                echo "<td>{$row['CREADO_POR']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['ID_UNIVERSIDAD']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FECHA_MODIFICACION']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['MODIFICADO_POR']}</td>";
                                                echo "<td class='text-center'> 
                                            <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editUserModal' 
                                                    data-id='{$row["IdUsuario"]}' 
                                                    data-num_identidad='{$row["NUM_IDENTIDAD"]}' 
                                                    data-direccion_1='{$row["DIRECCION_1"]}' 
                                                    data-usuario='{$row["USUARIO"]}' 
                                                    data-correo_electronico='{$row["CORREO_ELECTRONICO"]}' 
                                                    data-nombre_usuario='{$row["NOMBRE_USUARIO"]}' 
                                                    data-num_empleado='" . $row["NUM_EMPLEADO"] . "'
                                                    data-estado_usuario='{$row["ESTADO_USUARIO"]}' 
                                                    data-id_rol='{$row["IdRol"]}' 
                                                    data-creado_por='{$row["CREADO_POR"]}' 
                                                    data-id_universidad='{$row["ID_UNIVERSIDAD"]}' 
                                                    data-fecha_modificacion='{$row["FECHA_MODIFICACION"]}' 
                                                    data-modificado_por='{$row["MODIFICADO_POR"]}'>
                                                    <i class='si si-note'></i>
                                            </button>
                                        </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='13' class='text-center'>No hay datos disponibles</td></tr>";
                                        }

                                        $conn = null;
                                    } catch (Exception $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary show-more-btn" id="showMoreBtn">Mostrar más</button>
                    </div>
                </div>

                </main>

                <?php require_once("../MainFooter/MainFooter.php"); ?>

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
                                            <?php echo editarEstadosInactivos($usuario); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_id_rol">Rol</label>
                                        <input type="text" class="form-control" id="edit_id_rol" name="id_rol" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-rol">Seleccionar Nuevo Rol</label>
                                        <select class="form-control" id="edit-rol" name="rol" required>
                                            <option value="" disabled selected style="display:none;">Seleccionar Rol</option>
                                            <?php echo obtenerRoles($usuario); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_universidad_nueva">Universidad Registrada</label>
                                        <input type="text" class="form-control" id="edit_universidad_nueva" name="nom_universidad" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_universidad_nueva">Seleccionar Nueva Universidad</label>
                                        <select class="form-control" id="edit_universidad_nueva" name="id_universidad" required>
                                            <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                            <?php echo obtenerUniversidades($usuario); ?>
                                        </select>
                                        <br>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php require_once("../MainJs/MainJs.php"); ?>
            <script src="./Usuarios/js/validaciones.js"></script>

    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>