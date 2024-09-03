<?php
session_start();

require_once("../../config/conexion.php");
if (isset($_SESSION["ID_USUARIO"])) {

?>

<?php
    $id_rol = $_SESSION['ID_ROL'] ?? null;
    $id_objeto = 24; // ID del objeto o módulo correspondiente a esta página

    if (!$id_rol) {
        header("Location: ../Seguridad/Permisos/denegado.php");
        exit();
    }

    // Conectar a la base de datos
    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    // Verificar permiso en la base de datos
    $sql = "SELECT * FROM tbl_permisos WHERE ID_ROL = :idRol AND ID_OBJETO = :idObjeto";
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
        <title>Gestión de Universidades</title>
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
                        <h3 class="block-title">Gestión de Universidades</h3>
                        <a href="./Universidades/exportar_universidad.php" class="btn btn-success mx-2">Descargar Excel</a>

                    </div>
                    <div class="block-content block-content-full">
                        <td class="text-center">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUniversidadModal">Añadir Universidad</button>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID UNIVERSIDAD</th>
                                    <th class="d-none d-sm-table-cell">NOMBRE UNIVERSIDAD</th>
                                    <th class="d-none d-sm-table-cell">DEPARTAMENTO(SEDE PRINCIPAL)</th>
                                    <th class="d-none d-sm-table-cell">MUNICIPIO(SEDE PRINCIPAL)</th>
                                    <th class="text-center" style="width: 15%;">EDITAR UNIVERSIDAD</th>
                                    <th class="text-center" style="width: 15%;">ELIMINAR UNIVERSIDAD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT 
                                u.ID_UNIVERSIDAD, 
                                u.NOM_UNIVERSIDAD, 
                                d.ID_DEPARTAMENTO, 
                                d.NOM_DEPTO, 
                                m.ID_MUNICIPIO, 
                                m.NOM_MUNICIPIO
                            FROM 
                                tbl_universidad_centro u
                            JOIN 
                                tbl_deptos d ON u.ID_DEPARTAMENTO = d.ID_DEPARTAMENTO
                            JOIN 
                                tbl_municipios m ON u.ID_MUNICIPIO = m.ID_MUNICIPIO;
                           ORDER BY
                           u.ID_UNIVERSIDAD";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['ID_UNIVERSIDAD']}</td>";
                                        echo "<td>{$row['NOM_UNIVERSIDAD']}</td>";
                                        echo "<td>{$row['NOM_DEPTO']}</td>";
                                        echo "<td>{$row['NOM_MUNICIPIO']}</td>"; 
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editUniversidadModal' 
                                        data-id='" . $row["ID_UNIVERSIDAD"] . "' 
                                        data-nom_universidad='" . $row["NOM_UNIVERSIDAD"] . "' 
                                        data-nom_depto='" . $row["NOM_DEPTO"] . "' 
                                        data-nom_municipio='" . $row["NOM_MUNICIPIO"] . "' 
                                        >
                                    <i class='si si-note'></i>
                                </button>
                            </td>";
                                        echo "<td class='text-center'> 
                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteUniversidadModal' 
                                        data-id='" . $row["ID_UNIVERSIDAD"] . "'>
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

            <!-- Modal para agregar universidades -->
            <div class="modal fade" id="addUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="addUniversidadModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUniversidadodalLabel">Añadir Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Agregar_Universidades.php">
                                <div class="form-group">
                                    <label for="nom_universidad">Nombre Universidad</label>
                                    <input type="text" class="form-control" id="nom_universidad" name="nom_universidad" required>
                                </div>
                                <div class="form-group">
                                    <label for="nom_depto">Departamento</label>
                                    <select class="form-control" id="nom_depto" name="nom_depto" required>
                                    <?php
                                    // Cargar universidades desde tbl_deptos
                                    $departamentos = $conn->query("SELECT * FROM tbl_deptos");
                                    while ($depto = $departamentos->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$uni['ID_DEPTO']}'>{$uni['NOM_DEPTO']}</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                                <div class="form-group">
                                    <label for="nom_municipio">Municipio</label>
                                <select class="form-control" id="nom_municipio" name="nom_municipio" required>
                                    <?php
                                    // Cargar departamentos desde tbl_deptos
                                    $municipios = $conn->query("SELECT * FROM tbl_municipios");//PENDIENTE AÑADIR AJAX PARA CONSULTA EN TIEMPO REAL
                                    while ($muni = $municipios->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$uni['ID_MUNICIPIO']}'>{$uni['NOM_MUNICIPIO']}</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                                

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Añadir Universidad</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar categorías -->
            <div class="modal fade" id="editUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="editUniversidadModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUniversidadModalLabel">Editar Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editUniversidadForm" method="POST" action="../MantenimientoSistema/Universidad/Editar_Universidad.php">
                                <input type="hidden" id="edit_id_universidad" name="id_universidad">
                                <div class="form-group">
                                <label for="nom_universidad">Nombre Universidad</label>
                                <input type="text" class="form-control" id="nom_universidad" name="nom_universidad" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_universidad">Categoría</label>
                                    <select type="text" class="form-control" id="edit_universidad" name="universidad" required>
                                        <?php
                                        // Cargar departamentos desde tbl_deptos
                                        $departamentos = $conn->query("SELECT * FROM tbl_deptos");
                                        while ($depto = $departamentos->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$uni['ID_DEPTO']}'>{$uni['NOM_DEPTO']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_nom_municipio">Tipo de Solicitud</label>
                                    <select class="form-control" id="edit_nom_municipio" name="nom_municipio" required>
                                        <?php
                                        // Cargar municipios desde tbl_municipios
                                        $municipios = $conn->query("SELECT * FROM tbl_municipios");
                                        while ($muni = $municipios->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$uni['ID_MUNICIPIO']}'>{$uni['NOM_MUNICIPIO']}</option>";
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

            <!-- Modal para confirmar eliminación de categoría -->
            <div class="modal fade" id="confirmDeleteUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUniversidadModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteUniversidadModalLabel">Eliminar Categoría</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres eliminar esta universidad?</p>
                            <form id="deleteUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Eliminar_Universidad.php">
                                <input type="hidden" id="delete_id_universidad" name="id_universidad">
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
                $('#editUniversidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var nom_universidad = button.data('nom_universidad');
                    var departamento= button.data('nom_depto');
                    var municipio = button.data('nom_municipio');
               

                    var modal = $(this);
                    modal.find('#edit_id_universidad').val(id);
                    modal.find('#edit_nom_universidad').val(nom_universidad);
                    modal.find('#edit_nom_depto').val(departamento);
                    modal.find('#edit_nom_municipio').val(municipio);
                   
                });

                $('#confirmDeleteUniversidadModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');

                    var modal = $(this);
                    modal.find('#delete_id_universidad').val(id);
                });
            </script>
            <script src="Universidades/Script_Universidad.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>