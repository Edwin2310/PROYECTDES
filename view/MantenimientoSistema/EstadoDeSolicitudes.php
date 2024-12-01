<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 30; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);
?>

<!doctype html>
<html lang="en" class="no-focus">

<head>
    <?php require_once("../MainHead/MainHead.php"); ?>
    <title>Estado de Solicitudes</title>
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
                                <span><?php echo $_SESSION["USUARIO"] ?> <?php echo $_SESSION["NOMBRE_USUARIO"] ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <nav id="sidebar">
            <div id="sidebar-scroll">
                <div class="sidebar-content">
                    <?php require_once("../MainSidebar/MainSidebar.php"); ?>
                    <?php require_once("../MainMenu/MainMenu.php"); ?>
                </div>
            </div>
        </nav>

        <?php require_once("../MainHeader/MainHeader.php"); ?>

        <main id="main-container">

            <div class="content">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Gestión de Estados de Solicitudes</h3>
                </div>

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center">Id Estado</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Editar</th>
                            <th class="text-center">Bloquear</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Realizar la consulta a la base de datos
                        $conexion = new Conectar();
                        $conn = $conexion->Conexion();
                        $sql = "SELECT IdEstado, EstadoSolicitud FROM `mantenimiento.tblestadossolicitudes`";
                        $result = $conn->query($sql);

                        if ($result !== false && $result->rowCount() > 0) {
                            // Iterar sobre los resultados
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td class='text-center'>{$row['IdEstado']}</td>";
                                echo "<td class='text-center'>{$row['EstadoSolicitud']}</td>";
                                echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editSolicitudModal' 
                                        data-id='{$row['IdEstado']}' 
                                        data-descripcion='{$row['EstadoSolicitud']}'>
                                            <i class='si si-note'></i>
                                        </button>
                                      </td>";
                                echo "<td class='text-center'>
                                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                        data-id='{$row['IdEstado']}'>
                                            <i class='si si-trash'></i>
                                        </button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No hay datos disponibles</td></tr>";
                        }
                        $conn = null;
                        ?>
                    </tbody>
                </table>

            </div>
        </main>
    </div>

<?php
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>

</body>
</html>
