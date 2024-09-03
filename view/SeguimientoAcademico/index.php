<?php
session_start();
require_once("../../config/conexion.php");

if (isset($_SESSION["ID_USUARIO"])) {
?>

<?php
    $id_rol = $_SESSION['ID_ROL'] ?? null;
    $id_objeto = 21; // ID del objeto o módulo correspondiente a esta página

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
        <title>Usuarios</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"></script>
        <script src="../Seguridad//Bitacora//borrar_bitacora.js"></script>
        <script src="../Seguridad//Bitacora//Bitacora.js"></script>
        <style>
            /* Asegúrate de que el contenedor tenga un desplazamiento horizontal cuando sea necesario */
            .table-container {
                overflow-x: auto;
            }
        </style>
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
                        <h3 class="block-title">Registros de Bitacora Solicitud</h3>
                        <button id="borrarBitacora" class="btn btn-danger mx-2">Borrar Todos los Registros</button>
                        <a href="./Bitacora/exportar_bitacora.php" class="btn btn-success mx-2">Descargar Excel</a>
                    </div>

                    <div class="block-content block-content-full">
                        <br>
                        <br>
                        <!-- Contenedor para el desplazamiento horizontal -->
                        <div class="table-container">
                            <table id="bitacoraTable" class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="text-center">Id Bitacora</th>
                                        <th class="d-none d-sm-table-cell">Fecha y Hora</th>
                                        <th class="d-none d-sm-table-cell">Id Usuario</th>
                                        <th class="d-none d-sm-table-cell">Usuario</th>
                                        <th class="d-none d-sm-table-cell">Id Objeto</th>
                                        <th class="d-none d-sm-table-cell">Acción</th>
                                        <th class="d-none d-sm-table-cell">Descripción</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><input type="text" placeholder="Buscar por Id Bitacora" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por Fecha y Hora" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por ID Usuario" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por Usuario" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por ID Objeto" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por Acción" /></th>
                                        <th class="d-none d-sm-table-cell"><input type="text" placeholder="Buscar por Descripción" /></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conexion = new Conectar();
                                    $conn = $conexion->Conexion();
                                    $sql = "SELECT b.ID_BITACORA, b.FECHA_HORA, 
                                        b.ID_USUARIO, U.NOMBRE_USUARIO AS NOMBRE_USUARIO, o.ID_OBJETO, 
                                        b.ACCION, b.DESCRIPCION
                                    FROM tbl_ms_bitacora b
                                    INNER JOIN tbl_ms_usuario u ON b.ID_USUARIO = u.ID_USUARIO
                                    INNER JOIN tbl_ms_objetos o ON b.ID_OBJETO = o.ID_OBJETO
                                    ORDER BY b.ID_BITACORA ASC;";
                                    $result = $conn->query($sql);
                                    if ($result !== false && $result->rowCount() > 0) {
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>{$row['ID_BITACORA']}</td>";
                                            echo "<td>{$row['FECHA_HORA']}</td>";
                                            echo "<td>{$row['ID_USUARIO']}</td>";
                                            echo "<td>{$row['NOMBRE_USUARIO']}</td>";
                                            echo "<td>{$row['ID_OBJETO']}</td>";
                                            echo "<td>{$row['ACCION']}</td>";
                                            echo "<td>{$row['DESCRIPCION']}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No hay datos disponibles</td></tr>";
                                    }
                                    $conn = null;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once("../MainFooter/MainFooter.php"); ?>
            <?php require_once("../MainJs/MainJs.php"); ?>

            <script>
                $(document).ready(function() {
                    // Función para filtrar la tabla
                    $('#bitacoraTable thead tr:eq(1) th').each(function(i) {
                        var title = $(this).text();
                        $(this).html('<input type="text" placeholder="Filtrar por ' + title + '" />');
                        $('input', this).on('keyup change', function() {
                            if (table.column(i).search() !== this.value) {
                                table
                                    .column(i)
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });

                    var table = $('#bitacoraTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                        },
                        orderCellsTop: true,
                        fixedHeader: true
                    });
                });
            </script>
        </div>
    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
