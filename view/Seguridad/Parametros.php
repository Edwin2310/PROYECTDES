<?php
session_start();
require_once("../../config/conexion.php");
require_once(__DIR__ . '/Script/Funciones.php');
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 18; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Parámetros</title>
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
                        <h3 class="block-title">Parámetros del Sistema</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">Añadir Parametro</button>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Parámetro</th>
                                    <th class="d-none d-sm-table-cell">Parámetro</th>
                                    <th class="d-none d-sm-table-cell">Valor</th>
                                    <th class="d-none d-sm-table-cell">Fecha Creación</th>
                                    <th class="d-none d-sm-table-cell">Creado Por</th>
                                    <th class="d-none d-sm-table-cell">Fecha Modificación</th>
                                    <th class="d-none d-sm-table-cell">Modificado Por</th>
                                    <th class="text-center" style="width: 15%;">Editar Parámetro</th>
                                    <th class="text-center" style="width: 15%;">Eliminar Parámetro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();

                                // Llamada al procedimiento almacenado
                                $stmt = $conn->prepare("CALL splParametrosMostrar(:usuario)");
                                $stmt->bindValue(':usuario', $_SESSION["IdUsuario"], PDO::PARAM_STR);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($result !== false && count($result) > 0) {
                                    foreach ($result as $row) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['ID_PARAMETRO']}</td>";
                                        echo "<td>{$row['PARAMETRO']}</td>";
                                        echo "<td>{$row['VALOR']}</td>";
                                        echo "<td>{$row['FECHA_CREACION']}</td>";
                                        echo "<td>{$row['CREADO_POR']}</td>";
                                        echo "<td>{$row['FECHA_MODIFICACION']}</td>";
                                        echo "<td>{$row['MODIFICADO_POR']}</td>";
                                        echo "<td class='text-center'> 
                                            <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editParamModal' 
                                                    data-id='" . $row["ID_PARAMETRO"] . "' 
                                                    data-parametro='" . $row["PARAMETRO"] . "' 
                                                    data-valor='" . $row["VALOR"] . "'>
                                                <i class='si si-note'></i>
                                            </button>
                                        </td>";
                                        echo "<td class='text-center'> 
                                            <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteParamModal' 
                                                    data-id='" . $row["ID_PARAMETRO"] . "'>
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
                </div>
            </div>

            </main>

            <?php require_once("../MainFooter/MainFooter.php"); ?>


            <!-- Modal para agregar usuarios -->
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Añadir Parámetro</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm" method="POST" action="../Seguridad/Parametros/Agregar_Parametro.php">
                                <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['IdUsuario']; ?>">

                                <div class="form-group">
                                    <label for="parametro">Parámetro</label>
                                    <input type="text" class="form-control" id="parametro" name="parametro" required>
                                </div>
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input type="text" class="form-control" id="valor" name="valor" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal para editar parámetros -->
            <div class="modal fade" id="editParamModal" tabindex="-1" role="dialog" aria-labelledby="editParamModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editParamModalLabel">Editar Parámetro</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editParamForm" method="POST" action="../Seguridad/Parametros/Editar_Parametro.php">
                                <input type="hidden" id="edit_id_parametro" name="id_parametro">
                                <div class="form-group">
                                    <label for="edit_parametro">Parámetro</label>
                                    <input type="text" class="form-control" id="edit_parametro" name="parametro" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_valor">Valor</label>
                                    <input type="text" class="form-control" id="edit_valor" name="valor" required>
                                </div>
                                <input type="hidden" id="edit_id_usuario" name="id_usuario">
                                <input type="hidden" id="edit_creado_por" name="creado_por">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación para eliminar parámetros -->
            <div class="modal fade" id="confirmDeleteParamModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteParamModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteParamModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar este parámetro?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <form id="deleteParamForm" method="POST" action="../Seguridad/Parametros/Eliminar_Parametro.php">
                                <input type="hidden" id="delete_id_parametro" name="id_parametro">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php require_once("../MainJs/MainJs.php"); ?>
        <script>
            $('#editParamModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var parametro = button.data('parametro');
                var valor = button.data('valor');

                var modal = $(this);
                modal.find('.modal-body #edit_id_parametro').val(id);
                modal.find('.modal-body #edit_parametro').val(parametro);
                modal.find('.modal-body #edit_valor').val(valor);
            });

            $('#confirmDeleteParamModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                var modal = $(this);
                modal.find('.modal-footer #delete_id_parametro').val(id);
            });
        </script>
        <script>
            $(document).ready(function() {
                <?php if (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])) : ?>
                    $('#agregarUsuarioModal').modal('show');
                <?php endif; ?>
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../Seguridad//Bitacora//Bitacora.js"></script>
        <script src="./Usuarios/js/parametros.js"></script>
    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>