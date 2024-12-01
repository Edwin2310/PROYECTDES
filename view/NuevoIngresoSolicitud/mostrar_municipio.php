<?php
// Usa __DIR__ para construir la ruta correcta a conexion.php
require_once(__DIR__ . '/../../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'obtenerMunicipios') {
    $idDepartamento = intval($_POST['idDepartamento'] ?? 0);

    if ($idDepartamento > 0) {
        echo obtenerMunicipiosPorDepartamento($idDepartamento);
    } else {
        echo '<option value="0">Seleccionar Municipio</option>';
    }
    exit;
}

// Función existente
function obtenerMunicipiosPorDepartamento($idDepartamento)
{
    try {
        $conexion = new Conectar();
        $conn = $conexion->Conexion();

        // Llamar al procedimiento almacenado
        $sql = "CALL `proceso.splObtenerMunicipios`(:idDepartamento)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':idDepartamento', $idDepartamento, PDO::PARAM_INT);
        $stmt->execute();

        // Generar las opciones del select
        $municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $opciones = '<option value="0">Seleccionar Municipio</option>';
        foreach ($municipios as $municipio) {
            $idMunicipio = htmlspecialchars($municipio['IdMunicipio'], ENT_QUOTES, 'UTF-8');
            $nombreMunicipio = htmlspecialchars($municipio['NomMunicipio'], ENT_QUOTES, 'UTF-8');
            $opciones .= "<option value='$idMunicipio'>$nombreMunicipio</option>";
        }

        return $opciones;
    } catch (PDOException $e) {
        return '<option value="0">Error al cargar municipios</option>';
    }
}
