/* // Función para verificar permisos
async function verificarPermisos(idObjeto) {
  if (!idRol) {
    console.log("No se ha encontrado información de usuario.");
    alert("No se ha encontrado información de usuario.");
    return false;
  }

  try {
    const response = await fetch("../Seguridad/Permisos/accesos.php", {
      // URL del script PHP
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ idRol, idObjeto }),
    });

    const result = await response.json();

    if (result.tienePermiso) {
      return true;
    } else {

      console.log("Permiso denegado, redirigiendo...");
      window.location.href = "../Seguridad/Permisos/denegado.php"; // Redirigir a la página de acceso denegado
      return false;
    }
  } catch (error) {
    console.error("Error al verificar permisos:", error);
    window.location.href = "../Seguridad/Permisos/denegado.php"; // Redirigir a la página de acceso denegado en caso de error
    return false;
  }
}

// Agrega un evento de clic a cada enlace de módulo
document.querySelectorAll(".modulo-link").forEach((link) => {
  link.addEventListener("click", async (event) => {
    event.preventDefault(); // Evita la redirección por defecto

    const idObjeto = link.getAttribute("data-id-objeto");
    console.log("ID Objeto:", idObjeto);

    // Verifica permisos antes de redirigir
    const tienePermiso = await verificarPermisos(idObjeto);

    if (tienePermiso) {
      window.location.href = link.href; // Solo redirige si tiene permisos
    }else {
      console.log("Permiso no otorgado.");
      window.location.href = "../Seguridad/Permisos/denegado.php"; // Redirigir a la página de acceso denegado
    }
  });
});
 */

async function verificarPermisos(idObjeto) {
  if (!idRol) {
    console.log("No se ha encontrado información de usuario.");
    alert("No se ha encontrado información de usuario.");
    return false;
  }

  try {
    const data = { idRol, idObjeto };
    console.log("Datos enviados:", data); // Verifica los datos enviados

    const response = await fetch("../Seguridad/Permisos/accesos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    console.log("Respuesta del servidor:", result); // Verifica la respuesta del servidor

    if (result.tienePermiso) {
      return true;
    } else {
      console.log("Permiso denegado, redirigiendo...");
      window.location.href = "../Seguridad/Permisos/denegado.php";
      return false;
    }
  } catch (error) {
    console.error("Error al verificar permisos:", error);
    alert("Ocurrió un error al verificar los permisos.");
    window.location.href = "../Seguridad/Permisos/denegado.php";
    return false;
  }
}
