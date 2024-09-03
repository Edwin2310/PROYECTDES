Dropzone.autoDiscover = false; // Desactivar el auto-descubrimiento para personalizar

// Crear un nuevo Dropzone
var dropzoneDocuments = new Dropzone("#dropzone-documents", {
  // url: "http://localhost/PROYECTDES/view/NuevoIngresoSolicitud/upload.php", // Asegúrate de que esta ruta sea correcta
  url: "./upload.php", // Asegúrate de que esta ruta sea correcta
  paramName: "file", // Nombre del archivo enviado al servidor
  maxFilesize: 10, // Tamaño máximo del archivo en MB
  uploadMultiple: true, // Permitir múltiples archivos
  parallelUploads: 10, // Número de subidas simultáneas
  addRemoveLinks: true, // Permitir eliminar archivos de la lista de carga
  dictRemoveFile: "Eliminar archivo", // Texto del enlace para eliminar
  dictDefaultMessage:
    "Arrastra los archivos aquí para subirlos o haz clic para seleccionarlos", // Texto para el área de Dropzone
  acceptedFiles: ".pdf,.doc,.docx,.xls", // Tipos de archivo permitidos
  autoProcessQueue: false, // Desactivar la subida automática
  init: function () {
    var totalFiles = 0; // Total de archivos a subir
    var uploadedFiles = 0; // Archivos subidos exitosamente

    this.on("sending", function (file, xhr, formData) {
      // Añadir campos adicionales al enviar
      formData.append(
        "id_usuario",
        document.querySelector("#id_usuario").value
      );
      totalFiles++; // Incrementar el contador de archivos al comenzar la subida
    });

    this.on("addedfile", function (file) {
      var fileName = file.name;

      // Expresiones regulares para cada tipo de documento sin validación de fecha
      var regexList = [
        /^DIAG_/, // Diagnóstico
        /^PLAN_/, // Plan de Estudios
        /^PDOC_/, // Planta Docente
        /^SOLI_/, // Solicitud
      ];

      var valid = regexList.some(function (regex) {
        return regex.test(fileName);
      });

      if (!valid) {
        this.removeFile(file);
        alert(
          "El nombre del archivo debe comenzar con DIAG_, PLAN_, PDOC_ o SOLI_."
        );
      }
    });

    this.on("success", function (file, response) {
      console.log("Archivo subido con éxito:", response);
      uploadedFiles++; // Incrementar el contador de archivos subidos exitosamente
      if (uploadedFiles === totalFiles) {
        // Todos los archivos se han subido
        Swal.fire({
          icon: "success",
          title: "¡Solicitud Finalizada!",
          text: "Archivos Subidos Exitosamente",
          timer: 2000, // 2 segundos
          timerProgressBar: true,
          showConfirmButton: false, // Oculta el botón de confirmación
        }).then(() => {
          // Redirige a la URL después de que se cierra la alerta
          window.location.href =
            //"http://localhost/PROYECTDES/view/ConsultarSolicitudes/index.php";
            "../ConsultarSolicitudes/index.php";
        });
      }
    });

    this.on("error", function (file, response) {
      console.log("Error al subir el archivo:", response);
    });
  },
});

// Inicializa el wizard y botones

