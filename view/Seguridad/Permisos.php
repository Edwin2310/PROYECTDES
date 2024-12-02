<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
require_once(__DIR__ . '/../Seguridad/Bitacora/Funciones_Bitacoras.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_usuario = $_SESSION['IdUsuario'] ?? null;
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 54; // ID del objeto o módulo correspondiente a esta página

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
        <title>Usuarios</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

                        <?php
                        require_once("../../config/conexion.php");
                        $conexion = new Conectar();
                        $conn = $conexion->Conexion();

                        // Obtener el IdRol de la URL
                        $id_rol = isset($_GET['IdRol']) ? intval($_GET['IdRol']) : 0;

                        // Inicializar la variable $nombre_rol
                        $nombre_rol = 'Desconocido';

                        // Consulta para obtener el nombre del rol
                        $sql_rol = "SELECT NombreRol FROM `seguridad.tblmsroles` WHERE IdRol = :id_rol";
                        $stmt_rol = $conn->prepare($sql_rol);
                        $stmt_rol->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                        if ($stmt_rol->execute()) {
                            $row_rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);
                            if ($row_rol) {
                                $nombre_rol = $row_rol['NombreRol'];
                            }
                        } else {
                            echo "Error al obtener el nombre del rol";
                            print_r($stmt_rol->errorInfo());
                        }
                        ?>
                        
                        <h3 class="block-title text-center font-weight-bold display-5 ">Permisos del Rol <?php echo htmlspecialchars($nombre_rol); ?></h3>
                        
                    </div>
                    <div class="block-content block-content-full">
                        <br>
                        <br>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="d-none d-sm-table-cell text-center ">Nombre Pantalla</th>
                                    <th class="d-none d-sm-table-cell text-center">Permiso inserción</th>
                                    <th class="d-none d-sm-table-cell text-center">Permiso eliminación</th>
                                    <th class="d-none d-sm-table-cell text-center">Permiso Actualización</th>
                                    <th class="d-none d-sm-table-cell text-center">Permiso Consulta</th>
                                    <th class="text-center" style="width: 15%;">Editar Permiso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once("../../config/conexion.php");
                                $conexion = new Conectar();
                                $conn = $conexion->Conexion();

                                // Obtener el IdRol de la URL
                                $id_rol = isset($_GET['IdRol']) ? intval($_GET['IdRol']) : 0;

                                // Depuración: Verificar que se captura el IdRol correctamente
                                // echo "ID NombreRol: " . $id_rol;

                                $sql = "SELECT r.IdRol, r.NombreRol, o.IdObjeto, o.OBJETO, 
                                              p.PermisoInsercion,
                                              p.PermisoEliminacion,
                                              p.PermisoActualizacion,
                                              p.PermisoConsultar
                                              FROM `seguridad.tblpermisos` p
                                              INNER JOIN `seguridad.tblmsroles` r ON p.IdRol = r.IdRol
                                              INNER JOIN `seguridad.tblobjetos` o ON o.IdObjeto = p.IdObjeto
                                              WHERE p.IdRol = :id_rol";

                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);

                                if ($stmt->execute()) {
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } else {
                                    echo "Error al ejecutar la consulta";
                                    print_r($stmt->errorInfo());
                                }


                                if ($result) {
                                    foreach ($result as $row) {
                                        echo "<tr data-id-rol='{$row['IdRol']}' data-id-objeto='{$row['IdObjeto']}'>";
                                        echo "<td>{$row['OBJETO']}</td>";
                                        echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoInsercion'] ? 'checked' : '') . "></td>";
                                        echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoEliminacion'] ? 'checked' : '') . "></td>";
                                        echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoActualizacion'] ? 'checked' : '') . "></td>";
                                        echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoConsultar'] ? 'checked' : '') . "></td>";
                                        echo "<td class='text-center'>
                                             <button type='button' class='btn btn-sm btn-info toggle-checkboxes'>
                                            <i class='si si-settings'></i> <!-- Icono de habilitar/deshabilitar -->
                                             </button>
                                          </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12' class='text-center'>No hay datos disponibles</td></tr>";
                                }
                                $conn = null;
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

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    </body>

    </html>
<?php
    //validacion de segurida de inicio de sesion
    //si no hay una cuenta logueada no lo dejara entrar al sitio cambiadole la direccion
} else {
    header("Location:" . Conectar::ruta() . "index.php");
}
?>



