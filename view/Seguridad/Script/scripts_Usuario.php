<!-- SCRIPT PARA AGREGAR USUARIO -->
<script>
    document.getElementById('addUserForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        var form = event.currentTarget;
        var formData = new FormData(form);

        // Deshabilitar el botón de enviar para evitar múltiples envíos
        var submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        try {
            const response = await fetch('../Seguridad/Usuarios/Agregar_Usuario.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Usuario agregado exitosamente.',
                    icon: 'success',
                    timer: 1000, // Tiempo en milisegundos antes de que se cierre automáticamente
                    showConfirmButton: false // Ocultar el botón de confirmación
                }).then(() => {
                    window.location.reload();
                });
            } else {
                await Swal.fire({
                    title: 'Error',
                    text: data.message || 'Hubo un problema al agregar el usuario.',
                    icon: 'error'
                });
            }
        } catch (error) {
            console.error('Error al agregar el usuario:', error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al agregar el usuario.',
                icon: 'error'
            });
        } finally {
            // Habilitar el botón de enviar después de completar la operación
            submitButton.disabled = false;
        }
    });
</script>

<!-- Script JavaScript para actualizar la selección de Universidad -->
<script>
    document.getElementById('empleado_des_select').addEventListener('change', function() {
        const universidadSelect = document.getElementById('id_universidad');

        if (this.value === '1') {
            // Si selecciona "Sí", se selecciona automáticamente "UNAH" y se deshabilita el select
            universidadSelect.value = '1'; // Asegúrate de que este valor coincide con el de "UNAH"
            universidadSelect.setAttribute('disabled', 'disabled');
        } else {
            // Si selecciona "No", se habilita el select y se permite cambiar la selección
            universidadSelect.value = ''; // Opcional: se limpia la selección
            universidadSelect.removeAttribute('disabled');
        }
    });
</script>


<!-- SCRIPT PARA CREAR EDITAR USUARIO -->
<script>
    $('#editUserModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var num_identidad = button.data('num_identidad');
        var direccion_1 = button.data('direccion_1');
        var usuario = button.data('usuario');
        var correo_electronico = button.data('correo_electronico');
        var nombre_usuario = button.data('nombre_usuario');
        var num_empleado = button.data('num_empleado');
        var estado_usuario = button.data('estado_usuario');
        var id_rol = button.data('id_rol');
        var creado_por = button.data('creado_por');
        var id_universidad = button.data('id_universidad');

        var modal = $(this);
        modal.find('.modal-body #edit_id_usuario').val(id);
        modal.find('.modal-body #edit_num_identidad').val(num_identidad);
        modal.find('.modal-body #edit_direccion_1').val(direccion_1);
        modal.find('.modal-body #edit_usuario').val(usuario);
        modal.find('.modal-body #edit_correo_electronico').val(correo_electronico);
        modal.find('.modal-body #edit_nombre_usuario').val(nombre_usuario);
        modal.find('.modal-body #edit_num_empleado').val(num_empleado);
        modal.find('.modal-body #edit_estado_usuario').val(estado_usuario);
        modal.find('.modal-body #edit_id_rol').val(id_rol);
        modal.find('.modal-body #edit_creado_por').val(creado_por);
        modal.find('.modal-body #edit_universidad_nueva').val(id_universidad);
    });

    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        var modal = $(this);
        modal.find('.modal-footer #delete_id_usuario').val(id);
    });
</script>


<script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])) : ?>
            $('#agregarUsuarioModal').modal('show');
        <?php endif; ?>
    });
</script>



<script>
    document.getElementById('correo_electronico').addEventListener('blur', function() {
        const email = this.value;
        const xhr = new XMLHttpRequest();
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const emailField = document.getElementById('correo_electronico');
                if (response.exists) {
                    emailField.setCustomValidity('El correo electrónico ya está en uso.');
                    emailField.classList.add('is-invalid');
                } else {
                    emailField.setCustomValidity('');
                    emailField.classList.remove('is-invalid');
                }
            }
        };
        xhr.send('correo_electronico=' + encodeURIComponent(email));
    });

    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        this.classList.add('was-validated');
    }, false);
</script>

