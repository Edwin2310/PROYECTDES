document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formEditarPerfil");
  const contrasena = document.getElementById("contrasena");
  const confirmarContrasena = document.getElementById("confirmarContrasena");
  const nombreUsuario = document.getElementById("nombreUsuario"); // Solo letras y espacios
  const correoElectronico = document.getElementById("correoElectronico");
  const numIdentidad = document.getElementById("numIdentidad");
  const direccion = document.getElementById("direccion");
  const usuarioInput = document.getElementById("Usuario"); // Campo Usuario
  const mostrarContrasena = document.getElementById("mostrarContrasena"); // Checkbox
  const fotoPerfilInput = document.getElementById("fotoPerfilInput");
  const btnSubirFoto = document.getElementById("btnSubirFoto");
  const fotoPerfilPreview = document.getElementById("fotoPerfilPreview");
  const btnEliminarFoto = document.getElementById("btnEliminarFoto");
  let fotoEliminada = false; // Variable temporal para gestionar la eliminación

  // Validaciones del formulario
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Prevenir envío inicial
    let valid = true;
    let errorMessages = []; // Acumular errores

    // Validar campos obligatorios excepto contraseñas
    if (nombreUsuario.value.trim() === "") {
      errorMessages.push('El campo "Nombre de Usuario" está vacío.');
      valid = false;
    }

    if (correoElectronico.value.trim() === "") {
      errorMessages.push('El campo "Correo Electrónico" está vacío.');
      valid = false;
    }

    if (numIdentidad.value.trim() === "") {
      errorMessages.push('El campo "Número de Identidad" está vacío.');
      valid = false;
    }

    if (direccion.value.trim() === "") {
      errorMessages.push('El campo "Dirección" está vacío.');
      valid = false;
    }

    if (usuarioInput.value.trim() === "") {
      errorMessages.push('El campo "Usuario" está vacío.');
      valid = false;
    }

    // Validar Nombre de Usuario: solo letras y espacios, máximo 40 caracteres
    nombreUsuario.addEventListener("input", function () {
      this.value = this.value.replace(/[^A-Za-z\s]/g, ""); // Eliminar números y caracteres no permitidos
      if (this.value.length > 40) {
        this.value = this.value.substring(0, 40); // Limitar a 40 caracteres
      }
    });

    if (nombreUsuario.value.trim().length > 40) {
      errorMessages.push(
        "El Nombre de Usuario no puede exceder los 40 caracteres."
      );
      valid = false;
    }

    // Validar correo electrónico
    const correoRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!correoRegex.test(correoElectronico.value.trim())) {
      errorMessages.push("Por favor, ingresa un correo electrónico válido.");
      valid = false;
    }

    // Validar número de identidad: máximo 13 dígitos
    if (!/^\d{1,13}$/.test(numIdentidad.value.trim())) {
      errorMessages.push(
        "El Número de Identidad debe tener máximo 13 dígitos."
      );
      valid = false;
    }

    // Validar dirección: máximo 50 caracteres
    if (direccion.value.trim().length > 50) {
      errorMessages.push("La Dirección no puede exceder los 50 caracteres.");
      valid = false;
    }

    // Validar Usuario: no espacios ni caracteres especiales no permitidos, pero permite @
    const usuarioRegex = /^[a-zA-Z0-9._@-]+$/;
    if (!usuarioRegex.test(usuarioInput.value.trim())) {
      errorMessages.push(
        "El Usuario solo puede contener letras, números, puntos, guiones, guion bajo y el símbolo '@'."
      );
      valid = false;
    }

    // Validar contraseñas solo si se proporciona
    if (contrasena.value.trim() || confirmarContrasena.value.trim()) {
      // Contraseña entre 8 y 20 caracteres
      if (
        contrasena.value.trim().length < 8 ||
        contrasena.value.trim().length > 20
      ) {
        errorMessages.push("La contraseña debe tener entre 8 y 20 caracteres.");
        valid = false;
      }
      // Contraseñas coincidan
      if (contrasena.value.trim() !== confirmarContrasena.value.trim()) {
        errorMessages.push("Las contraseñas no coinciden.");
        valid = false;
      }
    }

    // Mostrar errores si los hay
    if (!valid) {
      Swal.fire({
        icon: "error",
        title: "Errores en el formulario",
        html: errorMessages.map((msg) => `<div>${msg}</div>`).join(""),
        confirmButtonText: "OK",
      });
      return;
    }

    // Agregar información sobre eliminación de foto al formulario
    if (fotoEliminada) {
      const inputEliminarFoto = document.createElement("input");
      inputEliminarFoto.type = "hidden";
      inputEliminarFoto.name = "eliminarFoto";
      inputEliminarFoto.value = "1";
      form.appendChild(inputEliminarFoto);
    }

    // Mostrar éxito y enviar el formulario
    Swal.fire({
      icon: "success",
      title: "¡Éxito!",
      text: "Cambios guardados con éxito.",
      confirmButtonText: "Aceptar",
      customClass: {
        confirmButton: "btn btn-primary", // Clase personalizada para el botón
      },
      buttonsStyling: false, // Desactivar estilos por defecto de SweetAlert2
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Enviar el formulario
      }
    });
  });

  // Validar Usuario: eliminar espacios automáticamente
  usuarioInput.addEventListener("input", function (e) {
    this.value = this.value.replace(/\s/g, ""); // Eliminar espacios
    const regex = /^[a-zA-Z0-9._-]+$/; // Solo caracteres permitidos
    if (!regex.test(this.value)) {
      this.value = this.value.replace(/[^a-zA-Z0-9._-]/g, ""); // Eliminar caracteres no permitidos
    }
  });

  // Validar número de identidad: solo números, máximo 13 dígitos
  numIdentidad.addEventListener("input", function (e) {
    e.target.value = e.target.value.replace(/\D/g, "").substring(0, 13);
  });

  // Validar dirección: máximo 50 caracteres
  direccion.addEventListener("input", function (e) {
    if (e.target.value.length > 50) {
      e.target.value = e.target.value.substring(0, 50);
    }
  });

  // Mostrar/Ocultar contraseñas
  if (mostrarContrasena) {
    mostrarContrasena.addEventListener("change", function () {
      if (this.checked) {
        contrasena.type = "text";
        confirmarContrasena.type = "text";
      } else {
        contrasena.type = "password";
        confirmarContrasena.type = "password";
      }
    });
  }

  // Previsualizar nueva foto de perfil al seleccionar un archivo
  fotoPerfilInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        fotoPerfilPreview.src = e.target.result;
        fotoEliminada = false; // Si se sube una nueva foto, no se eliminará la actual
      };
      reader.readAsDataURL(file);
    }
  });

  // Cambiar a imagen predeterminada al eliminar la foto
  btnEliminarFoto.addEventListener("click", function () {
    Swal.fire({
      title: "¿Estás seguro?",
      html: `
                <p>La foto de perfil será eliminada al guardar los cambios.</p>
                <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
                    <button class="swal2-cancel btn btn-danger" style="width: 100px;">Cancelar</button>
                    <button class="swal2-confirm btn btn-primary" style="width: 100px;">Aceptar</button>
                </div>
            `,
      icon: "warning",
      showConfirmButton: false, // Desactivar botones automáticos de SweetAlert2
      showCancelButton: false,
      allowOutsideClick: false, // Evita cerrar el modal al hacer clic fuera
    });

    // Asignar eventos a los botones
    document.querySelector(".swal2-cancel").addEventListener("click", () => {
      Swal.close(); // Cierra el modal sin hacer cambios
    });

    document.querySelector(".swal2-confirm").addEventListener("click", () => {
      fotoPerfilPreview.src = "../../public/assets/img/avatars/avatar15.jpg"; // Mostrar imagen predeterminada
      fotoEliminada = true; // Indicar que la foto debe eliminarse
      Swal.fire({
        title: "Foto marcada para eliminación",
        text: "Recuerda guardar los cambios para aplicarlo.",
        icon: "success",
        confirmButtonText: "Aceptar",
        customClass: {
          confirmButton: "btn btn-primary", // Clase personalizada para el botón
        },
        buttonsStyling: false, // Desactivar estilos por defecto de SweetAlert2
      });
    });
  });

  // Hacer clic en el input cuando se presiona el botón "Subir Foto"
  btnSubirFoto.addEventListener("click", function () {
    fotoPerfilInput.click();
  });
});
