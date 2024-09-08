<?php
session_start();

require_once("../../config/conexion.php");
if (isset($_SESSION["IdUsuario"])) {

?>

<?php
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 25; // ID del objeto o módulo correspondiente a esta página

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
                    <td class="text-center">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCareerModal">Añadir Carrera</button>
                    </td>
                    <br>
                    <br>
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center">ID CARRERA</th>
                                <th class="d-none d-sm-table-cell">CARRERAS</th>
                                <th class="d-none d-sm-table-cell">UNIVERSIDADES</th>
                                <th class="d-none d-sm-table-cell">MODALIDADES</th>
                                <th class="d-none d-sm-table-cell">GRADOS ACADÉMICOS</th>
                                <th class="text-center" style="width: 15%;">EDITAR CARRERA</th>
                                <th class="text-center" style="width: 15%;">ELIMINAR CARRERA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../../config/conexion.php");
                            $conexion = new Conectar();
                            $conn = $conexion->Conexion();
                            $sql = "SELECT
                            c.ID_CARRERA,
                            c.NOM_CARRERA,
                            uc.NOM_UNIVERSIDAD,
                            m.NOM_MODALIDAD,
                            g.NOM_GRADO,
                            c.ID_UNIVERSIDAD,
                            c.ID_MODALIDAD,
                            c.ID_GRADO
                            FROM
                            tbl_carrera c
                            LEFT JOIN tbl_universidad_centro uc ON c.ID_UNIVERSIDAD = uc.ID_UNIVERSIDAD
                            LEFT JOIN tbl_modalidad m ON c.ID_MODALIDAD = m.ID_MODALIDAD
                            LEFT JOIN tbl_grado_academico g ON c.ID_GRADO = g.ID_GRADO
                            ORDER BY
                            c.ID_CARRERA";
                            
                            $result = $conn->query($sql);
                            if ($result !== false && $result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$row['ID_CARRERA']}</td>";
                                    echo "<td>{$row['NOM_CARRERA']}</td>";
                                    echo "<td>{$row['NOM_UNIVERSIDAD']}</td>";
                                    echo "<td>{$row['NOM_MODALIDAD']}</td>";
                                    echo "<td>{$row['NOM_GRADO']}</td>";
                                    echo "<td class='text-center'> 
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editCareerModal' 
                                                data-id='" . $row["ID_CARRERA"] . "' 
                                                data-nom_carrera='" . $row["NOM_CARRERA"] . "' 
                                                data-id_universidad='" . $row["ID_UNIVERSIDAD"] . "' 
                                                data-id_modalidad='" . $row["ID_MODALIDAD"] . "' 
                                                data-id_grado='" . $row["ID_GRADO"] . "'>
                                            <i class='si si-note'></i>
                                        </button>
                                    </td>";
                                    echo "<td class='text-center'> 
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteCareerModal' 
                                                data-id='" . $row["ID_CARRERA"] . "'>
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
        <div class="modal fade" id="addCareerModal" tabindex="-1" role="dialog" aria-labelledby="addCareerModalLabel" aria-hidden="true">
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
                                <label for="nom_carrera">Nombre Carrera</label>
                                <input type="text" class="form-control" id="nom_carrera" name="nom_carrera" required>
                            </div>
                            <div class="form-group">
                                <label for="id_universidad">Universidad</label>
                                <select class="form-control" id="id_universidad" name="id_universidad" required>
                                    <?php
                                    // Cargar universidades desde tbl_universidad_centro
                                    $universidades = $conn->query("SELECT * FROM tbl_universidad_centro");
                                    while ($uni = $universidades->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$uni['ID_UNIVERSIDAD']}'>{$uni['NOM_UNIVERSIDAD']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_modalidad">Modalidad</label>
                                <select class="form-control" id="id_modalidad" name="id_modalidad" required>
                                    <?php
                                    // Cargar modalidades desde tbl_modalidad
                                    $modalidades = $conn->query("SELECT * FROM tbl_modalidad");
                                    while ($mod = $modalidades->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$mod['ID_MODALIDAD']}'>{$mod['NOM_MODALIDAD']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_grado">Grado Académico</label>
                                <select class="form-control" id="id_grado" name="id_grado" required>
                                    <?php
                                    // Cargar grados desde tbl_grado_academico
                                    $grados = $conn->query("SELECT * FROM tbl_grado_academico");
                                    while ($gra = $grados->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$gra['ID_GRADO']}'>{$gra['NOM_GRADO']}</option>";
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
        <div class="modal fade" id="editCareerModal" tabindex="-1" role="dialog" aria-labelledby="editCareerModalLabel" aria-hidden="true">
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
                            <input type="hidden" id="edit_id_carrera" name="id_carrera">
                            <div class="form-group">
                                <label for="edit_nom_carrera">Nombre Carrera</label>
                                <input type="text" class="form-control" id="edit_nom_carrera" name="nom_carrera" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_universidad">Universidad</label>
                                <select class="form-control" id="edit_id_universidad" name="id_universidad" required>
                                    <?php
                                    // Cargar universidades desde tbl_universidad_centro
                                    $universidades = $conn->query("SELECT * FROM tbl_universidad_centro");
                                    while ($uni = $universidades->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$uni['ID_UNIVERSIDAD']}'>{$uni['NOM_UNIVERSIDAD']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_modalidad">Modalidad</label>
                                <select class="form-control" id="edit_id_modalidad" name="id_modalidad" required>
                                    <?php
                                    // Cargar modalidades desde tbl_modalidad
                                    $modalidades = $conn->query("SELECT * FROM tbl_modalidad");
                                    while ($mod = $modalidades->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$mod['ID_MODALIDAD']}'>{$mod['NOM_MODALIDAD']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_grado">Grado Académico</label>
                                <select class="form-control" id="edit_id_grado" name="id_grado" required>
                                    <?php
                                    // Cargar grados desde tbl_grado_academico
                                    $grados = $conn->query("SELECT * FROM tbl_grado_academico");
                                    while ($gra = $grados->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$gra['ID_GRADO']}'>{$gra['NOM_GRADO']}</option>";
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
        <div class="modal fade" id="confirmDeleteCareerModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCareerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteCareerModalLabel">Eliminar Carrera</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar esta carrera?</p>
                        <form id="deleteCareerForm" method="POST" action="../MantenimientoSistema/Carreras/Eliminar_Carrera.php">
                            <input type="hidden" id="delete_id_carrera" name="id_carrera">
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
        // Código JavaScript para manejar los modales
        $('#editCareerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nom_carrera = button.data('nom_carrera');
            var id_universidad = button.data('id_universidad');
            var id_modalidad = button.data('id_modalidad');
            var id_grado = button.data('id_grado');

            var modal = $(this);
            modal.find('#edit_id_carrera').val(id);
            modal.find('#edit_nom_carrera').val(nom_carrera);
            modal.find('#edit_id_universidad').val(id_universidad);
            modal.find('#edit_id_modalidad').val(id_modalidad);
            modal.find('#edit_id_grado').val(id_grado);
        });

        $('#confirmDeleteCareerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modal = $(this);
            modal.find('#delete_id_carrera').val(id);
        });
    </script>
    <script src="Carreras/Script_Carrera.js"></script> 

</body>

</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>