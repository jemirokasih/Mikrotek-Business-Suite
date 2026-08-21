<div id="headerbar">
    <h1 class="headerbar-title"><?php _htmlsc($project->project_name); ?></h1>

    <div class="headerbar-item pull-right">
        <div class="btn-group btn-group-sm">
            <a href="<?php echo site_url('tasks/form/'); ?>" class="btn btn-default">
                <i class="fa fa-check-square-o fa-margin"></i><?php _trans('new_task'); ?>
            </a>
            <a href="<?php echo site_url('projects/form/' . $project->project_id); ?>" class="btn btn-default">
                <i class="fa fa-edit"></i> <?php _trans('edit'); ?>
            </a>
            <form action="<?php echo site_url('projects/delete/' . $project->project_id); ?>"
                  method="post" style="display:inline-block;">
                <?php _csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                    <i class="fa fa-trash-o"></i> <?php _trans('delete'); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<ul id="submenu" class="nav nav-tabs nav-tabs-noborder">
    <li<?php echo ($activeTab == 'tasks' || empty($activeTab)) ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#project-tasks"><?php _trans('tasks'); ?></a></li>
    <li<?php echo $activeTab == 'quotes' ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#project-quotes"><?php _trans('quotes'); ?></a></li>
    <li<?php echo $activeTab == 'invoices' ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#project-invoices"><?php _trans('invoices'); ?></a></li>
    <li<?php echo $activeTab == 'payments' ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#project-payments"><?php _trans('payments'); ?></a></li>
    <li<?php echo $activeTab == 'receipts' ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#project-receipts"><?php _trans('receipts'); ?></a></li>
</ul>

<div id="content" class="tabbable tabs-below no-padding">
    <div class="tab-content no-padding">

        <div id="project-tasks" class="tab-pane tab-rich-content<?php echo ($activeTab == 'tasks' || empty($activeTab)) ? ' active' : ''; ?>">
            <div class="row" style="margin: 15px 0;">
                <div class="col-xs-12 col-md-4">
                    <?php if (!empty($project->client_name)) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong><?php _htmlsc(format_client($project)); ?></strong>
                            </div>
                            <div class="panel-body">
                                <div class="client-address">
                                    <?php $this->layout->load_view('clients/partial_client_address', ['client' => $project]); ?>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info"><?php _trans('alert_no_client_assigned'); ?></div>
                    <?php } ?>
                </div>

                <div class="col-xs-12 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php _trans('tasks'); ?>
                        </div>
                        <div class="panel-body no-padding">
                            <?php echo $task_table; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="project-quotes" class="tab-pane tab-rich-content<?php echo $activeTab == 'quotes' ? ' active' : ''; ?>">
            <div style="padding: 15px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('quotes'); ?>
                    </div>
                    <div class="panel-body no-padding">
                        <?php echo $quote_table; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="project-invoices" class="tab-pane tab-rich-content<?php echo $activeTab == 'invoices' ? ' active' : ''; ?>">
            <div style="padding: 15px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('invoices'); ?>
                    </div>
                    <div class="panel-body no-padding">
                        <?php echo $invoice_table; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="project-payments" class="tab-pane tab-rich-content<?php echo $activeTab == 'payments' ? ' active' : ''; ?>">
            <div style="padding: 15px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('payments'); ?>
                    </div>
                    <div class="panel-body no-padding">
                        <?php echo $payment_table; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="project-receipts" class="tab-pane tab-rich-content<?php echo $activeTab == 'receipts' ? ' active' : ''; ?>">
            <div style="padding: 15px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('receipts'); ?>
                    </div>
                    <div class="panel-body no-padding">
                        <?php echo $receipt_table; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
