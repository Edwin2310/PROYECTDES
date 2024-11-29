<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 27; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Grados Académicos</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="GradosAcademicos/Script_GradoAcademico.js"></script>
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
                        <h3 class="block-title">Gestión de Grados Académicos</h3>
                        <a href="./GradosAcademicos/exportar_gradosacademicos.php" class="btn btn-success mx-2">Descargar Excel</a>

                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="GradosAcademicos_Bloqueados.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Grados Académicos Bloqueados">
                                    <i class="fa fa-lock"></i>
                                </button>
                            </a>
                        </td>
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addGradoModal">Añadir Grado Académico</button>
                        <br><br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID GRADO</th>
                                    <th class="text-center">GRADOS ACADÉMICOS</th>
                                    <th class="text-center" style="width: 15%;">EDITAR GRADO ACADÉMICO</th>
                                    <th class="text-center" style="width: 15%;">BLOQUEAR GRADO ACADÉMICO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT g.IdGrado, 
                                           g.NomGrado 
                                    FROM `mantenimiento.tblgradosacademicos` g
                                    LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON g.IdVisibilidad = e.IdVisibilidad
                                    WHERE e.IdVisibilidad IN (1)
                                    ORDER BY g.IdGrado";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdGrado']}</td>";
                                        echo "<td class='text-center'>{$row['NomGrado']}</td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editGradoModal' 
                                                data-id='" . $row["IdGrado"] . "' 
                                                data-NomGrado='" . $row["NomGrado"] . "'>
                                            <i class='si si-note'></i>
                                        </button>
                                    </td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteGradoModal' 
                                                data-id='" . $row["IdGrado"] . "'>
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

            <!-- Modal para agregar grados académicos -->
            <div class="modal fade" id="addGradoModal" tabindex="-1" role="dialog" aria-labelledby="addGradoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addGradoModalLabel">Añadir Grado Académico</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addGradoForm" method="POST" action="../MantenimientoSistema/GradosAcademicos/Agregar_GradoAcademico.php">
                                <div class="form-group">
                                    <label for="NomGrado">Grado Académico</label>
                                    <input type="text" class="form-control" id="NomGrado" name="NomGrado" required oninput="validarGradoAcademico(this)" style="text-transform:uppercase;">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Grado Académico</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar grados académicos -->
            <div class="modal fade" id="editGradoModal" tabindex="-1" role="dialog" aria-labelledby="editGradoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editGradoModalLabel">Editar Grado Académico</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editGradoForm" method="POST" action="../MantenimientoSistema/GradosAcademicos/Editar_GradoAcademico.php">
                                <input type="hidden" id="edit_IdGrado" name="IdGrado">
                                <div class="form-group">
                                    <label for="edit_NomGrado">Grado Académico</label>
                                    <input type="text" class="form-control" id="edit_NomGrado" name="NomGrado" maxlength="30" required oninput="validarGradoAcademico(this)" style="text-transform:uppercase;">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de grados académicos -->
            <div class="modal fade" id="confirmDeleteGradoModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteGradoGradoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteGradoModalLabel">Bloquear Grado Académico</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres bloquear este grado académico?</p>
                            <form id="deleteGradoForm" method="POST" action="../MantenimientoSistema/GradosAcademicos/Eliminar_GradoAcademico.php">
                                <input type="hidden" id="delete_IdGrado" name="IdGrado">
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
                $('#editGradoModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var IdGrado = button.data('id');
                    var NomGrado = button.data('NomGrado');

                    var modal = $(this);
                    modal.find('#edit_IdGrado').val(IdGrado);
                    modal.find('#edit_NomGrado').val(NomGrado);
                });

                $('#confirmDeleteGradoModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var IdGrado = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_IdGrado').val(IdGrado);
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