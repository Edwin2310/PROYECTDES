<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Verificación de Código | Dirección de Educación Superior</title>
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
    <!-- Incluir la librería de SweetAlert2 para mostrar mensajes -->
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
                                <!-- Logo de la Dirección de Educación Superior -->
                                <img src="../public/assets/img/Logo/LOGO.png" alt="Logo" style="max-width: 100px;">
                            </div>
                            <div class="text-center mb-20">
                                <a class="link-effect font-w700">
                                    <span class="font-size-xl text-primary-dark">Dirección de Educación Superior</span>
                                </a>
                                <div class="subtitle-container">
                                    <br>
                                    <!-- Títulos de la página -->
                                    <h1 class="h3 font-w700 reduce-margin">Verificación de Código</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0 reduce-margin">Ingrese su código de validación</h2>
                                </div>
                            </div>
                            <div class="px-30">
                                <!-- Formulario para ingresar el código de validación -->
                                <form id="form-verificar-codigo">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="codigo" name="codigo" required pattern="[0-9]+" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);" onpaste="return false;">
                                                <label for="codigo">Código de Validación</label>
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

    <!-- Inclusión de archivos JavaScript necesarios -->
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
    <script src="resetBack/verificar_Codigo.js"></script>
    
</body>
</html>
