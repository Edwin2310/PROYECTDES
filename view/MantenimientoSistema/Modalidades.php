<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 26; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>

    ?>

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Modalidades</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="Modalidades/Script_Modalidad.js"></script>
    </head>

    <body>
        <div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">
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
                        <h3 class="block-title">Gestión de Modalidades</h3>
                        <a href="./Modalidades/exportar_modalidad.php" class="btn btn-success mx-2">Descargar Excel</a>
                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Modalidades_Bloqueadas.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Modalidades Bloqueadas">
                                    <i class="fa fa-lock"></i>
                                </button>
                            </a>
                        </td>

                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModalidadModal">Añadir Modalidad</button>
                        <br><br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID MODALIDAD</th>
                                    <th class="text-center">MODALIDADES</th>
                                    <th class="text-center" style="width: 15%;">EDITAR MODALIDAD</th>
                                    <th class="text-center" style="width: 15%;">BLOQUEAR MODALIDAD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT m.IdModalidad, 
                                               m.NomModalidad
                                        FROM  `mantenimiento.tblmodalidades` m
                                        LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON m.IdVisibilidad = e.IdVisibilidad
                                        WHERE e.IdVisibilidad IN (1)    
                                        ORDER BY m.IdModalidad";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdModalidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomModalidad']}</td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editModalidadModal' 
                                                data-id='" . $row["IdModalidad"] . "' 
                                                data-NomModalidad='" . $row["NomModalidad"] . "'>
                                            <i class='si si-note'></i>
                                        </button>
                                    </td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModalidadModal' 
                                                data-id='" . $row["IdModalidad"] . "'>
                                            <i class='si si-trash'></i>
                                        </button>
                                    </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No hay datos disponibles</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar modalidades -->
            <div class="modal fade" id="addModalidadModal" tabindex="-1" role="dialog" aria-labelledby="addModalidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalidadModalLabel">Añadir Modalidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addModalidadForm" method="POST" action="../MantenimientoSistema/Modalidades/Agregar_Modalidad.php">
                                <div class="form-group">
                                    <label for="NomModalidad">Nombre Modalidad</label>
                                    <input type="text" class="form-control" id="NomModalidad" name="NomModalidad" maxlength="40" required oninput="validarNombreModalidad(this)" style="text-transform:uppercase;">
                                </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Añadir Modalidad</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar modalidades -->
        <div class="modal fade" id="editModalidadModal" tabindex="-1" role="dialog" aria-labelledby="editModalidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalidadModalLabel">Editar Modalidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editModalidadForm" method="POST" action="../MantenimientoSistema/Modalidades/Editar_Modalidad.php">
                            <input type="hidden" id="edit_IdModalidad" name="IdModalidad">
                            <div class="form-group">
                                <label for="edit_NomModalidad">Nombre Modalidad</label>
                                <input type="text" class="form-control" id="edit_NomModalidad" name="NomModalidad" maxlength="20" required oninput="validarNombreModalidad(this)" style="text-transform:uppercase;">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmar eliminación de modalidad -->
        <div class="modal fade" id="confirmDeleteModalidadModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalidadModalLabel">bloquear Modalidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres bloquear esta modalidad?</p>
                        <form id="deleteModalidadForm" method="POST" action="../MantenimientoSistema/Modalidades/Eliminar_Modalidad.php">
                            <input type="hidden" id="delete_IdModalidad" name="IdModalidad">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger">Bloquear</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
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
            $(document).ready(function() {
                $('#editModalidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var IdModalidad = button.data('id');
                    var NomModalidad = button.data('NomModalidad');

                    var modal = $(this);
                    modal.find('#edit_IdModalidad').val(IdModalidad);
                    modal.find('#edit_NomModalidad').val(NomModalidad);
                });

                $('#confirmDeleteModalidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var IdModalidad = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_IdModalidad').val(IdModalidad);
                });
            });
        </script>
    </body>

    </html>
<?php
} else {
    header("Location: ../../index.php");
}
?>