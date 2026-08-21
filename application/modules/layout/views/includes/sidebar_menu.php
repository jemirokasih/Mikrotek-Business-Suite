<div id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img src="<?php echo base_url('assets/core/img/favicon.png'); ?>" alt="Logo">
        <span>Mikrotek Suite</span>
    </div>

    <div class="sidebar-menu-body">
        <ul class="sidebar-nav">
            <!-- Dashboard -->
            <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo site_url('dashboard'); ?>" class="sidebar-nav-link">
                    <i class="fa fa-dashboard nav-icon"></i>
                    <span class="nav-text"><?php _trans('dashboard'); ?></span>
                </a>
            </li>

            <!-- Sales & Billing Group -->
            <?php if (has_permission('invoices') || has_permission('quotes') || has_permission('payments') || has_permission('receipts')) : ?>
                <li class="sidebar-group-title">Sales &amp; Billing</li>
                
                <?php if (has_permission('invoices')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['invoices', 'recurring']) ? 'open active' : ''; ?>">
                        <a href="#" class="sidebar-nav-link toggle-submenu">
                            <i class="fa fa-file-text-o nav-icon"></i>
                            <span class="nav-text"><?php _trans('invoices'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('invoices/index'); ?>" class="sidebar-nav-link"><?php _trans('view_invoices'); ?></a></li>
                            <?php if (has_permission('invoices', 'create')) : ?>
                                <li><a href="javascript:void(0)" class="sidebar-nav-link create-invoice"><?php _trans('create_invoice'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo site_url('invoices/recurring/index'); ?>" class="sidebar-nav-link"><?php _trans('recurring_invoices'); ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('quotes')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'quotes') ? 'open active' : ''; ?>">
                        <a href="#" class="sidebar-nav-link toggle-submenu">
                            <i class="fa fa-file-o nav-icon"></i>
                            <span class="nav-text"><?php _trans('quotes'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('quotes/index'); ?>" class="sidebar-nav-link"><?php _trans('view_quotes'); ?></a></li>
                            <?php if (has_permission('quotes', 'create')) : ?>
                                <li><a href="javascript:void(0)" class="sidebar-nav-link create-quote"><?php _trans('create_quote'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('receipts')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'receipts') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('receipts/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-id-card-o nav-icon"></i>
                            <span class="nav-text"><?php _trans('receipts'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('payments')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'payments') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('payments/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-credit-card nav-icon"></i>
                            <span class="nav-text"><?php _trans('payments'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Clients & Directory Group -->
            <?php if (has_permission('clients') || has_permission('bank_accounts')) : ?>
                <li class="sidebar-group-title">Clients &amp; Accounts</li>

                <?php if (has_permission('clients')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'clients') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('clients/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-users nav-icon"></i>
                            <span class="nav-text"><?php _trans('clients'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('bank_accounts')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'bank_accounts') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('bank_accounts/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-university nav-icon"></i>
                            <span class="nav-text">Rekening Bank</span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Products & Projects Group -->
            <?php if (has_permission('products') || (get_setting('projects_enabled') == 1 && (has_permission('projects') || has_permission('tasks')))) : ?>
                <li class="sidebar-group-title">Products &amp; Projects</li>

                <?php if (has_permission('products')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'products') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('products/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-cube nav-icon"></i>
                            <span class="nav-text"><?php _trans('products'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (get_setting('projects_enabled') == 1) : ?>
                    <?php if (has_permission('projects')) : ?>
                        <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'projects') ? 'active' : ''; ?>">
                            <a href="<?php echo site_url('projects/index'); ?>" class="sidebar-nav-link">
                                <i class="fa fa-list-alt nav-icon"></i>
                                <span class="nav-text"><?php _trans('projects'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (has_permission('tasks')) : ?>
                        <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'tasks') ? 'active' : ''; ?>">
                            <a href="<?php echo site_url('tasks/index'); ?>" class="sidebar-nav-link">
                                <i class="fa fa-check-square-o nav-icon"></i>
                                <span class="nav-text"><?php _trans('tasks'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- HR & Employee Group -->
            <li class="sidebar-group-title">Human Resources</li>

            <?php if (has_permission('employees')) : ?>
                <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'employees') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('employees/index'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-id-badge nav-icon"></i>
                        <span class="nav-text"><?php _trans('employees'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['attendance', 'leaves']) ? 'open active' : ''; ?>">
                <a href="#" class="sidebar-nav-link toggle-submenu">
                    <i class="fa fa-clock-o nav-icon"></i>
                    <span class="nav-text"><?php _trans('attendance'); ?> &amp; <?php _trans('leave'); ?></span>
                    <i class="fa fa-chevron-right arrow-icon"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?php echo site_url('attendance/clock'); ?>" class="sidebar-nav-link"><?php _trans('attendance_portal'); ?></a></li>
                    <li><a href="<?php echo site_url('leaves/my_leaves'); ?>" class="sidebar-nav-link"><?php _trans('my_leave_requests'); ?></a></li>
                    <?php if (has_permission('attendance')) : ?>
                        <li><a href="<?php echo site_url('attendance/index'); ?>" class="sidebar-nav-link"><?php _trans('daily_attendance'); ?></a></li>
                        <li><a href="<?php echo site_url('attendance/report'); ?>" class="sidebar-nav-link"><?php _trans('attendance_report'); ?></a></li>
                    <?php endif; ?>
                    <?php if (has_permission('leaves')) : ?>
                        <li><a href="<?php echo site_url('leaves/index'); ?>" class="sidebar-nav-link"><?php _trans('leave_requests_admin'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <!-- Reports Group -->
            <?php if (has_permission('reports')) : ?>
                <li class="sidebar-group-title">Reports</li>
                <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'reports') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reports'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-bar-chart nav-icon"></i>
                        <span class="nav-text"><?php _trans('reports'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Administration Group -->
            <?php if (has_permission('settings') || has_permission('users') || has_permission('roles')) : ?>
                <li class="sidebar-group-title">Administration</li>

                <?php if (has_permission('settings')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'settings') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('settings'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-cogs nav-icon"></i>
                            <span class="nav-text"><?php _trans('settings'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('users')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'users') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('users/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-user-circle nav-icon"></i>
                            <span class="nav-text"><?php _trans('user_accounts'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('roles')) : ?>
                    <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'roles') ? 'active' : ''; ?>">
                        <a href="<?php echo site_url('roles/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-shield nav-icon"></i>
                            <span class="nav-text"><?php _trans('user_roles'); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
