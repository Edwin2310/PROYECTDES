var OpAuthSignIn = function() {
    var initValidationSignIn = function(){
        jQuery('.js-validation-signin').validate({
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, element) {
                jQuery(element).parents('.form-group > div').append(error);
            },
            highlight: function(element) {
                jQuery(element).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(label) {
                label.closest('.form-group').removeClass('is-invalid');
                label.remove();
            },
            rules: {
                'correo': {
                    required: true,
                    email: true // Validando formato de email
                },
                'password': {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                'correo': {
                    required: 'Por favor ingresa una dirección de correo electrónico',
                    email: 'Por favor ingresa un correo electrónico válido'
                },
                'password': {
                    required: 'Por favor ingresa tu contraseña',
                    minlength: 'Tu contraseña debe tener al menos 5 caracteres'
                }
            }
        });
    };

    return {
        init: function () {
            initValidationSignIn();
        }
    };
}();

jQuery(function(){ OpAuthSignIn.init(); });
