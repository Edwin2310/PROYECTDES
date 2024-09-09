<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 41; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

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

                                    $sql = "SELECT a.ID_SOLICITUD, b.NOM_CARRERA, c.NOM_CATEGORIA, d.NOM_UNIVERSIDAD, e.NOM_GRADO, f.ESTADO_sOLICITUD, g.NOM_MODALIDAD, a.ID_ESTADO
                                    FROM tbl_solicitudes a 
                                    LEFT JOIN tbl_carrera b ON a.ID_CARRERA = b.ID_CARRERA
                                    LEFT JOIN tbl_categoria c ON a.ID_CATEGORIA = c.ID_CATEGORIA
                                    LEFT JOIN tbl_universidad_centro d ON a.ID_UNIVERSIDAD = d.ID_UNIVERSIDAD
                                    LEFT JOIN tbl_grado_academico e ON a.ID_GRADO = e.ID_GRADO
                                    LEFT JOIN tbl_estado_solicitud f ON a.ID_ESTADO = f.ID_ESTADO
                                    LEFT JOIN tbl_modalidad g ON a.ID_MODALIDAD = g.ID_MODALIDAD
                                    WHERE a.ID_ESTADO IN (8, 11, 12, 25, 26);"; // IN para seleccionar los estados 8, 11, 12, 25, 26

                                    $result = $conn->query($sql);
                                    if ($result !== false && $result->rowCount() > 0) {
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>{$row['ID_SOLICITUD']}</td>";
                                            echo "<td>{$row['NOM_CARRERA']}</td>";
                                            echo "<td>{$row['NOM_CATEGORIA']}</td>";
                                            echo "<td>{$row['NOM_UNIVERSIDAD']}</td>";
                                            echo "<td>{$row['NOM_GRADO']}</td>";

                                            // Condición para mostrar el badge correcto según el estado
                                            if ($row['ID_ESTADO'] == 8) {
                                                echo "<td><span class='badge badge-success'>{$row['ESTADO_sOLICITUD']}</span></td>";
                                            } elseif ($row['ID_ESTADO'] == 11) {
                                                echo "<td><span class='badge badge-info'>{$row['ESTADO_sOLICITUD']}</span></td>";
                                            } elseif ($row['ID_ESTADO'] == 12 || $row['ID_ESTADO'] == 25 || $row['ID_ESTADO'] == 26) {
                                                echo "<td><span class='badge badge-danger'>{$row['ESTADO_sOLICITUD']}</span></td>";
                                            }

                                            // Determinar el enlace a usar
                                            $enlace = ($row['ID_ESTADO'] == 12 || $row['ID_ESTADO'] == 25 || $row['ID_ESTADO'] == 26)
                                                ? "EmisionORFinal.php?id={$row['ID_SOLICITUD']}"
                                                : "EmisionORDatos.php?id={$row['ID_SOLICITUD']}";

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
<script src="../Seguridad//Bitacora//Bitacora.js"></script>