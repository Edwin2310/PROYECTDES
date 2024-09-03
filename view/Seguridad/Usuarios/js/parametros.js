document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencias a los campos del formulario
  const camposTexto = document.querySelectorAll('input[type="text"]');
  const camposEmail = document.querySelectorAll('input[type="email"]');

  // Función para validar campos de texto
  function validarTexto(campo) {
    campo.addEventListener("input", function () {
      this.value = this.value.replace(/\s/g, ""); // Elimina espacios en blanco
      this.value = this.value.replace(/[<>]/g, ""); // No permite < ni >
    });
  }

  // Aplicar la validación a cada campo de texto
  camposTexto.forEach(validarTexto);

  // Validación para campos de email
  camposEmail.forEach(function (campo) {
    campo.addEventListener("input", function () {
      this.value = this.value.replace(/[<>]/g, ""); // No permite < ni >
    });
  });

  // Bloquear copiar y pegar en todos los campos de texto
  camposTexto.forEach(function (campo) {
    campo.addEventListener("paste", function (event) {
      event.preventDefault();
    });
  });

  // Bloquear copiar y pegar en todos los campos de email
  camposEmail.forEach(function (campo) {
    campo.addEventListener("paste", function (event) {
      event.preventDefault();
    });
  });
});
