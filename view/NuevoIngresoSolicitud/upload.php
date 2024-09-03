<?php
// Iniciar sesión para almacenar mensajes de error
session_start();

// Mostrar errores para depuración (deshabilitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión a la base de datos
include("../../config/conexion.php");

// Crear una instancia de la conexión a la base de datos
$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión a la base de datos
if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

// Configurar la zona horaria de Tegucigalpa
date_default_timezone_set('America/Tegucigalpa');

// Variable global para almacenar errores de archivo
$_SESSION['file_errors'] = [];

// Comprobar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitización de datos: obtener el ID del usuario desde la sesión
    $creado_por = intval($_SESSION['ID_USUARIO']);

    // Obtener el ID_SOLICITUD de la sesión
    if (!isset($_SESSION['ID_SOLICITUD'])) {
        $_SESSION['file_errors'][] = "ID_SOLICITUD no está disponible en la sesión.";
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'ID_SOLICITUD no está disponible en la sesión.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../view/NuevoIngresoSolicitud/index.php';
                }
            });
        </script>";
        exit;
    }
    $id_solicitud = $_SESSION['ID_SOLICITUD'];

    // Función para manejar la subida de archivos
    function uploadFile($file, $allowed_extensions)
    {
        global $conn; // Asegurarse de que la conexión global sea accesible
        $uploaded_files = []; // Array para almacenar los archivos subidos
        $subdir = ''; // Subdirectorio donde se guardará el archivo

        // Verificar si el archivo fue subido
        if (isset($_FILES[$file])) {
            $file_name = $_FILES[$file]['name'];
            $file_tmp_name = $_FILES[$file]['tmp_name'];
            $file_error = $_FILES[$file]['error'];
            $file_size = $_FILES[$file]['size'];

            // Asegúrate de que los archivos sean arrays si se suben múltiples archivos
            if (!is_array($file_name)) {
                $file_name = [$file_name];
                $file_tmp_name = [$file_tmp_name];
                $file_error = [$file_error];
                $file_size = [$file_size];
            }

            // Iterar sobre cada archivo subido
            foreach ($file_name as $index => $name) {
                // Depuración: imprimir el nombre del archivo recibido
                echo "Nombre del archivo recibido: $name<br>";

                // Obtener la extensión del archivo
                $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                // Depuración: imprimir la extensión del archivo
                echo "Extensión del archivo: $file_ext<br>";

                // Verificar si no hubo errores al subir el archivo
                if ($file_error[$index] === UPLOAD_ERR_OK) {
                    // Comprobar si el nombre del archivo no está vacío
                    if ($name != '') {
                        // Verificar si la extensión del archivo está permitida
                        if (in_array($file_ext, $allowed_extensions)) {
                            // Obtener el nombre base del archivo
                            $file_base_name = pathinfo($name, PATHINFO_FILENAME);
                            // Obtener la fecha actual en el formato YYYYMMDD
                            //$dateStr = date('Ymd');

                            // Depuración: imprimir el nombre base del archivo
                            //echo "Nombre base del archivo: $file_base_name<br>";
                            // Depuración: imprimir la fecha esperada
                            //echo "Fecha esperada: $dateStr<br>";

                            // Define los patrones para cada tipo de archivo
                            $patterns = [
                                'diagnostico' => "/^DIAG_/",
                                'plan_estudios' => "/^PLAN_/",
                                'planta_docente' => "/^PDOC_/",
                                'solicitud' => "/^SOLI_/"
                            ];

                            // Verificar si el nombre del archivo coincide con alguno de los patrones
                            $is_valid = false;
                            foreach ($patterns as $dir => $pattern) {
                                if (preg_match($pattern, $file_base_name)) {
                                    $is_valid = true;
                                    $subdir = $dir;
                                    break;
                                }
                            }

                            // Si el nombre del archivo es válido
                            if ($is_valid) {
                                // Determinar el directorio de destino basado en el tipo de archivo
                                $dir = 'documentos/' . $subdir . '/';
                                // Crear el directorio si no existe
                                if (!file_exists($dir)) {
                                    mkdir($dir, 0777, true);
                                }

                                // Definir la ruta de destino del archivo
                                $target_path = $dir . $name;

                                // Mover el archivo a la ubicación deseada
                                if (move_uploaded_file($file_tmp_name[$index], $target_path)) {
                                    // Añadir el archivo a la lista de archivos subidos
                                    $uploaded_files[] = $target_path;
                                } else {
                                    $_SESSION['file_errors'][] = "Error al mover el archivo: $name";
                                }
                            } else {
                                $_SESSION['file_errors'][] = "Formato de nombre de archivo no válido: $name";
                            }
                        } else {
                            $_SESSION['file_errors'][] = "Extensión de archivo no permitida: $file_ext";
                        }
                    } else {
                        $_SESSION['file_errors'][] = "Nombre de archivo vacío";
                    }
                } else {
                    $_SESSION['file_errors'][] = "Error de subida: " . $file_error[$index];
                }
            }
        }

        // Devolver la lista de archivos subidos
        return $uploaded_files;
    }

    // Definir las extensiones de archivo permitidas
    $allowed_extensions = ['pdf', 'doc', 'docx', 'xls'];

    // Llamar a la función uploadFile para manejar la subida de archivos
    $uploaded_files = uploadFile('file', $allowed_extensions);

    // **PASO 2: Insertar datos en tbl_archivos_adjuntos**
    $sql_adjuntos = "INSERT INTO tbl_archivos_adjuntos (FECHA_ADJUNTOS, ID_USUARIO, SOLICITUD, PLAN_ESTUDIOS, PLANTA_DOCENTE, DIAGNOSTICO, ID_SOLICITUD)
                     VALUES (NOW(), :id_usuario, :solicitud, :plan_estudios, :planta_docente, :diagnostico, :id_solicitud)";
    $stmt_adjuntos = $conn->prepare($sql_adjuntos);

    // Comprobar si la preparación de la consulta fue exitosa
    if ($stmt_adjuntos) {
        // Asignar el valor del ID del usuario
        $stmt_adjuntos->bindParam(':id_usuario', $creado_por, PDO::PARAM_INT);
        $stmt_adjuntos->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);

        // Inicializar variables para los campos
        $solicitud = $plan_estudios = $planta_docente = $diagnostico = null;

        // Asignar archivos subidos a las variables correspondientes
        foreach ($uploaded_files as $file) {
            if (strpos($file, 'SOLI_') !== false) {
                $solicitud = $file;
            } elseif (strpos($file, 'PLAN_') !== false) {
                $plan_estudios = $file;
            } elseif (strpos($file, 'PDOC_') !== false) {
                $planta_docente = $file;
            } elseif (strpos($file, 'DIAG_') !== false) {
                $diagnostico = $file;
            }
        }

        // Asignar valores a los parámetros
        $stmt_adjuntos->bindParam(':solicitud', $solicitud);
        $stmt_adjuntos->bindParam(':plan_estudios', $plan_estudios);
        $stmt_adjuntos->bindParam(':planta_docente', $planta_docente);
        $stmt_adjuntos->bindParam(':diagnostico', $diagnostico);

        // Ejecutar la consulta y comprobar si hay errores
        if ($stmt_adjuntos->execute()) {
            // Si se insertaron los datos correctamente
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'La solicitud y los archivos se han guardado exitosamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../view/NuevoIngresoSolicitud/procesar_solicitud.php';
                    }
                });
            </script>";
        } else {
            $_SESSION['file_errors'][] = "Error en la inserción en tbl_archivos_adjuntos: " . $stmt_adjuntos->errorInfo()[2];
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Errores en la carga de archivos: " . implode(' | ', $_SESSION['file_errors']) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../view/NuevoIngresoSolicitud/index.php';
                    }
                });
            </script>";
        }
    } else {
        $_SESSION['file_errors'][] = "Error al preparar la consulta para tbl_archivos_adjuntos.";
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Error al preparar la consulta para tbl_archivos_adjuntos.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../view/NuevoIngresoSolicitud/index.php';
                }
            });
        </script>";
    }
}
