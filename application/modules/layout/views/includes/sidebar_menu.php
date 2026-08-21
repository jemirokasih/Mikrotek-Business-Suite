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
                
                <?php if (has_permission('quotes')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'quotes') ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('quotes/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-file-o nav-icon"></i>
                            <span class="nav-text"><?php _trans('quotes'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('quotes/index'); ?>" class="sidebar-nav-link"><?php _trans('view_quotes'); ?></a></li>
                            <?php if (has_permission('quotes', 'create')) : ?>
                                <li><a href="javascript:void(0)" class="sidebar-nav-link create-quote"><?php _trans('create_quote'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('invoices')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['invoices', 'recurring']) ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('invoices/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-file-text-o nav-icon"></i>
                            <span class="nav-text"><?php _trans('invoices'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('invoices/index'); ?>" class="sidebar-nav-link"><?php _trans('view_invoices'); ?></a></li>
                            <?php if (has_permission('invoices', 'create')) : ?>
                                <li><a href="javascript:void(0)" class="sidebar-nav-link create-invoice"><?php _trans('create_invoice'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo site_url('invoices/recurring/index'); ?>" class="sidebar-nav-link"><?php _trans('view_recurring_invoices'); ?></a></li>
                            <li><a href="<?php echo site_url('invoices/archive'); ?>" class="sidebar-nav-link"><?php _trans('invoice_archive'); ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('payments')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'payments') ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('payments/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-credit-card nav-icon"></i>
                            <span class="nav-text"><?php _trans('payments'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('payments/index'); ?>" class="sidebar-nav-link"><?php _trans('view_payments'); ?></a></li>
                            <?php if (has_permission('payments', 'create')) : ?>
                                <li><a href="<?php echo site_url('payments/form'); ?>" class="sidebar-nav-link"><?php _trans('enter_payment'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo site_url('payments/online_logs'); ?>" class="sidebar-nav-link"><?php _trans('view_payment_logs'); ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('receipts')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'receipts') ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('receipts/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-print nav-icon"></i>
                            <span class="nav-text"><?php _trans('receipts'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('receipts/index'); ?>" class="sidebar-nav-link"><?php _trans('view_receipts'); ?></a></li>
                            <?php if (has_permission('receipts', 'create')) : ?>
                                <li><a href="<?php echo site_url('receipts/form'); ?>" class="sidebar-nav-link"><?php _trans('create_receipt'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Clients & Directory Group -->
            <?php if (has_permission('clients')) : ?>
                <li class="sidebar-group-title">Clients &amp; Directory</li>

                <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'clients') ? 'open active' : ''; ?>">
                    <a href="<?php echo site_url('clients/index'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-users nav-icon"></i>
                        <span class="nav-text"><?php _trans('clients'); ?></span>
                        <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo site_url('clients/index'); ?>" class="sidebar-nav-link"><?php _trans('view_clients'); ?></a></li>
                        <?php if (has_permission('clients', 'create')) : ?>
                            <li><a href="<?php echo site_url('clients/form'); ?>" class="sidebar-nav-link"><?php _trans('add_client'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Communication Group -->
            <li class="sidebar-group-title">Communication</li>
            <?php if (has_permission('settings')) : ?>
                <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'webmail') ? 'open active' : ''; ?>">
                    <a href="<?php echo site_url('webmail'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-envelope nav-icon"></i>
                        <span class="nav-text">Webmail / Email</span>
                        <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo site_url('webmail'); ?>" class="sidebar-nav-link">Buka Email</a></li>
                        <li><a href="<?php echo site_url('webmail/settings'); ?>" class="sidebar-nav-link">Pengaturan Webmail</a></li>
                    </ul>
                </li>
            <?php else : ?>
                <li class="sidebar-nav-item <?php echo ($this->router->fetch_class() == 'webmail') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('webmail'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-envelope nav-icon"></i>
                        <span class="nav-text">Buka Email</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Products & Projects Group -->
            <?php if (has_permission('products') || (get_setting('projects_enabled') == 1 && (has_permission('projects') || has_permission('tasks')))) : ?>
                <li class="sidebar-group-title">Products &amp; Projects</li>

                <?php if (has_permission('products')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['products', 'families', 'units']) ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('products/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-cube nav-icon"></i>
                            <span class="nav-text"><?php _trans('products'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo site_url('products/index'); ?>" class="sidebar-nav-link"><?php _trans('view_products'); ?></a></li>
                            <?php if (has_permission('products', 'create')) : ?>
                                <li><a href="<?php echo site_url('products/form'); ?>" class="sidebar-nav-link"><?php _trans('create_product'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo site_url('families/index'); ?>" class="sidebar-nav-link"><?php _trans('view_product_families'); ?></a></li>
                            <li><a href="<?php echo site_url('units/index'); ?>" class="sidebar-nav-link"><?php _trans('view_product_units'); ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (get_setting('projects_enabled') == 1) : ?>
                    <?php if (has_permission('projects') || has_permission('tasks')) : ?>
                        <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['projects', 'tasks']) ? 'open active' : ''; ?>">
                            <a href="<?php echo site_url('projects/index'); ?>" class="sidebar-nav-link">
                                <i class="fa fa-briefcase nav-icon"></i>
                                <span class="nav-text"><?php _trans('projects'); ?> &amp; <?php _trans('tasks'); ?></span>
                                <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                            </a>
                            <ul class="sidebar-submenu">
                                <?php if (has_permission('projects')) : ?>
                                    <li><a href="<?php echo site_url('projects/index'); ?>" class="sidebar-nav-link"><?php _trans('view_projects'); ?></a></li>
                                    <li><a href="<?php echo site_url('projects/form'); ?>" class="sidebar-nav-link"><?php _trans('create_project'); ?></a></li>
                                <?php endif; ?>
                                <?php if (has_permission('tasks')) : ?>
                                    <li><a href="<?php echo site_url('tasks/index'); ?>" class="sidebar-nav-link"><?php _trans('view_tasks'); ?></a></li>
                                    <li><a href="<?php echo site_url('tasks/form'); ?>" class="sidebar-nav-link"><?php _trans('create_task'); ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- HR & Employee Group -->
            <li class="sidebar-group-title">Human Resources</li>

            <?php if (has_permission('employees')) : ?>
                <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'employees') ? 'open active' : ''; ?>">
                    <a href="<?php echo site_url('employees/index'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-id-badge nav-icon"></i>
                        <span class="nav-text"><?php _trans('employees'); ?></span>
                        <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo site_url('employees/index'); ?>" class="sidebar-nav-link"><?php _trans('view_employees'); ?></a></li>
                        <?php if (has_permission('employees', 'create')) : ?>
                            <li><a href="<?php echo site_url('employees/form'); ?>" class="sidebar-nav-link"><?php _trans('add_employee'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['attendance', 'leaves']) ? 'open active' : ''; ?>">
                <a href="<?php echo site_url('attendance/clock'); ?>" class="sidebar-nav-link">
                    <i class="fa fa-clock-o nav-icon"></i>
                    <span class="nav-text"><?php _trans('attendance'); ?> &amp; <?php _trans('leave'); ?></span>
                    <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
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
                <li class="sidebar-nav-item has-submenu <?php echo ($this->router->fetch_class() == 'reports') ? 'open active' : ''; ?>">
                    <a href="<?php echo site_url('reports/invoice_aging'); ?>" class="sidebar-nav-link">
                        <i class="fa fa-bar-chart nav-icon"></i>
                        <span class="nav-text"><?php _trans('reports'); ?></span>
                        <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo site_url('reports/invoice_aging'); ?>" class="sidebar-nav-link"><?php _trans('invoice_aging'); ?></a></li>
                        <li><a href="<?php echo site_url('reports/payment_history'); ?>" class="sidebar-nav-link"><?php _trans('payment_history'); ?></a></li>
                        <li><a href="<?php echo site_url('reports/sales_by_client'); ?>" class="sidebar-nav-link"><?php _trans('sales_by_client'); ?></a></li>
                        <li><a href="<?php echo site_url('reports/sales_by_year'); ?>" class="sidebar-nav-link"><?php _trans('sales_by_date'); ?></a></li>
                        <li><a href="<?php echo site_url('reports/invoices_per_client'); ?>" class="sidebar-nav-link"><?php _trans('invoices_per_client'); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Administration Group -->
            <?php if (has_permission('settings') || has_permission('users') || has_permission('roles') || has_permission('bank_accounts')) : ?>
                <li class="sidebar-group-title">Administration</li>

                <?php if (has_permission('settings') || has_permission('bank_accounts')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['settings', 'companies', 'bank_accounts', 'custom_fields', 'email_templates', 'invoice_groups', 'payment_methods', 'tax_rates', 'import']) ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('settings'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-cogs nav-icon"></i>
                            <span class="nav-text"><?php _trans('settings'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if (has_permission('settings')) : ?>
                                <li><a href="<?php echo site_url('settings'); ?>" class="sidebar-nav-link"><?php _trans('system_settings'); ?></a></li>
                                <li><a href="<?php echo site_url('companies/index'); ?>" class="sidebar-nav-link"><?php _trans('companies'); ?></a></li>
                                <li><a href="<?php echo site_url('custom_fields/index'); ?>" class="sidebar-nav-link"><?php _trans('custom_fields'); ?></a></li>
                                <li><a href="<?php echo site_url('email_templates/index'); ?>" class="sidebar-nav-link"><?php _trans('email_templates'); ?></a></li>
                                <li><a href="<?php echo site_url('invoice_groups/index'); ?>" class="sidebar-nav-link"><?php _trans('invoice_groups'); ?></a></li>
                                <li><a href="<?php echo site_url('payment_methods/index'); ?>" class="sidebar-nav-link"><?php _trans('payment_methods'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('bank_accounts')) : ?>
                                <li><a href="<?php echo site_url('bank_accounts/index'); ?>" class="sidebar-nav-link">Rekening Bank</a></li>
                            <?php endif; ?>
                            <?php if (has_permission('settings')) : ?>
                                <li><a href="<?php echo site_url('tax_rates/index'); ?>" class="sidebar-nav-link"><?php _trans('tax_rates'); ?></a></li>
                                <li><a href="<?php echo site_url('import'); ?>" class="sidebar-nav-link"><?php _trans('import_data'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (has_permission('users') || has_permission('roles')) : ?>
                    <li class="sidebar-nav-item has-submenu <?php echo in_array($this->router->fetch_class(), ['users', 'roles']) ? 'open active' : ''; ?>">
                        <a href="<?php echo site_url('users/index'); ?>" class="sidebar-nav-link">
                            <i class="fa fa-shield nav-icon"></i>
                            <span class="nav-text"><?php _trans('user_accounts'); ?> &amp; <?php _trans('user_roles'); ?></span>
                            <i class="fa fa-chevron-right arrow-icon" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebarSubmenu(this); return false;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <?php if (has_permission('users')) : ?>
                                <li><a href="<?php echo site_url('users/index'); ?>" class="sidebar-nav-link"><?php _trans('user_accounts'); ?></a></li>
                            <?php endif; ?>
                            <?php if (has_permission('roles')) : ?>
                                <li><a href="<?php echo site_url('roles/index'); ?>" class="sidebar-nav-link"><?php _trans('user_roles'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