<?php
if (isset($_SESSION["IdUsuario"])) {
?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Función para registrar acciones en la bitácora
            function registrarEnBitacora(id_objeto, accion, descripcion) {
                const id_usuario = <?php echo $_SESSION["IdUsuario"]; ?>;

                // Enviar datos a Insertar_Bitacora.php
                $.ajax({
                    url: './Insertar_Bitacora.php',
                    type: 'POST',
                    data: {
                        id_usuario: id_usuario,
                        id_objeto: id_objeto,
                        accion: accion,
                        descripcion: descripcion
                    },
                    success: function(response) {
                        console.log('Datos insertados en la bitácora: ' + response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ocurrió un error al insertar en la bitácora:', xhr.responseText);
                    }
                });
            }

            // Evento click para los enlaces de módulo
            $(document).on('click', '.modulo-link', function(event) {
                event.preventDefault(); // Evitar la navegación inmediata

                const id_objeto = $(this).data('id-objeto');
                const accion = "accedio al modulo";
                const descripcion = $(this).find('span').text().trim();

                // Registrar en la bitácora
                registrarEnBitacora(id_objeto, accion, descripcion);

                // Navegar al enlace después de registrar en la bitácora
                window.location.href = $(this).attr('href');
            });
        });
    </script>

    <!-- SCRIPT PARA VALIDACIONES EN FORMULARIO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener referencias a los campos del formulario
            const numIdentidad = document.getElementById('num_identidad');
            const direccion = document.getElementById('direccion_1');
            const usuario = document.getElementById('usuario');
            const correoElectronico = document.getElementById('correo_electronico');
            const nombreUsuario = document.getElementById('nombre_usuario');

            // Agregar evento de entrada para validar en tiempo real
            numIdentidad.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, ''); // Solo permite números
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13); // Limita a 13 caracteres
                }
            });

            direccion.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            usuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            correoElectronico.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >

            });

            nombreUsuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            // Bloquear copiar y pegar en todos los campos
            const campos = document.querySelectorAll('input[type="text"]');
            campos.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
            // Bloquear copiar y pegar en todos los campos
            const camposEmail = document.querySelectorAll('input[type="email"]');
            camposEmail.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
        });
    </script>



    <!-- SCRIPT PARA VALIDACIONES EN FORMULARIO EN MODAL EDITAR -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener referencias a los campos del formulario
            const numIdentidad = document.getElementById('edit_num_identidad');
            const direccion = document.getElementById('edit_direccion_1');
            const usuario = document.getElementById('edit_usuario');
            const correoElectronico = document.getElementById('edit_correo_electronico');
            const nombreUsuario = document.getElementById('edit_nombre_usuario');

            // Agregar evento de entrada para validar en tiempo real
            numIdentidad.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, ''); // Solo permite números
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13); // Limita a 13 caracteres
                }
            });

            direccion.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            usuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            correoElectronico.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >

            });

            nombreUsuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            // Bloquear copiar y pegar en todos los campos
            const campos = document.querySelectorAll('input[type="text"]');
            campos.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
            // Bloquear copiar y pegar en todos los campos
            const camposEmail = document.querySelectorAll('input[type="email"]');
            camposEmail.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });


        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--   <script src="../Seguridad//Bitacora//Bitacora.js"></script> -->
    <script src="../Seguridad//Permisos//modificar_permisos.js"></script>




<?php
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}
?>