<?php
if (isset($_SESSION["IdUsuario"])) {
?>
    <!-- SCRIPT PARA VALIDACIONES EN FORMULARIO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener referencias a los campos del formulario
            const numIdentidad = document.getElementById('num_identidad');
            const direccion = document.getElementById('direccion_1');
            const usuario = document.getElementById('usuario');
            const correoElectronico = document.getElementById('correo_electronico');
            const nombreUsuario = document.getElementById('nombre_usuario');

            // Agregar evento de entrada para validar en tiempo real
            numIdentidad.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, ''); // Solo permite números
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13); // Limita a 13 caracteres
                }
            });

            direccion.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            usuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            correoElectronico.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >

            });

            nombreUsuario.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            // Bloquear copiar y pegar en todos los campos
            const campos = document.querySelectorAll('input[type="text"]');
            campos.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
            // Bloquear copiar y pegar en todos los campos
            const camposEmail = document.querySelectorAll('input[type="email"]');
            camposEmail.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
        });
    </script>

    <!-- Incluir SweetAlert2 CSS y JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Incluir SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addUserForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío del formulario para validar primero

                // Obtener los valores del formulario
                var numIdentidad = document.getElementById('num_identidad').value.trim();
                var usuario = document.getElementById('usuario').value.trim();

                // Verificar si el número de identidad tiene exactamente 13 caracteres
                if (numIdentidad.length !== 13) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El número de identidad debe tener 13 dígitos.',
                    });
                    return; // Detener la ejecución del script
                }

                // Verificar disponibilidad del número de identidad y del usuario en el servidor
                var xhr = new XMLHttpRequest();
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.num_identidad_existe) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El número de identidad ya está en uso.',
                            });
                        } else if (response.usuario_existe) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El nombre de usuario ya está en uso.',
                            });
                        } else {
                            // Si todo está bien, enviar el formulario
                            document.getElementById('addUserForm').submit();
                        }
                    }
                };

                xhr.send('num_identidad=' + encodeURIComponent(numIdentidad) + '&usuario=' + encodeURIComponent(usuario));
            });
        });
    </script>

    <script>
        document.getElementById('num_identidad').addEventListener('input', function() {
            var numIdentidad = this.value.trim();
            var countZeros = (numIdentidad.match(/0/g) || []).length;

            if (countZeros > 7) {
                // Si hay más de 7 ceros, muestra un mensaje de error o realiza alguna acción
                alert('El número de identidad no puede contener más de 7 ceros.');
                // Opcionalmente, puedes limitar la cantidad de ceros permitidos
                this.value = numIdentidad.replace(/0/g, '').slice(0, numIdentidad.length - (countZeros - 7)) + '0000000'.slice(0, Math.max(0, 7 - (countZeros - 7)));
            }
        });
    </script>

    <!-- SCRIPT PARA VALIDACIONES EN FORMULARIO EN MODAL EDITAR -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener referencias a los campos del formulario
            const numIdentidad = document.getElementById('edit_num_identidad');
            const direccion = document.getElementById('edit_direccion_1');
            const usuario = document.getElementById('edit_usuario');
            const correoElectronico = document.getElementById('edit_correo_electronico');
            const nombreUsuario = document.getElementById('edit_nombre_usuario');

            // Agregar evento de entrada para validar en tiempo real
            numIdentidad.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, ''); // Solo permite números
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13); // Limita a 13 caracteres
                }
            });

            direccion.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            usuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            correoElectronico.addEventListener('input', function() {
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >

            });

            nombreUsuario.addEventListener('input', function() {
                this.value = this.value.replace(/\s/g, ''); // Elimina espacios en blanco
                this.value = this.value.replace(/[<>]/g, ''); // No permite < ni >
            });

            // Bloquear copiar y pegar en todos los campos
            const campos = document.querySelectorAll('input[type="text"]');
            campos.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });
            // Bloquear copiar y pegar en todos los campos
            const camposEmail = document.querySelectorAll('input[type="email"]');
            camposEmail.forEach(function(campo) {
                campo.addEventListener('paste', function(event) {
                    event.preventDefault();
                });
            });


        });
    </script>

    <!-- SCRIPT PARA EL BOTON DE MOSTRAR MAS REGISTROS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const showMoreBtn = document.getElementById("showMoreBtn");
            const hiddenColumns = document.querySelectorAll(".hidden-column");

            showMoreBtn.addEventListener("click", function() {
                const isShowingMore = showMoreBtn.textContent === "Mostrar más";
                hiddenColumns.forEach(function(column) {
                    column.style.display = isShowingMore ? "table-cell" : "none";
                });
                showMoreBtn.textContent = isShowingMore ? "Mostrar menos" : "Mostrar más";
            });
        });
    </script>


<?php
} else {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}
?>