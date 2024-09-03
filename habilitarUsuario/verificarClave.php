<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Verificación de Código | Dirección de Educación Superior</title>
    <link rel="stylesheet" href="../public/assets/css/codebase.min.css">
    <link rel="shortcut icon" href="../public/assets/img/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../public/assets/img/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../public/assets/img/favicons/apple-touch-icon-180x180.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                                    <h1 class="h3 font-w700 reduce-margin">Verificación de Código</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0 reduce-margin">Ingrese su código de
                                        validación</h2>
                                </div>
                            </div>
                            <div class="px-30">
                                <form id="form-verificar-codigo" action="./autenticarClave.php" method="post">
                                    <input type="hidden" id="nombre_usuario" name="nombre_usuario" value="<?php echo $_SESSION['NOMBRE_USUARIO']; ?>">

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="codigo_validacion" name="codigo_validacion" required pattern="[0-9]+" maxlength="6">
                                                <label for="codigo_validacion">Código de Validación</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary" id="btn-verificar-codigo">
                                            <i class="si si-login mr-10"></i> Verificar
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener referencia al campo de código de validación
            const codigoValidacionInput = document.getElementById('codigo_validacion');

            // Función para validar en tiempo real y evitar copiar y pegar
            codigoValidacionInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, ''); // Solo permite números
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6); // Limita a 6 caracteres
                }
            });

            codigoValidacionInput.addEventListener('paste', function(event) {
                event.preventDefault(); // Evitar pegar en el campo
            });

            // Manejar la solicitud de verificación de código
            document.getElementById('form-verificar-codigo').addEventListener('submit', function(e) {
                e.preventDefault();

                var codigo_validacion = codigoValidacionInput.value;
                var form = new FormData();
                form.append('codigo_validacion', codigo_validacion);

                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor, espere mientras procesamos su solicitud.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('./autenticarClave.php', {
                        method: 'POST',
                        body: form
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'Éxito',
                                html: data.message,
                                icon: 'success',
                                showConfirmButton: false // Ocultar el botón "OK"
                            });
                            setTimeout(() => {
                                window.location.href = 'nuevaClave.php'; // Redirigir a la página nuevaClave.php
                            }, 1000); // Esperar 1 segundo antes de redirigir
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
        });
    </script>
</body>

</html>