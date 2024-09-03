$(document).ready(function () {
  $("#sendEmailButton").click(function () {
    // Recoge los datos del formulario
    var remitente = $("#remitente").val();
    var correo_remitente = $("#correo_remitente").val();

    // Envía los datos a send_email.php usando AJAX
    $.ajax({
      url: "http://localhost/PROYECTDES/view/NuevoIngresoSolicitud/email/correo_remitente.php",
      type: "POST",
      data: {
        remitente: remitente,
        correo_remitente: correo_remitente,
      },
      success: function (response) {
        // Muestra la respuesta del servidor (puede ser una notificación de éxito)
        alert(response);
      },
      error: function (xhr, status, error) {
        // Muestra un mensaje de error si la solicitud falla
        alert("Hubo un error al enviar el correo: " + error);
      },
    });
  });
});
