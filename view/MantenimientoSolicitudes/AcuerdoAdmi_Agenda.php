<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 37; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 7) {
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
        <title>Consultar Solicitudes</title>
        <style>
            .table-container {
                overflow-x: auto;
            }

            .hidden-column {
                display: none;
            }

            .show-more-btn {
                margin-top: 10px;
            }

            .text-right {
                text-align: right;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .text-right {
                text-align: right;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .text-right {
                text-align: right;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .d-flex {
                display: flex;
            }

            .justify-content-between {
                justify-content: space-between;
                /* Espacio entre los botones en los extremos */
            }

            .align-items-center {
                align-items: center;
                /* Centra verticalmente los elementos en el contenedor */
            }

            .button-group {
                display: flex;
                gap: 10px;
                /* Espacio entre los botones */
            }

            .show-more-btn {
                margin-right: auto;
                /* Empuja el botón "Mostrar más" hacia la izquierda */
            }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
                                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html">John Smith</a>
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
            <main id="main-container">
                <div class="content">
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Agendar acuerdo <small></small></h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="table-container">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                        <tr>
                                            <th class="text-center">IdSolicitud</th>
                                            <th class="text-center">CARRERA</th>
                                            <th class="text-center">CATEGORÍA</th>
                                            <th class="text-center">UNIVERSIDAD</th>
                                            <th class="text-center">GRADO ACADÉMICO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center hidden-column">TIPO DE SOLICITUD</th>
                                            <th class="text-center hidden-column">N° DE REFERENCIA</th>
                                            <th class="text-center hidden-column">DESCRIPCIÓN</th>
                                            <th class="text-center hidden-column">DEPARTAMENTO</th>
                                            <th class="text-center hidden-column">MUNICIPIO</th>
                                            <th class="text-center hidden-column">USUARIO</th>
                                            <th class="text-center hidden-column">NOMBRE COMPLETO</th>
                                            <th class="text-center hidden-column">CorreoElectronico</th>
                                            <th class="text-center hidden-column">FECHA DE INGRESO</th>
                                            <th class="text-center hidden-column">FECHA DE MODIFICACIÓN</th>
                                            <th class="text-center hidden-column">CÓDIGO DE PAGO</th>
                                            <th class="text-center">Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();
                                        $sql = "SELECT  s.IdSolicitud,
                                                        s.NumReferencia,
                                                        s.Descripcion,
                                                        s.NombreCompleto,
                                                        s.FechaIngreso,
                                                        s.FechaModificacion,
                                                        ts.NomTipoSolicitud,
                                                        cat.NomCategoria,
                                                        c.NomCarrera,
                                                        g.NomGrado,
                                                        m.NomModalidad,
                                                        uc.NomUniversidad,
                                                        d.NomDepto,
                                                        mu.NomMunicipio,
                                                        u.NombreUsuario,
                                                        s.CorreoElectronico,
                                                        cat.CodArbitrios,
                                                        s.NombreCarrera,
                                                        e.EstadoSolicitud
                                                    FROM
                                                        `proceso.tblSolicitudes` s
                                                    LEFT JOIN `mantenimiento.tblcategorias` cat ON s.IdCategoria = cat.IdCategoria
                                                    LEFT JOIN `mantenimiento.tblcarreras` c ON s.IdCarrera = c.IdCarrera
                                                    LEFT JOIN `mantenimiento.tblgradosacademicos` g ON s.IdGrado = g.IdGrado
                                                    LEFT JOIN `mantenimiento.tblmodalidades` m ON s.IdModalidad = m.IdModalidad
                                                    LEFT JOIN `mantenimiento.tbluniversidades` uc ON s.IdUniversidad = uc.IdUniversidad
                                                    LEFT JOIN `mantenimiento.tbldeptos` d ON s.IdDepartamento = d.IdDepartamento
                                                    LEFT JOIN `mantenimiento.tblmunicipios` mu ON s.IdMunicipio = mu.IdMunicipio
                                                    LEFT JOIN `seguridad.tbldatospersonales` u ON s.IdUsuario = u.IdUsuario
                                                    LEFT JOIN `mantenimiento.tblestadossolicitudes` e ON s.IdEstado = e.IdEstado
                                                    LEFT JOIN `mantenimiento.tbltiposolicitudes` ts ON cat.IdTipoSolicitud = ts.IdTipoSolicitud -- Relación con tipos de solicitud
                                                    WHERE e.IdEstado IN (4)
                                                    ORDER BY e.IdEstado;";

                                        $result = $conn->query($sql);
                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td class='text-center'>{$row['NomCarrera']}</td>";
                                                echo "<td class='text-center'>{$row['NomCategoria']}</td>";
                                                echo "<td class='text-center'>{$row['NomUniversidad']}</td>";
                                                echo "<td class='text-center'>{$row['NomGrado']}</td>";
                                                echo "<td class='text-center'>{$row['EstadoSolicitud']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomTipoSolicitud']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NumReferencia']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['Descripcion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomDepto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NomMunicipio']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreUsuario']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['NombreCompleto']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CorreoElectronico']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaIngreso']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['FechaModificacion']}</td>";
                                                echo "<td class='text-center hidden-column'>{$row['CodArbitrios']}</td>";
                                                echo "<td class='text-center'><input type='checkbox' class='select-checkbox'></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='19' class='text-center'>No hay datos disponibles</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3">
                                <!-- Contenedor para alinear los botones -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Botón "Mostrar más" a la izquierda -->
                                    <button class="btn btn-primary show-more-btn" id="showMoreBtn">Mostrar más</button>

                                    <!-- Grupo de botones a la derecha -->
                                    <div class="button-group">
                                        <button class="btn btn-success" id="selectAllBtn">Seleccionar todas</button>
                                        <button class="btn btn-danger" id="deselectAllBtn">Desmarcar todas</button>
                                    </div>
                                </div>
                                <!-- Botón "Asignar Sesión" centrado -->
                                <div class="text-center mt-3">
                                    <button id="asignarSesion" class="btn btn-primary" data-ids="">Asignar Sesión</button>

                                </div>
                            </div>


                        </div>
                    </div>
            </main>
            <?php require_once("../MainFooter/MainFooter.php"); ?>
        </div>
        <?php require_once("../MainJs/MainJs.php"); ?>
        <!-- Modal -->
        <div class="modal fade" id="asignarSesionModal" tabindex="-1" role="dialog" aria-labelledby="asignarSesionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="asignarSesionModalLabel">Asignar Sesión</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="asignarSesionForm">
                            <div class="form-group">
                                <label for="numeroSesion">Escriba el número de sesión:</label>
                                <input type="text" class="form-control" id="numeroSesion" maxlength="3" pattern="\d{1,3}" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese un número válido de hasta 3 dígitos.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" form="asignarSesionForm" class="btn btn-primary">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const showMoreBtn = document.getElementById("showMoreBtn");
                const hiddenColumns = document.querySelectorAll(".hidden-column");
                const selectAllBtn = document.getElementById("selectAllBtn");
                const deselectAllBtn = document.getElementById("deselectAllBtn");
                const checkboxes = document.querySelectorAll(".select-checkbox");
                const asignarSesionBtn = document.getElementById('asignarSesion');
                const asignarSesionForm = document.getElementById('asignarSesionForm');

                showMoreBtn.addEventListener("click", function() {
                    const isShowingMore = showMoreBtn.textContent === "Mostrar más";
                    hiddenColumns.forEach(function(column) {
                        column.style.display = isShowingMore ? "table-cell" : "none";
                    });
                    showMoreBtn.textContent = isShowingMore ? "Mostrar menos" : "Mostrar más";
                });

                selectAllBtn.addEventListener("click", function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                });

                deselectAllBtn.addEventListener("click", function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                });

                asignarSesionBtn.addEventListener('click', function() {
                    let selectedIds = [];
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            selectedIds.push(checkbox.closest('tr').querySelector('td:first-child').textContent);
                        }
                    });

                    if (selectedIds.length > 0) {
                        asignarSesionBtn.setAttribute('data-ids', selectedIds.join(','));
                        $('#asignarSesionModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe seleccionar al menos una solicitud para asignar una sesión.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });

                asignarSesionForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const numeroSesion = document.getElementById('numeroSesion').value;
                    const selectedIds = asignarSesionBtn.getAttribute('data-ids');

                    if (!numeroSesion.match(/^\d{1,3}$/)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Por favor ingrese un número válido de hasta 3 dígitos.',
                            confirmButtonText: 'Aceptar'
                        });
                        return;
                    }

                    fetch('guardar_sesion.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                numeroSesion: numeroSesion,
                                ids: selectedIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Ocultar las filas de las solicitudes asignadas
                                selectedIds.split(',').forEach(function(id) {
                                    const row = Array.from(document.querySelectorAll('td')).find(td => td.textContent === id)?.closest('tr');
                                    if (row) row.style.display = 'none';
                                });
                                $('#asignarSesionModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Asignado!',
                                    text: data.message,
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    // Recargar la página después de cerrar la alerta
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al asignar la sesión.'
                            });
                        });
                });
            });
        </script>




    </body>

    </html>
<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script src="../Seguridad/Permisos/acceso.js" defer></script>
<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->