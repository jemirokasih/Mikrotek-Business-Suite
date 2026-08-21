<!DOCTYPE html>
<html class="no-js" lang="<?php echo trans('cldr'); ?>">
<head>
    <title><?php echo get_setting('custom_title', 'Mikrotek Business Suite', true); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/core/img/favicon.png'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/core/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/core/css/custom.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/core/css/modern_sidebar.css'); ?>">
    
    <script src="<?php echo base_url('assets/core/js/dependencies.js'); ?>"></script>
</head>
<body class="layout-sidebar-active">

    <!-- Sidebar Menu Component -->
    <?php echo $this->layout->load_view('layout/includes/sidebar_menu'); ?>

    <!-- Main Topbar Header -->
    <header id="main-topbar">
        <div class="topbar-left" style="display: flex; align-items: center;">
            <button type="button" class="btn-toggle-sidebar" id="btn-toggle-sidebar" title="Toggle Sidebar">
                <i class="fa fa-bars"></i>
            </button>
            <span style="font-weight: 600; font-size: 16px; margin-left: 15px; color: #1e293b;" class="hidden-xs">
                <?php echo get_setting('custom_title', 'Mikrotek Business Suite', true); ?>
            </span>
        </div>

        <div class="topbar-right" style="display: flex; align-items: center; gap: 15px;">
            <!-- Quick Layout Switcher Button -->
            <button type="button" class="btn btn-sm btn-default btn-switch-layout-quick" title="Switch to Top Navbar Layout">
                <i class="fa fa-exchange text-primary"></i> <span class="hidden-xs">Classic Top Layout</span>
            </button>

            <!-- User Account Dropdown -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex; align-items: center; text-decoration: none; color: #334155;">
                    <i class="fa fa-user-circle-o fa-2x text-primary" style="margin-right: 8px;"></i>
                    <span style="font-weight: 600;" class="hidden-xs"><?php echo html_escape($this->session->userdata('user_name')); ?></span>
                    <i class="fa fa-caret-down" style="margin-left: 5px;"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header"><?php echo html_escape($this->session->userdata('user_email')); ?></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('users/form/' . $this->session->userdata('user_id')); ?>"><i class="fa fa-user fa-fw"></i> <?php _trans('edit_profile'); ?></a></li>
                    <?php if (has_permission('settings')) : ?>
                        <li><a href="<?php echo site_url('settings'); ?>"><i class="fa fa-cogs fa-fw"></i> <?php _trans('settings'); ?></a></li>
                    <?php endif; ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('sessions/logout'); ?>" class="text-danger"><i class="fa fa-power-off fa-fw"></i> <?php _trans('logout'); ?></a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Page Content Wrapper -->
    <div id="page-content-wrapper">
        <?php echo $content; ?>
    </div>

    <div id="modal-placeholder"></div>

    <script>
    $(function () {
        // Toggle Sidebar Collapsed State
        $('#btn-toggle-sidebar').click(function (e) {
            e.preventDefault();
            if ($(window).width() <= 768) {
                $('body').toggleClass('mobile-sidebar-open');
            } else {
                $('body').toggleClass('sidebar-collapsed');
                Cookies.set('sidebar_collapsed', $('body').hasClass('sidebar-collapsed') ? '1' : '0', { expires: 365 });
            }
        });

        // Restore Collapsed State from Cookie
        if (Cookies.get('sidebar_collapsed') === '1' && $(window).width() > 768) {
            $('body').addClass('sidebar-collapsed');
        }

        // Submenu Accordion Toggle
        $('.sidebar-nav-link.toggle-submenu').click(function (e) {
            e.preventDefault();
            var $item = $(this).parent('.sidebar-nav-item');
            $item.toggleClass('open');
        });

        // Quick Switch Layout Handler
        $('.btn-switch-layout-quick').click(function (e) {
            e.preventDefault();
            $.post("<?php echo site_url('settings/ajax/switch_layout'); ?>", {
                layout_mode: 'top',
                _csrf: Cookies.get('ip_csrf_cookie')
            }, function (response) {
                window.location.reload();
            });
        });
    });
    </script>
</body>
</html>
