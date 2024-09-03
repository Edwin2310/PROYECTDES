<?php

// Supongamos que el ID_ROL está en $_SESSION["ID_ROL"]
$id_rol = isset($_SESSION["ID_ROL"]) ? $_SESSION["ID_ROL"] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Main Menu</title>
</head>

<body>
    <div class="content-side content-side-full">
        <ul class="nav-main">
            <li>
                <a href="../NuevoIngresoSolicitud/" class="modulo-link" data-id-objeto="3" data-accion="accedio al modulo">
                    <i class="si si-book-open"></i>
                    <span class="sidebar-mini-hide">Nuevo Ingreso de Solicitud</span>
                </a>
            </li>
            <li>
                <a href="../ConsultarSolicitudes/" class="modulo-link" data-id-objeto="4" data-accion="accedio al modulo">
                    <i class="si si-magnifier"></i>
                    <span class="sidebar-mini-hide">Consultar Solicitudes</span>
                </a>
            </li>
            <!--  <li>
                <a href="../SeguimientoAcademico/" class="modulo-link" data-id-objeto="7" data-accion="accedio al modulo">
                    <i class="si si-list"></i>
                    <span class="sidebar-mini-hide">Seguimiento Académico</span>
                </a>
            </li> -->
            <li>
                <a href="../MantenimientoSolicitudes/" class="modulo-link" data-id-objeto="8" data-accion="accedio al modulo">
                    <i class="si si-wrench"></i>
                    <span class="sidebar-mini-hide">Mantenimiento Solicitudes</span>
                </a>
            </li>
            <li>
                <a href="../Reportes/" class="modulo-link" data-id-objeto="9" data-accion="accedio al modulo">
                    <i class="fa fa-book"></i>
                    <span class="sidebar-mini-hide">Reportes</span>
                </a>
            </li>
            <li>
                <a href="../MantenimientoSistema/" class="modulo-link" data-id-objeto="10" data-accion="accedio al modulo">
                    <i class="fa fa-cogs"></i>
                    <span class="sidebar-mini-hide">Mantenimiento Sistema</span>
                </a>
            </li>
            <li>
                <a href="../Seguridad/" class="modulo-link" data-id-objeto="11" data-accion="accedio al modulo">
                    <i class="fa fa-shield"></i>
                    <span class="sidebar-mini-hide">Seguridad</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Incluir el ID_ROL de la sesión en una variable de JavaScript -->
    <script>
        const idRol = <?php echo json_encode($id_rol); ?>;
    </script>

    <!-- Incluir el script acceso.js -->
    <script src="../Seguridad/Permisos/acceso.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--     <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->




</body>

</html>