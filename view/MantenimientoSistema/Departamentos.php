<?php
session_start();

require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {
?>

<?php
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 22; // ID del objeto o módulo correspondiente a esta página

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
        <title>Gestión de Modalidades</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script src="Departamentos/Script_Departamento.js"></script>
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
                        <h3 class="block-title">Gestión de Departamentos</h3>
                        <a href="./Departamentos/exportar_departamento.php" class="btn btn-success mx-2">Descargar Excel</a>
                    </div>
                    <div class="block-content block-content-full">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addDepartamentoModal">Añadir Departamento</button>
                        <br><br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID DEPARTAMENTO</th>
                                    <th class="d-none d-sm-table-cell">DEPARTAMENTOS</th>
                                    <th class="text-center" style="width: 15%;">EDITAR DEPARTAMENTO</th>
                                    <th class="text-center" style="width: 15%;">ELIMINAR DEPARTAMENTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT ID_DEPARTAMENTO, NOM_DEPTO FROM tbl_deptos ORDER BY ID_DEPARTAMENTO";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['ID_DEPARTAMENTO']}</td>";
                                        echo "<td>{$row['NOM_DEPTO']}</td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editDepartamentoModal' 
                                                data-id='" . $row["ID_DEPARTAMENTO"] . "' 
                                                data-nom_departamento='" . $row["NOM_DEPTO"] . "'>
                                            <i class='si si-note'></i>
                                        </button>
                                    </td>";
                                        echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteDepartamentoModal' 
                                                data-id='" . $row["ID_DEPARTAMENTO"] . "'>
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

            <!-- Modal para agregar departamentos -->
            <div class="modal fade" id="addDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="addDepartamentoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDepartamentoModalLabel">Añadir Departamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addDepartamentoForm" method="POST" action="../MantenimientoSistema/Departamentos/Agregar_Departamento.php">
                                <div class="form-group">
                                    <label for="nom_departamento">Nombre Departamento</label>
                                    <input type="text" class="form-control" id="nom_departamento" name="nom_departamento" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Departamento</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal para editar departamentos -->
            <div class="modal fade" id="editDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="editDepartamentoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDepartamentoModalLabel">Editar Departamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editDepartamentoForm" method="POST" action="../MantenimientoSistema/Departamentos/Editar_Departamento.php">
                                <input type="hidden" id="edit_id_departamento" name="id_departamento">
                                <div class="form-group">
                                    <label for="edit_nom_modalidad">Nombre Depatamento</label>
                                    <input type="text" class="form-control" id="edit_nom_departamento" name="nom_departamento" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de departamento -->
            <div class="modal fade" id="confirmDeleteDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteDepartamentoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteDepartamentoModalLabel">Eliminar Departamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres eliminar esta departamento?</p>
                            <form id="deleteDepartamentoForm" method="POST" action="../MantenimientoSistema/Departamentos/Eliminar_Departamento.php">
                                <input type="hidden" id="delete_id_departamento" name="id_departamento">
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
            $(document).ready(function() {
                $('#editDepartamentoModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id_departamento = button.data('id');
                    var nom_departamento = button.data('nom_departamento');

                    var modal = $(this);
                    modal.find('#edit_id_departamento').val(id_departamento);
                    modal.find('#edit_nom_departamento').val(nom_departamento);
                });

                $('#confirmDeleteDepartamentoModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id_departamento = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_id_departamento').val(id_departamento);
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