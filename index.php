<?php
session_start(); // Iniciar la sesión al inicio del script

// Incluir la conexión a la base de datos y la clase Usuario
require_once("config/conexion.php");
require_once("models/Usuario.php");

$usuario = new Usuario();

// Procesar el formulario de inicio de sesión cuando se envía
if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    $usuario->login();
}
?>
<!doctype html>
<html lang="en" class="no-focus">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>SIGAES </title>
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
    <link rel="shortcut icon" href="public/assets/img/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="public/assets/img/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="public/assets/img/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="public/assets/css/codebase.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="public/assets/css/index.css">
</head>

<body>
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="bg-image" style="background-image: url('public/assets/img/photos/photo34@2x.jpg');">
                <div class="row mx-0 bg-black-op">
                    <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end"></div>
                    <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
                        <div class="content content-full">
                            <div class="text-center mb-5">
                                <img src="public/assets/img/Logo/LOGO.png" alt="Logo" style="max-width: 100px;">
                            </div>
                            <div class="text-center mb-20">
                                <a class="link-effect font-w700">
                                    <span class="font-size-xl text-primary-dark">Dirección de Educación Superior</span>
                                </a>
                            </div>
                            <div class="subtitle-container">
                                <h1 class="h3 font-w700">Bienvenido a su panel de control</h1>
                                <h2 class="h5 font-w400 text-muted mb-0">Por favor, inicie sesión</h2>
                            </div>
                            <div class="js-validation-signin px-30" action="be_pages_auth_all.html" method="post">
                                <form action="index.php" method="post" id="loginnum1">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="email" class="form-control" id="correo" name="correo" onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="login-username">Correo Electrónico</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="password" name="contrasena" onpaste="return false;" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                                <label for="login-password">Contraseña</label>
                                                <span id="togglePassword" class="fa fa-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="login-remember-me" name="login-remember-me">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Recuérdame</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="enviar" class="form-control" value="si">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary">
                                            <i class="si si-login mr-10"></i> Iniciar sesión
                                        </button>
                                        <div class="mt-30">
                                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="registroUsuario/registro.php">
                                                <i class="fa fa-plus mr-5"></i> Crear cuenta
                                            </a>
                                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="recuperarcontraseña/recuperar.php">
                                                <i class="fa fa-warning mr-5"></i> ¿Olvidaste tu contraseña?
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="public/assets/js/core/jquery.min.js"></script>
    <script src="public/assets/js/core/popper.min.js"></script>
    <script src="public/assets/js/core/bootstrap.min.js"></script>
    <script src="public/assets/js/core/jquery.slimscroll.min.js"></script>
    <script src="public/assets/js/core/jquery.scrollLock.min.js"></script>
    <script src="public/assets/js/core/jquery.appear.min.js"></script>
    <script src="public/assets/js/core/jquery.countTo.min.js"></script>
    <script src="public/assets/js/core/js.cookie.min.js"></script>
    <script src="public/assets/js/codebase.js"></script>
    <script src="public/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="./navega.js"></script>
    <script>
        <?php if (isset($_SESSION["error"])) : ?>
            var errorMessage = '<?php echo $_SESSION["error"]; ?>';
            <?php unset($_SESSION["error"]); // Limpiar el mensaje de error después de mostrarlo 
            ?>
        <?php else : ?>
            var errorMessage = '';
        <?php endif; ?>
    </script>
    <script src="index.js"></script>
</body>

</html>