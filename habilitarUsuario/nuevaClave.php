<?php
include("../models/Email_Clave.php");
?>
<!DOCTYPE html>
<html lang="en" class="no-focus">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Confirmar Contraseña | Dirección de Educación Superior</title>
    <link rel="stylesheet" href="../public/assets/css/codebase.min.css">
    <link rel="icon" type="image/png" sizes="192x192" href="../public/assets/img/favicons/favicon-192x192.png">
</head>

<body>
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="bg-image" style="background-image: url('../public/assets/img/photos/photo34@2x.jpg');">
                <div class="row mx-0 bg-black-op">
                    <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end"></div>
                    <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
                        <div class="content content-full">
                            <div class="text-center mb-5">
                                <img src="../public/assets/img/Logo/LOGO.png" alt="Logo" style="max-width: 100px;">
                            </div>
                            <div class="text-center mb-20">
                                <a class="link-effect font-w700">
                                    <span class="font-size-xl text-primary-dark">Dirección de Educación Superior</span>
                                </a>
                                <div class="subtitle-container">
                                    <br>
                                    <h1 class="h3 font-w700 reduce-margin">Confirmar Contraseña</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0 reduce-margin">Ingrese su nueva contraseña</h2>
                                </div>
                            </div>
                            <div class="px-30">
                                <form id="form-nueva-contrasena" action="./procesarClave.php" method="post">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="contrasena" name="contrasena" maxlength="6" required onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="contrasena">Contraseña Temporal</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="nueva_contrasena">Nueva Contraseña</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input type="checkbox" id="mostrar_contrasena" onclick="togglePasswordVisibility()">
                                            <label for="mostrar_contrasena">Mostrar contraseñas</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary" id="btn-guardar-contrasena">
                                            <i class="si si-login mr-10"></i>Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
    <script src="../public/assets/js/pages/op_auth_signin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePasswordVisibility() {
            const contrasena = document.getElementById('contrasena');
            const nuevaContrasena = document.getElementById('nueva_contrasena');
            const confirmarContrasena = document.getElementById('confirmar_contrasena');
            const mostrarContrasena = document.getElementById('mostrar_contrasena');

            const tipo = mostrarContrasena.checked ? 'text' : 'password';

            contrasena.type = tipo;
            nuevaContrasena.type = tipo;
            confirmarContrasena.type = tipo;
        }
    </script>

    <script>
        document.getElementById('form-nueva-contrasena').addEventListener('submit', function(e) {
            e.preventDefault();

            var nuevaContrasena = document.getElementById('nueva_contrasena').value;
            var confirmarContrasena = document.getElementById('confirmar_contrasena').value;
            var errores = [];

            // Validaciones
            if (nuevaContrasena.length < 8) {
                errores.push('La nueva contraseña debe tener al menos 8 caracteres.');
            }
            if (!/[A-Z]/.test(nuevaContrasena)) {
                errores.push('La nueva contraseña debe contener al menos una letra mayúscula.');
            }
            if (!/[a-z]/.test(nuevaContrasena)) {
                errores.push('La nueva contraseña debe contener al menos una letra minúscula.');
            }
            if (!/[0-9]/.test(nuevaContrasena)) {
                errores.push('La nueva contraseña debe contener al menos un número.');
            }
            if (!/[!@#$%^&*]/.test(nuevaContrasena)) {
                errores.push('La nueva contraseña debe contener al menos un carácter especial.');
            }
            if (nuevaContrasena !== confirmarContrasena) {
                errores.push('La confirmación de la contraseña no coincide con la nueva contraseña.');
            }

            if (errores.length > 0) {
                Swal.fire({
                    title: 'Error',
                    html: errores.join('<br>'),
                    icon: 'error'
                });
                return;
            }

            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor, espere mientras procesamos su solicitud.',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            var form = document.getElementById('form-nueva-contrasena');
            var formData = new FormData(form);

            fetch('./procesarClave.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false, // Ocultar el botón "OK"
                            timer: 1000 // Tiempo en milisegundos (1 segundo)
                        }).then(() => {
                            window.location.href = 'http://localhost/PROYECTDES/index.php';
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
                        text: 'Hubo un problema con la solicitud. Por favor, inténtelo de nuevo más tarde.',
                        icon: 'error'
                    });
                });
        });
    </script>
</body>

</html>