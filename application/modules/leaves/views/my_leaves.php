<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('my_leave_requests'); ?></h1>

    <div class="headerbar-item pull-right">
        <button type="button" class="btn btn-sm btn-success" id="btn-apply-leave">
            <i class="fa fa-plus"></i> <?php _trans('apply_leave'); ?>
        </button>
        <?php if (has_permission('leaves')) : ?>
            <a href="<?php echo site_url('leaves/index'); ?>" class="btn btn-sm btn-default">
                <i class="fa fa-list"></i> <?php _trans('admin_management'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="content" class="table-content">

    <?php echo $this->layout->load_view('layout/alerts'); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <b><i class="fa fa-calendar fa-margin"></i> Leave Requests History for <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?></b>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _trans('leave_type'); ?></th>
                        <th><?php _trans('start_date'); ?></th>
                        <th><?php _trans('end_date'); ?></th>
                        <th><?php _trans('total_days'); ?></th>
                        <th><?php _trans('reason'); ?></th>
                        <th><?php _trans('status'); ?></th>
                        <th><?php _trans('manager_notes'); ?></th>
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
                                <td><?php echo nl2br(html_escape($leave->admin_notes ?: '-')); ?></td>
                                <td>
                                    <?php if ($leave->status === 'pending') : ?>
                                        <button type="button" class="btn btn-xs btn-danger btn-cancel-leave" data-id="<?php echo $leave->leave_request_id; ?>">
                                            <i class="fa fa-ban"></i> <?php _trans('cancel'); ?>
                                        </button>
                                    <?php else : ?>
                                        -
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
    $('#btn-apply-leave').click(function () {
        $('#modal-placeholder').load("<?php echo site_url('leaves/modal_form'); ?>");
    });

    $('.btn-cancel-leave').click(function () {
        if (!confirm('Are you sure you want to cancel this leave request?')) {
            return;
        }

        var id = $(this).data('id');
        $.post("<?php echo site_url('leaves/cancel/'); ?>" + id, {
            _csrf: Cookies.get('ip_csrf_cookie')
        }, function (response) {
            var data = JSON.parse(response);
            if (data.success == '1') {
                window.location.reload();
            } else {
                alert(data.message || 'Error cancelling request.');
            }
        });
    });
});
</script>
