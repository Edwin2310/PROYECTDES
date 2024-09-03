<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["ID_USUARIO"])) {

?>
<?php
    $id_rol = $_SESSION['ID_ROL'] ?? null;
    $id_objeto = 28; // ID del objeto o módulo correspondiente a esta página

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

    <title>Tipos de Solicitudes </title>

</head>

<body >

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
                            <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><!-- llamada del usuario   -->
                            <span><?php echo $_SESSION["USUARIO"] ?> <?php echo $_SESSION["NOMBRE_USUARIO"] ?></span></a>
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



        <!-- Inicio de la entrada de la universidad -->

        <main id="main-container">

            <div class="content">
                <h2 class="content-heading">Bienvenido, UNITEC </h2>



                <div class="block-header block-header-default">
                        <h3 class="block-title">Tipos De Solicitudes <small></small></h3>
                    </div>

                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr>
            <th class="text-center">Id Tipo Solicitud</th>
            <th>Tipo de Solicitud</th>
            <th class="text-center">Editar</th>
            <th class="text-center">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require_once("../../config/conexion.php");
        $conexion = new Conectar();
        $conn = $conexion->Conexion();
        
        // Consulta a la base de datos para obtener los tipos de solicitud
        $sql = "SELECT ID_TIPO_SOLICITUD, NOM_TIPO FROM tbl_tipo_solicitud";
        $result = $conn->query($sql);

        // Verificar si hay resultados
        if ($result !== false && $result->rowCount() > 0) {
            // Iterar sobre los resultados
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='text-center'>{$row['ID_TIPO_SOLICITUD']}</td>";
                echo "<td>{$row['NOM_TIPO']}</td>";
                echo "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#editTipoSolicitudModal' 
                                data-id='{$row['ID_TIPO_SOLICITUD']}' 
                                data-tipo='{$row['NOM_TIPO']}'>
                            <i class='si si-note'></i>
                        </button>
                      </td>";
                echo "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' 
                                data-id='{$row['ID_TIPO_SOLICITUD']}'>
                            <i class='si si-trash'></i>
                        </button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No hay datos disponibles</td></tr>";
        }

        // Cerrar la conexión
        $conn = null;
        ?>
    </tbody>
</table>


</script>

</body>
</html>

</html>

       </div>

        </main>


        <?php require_once("../MainFooter/MainFooter.php"); ?>


    </div>

    <?php require_once("../MainJs/MainJs.php"); ?>

</body>

</html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Seguridad//Bitacora//Bitacora.js"></script>
