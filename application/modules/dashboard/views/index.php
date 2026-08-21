<div id="content">
    <?php echo $this->layout->load_view('layout/alerts'); ?>

    <?php if (isset($employee) && $employee) : ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default" style="border-left: 4px solid #337ab7;">
                    <div class="panel-body clearfix" style="padding: 15px 20px;">
                        <div class="pull-left">
                            <h4 style="margin: 0 0 5px 0; font-weight: bold;">
                                <i class="fa fa-clock-o text-primary"></i> <?php _trans('attendance_portal'); ?>
                            </h4>
                            <p class="text-muted" style="margin: 0;">
                                <?php _trans('employee'); ?>: <strong><?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?></strong>
                                (<code><?php echo html_escape($employee->employee_number); ?></code>)
                                &nbsp;|&nbsp; Today: <strong><?php echo date('d F Y'); ?></strong>
                            </p>
                        </div>
                        <div class="pull-right" style="margin-top: 5px;">
                            <?php if ( ! $today_attendance || ! $today_attendance->clock_in) : ?>
                                <a href="<?php echo site_url('attendance/clock'); ?>" class="btn btn-success">
                                    <i class="fa fa-sign-in"></i> <?php _trans('clock_in'); ?> Now
                                </a>
                            <?php elseif ( ! $today_attendance->clock_out) : ?>
                                <span class="label label-info" style="font-size: 13px; padding: 6px 10px; margin-right: 10px; display: inline-block;">
                                    In: <?php echo date('H:i:s', strtotime($today_attendance->clock_in)); ?> (<?php _trans($today_attendance->status); ?>)
                                </span>
                                <a href="<?php echo site_url('attendance/clock'); ?>" class="btn btn-danger">
                                    <i class="fa fa-sign-out"></i> <?php _trans('clock_out'); ?> Now
                                </a>
                            <?php else : ?>
                                <span class="label label-success" style="font-size: 13px; padding: 6px 10px; margin-right: 10px; display: inline-block;">
                                    <i class="fa fa-check"></i> In: <?php echo date('H:i:s', strtotime($today_attendance->clock_in)); ?> | Out: <?php echo date('H:i:s', strtotime($today_attendance->clock_out)); ?>
                                </span>
                                <a href="<?php echo site_url('attendance/clock'); ?>" class="btn btn-default">
                                    <i class="fa fa-history"></i> <?php _trans('attendance_portal'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $can_add_client     = has_permission('clients', 'create');
    $can_create_quote   = has_permission('quotes', 'create');
    $can_create_invoice = has_permission('invoices', 'create');
    $can_enter_payment  = has_permission('payments', 'create');
    $show_quick_actions = get_setting('disable_quickactions') != 1 && ($can_add_client || $can_create_quote || $can_create_invoice || $can_enter_payment);
    ?>

    <?php if ($show_quick_actions) : ?>
        <div class="row">
            <div class="col-xs-12">
                <div id="panel-quick-actions" class="panel panel-default quick-actions">
                    <div class="panel-heading">
                        <b><i class="fa fa-bolt text-primary"></i> <?php _trans('quick_actions'); ?></b>
                    </div>
                    <div class="panel-body">
                        <div class="quick-actions-grid">
                            <?php if ($can_add_client) : ?>
                                <a href="<?php echo site_url('clients/form'); ?>" class="quick-action-item action-client">
                                    <div class="quick-action-icon">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <span class="quick-action-title"><?php _trans('add_client'); ?></span>
                                        <span class="quick-action-desc"><?php echo trans('client'); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <?php if ($can_create_quote) : ?>
                                <a href="javascript:void(0)" class="create-quote quick-action-item action-quote">
                                    <div class="quick-action-icon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <span class="quick-action-title"><?php _trans('create_quote'); ?></span>
                                        <span class="quick-action-desc"><?php echo trans('quote'); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <?php if ($can_create_invoice) : ?>
                                <a href="javascript:void(0)" class="create-invoice quick-action-item action-invoice">
                                    <div class="quick-action-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <span class="quick-action-title"><?php _trans('create_invoice'); ?></span>
                                        <span class="quick-action-desc"><?php echo trans('invoice'); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <?php if ($can_enter_payment) : ?>
                                <a href="<?php echo site_url('payments/form'); ?>" class="quick-action-item action-payment">
                                    <div class="quick-action-icon">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <div class="quick-action-info">
                                        <span class="quick-action-title"><?php _trans('enter_payment'); ?></span>
                                        <span class="quick-action-desc"><?php echo trans('payment'); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $has_quote_perm   = has_permission('quotes');
    $has_invoice_perm = has_permission('invoices');
    ?>

    <?php if ($has_quote_perm || $has_invoice_perm) : ?>
        <div class="row">
            <?php if ($has_quote_perm) : ?>
                <div class="col-xs-12 col-md-6">
                    <div id="panel-quote-overview" class="panel panel-default overview">
                        <div class="panel-heading">
                            <b><i class="fa fa-bar-chart fa-margin"></i> <?php _trans('quote_overview'); ?></b>
                            <span class="pull-right text-muted"><?php echo lang($quote_status_period); ?></span>
                        </div>
                        <table class="table table-hover table-bordered table-condensed no-margin">
                            <?php foreach ($quote_status_totals as $total) : ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo site_url($total['href']); ?>">
                                            <?php echo $total['label']; ?>
                                        </a>
                                    </td>
                                    <td class="amount">
                                        <span class="<?php echo $total['class']; ?>">
                                            <?php echo format_currency($total['sum_total']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($has_invoice_perm) : ?>
                <div class="col-xs-12 <?php echo $has_quote_perm ? 'col-md-6' : 'col-md-12'; ?>">
                    <div id="panel-invoice-overview" class="panel panel-default overview">
                        <div class="panel-heading">
                            <b><i class="fa fa-bar-chart fa-margin"></i> <?php _trans('invoice_overview'); ?></b>
                            <span class="pull-right text-muted"><?php echo lang($invoice_status_period); ?></span>
                        </div>
                        <table class="table table-hover table-bordered table-condensed no-margin">
                            <?php foreach ($invoice_status_totals as $total) : ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo site_url($total['href']); ?>">
                                            <?php echo $total['label']; ?>
                                        </a>
                                    </td>
                                    <td class="amount">
                                        <span class="<?php echo $total['class']; ?>">
                                            <?php echo format_currency($total['sum_total']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <?php if (empty($overdue_invoices)) : ?>
                        <div class="panel panel-default panel-heading">
                            <span class="text-muted"><?php _trans('no_overdue_invoices'); ?></span>
                        </div>
                    <?php else :
                        $overdue_invoices_total = 0;
                        foreach ($overdue_invoices as $invoice) {
                            $overdue_invoices_total += $invoice->invoice_balance;
                        }
                        ?>
                        <div class="panel panel-danger panel-heading">
                            <?php echo anchor('invoices/status/overdue', '<i class="fa fa-external-link"></i> ' . trans('overdue_invoices'), 'class="text-danger"'); ?>
                            <span class="pull-right text-danger">
                                <?php echo format_currency($overdue_invoices_total); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if ($has_quote_perm) : ?>
                <div class="col-xs-12 col-md-6">
                    <div id="panel-recent-quotes" class="panel panel-default">
                        <div class="panel-heading">
                            <b><i class="fa fa-history fa-margin"></i> <?php _trans('recent_quotes'); ?></b>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th><?php _trans('status'); ?></th>
                                        <th style="min-width: 15%;"><?php _trans('date'); ?></th>
                                        <th style="min-width: 15%;"><?php _trans('quote'); ?></th>
                                        <th style="min-width: 35%;"><?php _trans('client'); ?></th>
                                        <th class="amount"><?php _trans('balance'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($quotes as $quote) : ?>
                                        <tr>
                                            <td>
                                                <span class="label <?php echo $quote_statuses[$quote->quote_status_id]['class']; ?>">
                                                    <?php echo $quote_statuses[$quote->quote_status_id]['label']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date_from_mysql($quote->quote_date_created); ?></td>
                                            <td><?php echo anchor('quotes/view/' . $quote->quote_id, ($quote->quote_number ? htmlsc($quote->quote_number) : $quote->quote_id)); ?></td>
                                            <td><?php echo anchor('clients/view/' . $quote->client_id, htmlsc(format_client($quote))); ?></td>
                                            <td class="amount"><?php echo format_currency($quote->quote_total); ?></td>
                                            <td style="text-align: center;">
                                                <a href="<?php echo site_url('quotes/generate_pdf/' . $quote->quote_id) . '?' . _csrf_query(); ?>" target="_blank" title="<?php _trans('download_pdf'); ?>">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="6" class="text-right small">
                                            <?php echo anchor('quotes/status/all', trans('view_all')); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($has_invoice_perm) : ?>
                <div class="col-xs-12 <?php echo $has_quote_perm ? 'col-md-6' : 'col-md-12'; ?>">
                    <div id="panel-recent-invoices" class="panel panel-default">
                        <div class="panel-heading">
                            <b><i class="fa fa-history fa-margin"></i> <?php _trans('recent_invoices'); ?></b>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th><?php _trans('status'); ?></th>
                                        <th style="min-width: 15%;"><?php _trans('due_date'); ?></th>
                                        <th style="min-width: 15%;"><?php _trans('invoice'); ?></th>
                                        <th style="min-width: 35%;"><?php _trans('client'); ?></th>
                                        <th class="amount"><?php _trans('balance'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice) :
                                        if ($this->config->item('disable_read_only') == true) {
                                            $invoice->is_read_only = 0;
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="label <?php echo $invoice_statuses[$invoice->invoice_status_id]['class']; ?>">
                                                    <?php echo $invoice_statuses[$invoice->invoice_status_id]['label'];
                                        if ($invoice->invoice_sign == '-1') { ?>&nbsp;<i class="fa fa-credit-invoice" title="<?php _trans('credit_invoice') ?>"></i><?php }
                                        if ($invoice->is_read_only) { ?>&nbsp;<i class="fa fa-read-only" title="<?php _trans('read_only') ?>"></i><?php }
                                        if ($invoice->invoice_is_recurring) { ?>&nbsp;<i class="fa fa-refresh" title="<?php _trans('recurring') ?>"></i><?php }
                                        ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="<?php echo ($invoice->is_overdue) ? 'font-overdue' : '' ?>">
                                                    <?php echo date_from_mysql($invoice->invoice_date_due); ?>
                                                </span>
                                            </td>
                                            <td><?php echo anchor('invoices/view/' . $invoice->invoice_id, ($invoice->invoice_number ? htmlsc($invoice->invoice_number) : $invoice->invoice_id)); ?></td>
                                            <td><?php echo anchor('clients/view/' . $invoice->client_id, htmlsc(format_client($invoice))); ?></td>
                                            <td class="amount"><?php echo format_currency($invoice->invoice_balance * $invoice->invoice_sign); ?></td>
                                            <td style="text-align: center;">
                                                <?php if ($invoice->sumex_id != null) : ?>
                                                    <a href="<?php echo site_url('invoices/generate_sumex_pdf/' . $invoice->invoice_id); ?>" target="_blank" title="<?php _trans('generate_sumex'); ?>">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?php echo site_url('invoices/generate_pdf/' . $invoice->invoice_id) . '?' . _csrf_query(); ?>" target="_blank" title="<?php _trans('download_pdf'); ?>">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="6" class="text-right small">
                                            <?php echo anchor('invoices/status/all', trans('view_all')); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($employee) && $employee) : ?>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b><i class="fa fa-calendar fa-margin"></i> <?php _trans('my_attendance'); ?> (Recent Logs)</b>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed no-margin">
                            <thead>
                                <tr>
                                    <th><?php _trans('date'); ?></th>
                                    <th><?php _trans('clock_in'); ?></th>
                                    <th><?php _trans('clock_out'); ?></th>
                                    <th><?php _trans('status'); ?></th>
                                    <th><?php _trans('hours_worked'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( ! empty($employee_attendances)) : ?>
                                    <?php foreach ($employee_attendances as $att) :
                                        $status_class = match ($att->status) {
                                            'present'       => 'label-success',
                                            'late'          => 'label-warning',
                                            'absent'        => 'label-danger',
                                            'sick', 'leave' => 'label-info',
                                            default         => 'label-default'
                                        };
                                        $duration = '-';
                                        if ($att->clock_in && $att->clock_out) {
                                            $diff     = strtotime($att->clock_out) - strtotime($att->clock_in);
                                            $duration = floor($diff / 3600) . 'h ' . floor(($diff % 3600) / 60) . 'm';
                                        }
                                        ?>
                                        <tr>
                                            <td><strong><?php echo date('d/m/Y', strtotime($att->attendance_date)); ?></strong></td>
                                            <td><?php echo $att->clock_in ? date('H:i:s', strtotime($att->clock_in)) : '-'; ?></td>
                                            <td><?php echo $att->clock_out ? date('H:i:s', strtotime($att->clock_out)) : '-'; ?></td>
                                            <td><span class="label <?php echo $status_class; ?>"><?php _trans($att->status); ?></span></td>
                                            <td><code><?php echo $duration; ?></code></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted"><?php _trans('no_records_found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer text-right">
                        <a href="<?php echo site_url('attendance/clock'); ?>" class="btn btn-xs btn-primary">
                            <i class="fa fa-external-link"></i> Open Full Attendance Portal
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b><i class="fa fa-user fa-margin"></i> Employee Profile Summary</b>
                    </div>
                    <div class="panel-body">
                        <p style="margin-bottom: 8px;">
                            <span class="text-muted"><?php _trans('employee_number'); ?>:</span><br>
                            <strong><code><?php echo html_escape($employee->employee_number); ?></code></strong>
                        </p>
                        <p style="margin-bottom: 8px;">
                            <span class="text-muted"><?php _trans('department'); ?>:</span><br>
                            <strong><?php echo html_escape($employee->department ?: '-'); ?></strong>
                        </p>
                        <p style="margin-bottom: 8px;">
                            <span class="text-muted"><?php _trans('position'); ?>:</span><br>
                            <strong><?php echo html_escape($employee->job_title ?: '-'); ?></strong>
                        </p>
                        <p style="margin-bottom: 0;">
                            <span class="text-muted"><?php _trans('email'); ?>:</span><br>
                            <strong><?php echo html_escape($employee->email ?: '-'); ?></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (get_setting('projects_enabled') == 1 && (has_permission('projects') || has_permission('tasks'))) : ?>
        <div class="row">
            <?php if (has_permission('projects')) : ?>
                <div class="col-xs-12 col-md-6">
                    <div id="panel-projects" class="panel panel-default">
                        <div class="panel-heading">
                            <b><i class="fa fa-list fa-margin"></i> <?php _trans('projects'); ?></b>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th><?php _trans('project_name'); ?></th>
                                        <th><?php _trans('client_name'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projects as $project) : ?>
                                        <tr>
                                            <td><?php echo anchor('projects/view/' . $project->project_id, htmlsc($project->project_name)); ?></td>
                                            <td>
                                                <?php if ($project->client_id != null) : ?>
                                                    <?php echo anchor('clients/view/' . $project->client_id, htmlsc(format_client($project))); ?>
                                                <?php else : ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="6" class="text-right small">
                                            <?php echo anchor('projects/index', trans('view_all')); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (has_permission('tasks')) : ?>
                <div class="col-xs-12 <?php echo has_permission('projects') ? 'col-md-6' : 'col-md-12'; ?>">
                    <div id="panel-recent-invoices" class="panel panel-default">
                        <div class="panel-heading">
                            <b><i class="fa fa-check-square-o fa-margin"></i> <?php _trans('tasks'); ?></b>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th><?php _trans('status'); ?></th>
                                        <th><?php _trans('task_name'); ?></th>
                                        <th><?php _trans('task_finish_date'); ?></th>
                                        <th><?php _trans('project'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task) : ?>
                                        <tr>
                                            <td>
                                                <span class="label <?php echo $task_statuses[$task->task_status]['class'] ?? '' ?>">
                                                    <?php if (isset($task_statuses[$task->task_status]['label'])) {
                                                        echo $task_statuses[$task->task_status]['label'];
                                                    } ?>
                                                </span>
                                            </td>
                                            <td><?php echo anchor('tasks/form/' . $task->task_id, htmlsc($task->task_name)) ?></td>
                                            <td>
                                                <span class="<?php echo ($task->is_overdue) ? 'font-overdue' : ''; ?>">
                                                    <?php echo date_from_mysql($task->task_finish_date); ?>
                                                </span>
                                            </td>
                                            <td><?php echo empty($task->project_id) ? '' : anchor('projects/view/' . $task->project_id, htmlsc($task->project_name)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="6" class="text-right small">
                                            <?php echo anchor('tasks/index', trans('view_all')); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
