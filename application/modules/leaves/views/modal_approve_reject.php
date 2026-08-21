<div id="modal-approve-leave" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-approve-leave-title" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <a data-dismiss="modal" class="close"><i class="fa fa-close"></i></a>
            <h3 id="modal-approve-leave-title">Review Leave Request #<?php echo $leave->leave_request_id; ?></h3>
        </div>

        <form id="form-approve-leave">
            <?php _csrf_field(); ?>
            <input type="hidden" name="leave_request_id" value="<?php echo $leave->leave_request_id; ?>">

            <div class="modal-body">
                <div class="well well-sm">
                    <p><strong>Employee:</strong> <?php echo html_escape($leave->first_name . ' ' . $leave->last_name); ?> (<code><?php echo html_escape($leave->employee_number); ?></code>)</p>
                    <p><strong>Leave Type:</strong> <span class="label label-info"><?php _trans('leave_type_' . $leave->leave_type); ?></span></p>
                    <p><strong>Period:</strong> <?php echo date_from_mysql($leave->start_date); ?> to <?php echo date_from_mysql($leave->end_date); ?> (<strong><?php echo $leave->total_days; ?> day(s)</strong>)</p>
                    <p><strong>Reason:</strong> <?php echo nl2br(html_escape($leave->reason ?: '-')); ?></p>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="status">Decision *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="approved">Approve Leave Request</option>
                                <option value="rejected">Reject Leave Request</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="admin_notes">Manager Notes / Reason</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Optional notes or rejection reason..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn btn-success" id="btn-submit-approval" type="button">
                        <i class="fa fa-check"></i> <?php _trans('save'); ?> Decision
                    </button>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">
                        <i class="fa fa-times"></i> <?php _trans('cancel'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function () {
    $('#modal-approve-leave').modal('show');

    $('#btn-submit-approval').click(function () {
        $.post("<?php echo site_url('leaves/save_approval'); ?>", $('#form-approve-leave').serialize(), function (data) {
            var response = JSON.parse(data);
            if (response.success === 1) {
                $('#modal-approve-leave').modal('hide');
                window.location.reload();
            } else {
                alert(response.message || 'Error processing approval.');
            }
        });
    });
});
</script>
