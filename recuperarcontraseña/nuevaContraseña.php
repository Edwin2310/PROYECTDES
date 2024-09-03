<!DOCTYPE html>
<html lang="en" class="no-focus">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Restablecer Contraseña | Dirección de Educación Superior</title>
    <meta name="description" content="Codebase - Bootstrap 4 Admin Template & UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <!-- Open Graph Meta -->
    <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template & UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description" content="Codebase - Bootstrap 4 Admin Template & UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" href="../public/assets/img/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../public/assets/img/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../public/assets/img/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" href="../public/assets/css/codebase.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../public/assets/css/index.css">
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
                                    <h1 class="h3 font-w700 reduce-margin">Restablecer Contraseña</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0 reduce-margin">Ingrese su nueva contraseña</h2>
                                </div>
                            </div>
                            <div class="px-30">
                                <form id="form-nueva-contrasena" action="./guardarNuevaContraseña.php" method="post">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="nueva_contrasena">Nueva Contraseña</label>
                                                <i class="fas fa-eye toggle-password" id="toggle-nueva-contrasena"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                                                <i class="fas fa-eye toggle-password" id="toggle-confirmar-contrasena"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($_GET['correo']); ?>">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-hero btn-alt-primary" id="btn-guardar-contrasena">
                                            <i class="si si-login mr-10"></i> Restablecer contraseña
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="resetBack/nueva_Contraseña.js"></script>
    <script>
        $(document).ready(function() {
            $('#toggle-nueva-contrasena').click(function() {
                var nuevaContrasenaInput = $('#nueva_contrasena');
                var type = nuevaContrasenaInput.attr('type') === 'password' ? 'text' : 'password';
                nuevaContrasenaInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $('#toggle-confirmar-contrasena').click(function() {
                var confirmarContrasenaInput = $('#confirmar_contrasena');
                var type = confirmarContrasenaInput.attr('type') === 'password' ? 'text' : 'password';
                confirmarContrasenaInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
</body>

</html>