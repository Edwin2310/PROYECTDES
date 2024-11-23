<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 27; // ID del objeto o módulo correspondiente a esta página

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
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addGradoModal">Añadir Grado Académico</button>
                    <br><br>
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center">ID GRADO</th>
                                <th class="d-none d-sm-table-cell">GRADOS ACADÉMICOS</th>
                                <th class="text-center" style="width: 15%;">EDITAR GRADO ACADÉMICO</th>
                                <th class="text-center" style="width: 15%;">ELIMINAR GRADO ACADÉMICO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../../config/conexion.php");
                            $conexion = new Conectar();
                            $conn = $conexion->Conexion();
                            $sql = "SELECT ID_GRADO, NOM_GRADO FROM tbl_grado_academico ORDER BY ID_GRADO";
                            
                            $result = $conn->query($sql);
                            if ($result !== false && $result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$row['ID_GRADO']}</td>";
                                    echo "<td>{$row['NOM_GRADO']}</td>";
                                    echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editGradoModal' 
                                                data-id='" . $row["ID_GRADO"] . "' 
                                                data-nom_grado='" . $row["NOM_GRADO"] . "'>
                                            <i class='si si-note'></i>
                                        </button>
                                    </td>";
                                    echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteGradoModal' 
                                                data-id='" . $row["ID_GRADO"] . "'>
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
        <div class="modal fade" id="addGradoModal" tabindex="-1" role="dialog" aria-labelledby="addGradoModalLabel" aria-hidden="true">
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
                                <label for="nom_grado">Grado Académico</label>
                                <input type="text" class="form-control" id="nom_grado" name="nom_grado" required>
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
        <div class="modal fade" id="editGradoModal" tabindex="-1" role="dialog" aria-labelledby="editGradoModalLabel" aria-hidden="true">
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
                            <input type="hidden" id="edit_id_grado" name="id_grado">
                            <div class="form-group">
                                <label for="edit_nom_grado">Grado Académico</label>
                                <input type="text" class="form-control" id="edit_nom_grado" name="nom_grado" required>
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
        <div class="modal fade" id="confirmDeleteGradoModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteGradoGradoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteGradoModalLabel">Eliminar Grado Académico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este grado académico?</p>
                        <form id="deleteGradoForm" method="POST" action="../MantenimientoSistema/GradosAcademicos/Eliminar_GradoAcademico.php">
                            <input type="hidden" id="delete_id_grado" name="id_grado">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
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
        $(document).ready(function () {
            $('#editGradoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id_grado = button.data('id');
                var nom_grado = button.data('nom_grado');

                var modal = $(this);
                modal.find('#edit_id_grado').val(id_grado);
                modal.find('#edit_nom_grado').val(nom_grado);
            });

            $('#confirmDeleteGradoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id_grado = button.data('id');

                var modal = $(this);
                modal.find('#delete_id_grado').val(id_grado);
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