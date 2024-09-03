$(document).on("click", "#btnactualizar", function () {
    var pass = $("txtpass").val();
    var newpass = $("txtnewpass").val();

    if (pass.length == 0 || newpass.length == 0) {
        swal("!Error", "Campos vacios", "error");
    } else {
        if (pass == newpass) {
            var NUM_IDENTIDAD = $('#usu_idx').val();
            $.post("../../controllers/usuario.php?op=password", { NUM_IDENTIDAD: NUM_IDENTIDAD, CONTRASEÑA: newpass }, function (data){
                swal("Correcto","Actualizado Correctamente","success");
            })
        } else {
            swal("!Error", "Las contraseñas no coinciden", "error");
        }
    }

})