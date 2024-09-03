// Verificar el navegador del lado del cliente
(function() {
    var userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.indexOf('firefox') !== -1) {
        // Redirigir a la página de error si el navegador es Firefox
        window.location.href = 'pagina_no_soportada.html';
    }
})();
