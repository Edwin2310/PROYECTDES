document.addEventListener("DOMContentLoaded", function () {
  // Ocultar campos iniciales
  document.getElementById("div-centralized").style.display = "none";

  // Manejar el cambio del código de pago
  document
    .getElementById("codigo-pago")
    .addEventListener("change", function () {
      var codigoPago = this.value;
      if (codigoPagoEspecifico(codigoPago)) {
        ocultarCampos();
        document.getElementById("div-centralized").style.display = "block";
      } else {
        mostrarCampos();
        document.getElementById("div-centralized").style.display = "none";
      }
    });

  // Manejar el cambio de la universidad
  document
    .getElementById("Universidad")
    .addEventListener("change", function () {
      var idUniversidad = this.value;
      cargarCedulas(idUniversidad);
    });

  function codigoPagoEspecifico(codigo) {
    // Define los códigos específicos que requieren ocultar los campos
    var codigosEspecificos = ["1", "2", "3"]; // Reemplaza con los códigos específicos correctos
    return codigosEspecificos.includes(codigo);
  }

  function ocultarCampos() {
    document.getElementById("div-departamento1").style.display = "none";
    document.getElementById("div-municipio1").style.display = "none";
  }

  function mostrarCampos() {
    document.getElementById("div-departamento1").style.display = "block";
    document.getElementById("div-municipio1").style.display = "block";
  }

  function cargarCedulas(idUniversidad) {
    // Llamada AJAX para obtener las cédulas basadas en la universidad seleccionada
    $.ajax({
      url: "../NuevoIngresoSolicitud/obtener_sedes.php", // Archivo PHP para obtener las cédulas
      method: "POST",
      data: { id_universidad: idUniversidad },
      dataType: "json",
      success: function (data) {
        var cedulasSelect = document.getElementById("Cedulas");
        cedulasSelect.innerHTML = ""; // Limpiar opciones actuales

        data.forEach(function (cedula) {
          var option = document.createElement("option");
          option.value = cedula.ID_SEDES;
          option.text = cedula.NOM_SEDES;
          cedulasSelect.add(option);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar cédulas:", error);
      },
    });
  }
});
