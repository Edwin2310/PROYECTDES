<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Script/Funciones.php');
if (isset($_SESSION["IdUsuario"])) {

?>
    <?php
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 16; // ID del objeto o módulo correspondiente a esta página

    if (!$id_rol) {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Verificar permiso en la base de datos
    $sql = "SELECT * FROM `seguridad.tblpermisos` WHERE IdRol = :idRol AND IdObjeto = :idObjeto";
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
        <?php require_once("../MainHead/MainHead.php"); ?>
        <script src="../Seguridad/Bitacora/Bitacora.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Consejales</title>
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
                        <h3 class="block-title">Menú Ingreso De Consejales <small></small></h3>
                    </div>
                    <div class="block-content block-content-full">
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">Añadir Consejal</button>
                        </td>
                        <td class="text-center">
                            <a href="../Seguridad/Consejales_Inactivos.php">
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Consejales Inactivos">
                                    <i class="fa fa-user-times"></i>
                                </button>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="../Seguridad/Consejales_Bloqueados.php">
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Consejales Bloqueados">
                                    <i class="fa fa-lock"></i>
                                </button>
                            </a>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">Id Usuario</th>
                                    <th class="d-none d-sm-table-cell">Nombre</th>
                                    <th class="d-none d-sm-table-cell">Apellido</th>
                                    <th class="d-none d-sm-table-cell">Universidad</th>
                                    <th class="d-none d-sm-table-cell">Correo</th>
                                    <th class="d-none d-sm-table-cell">Miembro</th>
                                    <th class="d-none d-sm-table-cell">Estado Usuario</th>
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
                                $stmt = $conn->prepare("CALL splConsejalesMostrar(:usuario)");
                                $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($result !== false && count($result) > 0) {
                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['ID_CONSEJAL']}</td>";
                                        echo "<td>{$row['NOMBRE']}</td>";
                                        echo "<td>{$row['APELLIDO']}</td>";
                                        echo "<td>{$row['NOM_UNIVERSIDAD']}</td>";
                                        echo "<td>{$row['CORREO_CONS']}</td>";
                                        echo "<td>{$row['CATEGORIA_CONS']}</td>";
                                        echo "<td>{$row['NOM_ESTADO']}</td>";
                                        echo "<td class='text-center'> 
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editConsejalModal' 
                                                data-id='" . $row["ID_CONSEJAL"] . "' 
                                                data-nombre='" . $row["NOMBRE"] . "' 
                                                data-apellido='" . $row["APELLIDO"] . "' 
                                                data-universidad='" . $row["NOM_UNIVERSIDAD"] . "' 
                                                data-categoria_cons='" . $row["CATEGORIA_CONS"] . "' 
                                                data-estado='" . $row["NOM_ESTADO"] . "' 
                                                data-correo_cons='" . $row["CORREO_CONS"] . "'>
                                                <i class='si si-note'></i>
                                            </button>
                                        </td>";
                                        echo "<td class='text-center'> 
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                                data-id='" . $row["ID_CONSEJAL"] . "'>
                                                <i class='si si-trash'></i>
                                            </button>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No hay datos disponibles</td></tr>";
                                }
                                $conn = null;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                </main>

                <?php require_once("../MainFooter/MainFooter.php"); ?>

                <!-- Modal para agregar usuarios -->
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Añadir Consejal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addUserForm" method="POST" action="../Seguridad/Consejales/Agregar_Consejal.php">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellido">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_universidad">Universidad</label>
                                        <select class="form-control" id="id_universidad" name="id_universidad" required>
                                            <option value="" disabled selected style="font-weight:bold">Seleccionar Universidad</option>
                                            <?php echo obtenerUniversidades($usuario); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="categoria_cons">Categoria Consejal</label>
                                        <option value="" disabled selected style="display:none;">Seleccionar Categoria Consejal</option>
                                        <select class="form-control" id="categoria_cons" name="categoria_cons" required>
                                            <option value="0" style="font-weight:bold">Seleccionar Categoria</option>
                                            <option value="CTC">CTC</option>
                                            <option value="CES">CES</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="correo_cons">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo_cons" name="correo_cons" required>
                                    </div>
                                    <input type="hidden" id="estado" name="estado" value="1">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar consejal -->
                <div class="modal fade" id="editConsejalModal" tabindex="-1" role="dialog" aria-labelledby="editConsejalModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editConsejalModalLabel">Editar Consejal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editConsejalForm" method="POST" action="../Seguridad/Consejales/Editar_Consejal.php">
                                    <input type="hidden" id="edit-id" name="id">
                                    <div class="form-group">
                                        <label for="edit-nombre">Nombre</label>
                                        <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-apellido">Apellido</label>
                                        <input type="text" class="form-control" id="edit-apellido" name="apellido" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit-categoria">Categoria</label>
                                        <input type="text" class="form-control" id="edit-categoria" name="categoria_cons" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="categoria_cons">Nueva Categoria Consejal</label>
                                        <select class="form-control" id="categoria_cons" name="categoria_cons" required>
                                            <option value="" disabled selected style="display:none;">Seleccionar Categoria</option>
                                            <option value="CTC">CTC</option>
                                            <option value="CES">CES</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-universidad">Universidad Actual</label>
                                        <input type="text" class="form-control" id="edit-universidad" name="universidad" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-universidad">Nueva Universidad</label>
                                        <select class="form-control" id="edit-universidad" name="universidad" required>
                                            <option value="" disabled selected style="display:none;">Seleccionar Universidad</option>
                                            <?php echo obtenerUniversidades($usuario); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-estado">Estado Usuario</label>
                                        <input type="text" class="form-control" id="edit-estado" name="estado" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-estado">Seleccionar Nuevo Estado</label>
                                        <select class="form-control" id="edit-estado" name="estado" required>
                                            <option value="" disabled selected style="display:none;">Seleccionar Estado</option>
                                            <?php echo editarEstados($usuario); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-correo">Correo</label>
                                        <input type="email" class="form-control" id="edit-correo" name="correo_cons" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal de confirmación para eliminar objeto -->
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
                                <p>¿Estás seguro de que deseas eliminar este objeto?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <form id="deleteObjectForm" method="POST" action="../Seguridad/Consejales/Eliminar_Consejal.php">
                                    <input type="hidden" id="delete_id_consejal" name="id_consejal">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require_once("../MainJs/MainJs.php"); ?>
                <script src="./Usuarios/js/consejales.js"></script>
    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>