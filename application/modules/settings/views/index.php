<script>
    $().ready(function () {
        $('#btn-submit').click(function () {
            $('#form-settings').submit();
        });
        $('[name="settings[default_country]"]').select2({
            placeholder: '<?php _trans('country'); ?>',
            allowClear: true
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href');
            $('a[data-toggle="tab"]').removeClass('btn-primary').addClass('btn-default');
            $('a[data-toggle="tab"][href="' + target + '"]').removeClass('btn-default').addClass('btn-primary');
            if (window.ls) {
                localStorage.setItem(window.ls, target);
            }
        });

        if (window.ls) {
            var activeTab = localStorage.getItem(window.ls);
            if (activeTab) {
                $('a[data-toggle="tab"][href="' + activeTab + '"]').first().tab('show');
            }
        }
    });

    window.ls = typeof(localStorage) != 'undefined' ? 'activeTab-settings' : '';
    if (window.ls) {
        const lsother = window.ls + '-other';
        // Become from other page, Return to general tab (Clear memory)
        if (document.referrer != '<?php echo site_url('settings'); ?>') {
            localStorage.setItem(lsother, (localStorage.getItem(lsother) ? parseInt(localStorage.getItem(lsother)) : 0) + 1);
            if (localStorage.getItem(lsother) == 1 && localStorage.getItem(window.ls)) {
                localStorage.removeItem(window.ls); // Clear tab memory
            }
        } else {
            $(window).on('unload', function () {
                localStorage.removeItem(lsother); // Clear memory
            });
        }
    }
</script>

<form method="post" id="form-settings" enctype="multipart/form-data">

    <?php _csrf_field(); ?>

    <div id="headerbar">
        <div class="headerbar-left">
            <h1 class="headerbar-title"><?php _trans('settings'); ?></h1>

            <div class="headerbar-item visible-lg">
                <div class="btn-group btn-group-sm index-options" id="settings-tabs">
                    <a data-toggle="tab" href="#settings-general" class="btn btn-primary">
                        <?php _trans('general'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-invoices" class="btn btn-default">
                        <?php _trans('invoices'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-quotes" class="btn btn-default">
                        <?php _trans('quotes'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-taxes" class="btn btn-default">
                        <?php _trans('taxes'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-email" class="btn btn-default">
                        <?php _trans('email'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-online-payment" class="btn btn-default">
                        <?php echo lang('online_payment'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-projects-tasks" class="btn btn-default">
                        <?php _trans('projects'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-updates" class="btn btn-default">
                        <?php _trans('updates'); ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="headerbar-item pull-right">
            <button type="button" class="btn btn-default btn-sm submenu-toggle hidden-lg"
                    data-toggle="collapse" data-target="#ip-submenu-collapse">
                <i class="fa fa-bars"></i> <?php _trans('submenu'); ?>
            </button>
            <?php $this->layout->load_view('layout/header_buttons', ['hide_cancel_button' => true]); ?>
        </div>
    </div>

    <div id="submenu">
        <div class="collapse clearfix" id="ip-submenu-collapse">
            <div class="submenu-row">
                <div class="btn-group btn-group-sm index-options">
                    <a data-toggle="tab" href="#settings-general" class="btn btn-primary">
                        <?php _trans('general'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-invoices" class="btn btn-default">
                        <?php _trans('invoices'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-quotes" class="btn btn-default">
                        <?php _trans('quotes'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-taxes" class="btn btn-default">
                        <?php _trans('taxes'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-email" class="btn btn-default">
                        <?php _trans('email'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-online-payment" class="btn btn-default">
                        <?php echo lang('online_payment'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-projects-tasks" class="btn btn-default">
                        <?php _trans('projects'); ?>
                    </a>
                    <a data-toggle="tab" href="#settings-updates" class="btn btn-default">
                        <?php _trans('updates'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="tabbable tabs-below">

        <div class="tab-content">

            <div class="col-xs-12">
                <?php $this->layout->load_view('layout/alerts'); ?>

                <?php if ( ! empty($missing_allowlisted_template_settings)) { ?>
                    <div class="alert alert-warning">
                        <p>
                            <strong><?php _trans('custom_templates_upgrade_required'); ?></strong>
                        </p>
                        <p>
                            <?php _trans('custom_templates_upgrade_required_message'); ?>
                        </p>
                        <p>
                            <?php _trans('custom_templates_upgrade_required_ipconfig'); ?>
                        </p>
                        <ul>
                            <?php foreach ($missing_allowlisted_template_settings as $ipconfig_key => $template_names) { ?>
                                <li>
                                    <code><?php echo htmlsc($ipconfig_key); ?></code>:
                                    <?php echo htmlsc(implode(', ', $template_names)); ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <p>
                            <?php echo trans('custom_templates_upgrade_required_docs'); ?>
                        </p>
                    </div>
                <?php } ?>
            </div>

            <div id="settings-general" class="tab-pane active">
                <?php $this->layout->load_view('settings/partial_settings_general'); ?>
            </div>

            <div id="settings-invoices" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_invoices'); ?>
            </div>

            <div id="settings-quotes" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_quotes'); ?>
            </div>

            <div id="settings-taxes" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_taxes'); ?>
            </div>

            <div id="settings-email" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_email'); ?>
            </div>

            <div id="settings-online-payment" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_online_payment'); ?>
            </div>

            <div id="settings-projects-tasks" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_projects_tasks'); ?>
            </div>

            <div id="settings-updates" class="tab-pane">
                <?php $this->layout->load_view('settings/partial_settings_updates'); ?>
            </div>

        </div>

    </div>

</form>
