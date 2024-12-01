<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 22; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Gestión de Departamentos Bloqueados</title>
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
                    </div>
                    <div class="block-content block-content-full">
                        <!-- Botón para cambiar a registros bloqueados -->
                        <td class="text-center">
                            <a href="Departamentos.php">
                                <button type="button" class="btn btn-sm btn-secondary" title="Departamentos Habilitados">
                                    <i class="fa fa-unlock-alt"></i>
                                </button>
                            </a>
                        </td> <br><br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">ID DEPARTAMENTO</th>
                                    <th class="text-center">DEPARTAMENTOS</th>
                                    <th class="text-center" style="width: 15%;">HABILITAR DEPARTAMENTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();
                                $sql = "SELECT d.IdDepartamento, 
                                               d.NomDepto 
                                        FROM `mantenimiento.tbldeptos` d
                                        LEFT JOIN `mantenimiento.tblestadosvisualizaciones` e ON d.IdVisibilidad = e.IdVisibilidad
                                        WHERE e.IdVisibilidad IN (2)
                                        ORDER BY d.IdDepartamento";

                                $result = $conn->query($sql);
                                if ($result !== false && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$row['IdDepartamento']}</td>";
                                        echo "<td class='text-center'>{$row['NomDepto']}</td>";
                                        echo "<td class='text-center'>
                                    <button type='button' class='btn btn-sm btn-success' data-toggle='modal' data-target='#confirmEnableDepartamentoModal' 
                                            data-id='" . $row["IdDepartamento"] . "'>
                                        <i class='si si-check'></i> Habilitar
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

            <!-- Modal para confirmar habilitación de Departamento -->
            <div class="modal fade" id="confirmEnableDepartamentoModal" tabindex="-1" role="dialog" aria-labelledby="confirmEnableDepartamentoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmEnableDepartamentoModalLabel">Habilitar Departamento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que quieres habilitar este departamento?</p>
                            <form id="enableDepartamentoForm" method="POST" action="../MantenimientoSistema/Departamentos/Habilitar_Departamento.php">
                                <input type="hidden" id="enable_IdDepartamento" name="IdDepartamento">
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
            // JavaScript para pasar el IdDepartamento al formulario
            $('#confirmEnableDepartamentoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que abrió el modal
                var IdDepartamento = button.data('id'); // Extraer el ID de Departamento
                var modal = $(this);
                modal.find('.modal-body #enable_IdDepartamento').val(IdDepartamento); // Establecer el valor en el campo oculto
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
                            showAlert('El departamento ha sido habilitado exitosamente.', 'success');
                            break;
                        case 'error':
                            showAlert('Hubo un error al habilitar el departamento. Intenta nuevamente.', 'error');
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
    header("Location: ../../index.php");
}
?>