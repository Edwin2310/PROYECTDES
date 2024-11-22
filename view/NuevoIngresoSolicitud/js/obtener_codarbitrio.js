document.getElementById("IdCategoria").addEventListener("change", function () {
  const categoriaId = this.value;

  if (categoriaId && categoriaId !== "0") {
    // Realizar la solicitud AJAX al servidor
    fetch("obtenerCodArbitrios.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        IdCategoria: categoriaId,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Actualizar el campo Código de Pago
        if (data.success) {
          document.getElementById("CodigoPago").value = data.CodArbitrios || "";
        } else {
          document.getElementById("CodigoPago").value = "";
          alert(data.message || "No se pudo obtener el código de arbitrios.");
        }
      })
      .catch((error) => console.error("Error:", error));
  } else {
    // Si no se selecciona una categoría válida, limpiar el campo
    document.getElementById("CodigoPago").value = "";
  }
});
