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

    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Modalidades Bloqueadas</title>
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
                        <h3 class="block-title">Gestión de Modalidades Bloqueadas</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Modalidades.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Modalidades Habilitadas">
                                    <i class="fa fa-unlock-alt"></i>
                                </button>
                            </a>
                        </td>

                        <br><br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID MODALIDAD</th>
                                    <th class="text-center">MODALIDADES</th>
                                    <th class="text-center" style="width: 15%;">HABILITAR MODALIDAD</th>
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
                                    WHERE e.IdVisibilidad IN (2)    
                                    ORDER BY m.IdModalidad";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdModalidad']}</td>";
                                        echo "<td class='text-center'>{$row['NomModalidad']}</td>";
                                        echo "<td class='text-center'>
                                    <button type='button' class='btn btn-sm btn-success' data-toggle='modal' data-target='#confirmEnableModalidadModal' 
                                            data-id='" . $row["IdModalidad"] . "'>
                                        <i class='si si-check'></i> Habilitar
                                    </button>
                                </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No hay datos disponibles</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para confirmar habilitación de modalidad -->
            <div class="modal fade" id="confirmEnableModalidadModal" tabindex="-1" role="dialog" aria-labelledby="confirmEnableModalidadModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmEnableModalidadModalLabel">Habilitar Modalidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres habilitar esta modalidad?</p>
                            <form id="enableModalidadForm" method="POST" action="../MantenimientoSistema/Modalidades/Habilitar_Modalidad.php">
                                <input type="hidden" id="enable_IdModalidad" name="IdModalidad">
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
            // JavaScript para pasar el IdModalidad al formulario
            $('#confirmEnableModalidadModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que abrió el modal
                var idModalidad = button.data('id'); // Extraer el ID de modalidad
                var modal = $(this);
                modal.find('.modal-body #enable_IdModalidad').val(idModalidad); // Establecer el valor en el campo oculto
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
                            showAlert('La modalidad ha sido habilitada exitosamente.', 'success');
                            break;
                        case 'error':
                            showAlert('Hubo un error al habilitar la modalidad. Intenta nuevamente.', 'error');
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
    echo "<script>window.location = '../Seguridad/AccesoDenegado.php';</script>";
}
?>