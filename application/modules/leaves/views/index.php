<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('leave_requests'); ?></h1>

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('leaves/my_leaves'); ?>">
            <i class="fa fa-user"></i> <?php _trans('my_leave_requests'); ?>
        </a>
    </div>
</div>

<div id="content" class="table-content">

    <?php echo $this->layout->load_view('layout/alerts'); ?>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="btn-group pull-left">
                <a href="<?php echo site_url('leaves/index?status=pending'); ?>" class="btn btn-default btn-sm <?php echo $current_status === 'pending' ? 'active' : ''; ?>">
                    <i class="fa fa-clock-o text-warning"></i> <?php _trans('pending'); ?>
                </a>
                <a href="<?php echo site_url('leaves/index?status=approved'); ?>" class="btn btn-default btn-sm <?php echo $current_status === 'approved' ? 'active' : ''; ?>">
                    <i class="fa fa-check text-success"></i> <?php _trans('approved'); ?>
                </a>
                <a href="<?php echo site_url('leaves/index?status=rejected'); ?>" class="btn btn-default btn-sm <?php echo $current_status === 'rejected' ? 'active' : ''; ?>">
                    <i class="fa fa-times text-danger"></i> <?php _trans('rejected'); ?>
                </a>
                <a href="<?php echo site_url('leaves/index?status=all'); ?>" class="btn btn-default btn-sm <?php echo $current_status === 'all' ? 'active' : ''; ?>">
                    <i class="fa fa-list text-info"></i> <?php _trans('view_all'); ?>
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _trans('employee'); ?></th>
                        <th><?php _trans('leave_type'); ?></th>
                        <th><?php _trans('start_date'); ?></th>
                        <th><?php _trans('end_date'); ?></th>
                        <th><?php _trans('total_days'); ?></th>
                        <th><?php _trans('reason'); ?></th>
                        <th><?php _trans('status'); ?></th>
                        <th><?php _trans('options'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( ! empty($leaves)) : ?>
                        <?php foreach ($leaves as $leave) :
                            $status_class = match ($leave->status) {
                                'approved'  => 'label-success',
                                'pending'   => 'label-warning',
                                'rejected'  => 'label-danger',
                                'cancelled' => 'label-default',
                                default     => 'label-default',
                            };
                            ?>
                            <tr>
                                <td>#<?php echo $leave->leave_request_id; ?></td>
                                <td>
                                    <strong><?php echo html_escape($leave->first_name . ' ' . $leave->last_name); ?></strong><br>
                                    <small class="text-muted"><code><?php echo html_escape($leave->employee_number); ?></code> | <?php echo html_escape($leave->department ?: '-'); ?></small>
                                </td>
                                <td>
                                    <span class="label label-info">
                                        <?php _trans('leave_type_' . $leave->leave_type); ?>
                                    </span>
                                </td>
                                <td><?php echo date_from_mysql($leave->start_date); ?></td>
                                <td><?php echo date_from_mysql($leave->end_date); ?></td>
                                <td><strong><?php echo $leave->total_days; ?></strong> day(s)</td>
                                <td><?php echo nl2br(html_escape($leave->reason ?: '-')); ?></td>
                                <td>
                                    <span class="label <?php echo $status_class; ?>">
                                        <?php _trans($leave->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($leave->status === 'pending') : ?>
                                        <button type="button" class="btn btn-xs btn-primary btn-approve-reject" data-id="<?php echo $leave->leave_request_id; ?>">
                                            <i class="fa fa-edit"></i> Review / Approve
                                        </button>
                                    <?php else : ?>
                                        <small class="text-muted">
                                            By: <?php echo html_escape($leave->approver_name ?: 'System'); ?><br>
                                            At: <?php echo $leave->approved_at ? date_from_mysql($leave->approved_at, true) : '-'; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted"><?php _trans('no_records_found'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-placeholder"></div>

<script>
$(function () {
    $('.btn-approve-reject').click(function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').load("<?php echo site_url('leaves/modal_approve_reject'); ?>/" + id, function () {
            $('#modal-approve-leave').modal('show');
        });
    });
});
</script>
