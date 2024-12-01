document.addEventListener("DOMContentLoaded", function () {
  // Obtener el IdUniversidad desde el input oculto
  const idUniversidadUsuario = document.getElementById(
    "IdUniversidadUsuario"
  ).value;

  // Buscar y preseleccionar la opción correspondiente
  const selectUniversidad = document.getElementById("IdUniversidad");
  if (idUniversidadUsuario) {
    const optionToSelect = selectUniversidad.querySelector(
      `option[value="${idUniversidadUsuario}"]`
    );
    if (optionToSelect) {
      optionToSelect.selected = true;
    }
  }
});