document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("wizard-form");
  var btnNext = document.getElementById("btn-next");
  var btnBack = document.getElementById("btn-back"); // Botón "Atrás"
  var btnSubmit = document.getElementById("btn-submit");

  btnNext.addEventListener("click", function () {
    var activeTab = document.querySelector(".tab-pane.active");
    var stepIndex = Array.prototype.indexOf.call(
      activeTab.parentElement.children,
      activeTab
    );

    if (validateStep(stepIndex)) {
      if (stepIndex === 0) {
        // Paso 1 - Enviar datos a save_combined.php
        var data = new FormData();
        var step1Inputs = document.querySelectorAll(
          "#wizard-simple-step1 input, #wizard-simple-step1 select"
        );
        step1Inputs.forEach(function (input) {
          data.append(input.name, input.value);
        });
        data.append("step", "step1");

        fetch("save_combined.php", {
          method: "POST",
          body: data,
        })
          .then((response) => response.text())
          .then((result) => {
            if (result === "step1_success") {
              // Mover al siguiente paso (step 2)
              switchTab("#wizard-simple-step2");
            } else {
              // Manejar error
              alert("Error al guardar datos del paso 1: " + result);
            }
          })
          .catch((error) => console.error("Error:", error));
      } else if (stepIndex === 1) {
        // Paso 2 - Enviar datos a save_combined.php
        var data = new FormData();
        var step2Inputs = document.querySelectorAll(
          "#wizard-simple-step2 input, #wizard-simple-step2 select, #wizard-simple-step2 textarea"
        );
        step2Inputs.forEach(function (input) {
          data.append(input.name, input.value);
        });
        data.append("step", "step2");

        fetch("save_combined.php", {
          method: "POST",
          body: data,
        })
          .then((response) => response.text())
          .then((result) => {
            if (result === "step2_success" || result === "success") {
              // Mover al siguiente paso (step 3)
              switchTab("#wizard-simple-step3");
            } else {
              // Manejar error
              alert("Error al guardar datos del paso 2: " + result);
            }
          })
          .catch((error) => console.error("Error:", error));
      } else if (stepIndex === 2) {
        // Paso 3 - Enviar archivos adjuntos
        if (validateStep(stepIndex)) {
          uploadFiles(); // Procesar la cola de archivos
        } else {
          Swal.fire({
            icon: "error",
            title: "Campos Vacíos",
            text: "Por favor, completa todos los campos obligatorios antes de continuar.",
          });
        }
      }
    } else {
      Swal.fire({
        icon: "error",
        title: "Campos Vacíos",
        text: "Por favor, completa todos los campos obligatorios antes de continuar.",
      });
    }
  });

  btnBack.addEventListener("click", function () {
    var activeTab = document.querySelector(".tab-pane.active");
    var stepIndex = Array.prototype.indexOf.call(
      activeTab.parentElement.children,
      activeTab
    );

    if (stepIndex > 0) {
      var prevTabId = `#wizard-simple-step${stepIndex}`;
      switchTab(prevTabId);
    }
  });

  function switchTab(targetTabId) {
    var currentActiveTab = document.querySelector(".nav-link.active");
    if (currentActiveTab) {
      currentActiveTab.classList.remove("active");
    }

    var targetTab = document.querySelector(`a[href="${targetTabId}"]`);
    if (targetTab) {
      targetTab.classList.add("active");
      $(targetTab).tab("show");
    }

    var currentActivePane = document.querySelector(".tab-pane.active");
    if (currentActivePane) {
      currentActivePane.classList.remove("active");
      currentActivePane.classList.remove("show");
    }

    var targetPane = document.querySelector(targetTabId);
    if (targetPane) {
      targetPane.classList.add("active");
      targetPane.classList.add("show");
    }

    // Mostrar u ocultar el botón "Atrás" según el paso
    var stepIndex = Array.prototype.indexOf.call(
      targetPane.parentElement.children,
      targetPane
    );

    if (stepIndex === 1) {
      btnBack.style.display = "none"; // Ocultar botón "Atrás" en el paso 2
    } else {
      btnBack.style.display = "inline-block"; // Mostrar botón "Atrás" en otros pasos
    }

    // Mostrar u ocultar el botón "Guardar Adjuntos" según el paso
    if (stepIndex === 2) {
      btnSubmit.classList.remove("d-none");
    } else {
      btnSubmit.classList.add("d-none");
    }
  }

  function validateStep(stepIndex) {
    var isValid = true;

    if (stepIndex === 0) {
      var step1Inputs = document.querySelectorAll(
        "#wizard-simple-step1 input, #wizard-simple-step1 select"
      );
      step1Inputs.forEach(function (input) {
        if (input.required && !input.value.trim()) {
          isValid = false;
        }
      });
    } else if (stepIndex === 1) {
      var step2Inputs = document.querySelectorAll(
        "#wizard-simple-step2 input, #wizard-simple-step2 select, #wizard-simple-step2 textarea"
      );
      step2Inputs.forEach(function (input) {
        if (input.required && !input.value.trim()) {
          isValid = false;
        }
      });
    }

    return isValid;
  }
});

function uploadFiles() {
  dropzoneDocuments.processQueue(); // Procesar la cola de archivos
}

/* SCRIPT PARA IMPEDIR QUE SE MUEVAN LAS HREF  */

document.addEventListener("DOMContentLoaded", function () {
  const step1Tab = document.getElementById("step1-tab");
  const step2Tab = document.getElementById("step2-tab");
  const step3Tab = document.getElementById("step3-tab");

  // Deshabilitar movimiento entre pestañas al iniciar
  disableTab(step1Tab);
  disableTab(step2Tab);
  disableTab(step3Tab);

  // Habilitar solo la pestaña actual y deshabilitar todas las anteriores
  function disableTab(tabElement) {
    tabElement.classList.add("disabled");
    tabElement.addEventListener("click", function (event) {
      event.preventDefault(); // Deshabilitar el clic
    });
  }

  function enableTab(currentTab, nextTab) {
    currentTab.classList.remove("disabled");
    currentTab.addEventListener("click", function (event) {
      event.preventDefault(); // Deshabilitar movimiento hacia atrás
      if (!currentTab.classList.contains("disabled")) {
        const targetTab = currentTab.getAttribute("href");
        $('.nav-tabs a[href="' + targetTab + '"]').tab("show");
      }
    });
    if (nextTab) {
      nextTab.classList.remove("disabled");
    }
  }

  // Avanzar a paso 2 y deshabilitar paso 1
  document
    .querySelector("#wizard-simple-step1")
    .addEventListener("shown.bs.tab", function () {
      enableTab(step1Tab, step2Tab);
      disableTab(step1Tab); // Deshabilitar la pestaña 1 al avanzar a 2
    });

  // Avanzar a paso 3 y deshabilitar paso 2
  document
    .querySelector("#wizard-simple-step2")
    .addEventListener("shown.bs.tab", function () {
      enableTab(step2Tab, step3Tab);
      disableTab(step1Tab); // Deshabilitar paso 1
      disableTab(step2Tab); // Deshabilitar paso 2 al avanzar a 3
    });

  // Al estar en el paso 3, no permitir retroceso
  document
    .querySelector("#wizard-simple-step3")
    .addEventListener("shown.bs.tab", function () {
      disableTab(step1Tab);
      disableTab(step2Tab);
      disableTab(step3Tab); // Asegurarse que todos los tabs anteriores están deshabilitados
    });
});
