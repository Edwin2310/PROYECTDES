<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 25; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Carreras</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="Carreras/Script_Carrera.js"></script>

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
                        <h3 class="block-title">Gestión de Carreras</h3>
                        <a href="./Carreras/exportar_carrera.php" class="btn btn-success mx-2">Descargar Excel</a>

                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Carreras_Bloqueadas.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Carreras Bloqueadas">
                                    <i class="fa fa-lock"></i>
                                </button>
                            </a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCareerModal">Añadir Carrera</button>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID CARRERA</th>
                                    <th class="text-center">CARRERAS</th>
                                    <th class="text-center">UNIVERSIDADES</th>
                                    <th class="text-center">MODALIDADES</th>
                                    <th class="text-center">GRADOS ACADÉMICOS</th>
                                    <th class="text-center" style="width: 15%;">EDITAR CARRERA</th>
                                    <th class="text-center" style="width: 15%;">BLOQUEAR CARRERA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT
                            c.IdCarrera,
                            c.NomCarrera,
                            uc.NomUniversidad,
                            m.NomModalidad,
                            g.NomGrado,
                            c.IdUniversidad,
                            c.IdModalidad,
                            c.IdGrado
                            FROM
                            `mantenimiento.tblcarreras` c
                            LEFT JOIN `mantenimiento.tbluniversidades` uc ON c.IdUniversidad = uc.IdUniversidad
                            LEFT JOIN `mantenimiento.tblmodalidades` m ON c.IdModalidad = m.IdModalidad
                            LEFT JOIN `mantenimiento.tblgradosacademicos` g ON c.IdGrado = g.IdGrado
                            LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON c.IdVisibilidad = e.IdVisibilidad
                            WHERE e.IdVisibilidad IN (1)
                            ORDER BY c.IdCarrera";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdCarrera']}</td>";
                                        echo "<td class='text-center'>{$row['NomCarrera']}</td>";
                                        echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomModalidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomGrado']}</td>";
                                        echo "<td class='text-center'> 
                                            <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editCareerModal' 
                                                    data-id='" . $row["IdCarrera"] . "' 
                                                    data-NomCarrera='" . $row["NomCarrera"] . "' 
                                                    data-IdUniversidad='" . $row["IdUniversidad"] . "' 
                                                    data-IdModalidad='" . $row["IdModalidad"] . "' 
                                                    data-IdGrado='" . $row["IdGrado"] . "'>
                                                <i class='si si-note'></i>
                                            </button>
                                        </td>";
                                        echo "<td class='text-center'> 
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteCareerModal' 
                                                data-id='" . $row["IdCarrera"] . "'>
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

            <!-- Modal para agregar carreras -->
            <div class="modal fade" id="addCareerModal" tabindex="-1" role="dialog" aria-labelledby="addCareerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCareerModalLabel">Añadir Carrera</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addCareerForm" method="POST" action="../MantenimientoSistema/Carreras/Agregar_Carrera.php">
                                <div class="form-group">
                                    <label for="NomCarrera">Nombre Carrera</label>
                                    <input type="text" class="form-control" id="NomCarrera" name="NomCarrera" maxlength="30" required oninput="validarCarrera(this)" style="text-transform:uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="IdUniversidad">Universidad</label>
                                    <select class="form-control" id="IdUniversidad" name="IdUniversidad" required>
                                        <?php
                                        // Cargar universidades desde `mantenimiento.tbluniversidades`
                                        $universidades = $conn->query("SELECT * FROM `mantenimiento.tbluniversidades`");
                                        while ($uni = $universidades->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$uni['IdUniversidad']}'>{$uni['NomUniversidad']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="IdModalidad">Modalidad</label>
                                    <select class="form-control" id="IdModalidad" name="IdModalidad" required>
                                        <?php
                                        // Cargar modalidades desde `mantenimiento.tblmodalidades`
                                        $modalidades = $conn->query("SELECT * FROM `mantenimiento.tblmodalidades`");
                                        while ($mod = $modalidades->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$mod['IdModalidad']}'>{$mod['NomModalidad']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="IdGrado">Grado Académico</label>
                                    <select class="form-control" id="IdGrado" name="IdGrado" required>
                                        <?php
                                        // Cargar grados desde `mantenimiento.tblgradosacademicos`
                                        $grados = $conn->query("SELECT * FROM `mantenimiento.tblgradosacademicos`");
                                        while ($gra = $grados->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$gra['IdGrado']}'>{$gra['NomGrado']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Carrera</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar carreras -->
            <div class="modal fade" id="editCareerModal" tabindex="-1" role="dialog" aria-labelledby="editCareerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCareerModalLabel">Editar Carrera</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editCareerForm" method="POST" action="../MantenimientoSistema/Carreras/Editar_Carrera.php">
                                <input type="hidden" id="edit_IdCarrera" name="IdCarrera">
                                <div class="form-group">
                                    <label for="edit_NomCarrera">Nombre Carrera</label>
                                    <input type="text" class="form-control" id="edit_NomCarrera" name="NomCarrera" maxlength="30" required oninput="validarCarrera(this)" style="text-transform:uppercase;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_IdUniversidad">Universidad</label>
                                    <select class="form-control" id="edit_IdUniversidad" name="IdUniversidad" required>
                                        <?php
                                        // Cargar universidades desde `mantenimiento.tbluniversidades`
                                        $universidades = $conn->query("SELECT * FROM `mantenimiento.tbluniversidades`");
                                        while ($uni = $universidades->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$uni['IdUniversidad']}'>{$uni['NomUniversidad']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_IdModalidad">Modalidad</label>
                                    <select class="form-control" id="edit_IdModalidad" name="IdModalidad" required>
                                        <?php
                                        // Cargar modalidades desde `mantenimiento.tblmodalidades`
                                        $modalidades = $conn->query("SELECT * FROM `mantenimiento.tblmodalidades`");
                                        while ($mod = $modalidades->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$mod['IdModalidad']}'>{$mod['NomModalidad']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_IdGrado">Grado Académico</label>
                                    <select class="form-control" id="edit_IdGrado" name="IdGrado" required>
                                        <?php
                                        // Cargar grados desde `mantenimiento.tblgradosacademicos`
                                        $grados = $conn->query("SELECT * FROM `mantenimiento.tblgradosacademicos`");
                                        while ($gra = $grados->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$gra['IdGrado']}'>{$gra['NomGrado']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar eliminación de carrera -->
            <div class="modal fade" id="confirmDeleteCareerModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCareerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteCareerModalLabel">Bloquear Carrera</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres bloquear esta carrera?</p>
                            <form id="deleteCareerForm" method="POST" action="../MantenimientoSistema/Carreras/Eliminar_Carrera.php">
                                <input type="hidden" id="delete_IdCarrera" name="IdCarrera">
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
            // Código JavaScript para manejar los modales
            $('#editCareerModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var NomCarrera = button.data('NomCarrera');
                var IdUniversidad = button.data('IdUniversidad');
                var IdModalidad = button.data('IdModalidad');
                var IdGrado = button.data('IdGrado');

                var modal = $(this);
                modal.find('#edit_IdCarrera').val(id);
                modal.find('#edit_NomCarrera').val(NomCarrera);
                modal.find('#edit_IdUniversidad').val(IdUniversidad);
                modal.find('#edit_IdModalidad').val(IdModalidad);
                modal.find('#edit_IdGrado').val(IdGrado);
            });


            $('#confirmDeleteCareerModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                var modal = $(this);
                modal.find('#delete_IdCarrera').val(id);
            });
        </script>

        <script>
            function validarCarrera(input) {
                // Remueve caracteres que no sean letras, números o espacios
                input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóú\s]/g, '');

                // Convierte el texto a mayúsculas
                input.value = input.value.toUpperCase();
            }
        </script>

        <script src="Carreras/Script_Carrera.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>