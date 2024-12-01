<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 41; // ID del objeto o módulo correspondiente a esta página

    // Obtener la página actual y la última marca de acceso
    $current_page = basename($_SERVER['PHP_SELF']);
    $last_access_time = $_SESSION['last_access_time'][$current_page] ?? 0;

    // Obtener el tiempo actual
    $current_time = time();

    // Verificar si han pasado al menos 10 segundos desde el último registro
    if ($current_time - $last_access_time > 3) {
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

                    <div class="sidebar-content ">


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
                            <h3 class="block-title">Emitir Opinión Razonada<small></small></h3>
                        </div>
                        <div class="block-content block-content-full">

                            <table id="solicitudessinOR" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-center">Codigo de Tramite</th>
                                        <th>Carrera</th>
                                        <th class="d-none d-sm-table-cell">Categoria</th>
                                        <th class="d-none d-sm-table-cell">Institucion</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Grado Academico</th>
                                        <th class="text-center" style="width: 15%;">Estado</th>
                                        <th class="text-center" style="width: 15%;">Funciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php
                                    require_once("../../config/conexion.php");
                                    $conexion = new Conectar();
                                    $conn = $conexion->Conexion();

                                    try {
                                        // Llamar al procedimiento almacenado
                                        $sql = "CALL `proceso.splConsultarSolicitudesEO`()";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();

                                        // Verificar si hay resultados
                                        if ($stmt !== false && $stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td>{$row['NomCarrera']}</td>";
                                                echo "<td>{$row['NomCategoria']}</td>";
                                                echo "<td>{$row['NomUniversidad']}</td>";
                                                echo "<td>{$row['NomGrado']}</td>";
    
                                                // Condición para mostrar el badge correcto según el estado
                                                if ($row['IdEstado'] == 8) {
                                                    echo "<td><span class='badge badge-success'>{$row['EstadoSolicitud']}</span></td>";
                                                } elseif ($row['IdEstado'] == 11) {
                                                    echo "<td><span class='badge badge-info'>{$row['EstadoSolicitud']}</span></td>";
                                                } elseif ($row['IdEstado'] == 12 || $row['IdEstado'] == 25 || $row['IdEstado'] == 26) {
                                                    echo "<td><span class='badge badge-danger'>{$row['EstadoSolicitud']}</span></td>";
                                                }
    
                                                // Determinar el enlace a usar
                                                $enlace = ($row['IdEstado'] == 12 || $row['IdEstado'] == 25 || $row['IdEstado'] == 26)
                                                    ? "EmisionORFinal.php?id={$row['IdSolicitud']}"
                                                    : "EmisionORDatos.php?id={$row['IdSolicitud']}";
    
                                                echo "<td class='text-center'>
                                                    <a href='{$enlace}'>
                                                        <button type='button' class='btn btn-sm btn-secondary'>
                                                            Emitir Opinión Razonada
                                                        </button>
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No hay datos disponibles</td></tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>


            <?php require_once("../MainFooter/MainFooter.php"); ?>


        </div>

        <?php require_once("../MainJs/MainJs.php"); ?>
        <script>
            function emitirOpinionRazonada(id, carrera, categoria, universidad, grado) {
                // Crea un formulario de forma dinámica
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "EmisionORDatos.php";

                // Agrega los datos como campos ocultos
                var inputs = {
                    'id': id,
                    'carrera': carrera,
                    'categoria': categoria,
                    'universidad': universidad,
                    'grado': grado
                };

                for (var key in inputs) {
                    if (inputs.hasOwnProperty(key)) {
                        var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = key;
                        input.value = inputs[key];
                        form.appendChild(input);
                    }
                }

                // Envía el formulario
                document.body.appendChild(form);
                form.submit();
            }
        </script>

    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>
<!-- <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->