<?php
require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 4; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>

    <!doctype html>
    <html lang="en" class="no-focus">


    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>

        <style>



        </style>

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
                            <h3 class="block-title">Solicitudes en Proceso <small>DE</small></h3>
                        </div>
                        <div class="block-content block-content-full">

                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        <th class="text-center">Codigo de Tramite</th>
                                        <th>Carrera</th>
                                        <th class="d-none d-sm-table-cell">Categoria</th>
                                        <th class="d-none d-sm-table-cell">Institucion</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">Grado Academico</th>
                                        <th class="text-center" style="width: 15%;">Estado</th>
                                        <th class="text-center" style="width: 15%;">Progreso</th>
                                        <th class="text-center" style="width: 15%;">Funciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once("../../config/conexion.php");
                                    $conexion = new Conectar();
                                    $conn = $conexion->Conexion();

                                    // Define el mapeo de estados numéricos a valores de progreso
                                    $estado_progreso = array(
                                        18 => 0,   // SUBSANACION DE OPINION RAZONADA
                                        1  => 5,   // REVISION DE DOCUMENTOS
                                        2  => 5,   // PENDIENTE SUBSANACION DE DOCUMENTOS
                                        3  => 5,   // SUBSANACION DE DOCUMENTOS REALIZADA
                                        4  => 10,  // PRESENTAR DOCUMENTOS EN FISICO
                                        5  => 15,  // AGENDADO PARA ADMISION DEL CES
                                        6  => 20,  // ACUERDO DE ADMISION DEL CES
                                        7  => 25,  // AGENDADO PARA CTC
                                        8  => 30,  // DICTAMINADO POR EL CTC
                                        9  => 35,  // ANALISIS CURRICULAR
                                        10 => 35,  // ATENDER OBSERVACIONES Y/O CORRECCIONES
                                        11 => 35,  // OBSERVACIONES Y/O CORRECCIONES ATENDIDAS
                                        13 => 40,  // OPINION RAZONADA EMITIDA
                                        12 => 40,  // CORRECCION DE OPINION RAZONADA DGCA
                                        19 => 40,  // PENDIENTE DE APROBACION DGAC
                                        20 => 45,  // APROBADO POR DGAC
                                        21 => 45,  // PENDIENTE APROBACION D E
                                        25 => 50,  // CORRECCION DE OPINION RAZONADA D E
                                        22 => 50,  // APROBADO POR D E
                                        23 => 55,  // PENDIENTE APROBACION SEC.ADJUNTA
                                        26 => 55,  // CORRECCION DE OPINION RAZONADA DSA
                                        24 => 60,  // APROBADO POR SEC.ADJUNTA
                                        14 => 70,  // AGENDADO PARA APROBACION DEL CES
                                        15 => 80,  // ACUERDO DE APROBACION DEL CES
                                        16 => 90,  // REGISTRO DEL PLAN DE ESTUDIOS
                                        17 => 100  // ENTREGA DEL PLAN DE ESTUDIOS
                                    );

                                    // Define qué estados requieren un proceso adicional
                                    $requiere_proceso = array(2, 10, 17);

                                    // Mapeo de procesos y sus links
                                    $procesos_links = array(
                                        2 => 'SubsanarDocumentos.php',
                                        10 => 'SubsanacionAnalisisCurricular.php',
                                        17 => 'entregaplanestudios.php'
                                    );

                                    try {
                                        // Llamar al procedimiento almacenado
                                        $sql = "CALL `proceso.splConsultarSolicitudes`()";
                                        $result = $conn->query($sql);

                                        if ($result !== false && $result->rowCount() > 0) {
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                $estado_num = $row['IdEstado'];
                                                $progreso = isset($estado_progreso[$estado_num]) ? $estado_progreso[$estado_num] : 0;

                                                // Definir color según el progreso
                                                if ($progreso <= 20) {
                                                    $bg_color = 'bg-danger';
                                                } elseif ($progreso <= 40) {
                                                    $bg_color = 'bg-warning';
                                                } elseif ($progreso <= 60) {
                                                    $bg_color = 'bg-info';
                                                } elseif ($progreso <= 80) {
                                                    $bg_color = 'bg-primary';
                                                } elseif ($progreso < 100) {
                                                    $bg_color = 'bg-success';
                                                } else {
                                                    $bg_color = 'bg-success'; // Para 100%
                                                }

                                                // Cambiar color de la pleca SPA según el estado
                                                if ($progreso <= 20) {
                                                    $estado_color = 'badge-danger';
                                                } elseif ($progreso <= 40) {
                                                    $estado_color = 'badge-warning';
                                                } elseif ($progreso <= 60) {
                                                    $estado_color = 'badge-info';
                                                } elseif ($progreso <= 80) {
                                                    $estado_color = 'badge-primary';
                                                } elseif ($progreso < 100) {
                                                    $estado_color = 'badge-success';
                                                } else {
                                                    $estado_color = 'badge-success'; // Para 100%
                                                }

                                                // Verificar si el estado requiere un proceso
                                                $is_proceso = in_array($estado_num, $requiere_proceso);
                                                $btn_disabled = !$is_proceso ? 'disabled' : '';

                                                // Determinar el enlace basado en el estado y añadir la ID de la solicitud
                                                $link_proceso = $is_proceso ? (isset($procesos_links[$estado_num]) ? $procesos_links[$estado_num] . "?id=" . $row['IdSolicitud'] : '#') : '#';

                                                // Datos de la solicitud
                                                echo "<tr>";
                                                echo "<td class='text-center'>{$row['IdSolicitud']}</td>";
                                                echo "<td>{$row['NomCarrera']}</td>";
                                                echo "<td>{$row['NomCategoria']}</td>";
                                                echo "<td>{$row['NomUniversidad']}</td>";
                                                echo "<td>{$row['NomGrado']}</td>";
                                                echo "<td><span class='badge $estado_color'>{$row['EstadoSolicitud']}</span></td>";
                                                echo "<td class='text-center'>
                                                    <div class='progress'>
                                                        <div class='progress-bar progress-bar-striped progress-bar-animated $bg_color' role='progressbar' style='width: {$progreso}%; position: relative;' aria-valuenow='{$progreso}' aria-valuemin='0' aria-valuemax='100'>
                                                            <span class='progress-bar-label' style='position: absolute; left: 0; right: 0; top: 50%; transform: translateY(-50%); text-align: center; color: #000; font-weight: bold;'>{$progreso}%</span>
                                                        </div>
                                                    </div>
                                                </td>";
                                                                            echo "<td class='text-center'>
                                                    <a href='{$link_proceso}' class='btn btn-sm btn-secondary $btn_disabled' " . ($btn_disabled ? "title='No se puede realizar proceso en este estado'" : "") . ">
                                                        Proceso
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No hay datos disponibles</td></tr>";
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