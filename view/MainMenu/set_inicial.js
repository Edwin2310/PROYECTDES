function roles(id) {
    //alert(id)
    if (id == 1) {//Administrador 
        $('.menu-item has-sub ').each(function () {
            $(this).removeAttr("style");
        });
        $("#asistencia_voluntario").removeAttr("style");
        $("#validar_pres_autores").removeAttr("style");
        $("#actividades_voluntario").removeAttr("style");
        $("#mensajes_voluntario").removeAttr("style");
        $("#menu_voluntarios").removeAttr("style");
        $("#pagos_voluntarios").removeAttr("style");
        $("#menu_groles").removeAttr("style");
        $("#congresos_activos").removeAttr("style");
        $("#gestionar_linea_investigacion").removeAttr("style");
        $("#gestionar_tematicas").removeAttr("style");
        $("#crear_congreso").removeAttr("style");
        $("#asociar_administrador_congreso").removeAttr("style");
        $("#c_certificados").removeAttr("style");
        $("#generar_certificados").removeAttr("style");
        
            /* AGREGADOS */
        $("#menu_gasistente").removeAttr("style");
        $("#menu_congresos").removeAttr("style");
        $("#menu_gtrabajos").removeAttr("style");
        $("#menu_gcertificados").removeAttr("style");
        $("#menu_grevisor").removeAttr("style");
        $("#menu_edicion").removeAttr("style");
        $("#submenu_menu_geditor_p_seccion").removeAttr("style");
        $("#menu_geditor_p_seccion").removeAttr("style");
        $("#submenu_menu_geditor_s_seccion").removeAttr("style");
        $("#geditor_s_seccion").removeAttr("style"); 
        $("#submenu_menu_geditor_principal").removeAttr("style");
        $("#menu_geditor_principal").removeAttr("style");
        $("#menu_gprograma").removeAttr("style");
        $("#menu_gvoluntarios").removeAttr("style");
        $("#menu_geditor_gestor").removeAttr("style");


        $("#menu_traducciones").attr("style", "display:none");

    }
}
