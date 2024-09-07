<!-- <?php
// Iniciar la sesión
session_start();
?> -->

<header id="page-header">
    <div class="content-header">
        <div class="content-header-section">
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-navicon"></i>
            </button>
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
                <i class="fa fa-search"></i>
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-circle btn-dual-secondary" id="page-header-options-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-wrench"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="page-header-options-dropdown">
                    <h6 class="dropdown-header">Encabezamiento</h6>
                    <button type="button" class="btn btn-sm btn-block btn-alt-secondary" data-toggle="layout" data-action="header_fixed_toggle">Modo fijo</button>
                    <button type="button" class="btn btn-sm btn-block btn-alt-secondary d-none d-lg-block mb-10" data-toggle="layout" data-action="header_style_classic">Estilo clásico</button>
                    <div class="d-none d-xl-block">
                        <h6 class="dropdown-header">Contenido principal</h6>
                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="content_layout_toggle">Alternar diseño</button>
                    </div>
                    <div class="dropdown-divider"></div>
                </div>
            </div>
            <div class="btn-group" role="group">
                <div class="dropdown-menu min-width-150" aria-labelledby="page-header-themes-dropdown">
                    <h6 class="dropdown-header text-center">Color Themes</h6>
                    <div class="row no-gutters text-center mb-5">
                        <div class="col-4 mb-5">
                            <a class="text-default" data-toggle="theme" data-theme="default" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-4 mb-5">
                            <a class="text-elegance" data-toggle="theme" data-theme="assets/css/themes/elegance.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-4 mb-5">
                            <a class="text-pulse" data-toggle="theme" data-theme="assets/css/themes/pulse.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-4 mb-5">
                            <a class="text-flat" data-toggle="theme" data-theme="assets/css/themes/flat.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-4 mb-5">
                            <a class="text-corporate" data-toggle="theme" data-theme="assets/css/themes/corporate.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-4 mb-5">
                            <a class="text-earth" data-toggle="theme" data-theme="assets/css/themes/earth.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="sidebar_style_inverse_toggle">Sidebar Style</button>
                    <div class="dropdown-divider"></div>
                </div>
            </div>
        </div>
        <div class="content-header-section">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="badge badge-primary">
                        <span class="fa fa-bell-o" class="fa fa-angle-down"> 3</span>
                        <span class="fa fa-angle-down"></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="be_pages_generic_profile.html">
                        <i class="fa fa-file-o mr-5"></i> Su solicitud requiere subsanacion
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
                        <span><i class="fa fa-users mr-5"></i> Su solicitud fue agendada al CES</span>
                    </a>
                </div>
            </div>
            <input type="hidden" id="user_idx" value="<?php echo isset($_SESSION["IdUsuario"]) ? $_SESSION["IdUsuario"] : ''; ?>"> <!-- ID del Usuario -->
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- llamada del usuario   -->
                    <span><?php echo isset($_SESSION["NombreUsuario"]) ? $_SESSION["NombreUsuario"] : 'Usuario'; ?> </span>
                    <i class="fa fa-angle-down ml-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="be_pages_generic_profile.html">
                        <i class="si si-user mr-5"></i> Perfil
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
                        <span><i class="fa fa-bell-o mr-5"></i> Notificaciones</span>
                        <span class="badge badge-primary"> 3</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                        <i class="si si-wrench mr-5"></i> Configuracion
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../Logout/logout.php">
                        <i class="si si-logout mr-5"></i> Cerrar Sesion
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="page-header-search" class="overlay-header">
        <div class="content-header content-header-fullrow">
            <form action="be_pages_generic_search.html" method="post">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                    <input type="text" class="form-control" placeholder="Busca o presiona ESC.." id="page-header-search-input" name="page-header-search-input">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
</header>
