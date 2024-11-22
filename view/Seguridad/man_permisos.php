<?php
session_start();

require_once("../../config/conexion.php");
require_once(__DIR__ . '/../Seguridad/Permisos/Funciones_Permisos.php');
if (isset($_SESSION["IdUsuario"])) {

    // Obtener los valores necesarios para la verificación
    $id_rol = $_SESSION['IdRol'] ?? null;
    $id_objeto = 17; // ID del objeto o módulo correspondiente a esta página
    // Llama a la función para verificar los permisos
    verificarPermiso($id_rol, $id_objeto);

?>


    <!doctype html>
    <html lang="en" class="no-focus">

    <head>
        <?php require_once("../MainHead/MainHead.php"); ?>
        <title>Usuarios</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

        <style>
            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #e4e4e4;
                /* Color de fondo de las etiquetas seleccionadas */
                color: black;
                /* Color del texto de las etiquetas seleccionadas */
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: black;
                /* Color de la 'x' para eliminar las etiquetas seleccionadas */
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
                        <h3 class="block-title text-center font-weight-bold display-5 ">MANTENIMIENTO DE ACCESOS</h3>
                    </div>
                    <br>
                    <form id="permisoForm" class="d-flex justify-content-center" method="POST" action="../Seguridad/Manpermisos/guardar_pantallas.php">
                        <!-- Elemento oculto para almacenar el IdRol del usuario -->
                        <input type="hidden" id="usuario-id-rol" value="<?php echo $id_rol; ?>">
                        <div class="row justify-content-center">
                            <!-- Listbox para nombres de roles -->
                            <div class="col-md-6 mb-3">
                                <label for="roleList">Roles:</label>
                                <select id="roleList" name="roleList" class="form-control">
                                    <!-- Opciones llenadas con PHP o JavaScript -->
                                    <?php
                                    try {
                                        // Asumiendo que ya tienes una clase llamada 'Conectar' con un método 'Conexion' que establece la conexión a la base de datos.
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Llamada al procedimiento almacenado
                                        $sql = "CALL `seguridad.splRolesLlenarSelect`()";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();

                                        // Obtener los resultados
                                        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Cerrar el cursor
                                        $stmt->closeCursor();

                                        // Poblar el elemento select
                                        foreach ($roles as $rol) {
                                            $idRol = htmlspecialchars($rol['IdRol'], ENT_QUOTES, 'UTF-8');
                                            $nombreRol = htmlspecialchars($rol['NombreRol'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$idRol'>$nombreRol</option>";
                                        }
                                    } catch (PDOException $e) {
                                        // Manejo de errores
                                        echo "<option>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
                            <!-- Select multiple para nombres de objetos -->
                            <div class="col-md-6 mb-3">
                                <label for="objectSelect">Pantallas:</label>
                                <select id="objectSelect" name="objectSelect[]" class="form-control basic-multiple" multiple>
                                    <!-- Opciones llenadas con PHP o JavaScript -->
                                    <?php
                                    try {
                                        // Asumiendo que ya tienes una clase llamada 'Conectar' con un método 'Conexion' que establece la conexión a la base de datos.
                                        $conexion = new Conectar();
                                        $conn = $conexion->Conexion();

                                        // Llamada al procedimiento almacenado
                                        $sql = "CALL `seguridad.splObjetosLlenarSelect`()";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();

                                        // Obtener los resultados
                                        $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Cerrar el cursor
                                        $stmt->closeCursor();

                                        // Poblar el elemento select
                                        foreach ($objetos as $objeto) {
                                            $idObjeto = htmlspecialchars($objeto['IdObjeto'], ENT_QUOTES, 'UTF-8');
                                            $nombreObjeto = htmlspecialchars($objeto['Objeto'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$idObjeto'>$nombreObjeto</option>";
                                        }
                                    } catch (PDOException $e) {
                                        // Manejo de errores
                                        echo "<option>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</option>";
                                    }

                                    ?>
                                </select>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        $('.basic-multiple').select2({
                                            tags: true,
                                            tokenSeparators: [',', ' ']
                                        });
                                    });
                                </script>

                                <br>
                                <br>
                            </div>

                            <button type="button" class="btn btn-primary accion-permiso" data-id-a-objeto="17" data-permiso="1">Guardar</button>
                        </div>

                    </form>
                    <?php
                    if (isset($_SESSION['permiso_msg'])) {
                        echo "<script>
                             Swal.fire({
                             icon: '" . $_SESSION['permiso_msg_type'] . "',
                             title: 'Pantallas',
                             text: '" . $_SESSION['permiso_msg'] . "',
                             });
                            </script>";
                        unset($_SESSION['permiso_msg']);
                        unset($_SESSION['permiso_msg_type']);
                    }
                    ?>



                    <!-- Filtros arriba de la tabla -->
                    <div class="block-content block-content-full">
                        <!-- Elemento oculto para almacenar el IdRol del usuario -->
                        <input type="hidden" id="usuario-id-rol" value="<?php echo $id_rol; ?>">
                        <div class="block-content block-content-full">
                            <form method="GET" action="">
                                <!-- Filtros -->
                                <div class="row align-items-end">
                                    <div class="w-50">
                                        <label for="filterRole">Filtrar por Nombre de NombreRol:</label>
                                        <select id="filterRole" name="filterRole" class="form-control">
                                            <option value="">Todos los Roles</option>
                                            <?php
                                            // Conectar y obtener roles
                                            $conexion = new Conectar();
                                            $conn = $conexion->Conexion();
                                            $sql = "CALL `seguridad.splRolesLlenarSelect`()";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($roles as $rol) {
                                                $idRol = htmlspecialchars($rol['IdRol'], ENT_QUOTES, 'UTF-8');
                                                $nombreRol = htmlspecialchars($rol['NombreRol'], ENT_QUOTES, 'UTF-8');
                                                echo "<option value='$idRol'" . ($idRol == $_GET['filterRole'] ? ' selected' : '') . ">$nombreRol</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <div class="w-100">
                                            <label for="filterScreen">Filtrar por Nombre de Pantalla:</label>
                                            <select id="filterScreen" name="filterScreen" class="form-control">
                                                <option value="">Todas las Pantallas</option>
                                                <?php
                                                // Conectar y obtener pantallas
                                                $sql = "CALL `seguridad.splObjetosLlenarSelect`()";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $pantallas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($pantallas as $pantalla) {
                                                    $idPantalla = htmlspecialchars($pantalla['IdObjeto'], ENT_QUOTES, 'UTF-8');
                                                    $nombrePantalla = htmlspecialchars($pantalla['Objeto'], ENT_QUOTES, 'UTF-8');
                                                    echo "<option value='$idPantalla'" . ($idPantalla == $_GET['filterScreen'] ? ' selected' : '') . ">$nombrePantalla</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <style>
                                            .custom-margin-left {
                                                margin-left: 20px;
                                                /* Ajusta el valor según sea necesario */
                                            }
                                        </style>
                                        <button type="submit" class="btn btn-primary custom-margin-left">Aplicar Filtros</button>
                                    </div>
                                </div>
                                <br>
                            </form>

                            <br>
                            <table id="permisosTable" class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="d-none d-sm-table-cell text-center ">Nombre NombreRol</th>
                                        <th class="d-none d-sm-table-cell text-center ">Nombre Pantalla</th>
                                        <th class="d-none d-sm-table-cell text-center">Permiso Inserción</th>
                                        <th class="d-none d-sm-table-cell text-center">Permiso Eliminación</th>
                                        <th class="d-none d-sm-table-cell text-center">Permiso Actualización</th>
                                        <th class="d-none d-sm-table-cell text-center">Permiso Consulta</th>
                                        <th class="text-center" style="width: 15%;">Eliminar</th>
                                    </tr>
                                <tbody>
                                    <?php
                                    $conexion = new Conectar();
                                    $conn = $conexion->Conexion();

                                    // Obtener los parámetros de filtrado
                                    $filterRole = isset($_GET['filterRole']) ? $_GET['filterRole'] : '';
                                    $filterScreen = isset($_GET['filterScreen']) ? $_GET['filterScreen'] : '';

                                    try {
                                        // Llamar al procedimiento almacenado
                                        $sql = "CALL `seguridad.splPermisosMostrar`(:filterRole, :filterScreen)";
                                        $stmt = $conn->prepare($sql);

                                        $stmt->bindParam(':filterRole', $filterRole, PDO::PARAM_STR);
                                        $stmt->bindParam(':filterScreen', $filterScreen, PDO::PARAM_STR);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($result) {
                                            foreach ($result as $row) {
                                                echo "<tr data-id-rol='{$row['IdRol']}' data-id-objeto='{$row['IdObjeto']}'>";
                                                echo "<td>{$row['NombreRol']}</td>";
                                                echo "<td>{$row['Objeto']}</td>";
                                                echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoInsercion'] ? 'checked' : '') . "></td>";
                                                echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoEliminacion'] ? 'checked' : '') . "></td>";
                                                echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoActualizacion'] ? 'checked' : '') . "></td>";
                                                echo "<td class='text-center'><input type='checkbox' disabled " . ($row['PermisoConsultar'] ? 'checked' : '') . "></td>";
                                                echo "<td class='text-center'>
                                               <button type='button' class='btn btn-sm btn-danger delete-row accion-permiso' data-id-a-objeto='17' data-permiso='2'  data-id-rol='{$row['IdRol']}' data-id-objeto='{$row['IdObjeto']}'>
                                                  <i class='si si-trash'></i>
                                               </button>
                                               </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='12' class='text-center'>Sin resultados</td></tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error: " . $e->getMessage();
                                    }

                                    // Cerrar la conexión
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
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const filterRoleSelect = document.getElementById('filterRole');
                    const filterScreenSelect = document.getElementById('filterScreen');
                    const permisosBody = document.getElementById('permisosBody');

                    function fetchFilteredData() {
                        const selectedRole = filterRoleSelect.value;
                        const selectedScreen = filterScreenSelect.value;

                        fetch(`fetch_permisos.php?filterRole=${encodeURIComponent(selectedRole)}&filterScreen=${encodeURIComponent(selectedScreen)}`)
                            .then(response => response.text())
                            .then(data => {
                                permisosBody.innerHTML = data;
                            })
                            .catch(error => console.error('Error fetching data:', error));
                    }

                    filterRoleSelect.addEventListener('change', fetchFilteredData);
                    filterScreenSelect.addEventListener('change', fetchFilteredData);

                    // Initial fetch
                    fetchFilteredData();
                });
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



<?php
if (isset($_SESSION["IdUsuario"])) {
?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!--  <script>
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
    </script> -->




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
    <script src="../Seguridad//Bitacora//Bitacora.js"></script>
    <script src="../Seguridad//Permisos//modificar_permisos.js"></script>
    <script src="../Seguridad//Manpermisos//eliminar_pantalla.js"></script>
    <script src="../Seguridad//Permisos//accion_permiso.js"></script>




<?php
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}
?>