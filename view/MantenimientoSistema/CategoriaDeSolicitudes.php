<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 29; // ID del objeto o módulo correspondiente a esta página

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
        <title>Gestión de Categorias</title>
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
                        <h3 class="block-title">Gestión de Plan de Arbitrios/Categorias</h3>
                        <a href="./Categorias/exportar_categoria.php" class="btn btn-success mx-2">Descargar Excel</a>

                    </div>
                    <div class="block-content block-content-full">
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCategoriaModal">Añadir Categoria</button>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID CATEGORIA</th>
                                    <th class="d-none d-sm-table-cell">CODIGO PLAN DE ARBITRIOS</th>
                                    <th class="d-none d-sm-table-cell">CATEGORIA</th>
                                    <th class="d-none d-sm-table-cell">TIPO DE SOLICITUD</th>
                                    <th class="d-none d-sm-table-cell">MONTO</th>
                                    <th class="text-center" style="width: 15%;">EDITAR CATEGORIA</th>
                                    <th class="text-center" style="width: 15%;">ELIMINAR CATEGORIA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT
                        c.ID_CATEGORIA,
                        c.COD_ARBITRIOS,
                        c.NOM_CATEGORIA,
                        t.NOM_TIPO,  -- Aquí seleccionamos el nombre del tipo de solicitud
                        c.MONTO
                    FROM
                        tbl_categoria c
                    LEFT JOIN
                        tbl_tipo_solicitud t ON c.ID_TIPO_SOLICITUD = t.ID_TIPO_SOLICITUD
                    ORDER BY
                        c.ID_CATEGORIA";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['ID_CATEGORIA']}</td>";
                                        echo "<td>{$row['COD_ARBITRIOS']}</td>";
                                        echo "<td>{$row['NOM_CATEGORIA']}</td>";
                                        echo "<td>{$row['NOM_TIPO']}</td>";  // Aquí mostramos el nombre del tipo
                                        echo "<td>{$row['MONTO']}</td>";
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editCategoriaModal' 
                                        data-id='" . $row["ID_CATEGORIA"] . "' 
                                        data-cod_arbitrios='" . $row["COD_ARBITRIOS"] . "' 
                                        data-nom_categoria='" . $row["NOM_CATEGORIA"] . "' 
                                        data-id_tipo_solicitud='" . $row["NOM_TIPO"] . "' 
                                        data-monto='" . $row["MONTO"] . "'>
                                    <i class='si si-note'></i>
                                </button>
                            </td>";
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteCategoriaModal' 
                                        data-id='" . $row["ID_CATEGORIA"] . "'>
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

            <!-- Modal para agregar categorias -->
            <div class="modal fade" id="addCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="addCategoriaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoriaModalLabel">Añadir Categoria</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addCategoriaForm" method="POST" action="../MantenimientoSistema/Categorias/Agregar_Categoria.php">
                                <div class="form-group">
                                    <label for="cod_arbitrios">Codigo del Plan de Arbitrios</label>
                                    <input type="text" class="form-control" id="cod_arbitrios" name="cod_arbitrios" required>
                                </div>
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <input type="text" class="form-control" id="categoria" name="categoria" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_tipo_solicitud">Tipo de Solicitud</label>
                                    <select class="form-control" id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                                        <?php
                                        // Cargar universidades desde tbl_universidad_centro
                                        $tipo_solicitud = $conn->query("SELECT * FROM tbl_tipo_solicitud");
                                        while ($tipo_sol = $tipo_solicitud->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$tipo_sol['ID_TIPO_SOLICITUD']}'>{$tipo_sol['NOM_TIPO']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="monto">Monto</label>
                                    <input type="text" class="form-control" id="monto" name="monto" required>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Categoria</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar categorías -->
            <div class="modal fade" id="editCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="editCategoriaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoriaModalLabel">Editar Categoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editCategoriaForm" method="POST" action="../MantenimientoSistema/Categorias/Editar_Categoria.php">
                                <input type="hidden" id="edit_id_categoria" name="id_categoria">
                                <div class="form-group">
                                    <label for="edit_cod_arbitrios">Código del Plan de Arbitrios</label>
                                    <input type="text" class="form-control" id="edit_cod_arbitrios" name="cod_arbitrios" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_categoria">Categoría</label>
                                    <input type="text" class="form-control" id="edit_categoria" name="categoria" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_tipo_solicitud">Tipo de Solicitud</label>
                                    <select class="form-control" id="edit_id_tipo_solicitud" name="id_tipo_solicitud" required>
                                        <?php
                                        // Cargar tipos de solicitud desde tbl_tipo_solicitud
                                        $tipo_solicitud = $conn->query("SELECT * FROM tbl_tipo_solicitud");
                                        while ($tipo_sol = $tipo_solicitud->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$tipo_sol['ID_TIPO_SOLICITUD']}'>{$tipo_sol['NOM_TIPO']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_monto">Monto</label>
                                    <input type="text" class="form-control" id="edit_monto" name="monto" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de categoría -->
            <div class="modal fade" id="confirmDeleteCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCategoriaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteCategoriaModalLabel">Eliminar Categoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres eliminar esta categoría?</p>
                            <form id="deleteCategoriaForm" method="POST" action="../MantenimientoSistema/Categorias/Eliminar_Categoria.php">
                                <input type="hidden" id="delete_id_categoria" name="id_categoria">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
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
                // Código JavaScript para manejar los modales de editar y eliminar categorías
                $('#editCategoriaModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var cod_arbitrios = button.data('cod_arbitrios');
                    var categoria = button.data('nom_categoria');
                    var id_tipo_solicitud = button.data('id_tipo_solicitud');
                    var monto = button.data('monto');

                    var modal = $(this);
                    modal.find('#edit_id_categoria').val(id);
                    modal.find('#edit_cod_arbitrios').val(cod_arbitrios);
                    modal.find('#edit_categoria').val(categoria);
                    modal.find('#edit_id_tipo_solicitud').val(id_tipo_solicitud);
                    modal.find('#edit_monto').val(monto);
                });

                $('#confirmDeleteCategoriaModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_id_categoria').val(id);
                });
            </script>
            <script src="Categoria/Script_Categoria.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>