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
    $id_objeto = 20; // ID del objeto o módulo correspondiente a esta página

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

        <title>Objetos</title>
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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><!-- llamada del usuario   -->
                                    <span><?php echo $_SESSION["NOMBRE_USUARIO"] ?></span>
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
                        <h3 class="block-title">Menú Ingreso De Objetos <small></small></h3>
                    </div>
                    <div class="block-content block-content-full">
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">Añadir Objetos</button>
                        </td>
                        <br>
                        <br>
                        <div class="block-content block-content-full">
                            <div class="table-container">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Id Objeto</th>
                                            <th class="d-none d-sm-table-cell">Objeto</th>
                                            <th class="d-none d-sm-table-cell">Tipo Objeto</th>
                                            <th class="d-none d-sm-table-cell" style="width: 15%;">Descripcion</th>
                                            <th>Fecha Creacion</th>
                                            <th class="text-center" style="width: 15%;">Creado Por</th>
                                            <th class="text-center hidden-column">Fecha Modificacion</th>
                                            <th class="text-center hidden-column">Modificado Por</th>
                                            <th class="text-center" style="width: 15%;">Editar Objeto</th>
                                            <th class="text-center" style="width: 15%;">Eliminar Objeto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once("../../config/conexion.php");
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Llamada al procedimiento almacenado
                                        $stmt = $conn->prepare("CALL `seguridad.splObjetosMostrar`(:usuario)");
                                        $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($result !== false && count($result) > 0) {
                                            foreach ($result as $row) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdObjeto']}</td>";
                                                echo "<td class='d-none d-sm-table-cell'>{$row['Objeto']}</td>";
                                                echo "<td class='d-none d-sm-table-cell'>{$row['TipoObjeto']}</td>";
                                                echo "<td class='d-none d-sm-table-cell'>{$row['Descripcion']}</td>";
                                                echo "<td>{$row['FechaCreacion']}</td>";
                                                echo "<td class='text-center'>{$row['CreadoPor']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaModificacion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['ModificadoPor']}</td>";
                                                echo "<td class='text-center'> 
                                                <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editObjectModal' 
                                                        data-id='{$row['IdObjeto']}' 
                                                        data-objeto='{$row['Objeto']}' 
                                                        data-tipo_objeto='{$row['TipoObjeto']}' 
                                                        data-descripcion='{$row['Descripcion']}' 
                                                        data-creado_por='{$row['CreadoPor']}'
                                                        data-id_usuario='{$_SESSION['IdUsuario']}'> <!-- Añadir ID del usuario aquí -->
                                                    <i class='si si-note'></i>
                                                </button>
                                                </td>";
                                                echo "<td class='text-center'> 
                                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                                        data-id='{$row['IdObjeto']}'>
                                                    <i class='si si-trash'></i>
                                                </button>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='9' class='text-center'>No hay datos disponibles</td></tr>";
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

            <!-- Modal para agregar objetos -->
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Añadir Objeto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addObjectForm" method="POST" action="../Seguridad/Objetos/Agregar_Objeto.php">
                                <div class="form-group">
                                    <label for="objeto">Objeto</label>
                                    <input type="text" class="form-control" id="objeto" name="Objeto" required>
                                </div>
                                <div class="form-group">
                                    <label for="tipo_objeto">Tipo Objeto</label>
                                    <input type="text" class="form-control" id="tipo_objeto" name="TipoObjeto" required>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label>
                                    <input type="text" class="form-control" id="descripcion" name="Descripcion" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar objetos -->
            <div class="modal fade" id="editObjectModal" tabindex="-1" role="dialog" aria-labelledby="editObjectModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editObjectModalLabel">Editar Objeto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editObjectForm" method="POST" action="../Seguridad/Objetos/Editar_Objeto.php">
                                <input type="hidden" id="edit-id_objeto" name="IdObjeto">
                                <input type="hidden" id="edit-id_usuario" name="IdUsuario"> <!-- Campo oculto para id_usuario -->
                                <div class="form-group">
                                    <label for="edit-objeto">Objeto</label>
                                    <input type="text" class="form-control" id="edit-objeto" name="Objeto" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-tipo_objeto">Tipo Objeto</label>
                                    <input type="text" class="form-control" id="edit-tipo_objeto" name="TipoObjeto" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-descripcion">Descripcion</label>
                                    <input type="text" class="form-control" id="edit-descripcion" name="Descripcion" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-creado_por">Creado Por</label>
                                    <input type="text" class="form-control" id="edit-creado_por" name="CreadoPor" readonly>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
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
                            <form id="deleteObjectForm" method="POST" action="../Seguridad/Objetos/Eliminar_Objeto.php">
                                <input type="hidden" id="delete_id_objeto" name="IdObjeto">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("../MainJs/MainJs.php"); ?>
            <script src="./Usuarios/js/objetos.js"></script>

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