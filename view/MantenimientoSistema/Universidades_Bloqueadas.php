<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 24; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Universidades Bloqueadas</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="Universidades/Script_Universidad.js"></script>
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
                        <h3 class="block-title">Gestión de Universidades Bloqueadas</h3>

                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Universidades.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Universidades Habilitadas">
                                    <i class="fa fa-unlock-alt"></i>
                                </button>
                            </a>
                        </td>
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID UNIVERSIDAD</th>
                                    <th class="text-center">UNIVERSIDAD</th>
                                    <th class="text-center" style="width: 15%;">HABILITAR UNIVERSIDAD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT 
                                        u.IdUniversidad, 
                                        u.NomUniversidad
                                        FROM 
                                        `mantenimiento.tbluniversidades` u
                                        LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON u.IdVisibilidad = e.IdVisibilidad
                                        WHERE e.IdVisibilidad IN (2)
                                        ORDER BY
                                        u.IdUniversidad";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdUniversidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                        echo "<td class='text-center'>
                                    <button type='button' class='btn btn-sm btn-success' data-toggle='modal' data-target='#confirmEnableUniversidadModal' 
                                            data-id='" . $row["IdUniversidad"] . "'>
                                        <i class='si si-check'></i> Habilitar
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

            <!-- Modal para confirmar habilitación de Universidad -->
            <div class="modal fade" id="confirmEnableUniversidadModal" tabindex="-1" role="dialog" aria-labelledby="confirmEnableUniversidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmEnableUniversidadModalLabel">Habilitar Universidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres habilitar esta Universidad?</p>
                            <form id="enableUniversidadForm" method="POST" action="../MantenimientoSistema/Universidades/Habilitar_Universidad.php">
                                <input type="hidden" id="enable_IdUniversidad" name="IdUniversidad">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-success">Habilitar</button>
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
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <script>
            // JavaScript para pasar el IdUniversidad al formulario
            $('#confirmEnableUniversidadModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que abrió el modal
                var IdUniversidad = button.data('id'); // Extraer el ID de Universidad
                var modal = $(this);
                modal.find('.modal-body #enable_IdUniversidad').val(IdUniversidad); // Establecer el valor en el campo oculto
            });
        </script>

        <script>
            // Ejecutar funciones basadas en parámetros URL
            function handleURLParams() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('mensaje')) {
                    const mensaje = urlParams.get('mensaje');
                    switch (mensaje) {
                        case 'habilitada':
                            showAlert('La Universidad ha sido habilitada exitosamente.', 'success');
                            break;
                        case 'error':
                            showAlert('Hubo un error al habilitar la Universidad. Intenta nuevamente.', 'error');
                            break;
                        default:
                            break;
                    }
                }
            }

            // Ejecutar al cargar el documento
            document.addEventListener('DOMContentLoaded', function() {
                handleURLParams();
            });
        </script>

        

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>