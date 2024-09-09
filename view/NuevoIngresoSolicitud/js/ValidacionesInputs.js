function validateForm() {
  const nombreField = document.getElementById("remitente");
  const correoField = document.getElementById("correo_remitente");
  const descripcionField = document.getElementById("Descripcion_solicitud");

  const nombre = nombreField.value.trim();
  const correo = correoField.value.trim();
  const descripcion = descripcionField.value.trim();

  // Expresión regular para validar el correo electrónico
  const correoRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const nombreRegex = /^[a-zA-Z\s]+$/; // Solo permite letras y espacios

  // Validación de campos
  if (nombre === "") {
    alert("El nombre es obligatorio.");
    nombreField.focus();
    return false;
  }

  if (!nombreRegex.test(nombre)) {
    alert("El nombre solo puede contener letras y espacios.");
    nombreField.focus();
    return false;
  }

  if (!correoRegex.test(correo)) {
    alert("Por favor, ingresa un correo electrónico válido.");
    correoField.focus();
    return false;
  }

  if (descripcion === "") {
    alert("La descripción es obligatoria.");
    descripcionField.focus();
    return false;
  }

  return true;
}

// Deshabilitar copiar, pegar, y combinaciones de teclas en campos de entrada
function disableCopyPaste(element) {
  element.addEventListener("paste", (e) => e.preventDefault());
  element.addEventListener("copy", (e) => e.preventDefault());
  element.addEventListener("cut", (e) => e.preventDefault());
}

// Deshabilitar copiar, pegar, combinaciones de teclas, y eliminar caracteres no permitidos
function disableKeyboardShortcuts(e) {
  // Deshabilitar Ctrl+C, Ctrl+V, Ctrl+X
  if (
    (e.ctrlKey || e.metaKey) &&
    (e.key === "c" || e.key === "v" || e.key === "x")
  ) {
    e.preventDefault();
  }
  // Deshabilitar pegado de contenido
  if (e.key === "v" && (e.ctrlKey || e.metaKey)) {
    e.preventDefault();
  }
}

// Función para eliminar caracteres no permitidos en tiempo real
function removeInvalidCharacters(e) {
  // Remover caracteres '<' y '>'
  e.target.value = e.target.value.replace(/[<>]/g, "");
}

// Aplicar restricciones a los campos de entrada
document.querySelectorAll("input, textarea").forEach((element) => {
  disableCopyPaste(element);
  element.addEventListener("keydown", disableKeyboardShortcuts);
  element.addEventListener("input", removeInvalidCharacters);
});

// Deshabilitar copiar y pegar para todos los campos de entrada en la página
document.querySelectorAll("input, textarea").forEach((element) => {
  element.addEventListener("paste", (e) => e.preventDefault());
  element.addEventListener("copy", (e) => e.preventDefault());
});

function validateInput(event) {
  const input = event.target;
  const nombreRegex = /^[a-zA-Z\s.]*$/;

  if (!nombreRegex.test(input.value)) {
    input.value = input.value.replace(/[^a-zA-Z\s.]/g, "");
  }
}

function validateEmailInput(event) {
  const input = event.target;
  const forbiddenCharsRegex = /[<>\/]/;

  if (forbiddenCharsRegex.test(input.value)) {
    input.value = input.value.replace(/[<>\/]/g, "");
  }
}

function validateTextarea(event) {
  const textarea = event.target;
  const forbiddenCharsRegex = /[<>\/]/;

  if (forbiddenCharsRegex.test(textarea.value)) {
    textarea.value = textarea.value.replace(/[<>\/]/g, "");
  }
}

// Sanitiza la entrada del usuario para evitar inyección de SQL
function sanitizeInput(input) {
  // Evitar inyección de SQL
  return input
    .replace(/[\0\x08\x09\x1a\n\r"'\\%]/g, "\\$&")
    .replace(/--|\/\*|\*\/|;/g, ""); // Prevencion de inyeccion sql
}

// Transforma minus en mayus
function validateUppercaseInput(event) {
  const input = event.target;
  input.value = sanitizeInput(input.value).toUpperCase(); // Sanitiza y convierte a mayus
}

// Solo numeros en el campo de numero de referencia
function validateNumericInput(event) {
  const input = event.target;
  input.value = sanitizeInput(input.value).replace(/[^0-9]/g, ""); // Sanitiza el campo y solo permite numeros
}

// Listeners para validar en tiempo real
document
  .getElementById("Descripcion_solicitud")
  .addEventListener("input", validateUppercaseInput);
document
  .getElementById("id_carrera")
  .addEventListener("input", validateUppercaseInput);
document
  .getElementById("Num_referencia")
  .addEventListener("input", validateNumericInput);
