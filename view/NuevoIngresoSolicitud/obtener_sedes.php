


<?php
require_once("../../config/conexion.php");

if (isset($_POST['id_universidad'])) {
    $id_universidad = $_POST['id_universidad'];

    $conexion = new Conectar();
    $conn = $conexion->Conexion();

    if (!$conn) {
        die("Conexión fallida: " . $conn->errorInfo()[2]);
    }

    $sql = "SELECT ID_SEDES, NOM_SEDES FROM tbl_sedes WHERE ID_UNIVERSIDAD = :id_universidad";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_universidad', $id_universidad, PDO::PARAM_INT);
    $stmt->execute();

    $cedulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cedulas);

    $conn = null;
}
?>
