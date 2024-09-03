<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="usuario_form">
                <div class="modal-body">
                    <input type="hidden" id="NUM_IDENTIDAD" name="NUM_IDENTIDAD">

                    <div class="form-group">
                        <label class="form-label" for="NUM_IDENTIDAD">Usuario</label>
                        <input type="text" class="form-control" id="NUM_IDENTIDAD" name="uNUM_IDENTIDAD" placeholder="Ingrese su Usuario" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="NOMBRE_USUARIO">Nombre Usuario</label>
                        <input type="text" class="form-control" id="NOMBRE_USUARIO" name="NOMBRE_USUARIO" placeholder="Ingrese su nombre de Usuario" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="CORREO_ELECTRONICO">Correo Electronico</label>
                        <input type="email" class="form-control" id="CORREO_ELECTRONICO" name="CORREO_ELECTRONICO" placeholder="test@test.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="CONTRASEÑA">Contraseña</label>
                        <input type="text" class="form-control" id="CONTRASEÑA" name="CONTRASEÑA" placeholder="************" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="ID_ROL">Rol</label>
                        <select class="select2" id="ID_ROL" name="ID_ROL">
                            <option value="1">Usuario</option>
                            <option value="2">Soporte</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="#" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>