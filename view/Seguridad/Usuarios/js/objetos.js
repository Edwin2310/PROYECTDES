document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencias a los campos del formulario
  const camposTexto = document.querySelectorAll('input[type="text"]');
  const camposEmail = document.querySelectorAll('input[type="email"]');

  // Función para validar campos de texto
  function validarTexto(campo) {
    campo.addEventListener("input", function () {
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

// Pasar datos al modal de edición
$("#editObjectModal").on("show.bs.modal", function (event) {
  var button = $(event.relatedTarget); // Botón que abrió el modal
  var id_objeto = button.data("id");
  var objeto = button.data("objeto");
  var tipo_objeto = button.data("tipo_objeto");
  var descripcion = button.data("descripcion");
  var creado_por = button.data("creado_por");
  var id_usuario = button.data("id_usuario"); // ID del usuario que está editando

  var modal = $(this);
  modal.find(".modal-body #edit-id_objeto").val(id_objeto);
  modal.find(".modal-body #edit-objeto").val(objeto);
  modal.find(".modal-body #edit-tipo_objeto").val(tipo_objeto);
  modal.find(".modal-body #edit-descripcion").val(descripcion);
  modal.find(".modal-body #edit-creado_por").val(creado_por);
  modal.find(".modal-body #edit-id_usuario").val(id_usuario); // Pasar el ID del usuario que está editando
});

// Pasar datos al modal de confirmación de eliminación
$("#confirmDeleteModal").on("show.bs.modal", function (event) {
  var button = $(event.relatedTarget); // Botón que abrió el modal
  var id_objeto = button.data("id");

  var modal = $(this);
  modal.find("#delete_id_objeto").val(id_objeto);
});

document.addEventListener("DOMContentLoaded", function () {
  const showMoreBtn = document.getElementById("showMoreBtn");
  const hiddenColumns = document.querySelectorAll(".hidden-column");

  showMoreBtn.addEventListener("click", function () {
    const isShowingMore = showMoreBtn.textContent === "Mostrar más";
    hiddenColumns.forEach(function (column) {
      column.style.display = isShowingMore ? "table-cell" : "none";
    });
    showMoreBtn.textContent = isShowingMore ? "Mostrar menos" : "Mostrar más";
  });
});
