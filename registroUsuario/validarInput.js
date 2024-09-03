function validarInput(elemento) {
    // Prevenir espacios
    elemento.onkeypress = function(event) {
        if (event.which === 32) {
            return false;
        }
    };

    // Prevenir copiar y pegar
    elemento.onpaste = function(event) {
        event.preventDefault();
    };

    elemento.oncopy = function(event) {
        event.preventDefault();
    };

    // Prevenir caracteres <>
    elemento.oninput = function() {
        this.value = this.value.replace(/[<>]/g, '');
    };
}




