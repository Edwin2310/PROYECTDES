<?php

// Supongamos que el IdRol está en $_SESSION["IdRol"]
$id_rol = isset($_SESSION["IdRol"]) ? $_SESSION["IdRol"] : null;
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
                <a href="../NuevoIngresoSolicitud/NuevaSolicitudoCreacion.php" class="modulo-link" data-id-objeto="3" data-accion="accedio al modulo">
                    <i class="si si-book-open"></i>
                    <span class="sidebar-mini-hide">Nuevo Ingreso de Solicitud</span>
                </a>
            </li>
            <li>
                <a id="consultar_solicitud" href="../ConsultarSolicitudes/" class="modulo-link" data-id-objeto="4" data-accion="accedio al modulo">
                    <i class="si si-magnifier"></i>
                    <span class="sidebar-mini-hide">Consultar Solicitudes</span>
                </a>
            </li>
            <!-- <li>
                <a href="../SeguimientoAcademico/" class="modulo-link" data-id-objeto="7" data-accion="accedio al modulo">
                    <i class="si si-list"></i>
                    <span class="sidebar-mini-hide">Seguimiento Académico</span>
                </a>
            </li> -->
            <li>
                <a id="mantenimiento_solicitud" href="../MantenimientoSolicitudes/" class="modulo-link" data-id-objeto="8" data-accion="accedio al modulo">
                    <i class="si si-wrench"></i>
                    <span class="sidebar-mini-hide">Mantenimiento Solicitudes</span>
                </a>
            </li>
            <li>
                <a id="reportes" href="../Reportes/" class="modulo-link" data-id-objeto="9" data-accion="accedio al modulo">
                    <i class="si si-doc"></i>
                    <span class="sidebar-mini-hide">Reportes</span>
                </a>
            </li>
            <li>
                <a id="mantenimiento_sistema" href="../MantenimientoSistema/" class="modulo-link" data-id-objeto="10" data-accion="accedio al modulo">
                    <i class="si si-settings"></i>
                    <span class="sidebar-mini-hide">Mantenimiento Sistema</span>
                </a>
            </li>
            <li>
                <a id="seguridad" href="../Seguridad/" class="modulo-link" data-id-objeto="11" data-accion="accedio al modulo">
                    <i class="si si-shield"></i>
                    <span class="sidebar-mini-hide">Seguridad</span>
                </a>
            </li>
        </ul>
    </div>


    <!--    <script>
        const idRol = <?php echo json_encode($id_rol); ?>

// Definición de la función roles
        function roles(id) {
            if (id == 1) { // Administrador
                $('.content-side.content-side-full').each(function () {
                    $(this).removeAttr("style");
                });
                
                //AGREGAR
                $("#nuevo_ingreso").removeAttr("style");
                $("#consultar_solicitud").removeAttr("style");
                //QUITAR
                $("#mantenimiento_solicitud").attr("style", "display:none");
                $("#reportes").attr("style", "display:none");
                $("#mantenimiento_sistema").attr("style", "display:none");
                $("#seguridad").attr("style", "display:none");

            }
            // Agregar las demás condiciones según los otros roles...
        }

        // Ejecutar la función roles pasando el ID del rol
        if (idRol) {
            roles(idRol);
        }
    </script> -->


    <!-- Incluir el script acceso.js -->
    <script src="../Seguridad/Permisos/acceso.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!--     <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->




</body>

</html>