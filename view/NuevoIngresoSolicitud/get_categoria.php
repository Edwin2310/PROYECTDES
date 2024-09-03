<?php
session_start();

include("../../../config/conexion.php");

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->Conexion();

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . $conn->errorInfo()[2]);
}

// Obtener los parámetros POST
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

if ($type == 'city') {
    $sql = "SELECT ID_CATEGORIA, NOM_CATEGORIA FROM tbl_categoria WHERE ID_CATEGORIA = :id";
} else {
    echo "Error: Tipo de lista no válido";
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

// Obtener todos los resultados
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar el HTML para las opciones
$html = '';
foreach ($arr as $list) {
    $html .= '<option value="' . htmlspecialchars($list['ID_CATEGORIA'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($list['NOM_CATEGORIA'], ENT_QUOTES, 'UTF-8') . '</option>';
}

// Devolver el HTML como respuesta
echo $html;

// Cerrar la conexión
$conn = null;
?>
