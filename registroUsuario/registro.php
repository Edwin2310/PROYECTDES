<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Usuario | Dirección de Educación Superior</title>
    <meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" href="../public/assets/img/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../public/assets/img/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../public/assets/img/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="../public/assets/css/codebase.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="bg-image" style="background-image: url('../public/assets/img/photos/photo34@2x.jpg');">
                <div class="row mx-0 bg-black-op">
                    <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end"></div>
                    <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white">
                        <div class="content content-full">
                            <div class="text-center mb-5">
                                <img src="../public/assets/img/Logo/LOGO.png" alt="Logo" style="max-width: 100px;">
                            </div>
                            <div class="text-center mb-20">
                                <a class="link-effect font-w700">
                                    <span class="font-size-xl text-primary-dark">Dirección de Educación Superior</span>
                                </a>
                            </div>
                            <div class="px-30 py-10">
                                <h1 class="h3 font-w700 mt-30 mb-10">Crear nueva cuenta</h1>
                                <h2 class="h5 font-w400 text-muted mb-0">Por favor agregue los detalles</h2>
                            </div>
                            <div class="px-30">
                                <form id="addUserForm" method="POST">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="num_identidad" name="num_identidad" required>
                                                <label for="num_identidad">Número de Identidad</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input maxlength="50" type="text" class="form-control" id="direccion_1" name="direccion_1" required>
                                                <label for="direccion_1">Dirección</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input maxlength="50" type="text" class="form-control" id="usuario" name="usuario" required>
                                                <label for="usuario">Usuario</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input maxlength="50" type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                                                <label for="edit_correo_electronico">Correo Electrónico</label>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese un correo electrónico válido.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input maxlength="50" type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                                                <label for="nombre_usuario">Nombre de Usuario</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Campo para seleccionar nueva universidad -->
                                    <div class="form-group">
                                        <div class="form-material floating">
                                            <label for="edit_universidad_nueva">Seleccionar Universidad</label>
                                            <select class="form-control" id="id_universidad" name="id_universidad" required>
                                                <option value="" disabled selected style="display:none;"></option>
                                                <?php
                                                require_once("../config/conexion.php");

                                                try {
                                                    // Crear una instancia de la conexión
                                                    $conexion = new Conectar();
                                                    $conn = $conexion->Conexion();

                                                    // Llamar al procedimiento almacenado
                                                    $sql = "CALL `proceso.splUniversidadesListar`();";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();

                                                    // Verificar si hay resultados y generar las opciones del select
                                                    if ($stmt->rowCount() > 0) {
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='" . htmlspecialchars($row["IdUniversidad"]) . "'>" . htmlspecialchars($row["NomUniversidad"]) . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value='' disabled>No hay universidades disponibles</option>";
                                                    }

                                                    // Cerrar el cursor
                                                    $stmt->closeCursor();
                                                } catch (PDOException $e) {
                                                    echo "<script>Swal.fire('Error', 'Error al cargar universidades: " . $e->getMessage() . "', 'error');</script>";
                                                } finally {
                                                    $conn = null;
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <br>

                                        <input type="hidden" id="estado_usuario" name="estado_usuario" value="1">
                                        <div class="form-group" style="display: none;">
                                            <label for="id_rol">Seleccionar Nuevo Rol</label>
                                            <select class="form-control" id="id_rol" name="id_rol" required>
                                                <option value="1" selected>Administrador</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="id_usuario" name="id_usuario" value="1">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary" id="guardarBtn">
                                            <i class="fa fa-plus mr-10"></i>Guardar
                                        </button>
                                </form>
                                <div class="mt-30">
                                    <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="../index.php">
                                        <i class="fa fa-plus mr-5"></i> Iniciar Sesion
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-slidedown" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Terms & Conditions</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                            <i class="fa fa-check"></i> Perfect
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../public/assets/js/core/jquery.min.js"></script>
    <script src="../public/assets/js/core/popper.min.js"></script>
    <script src="../public/assets/js/core/bootstrap.min.js"></script>
    <script src="../public/assets/js/core/jquery.slimscroll.min.js"></script>
    <script src="../public/assets/js/core/jquery.scrollLock.min.js"></script>
    <script src="../public/assets/js/core/jquery.appear.min.js"></script>
    <script src="../public/assets/js/core/jquery.countTo.min.js"></script>
    <script src="../public/assets/js/core/js.cookie.min.js"></script>
    <script src="../public/assets/js/codebase.js"></script>
    <script src="../public/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../public/assets/js/pages/op_auth_signup.js"></script>
    <script src="validarInput.js"></script> <!-- Incluir el archivo de validación -->

    <!-- Script para validar y agregar usuario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var numIdentidadInput = document.getElementById('num_identidad');
            var nombreUsuarioInput = document.getElementById('nombre_usuario');
            var direccionInput = document.getElementById('direccion_1');
            var usuarioInput = document.getElementById('usuario');
            var correoElectronicoInput = document.getElementById('correo_electronico');
            var universidadInput = document.getElementById('id_universidad');

            numIdentidadInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, ''); // Solo números
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13); // Limitar a 13 caracteres
                }
            });

            nombreUsuarioInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Solo letras y espacios
                this.value = this.value.replace(/\s+/g, ' '); // Eliminar espacios adicionales
            });

            direccionInput.addEventListener('input', function() {
                // Permitir solo letras, espacios y caracteres especiales no incluyendo < >
                this.value = this.value.replace(/[^\w\s,.-]/g, '');
                this.value = this.value.replace(/\s+/g, ' '); // Eliminar espacios adicionales
            });

            usuarioInput.addEventListener('input', function() {
                // Permitir solo letras, números, guiones bajos y medios, sin espacios
                this.value = this.value.replace(/[^\w.-]/g, '');
            });

            correoElectronicoInput.addEventListener('input', function() {
                // Eliminar caracteres < y >
                this.value = this.value.replace(/[<>]/g, '');
            });

            correoElectronicoInput.addEventListener('paste', function(event) {
                event.preventDefault();
                return false;
            });

            // Evitar copiar y pegar en todos los campos de texto
            var inputs = document.querySelectorAll('input[type=text], textarea');
            inputs.forEach(function(input) {
                input.addEventListener('paste', function(event) {
                    event.preventDefault();
                    return false;
                });
            });

            document.getElementById('addUserForm').addEventListener('submit', function(e) {
                e.preventDefault();

                var numIdentidad = numIdentidadInput.value.trim();
                var containsMoreThanSevenZeros = /0{8,}/.test(numIdentidad);

                if (numIdentidad.length !== 13 || containsMoreThanSevenZeros) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Número de Identidad incorrecto',
                        text: 'Por favor ingrese un número de identidad válido (13 dígitos y no puede contener más de siete ceros consecutivos).'
                    });
                    return; // Detener el envío del formulario si la validación falla
                }

                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor, espere mientras guardamos el usuario.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('./Agregar_Usuario.php', {
                        method: 'POST',
                        body: new FormData(this) // Asegúrate de que 'this' se refiere al formulario correcto
                    })
                    .then(response => response.json()) // Convierte la respuesta en JSON
                    .then(data => {
                        console.log(data); // Muestra la respuesta en la consola del navegador
                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'Usuario creado',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al crear el usuario. Por favor, inténtelo de nuevo.',
                            icon: 'error'
                        });
                    });
            });
        });
    </script>




</body>

</html>