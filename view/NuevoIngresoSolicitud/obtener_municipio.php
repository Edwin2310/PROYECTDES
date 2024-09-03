

<?php
require_once("../../config/conexion.php");

if (isset($_POST['departamentos'])) {
    $departamentos = $_POST['departamentos'];
    $placeholders = rtrim(str_repeat('?,', count($departamentos)), ',');
    $sql = "SELECT ID_MUNICIPIO, NOM_MUNICIPIO FROM tbl_municipios WHERE ID_DEPARTAMENTO IN ($placeholders)";
    $conexion = new Conectar();
    $conn = $conexion->Conexion();
    $stmt = $conn->prepare($sql);
    $stmt->execute($departamentos);
    $municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($municipios);
}
?>
