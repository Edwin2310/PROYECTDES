function cargarMunicipios(idDepartamento) {
  if (idDepartamento == 0) {
    document.getElementById("IdMunicipio").innerHTML =
      '<option value="0">Seleccionar Municipio</option>';
    return;
  }

  // Realizar la solicitud AJAX
  fetch("mostrar_municipio.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `accion=obtenerMunicipios&idDepartamento=${idDepartamento}`,
  })
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("IdMunicipio").innerHTML = data;
    })
    .catch((error) => {
      console.error("Error al cargar los municipios:", error);
    });
}
