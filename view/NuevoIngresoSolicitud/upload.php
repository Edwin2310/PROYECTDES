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
    $creado_por = intval($_SESSION['IdUsuario']);

    // Obtener el IdSolicitud de la sesión
    if (!isset($_SESSION['IdSolicitud'])) {
        $_SESSION['file_errors'][] = "IdSolicitud no está disponible en la sesión.";
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'IdSolicitud no está disponible en la sesión.',
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
    $IdSolicitud = $_SESSION['IdSolicitud'];

    // Función para manejar la subida de archivos
    function uploadFile($file, $allowed_extensions)
    {
        global $conn; // Asegurarse de que la conexión global sea accesible
        $uploaded_files = []; // Array para almacenar los archivos subidos
        $subdir = ''; // Subdirectorio donde se guardará el archivo

        if (isset($_FILES[$file])) {
            $file_name = $_FILES[$file]['name'];
            $file_tmp_name = $_FILES[$file]['tmp_name'];
            $file_error = $_FILES[$file]['error'];
            $file_size = $_FILES[$file]['size'];

            if (!is_array($file_name)) {
                $file_name = [$file_name];
                $file_tmp_name = [$file_tmp_name];
                $file_error = [$file_error];
                $file_size = [$file_size];
            }

            foreach ($file_name as $index => $name) {
                $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if ($file_error[$index] === UPLOAD_ERR_OK) {
                    if ($name != '') {
                        if (in_array($file_ext, $allowed_extensions)) {
                            $file_base_name = pathinfo($name, PATHINFO_FILENAME);

                            $patterns = [
                                'Diagnostico' => "/^DIAG/",
                                'PlanEstudios' => "/^PLAN/",
                                'PlantaDocente' => "/^PDOC/",
                                'Solicitud' => "/^SOLI/"
                            ];

                            $is_valid = false;
                            foreach ($patterns as $dir => $pattern) {
                                if (preg_match($pattern, $file_base_name)) {
                                    $is_valid = true;
                                    $subdir = $dir;
                                    break;
                                }
                            }

                            if ($is_valid) {
                                $dir = 'documentos/' . $subdir . '/';
                                if (!file_exists($dir)) {
                                    mkdir($dir, 0777, true);
                                }

                                $dateStr = date('Ymd'); // Fecha actual en formato YYYYMMDD
                                $sequence = getSequentialNumber($dir, $file_base_name, $dateStr);
                                $new_name = $file_base_name . '_' . $dateStr . '_' . $sequence . '.' . $file_ext;

                                $target_path = $dir . $new_name;

                                if (move_uploaded_file($file_tmp_name[$index], $target_path)) {
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

        return $uploaded_files;
    }

    // Función para generar un número secuencial único
    function getSequentialNumber($dir, $base_name, $dateStr)
    {
        $files = scandir($dir);
        $sequence_numbers = [];

        foreach ($files as $file) {
            if (preg_match("/^" . preg_quote($base_name, '/') . "_{$dateStr}_(\d{3})\.\w+$/", $file, $matches)) {
                $sequence_numbers[] = (int)$matches[1];
            }
        }

        $next_sequence = count($sequence_numbers) > 0 ? max($sequence_numbers) + 1 : 1;
        return str_pad($next_sequence, 3, '0', STR_PAD_LEFT); // Formato de tres dígitos
    }



    // Definir las extensiones de archivo permitidas
    $allowed_extensions = ['pdf', 'doc', 'docx', 'xls'];

    // Llamar a la función uploadFile para manejar la subida de archivos
    $uploaded_files = uploadFile('file', $allowed_extensions);

    // **PASO 2: Insertar datos en tbl_archivos_adjuntos**
    $sql_adjuntos = "INSERT INTO `documentos.tblarchivosadjuntos` (FechaAdjunto, IdUsuario, Solicitud, PlanEstudios, PlantaDocente, Diagnostico, IdSolicitud)
                     VALUES (NOW(), :IdUsuario, :Solicitud, :PlanEstudios, :PlantaDocente, :Diagnostico, :IdSolicitud)";
    $stmt_adjuntos = $conn->prepare($sql_adjuntos);

    // Comprobar si la preparación de la consulta fue exitosa
    if ($stmt_adjuntos) {
        // Asignar el valor del ID del usuario
        $stmt_adjuntos->bindParam(':IdUsuario', $creado_por, PDO::PARAM_INT);
        $stmt_adjuntos->bindParam(':IdSolicitud', $IdSolicitud, PDO::PARAM_INT);

        // Inicializar variables para los campos
        $Solicitud = $PlanEstudios = $PlantaDocente = $Diagnostico = null;

        // Asignar archivos subidos a las variables correspondientes
        foreach ($uploaded_files as $file) {
            if (strpos($file, 'SOLI') !== false) {
                $Solicitud = $file;
            } elseif (strpos($file, 'PLAN') !== false) {
                $PlanEstudios = $file;
            } elseif (strpos($file, 'PDOC') !== false) {
                $PlantaDocente = $file;
            } elseif (strpos($file, 'DIAG') !== false) {
                $Diagnostico = $file;
            }
        }

        // Asignar valores a los parámetros
        $stmt_adjuntos->bindParam(':Solicitud', $Solicitud);
        $stmt_adjuntos->bindParam(':PlanEstudios', $PlanEstudios);
        $stmt_adjuntos->bindParam(':PlantaDocente', $PlantaDocente);
        $stmt_adjuntos->bindParam(':Diagnostico', $Diagnostico);

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
