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
        // Procesar la respuesta y mostrar los CodArbitrios
        if (data.success) {
          console.log("CodArbitrios obtenidos:", data.CodArbitrios); // Mostrar en la consola
        } else {
          alert(data.message || "No se pudo obtener los CodArbitrios.");
        }
      })
      .catch((error) => console.error("Error:", error));
  }
});
