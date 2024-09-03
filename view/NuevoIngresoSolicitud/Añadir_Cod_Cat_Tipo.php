<?php
session_start();

include("../../../config/conexion.php");

$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_pago = filter_input(INPUT_POST, 'codigo-pago', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $categ = filter_input(INPUT_POST, 'id_categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tipo_sol = filter_input(INPUT_POST, 'id_tipo_solicitud', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nom_carrera = filter_input(INPUT_POST, 'id_nom_carrera', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $grad_acad = filter_input(INPUT_POST, 'id_grad_acad', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $modalidad = filter_input(INPUT_POST, 'id_modalidad', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Consulta SQL para insertar el nuevo remitente
    $sql = "INSERT INTO tbl_solicitudes (ID_TIPO_SOLICITUD, ID_CATEGORIA, NOM_SOLICITUD, ID_CARRERA, ID_GRADO, ID_MODALIDAD) VALUES ( ?, ?, ?, ?, ?, ?, ?)";

    
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if ($stmt->execute([$cod_pago, $categ, $tipo_sol, $nom_carrera, $grad_acad, $modalidad])) {
            echo "Datos agregados exitosamente";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->errorInfo()[2];
    }

    // Cerrar la conexión
    $stmt = null;
    $conn = null;
}

exit();
?>

