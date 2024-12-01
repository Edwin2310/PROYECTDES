<?php
session_start();
if (!isset($_SESSION["IdUsuario"])) {
    header("Location: ../index.php"); // Redirige al login si no hay sesión
    exit();
}

// Conexión a la base de datos para obtener los datos actuales
require_once("../../config/conexion.php");

$conexion = new Conectar();
$db = $conexion->Conexion();

// Inicializar variables
$datosUsuario = [
    'CorreoElectronico' => '',
    'NombreUsuario' => '',
    'NumIdentidad' => '',
    'NumEmpleado' => '',
    'Direccion' => '',
    'NombreRol' => '',
    'Usuario' => ''
];

$idUsuario = $_SESSION["IdUsuario"];
try {
    // Llamar al procedimiento almacenado
    $query = $db->prepare("CALL `seguridad.splMiPerfilMostrar`(:idUsuario)");
    $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $query->execute();

    $datosUsuario = $query->fetch(PDO::FETCH_ASSOC);

    $query->closeCursor(); // Cerrar el cursor después de ejecutar un procedimiento almacenado
} catch (Exception $e) {
    error_log("Error al obtener datos del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="shortcut icon" href="../../public/assets/img/favicons/favicon.png">
    <link rel="stylesheet" href="../../public/assets/css/codebase.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="perfil.css">
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-content">
                <!-- Logo y título -->
                <div class="content-side content-side-full px-10">
                    <div class="text-center">
                        <a class="img-link" href="../home/index.php">
                            <img class="img-avatar" src="../../public/assets/img/logo/LOGO.png" alt="">
                        </a>
                        <div class="font-size-lg text-dual-primary-dark mt-10">
                            Dirección de <br> Educación Superior
                        </div>
                    </div>
                </div>

                <!-- Opciones del usuario -->
                <div class="content-side content-side-full content-side-user px-10 align-parent">
                    <div class="sidebar-mini-hidden-b text-center">
                        <a class="img-link" href="../MntPerfil/perfil.php">
                            <img class="img-avatar" src="../../public/assets/img/avatars/avatar15.jpg" alt="Avatar">
                        </a>
                        <ul class="list-inline mt-10">
                            <li class="list-inline-item">
                                <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="../MntPerfil/perfil.php">
                                    <?php echo $_SESSION["NombreUsuario"]; ?>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                                    <i class="si si-drop"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="link-effect text-dual-primary-dark modulo-link" data-id-objeto="49" data-accion="Cerro sesión" href="../Logout/logout.php">
                                    <i class="si si-logout"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <nav id="sidebar" class="text-warning">

                    <div id="sidebar-scroll">

                        <div class="sidebar-content ">


                            <?php require_once("../MainSidebar/MainSidebar.php"); ?>


                            <?php require_once("../MainMenu/MainMenu.php"); ?>

                        </div>

                    </div>

                </nav>
            </div>
        </nav>
        <!-- Fin Sidebar -->

        <!-- Contenido Principal -->
        <main id="main-container">
            <div class="bg-image" style="background-image: url('../../public/assets/img/photos/photo35@2x.webp');">
                <div class="bg-primary-dark-op py-10">
                    <h1 class="h3 text-white">Mi Perfil</h1>
                </div>
                <div style="position: absolute; top: 60%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                    <!-- Foto de Perfil -->
                    <label for="fotoPerfilInput" style="cursor: pointer;">
                        <img src="obtener_foto.php?id=<?php echo $idUsuario; ?>" alt="Foto de Perfil" id="fotoPerfilPreview" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover;">
                    </label>

                    <!-- Contenedor de los botones -->
                    <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px;">
                        <!-- Botón para eliminar la foto -->
                        <button type="button" class="btn btn-danger" id="btnEliminarFoto">Eliminar Foto</button>
                        <!-- Botón para subir nueva foto -->
                        <button type="button" class="btn btn-primary" id="btnSubirFoto">Subir Foto</button>
                    </div>
                </div>


            </div>

            <div class="content">
                <!-- Formulario para editar perfil -->
                <div class="block">
                    <div class="block-content">
                        <!-- Formulario -->
                        <form action="guardar_perfil.php" method="post" id="formEditarPerfil" enctype="multipart/form-data" novalidate>
                            <!-- Información del perfil -->
                            <h4 class="mb-3">Información del Perfil</h4>
                            <div class="row">
                                <!-- Rol del Usuario -->
                                <div class="form-group col-md-6">
                                    <label for="nombreRol">Rol del Usuario</label>
                                    <input type="text" class="form-control" id="nombreRol" name="nombreRol" value="<?php echo htmlspecialchars($datosUsuario['NombreRol']); ?>" readonly>
                                </div>
                                <!-- Número de Empleado -->
                                <div class="form-group col-md-6">
                                    <label for="numEmpleado">Número de Empleado</label>
                                    <input type="text" class="form-control" id="numEmpleado" name="numEmpleado" value="<?php echo htmlspecialchars($datosUsuario['NumEmpleado']); ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Nombre de Usuario -->
                                <div class="form-group col-md-6">
                                    <label for="nombreUsuario">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($datosUsuario['NombreUsuario']); ?>" required maxlength="40" pattern="[A-Za-z\s]+" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');">
                                </div>
                                <!-- Número de Identidad -->
                                <div class="form-group col-md-6">
                                    <label for="numIdentidad">Número de Identidad</label>
                                    <input type="text" class="form-control" id="numIdentidad" name="numIdentidad" value="<?php echo htmlspecialchars($datosUsuario['NumIdentidad']); ?>" required maxlength="13" pattern="\d+">
                                </div>
                            </div>
                            <div class="row">
                                <!-- Usuario General -->
                                <div class="form-group col-md-6">
                                    <label for="Usuario">Usuario</label>
                                    <input type="text" class="form-control" id="Usuario" name="Usuario" value="<?php echo htmlspecialchars($datosUsuario['Usuario']); ?>" required maxlength="25" pattern="[a-zA-Z0-9._-]+" oninput="this.value = this.value.replace(/\s/g, '');">
                                </div>
                                <!-- Correo Electrónico -->
                                <div class="form-group col-md-6">
                                    <label for="correoElectronico">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correoElectronico" name="correoElectronico" value="<?php echo htmlspecialchars($datosUsuario['CorreoElectronico']); ?>" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                </div>
                            </div>

                            <div class="row">
                                <!--Dirección de residencia -->
                                <div class="form-group col-md-12">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($datosUsuario['Direccion']); ?>" maxlength="50">
                                </div>
                            </div>

                            <!-- Campo oculto para marcar la solicitud de eliminación -->
                            <input type="file" id="fotoPerfilInput" name="fotoPerfil" accept="image/*" style="display: none;">

                            <!-- Separador -->
                            <hr class="my-4">

                            <!-- Sección de contraseñas -->
                            <h4 class="mb-3">Cambio de Contraseña</h4>
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <i class="fa fa-info-circle mr-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>Nota:</strong> Si dejas los campos de contraseña vacíos, tu contraseña actual no será cambiada. Si decides ingresar una nueva, esta remplazará a la actual.
                                </div>
                            </div>
                            <div class="row">
                                <!-- Contraseña -->
                                <div class="form-group col-md-6">
                                    <label for="contrasena">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="contrasena" name="contrasena" maxlength="20" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                </div>
                                <!-- Confirmar Contraseña -->
                                <div class="form-group col-md-6"> 
                                    <label for="confirmarContrasena">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirmarContrasena" name="confirmarContrasena" maxlength="20" onkeypress="return event.charCode != 60 && event.charCode != 62 && event.charCode != 32 && event.charCode != 34;">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Checkbox para mostrar contraseñas -->
                                <div class="form-group col-md-12 d-flex align-items-center">
                                    <input type="checkbox" id="mostrarContrasena" class="mr-2">
                                    <label for="mostrarContrasena">Mostrar contraseñas</label>
                                </div>
                            </div>

                            <!-- Campo oculto para la URL de redirección -->
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '../home/index.php'); ?>">

                            <!-- Botones -->
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="../home/index.php" class="btn btn-secondary btn-block">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="perfil.js"></script>

</body>

</html>