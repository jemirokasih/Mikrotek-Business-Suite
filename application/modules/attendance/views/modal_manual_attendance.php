<div id="modal-manual-attendance" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3"
     role="dialog" aria-labelledby="modal-manual-attendance" aria-hidden="true">
    <form id="form-manual-attendance">
        <?php _csrf_field(); ?>
        <input type="hidden" name="employee_id" value="<?php echo $employee->employee_id; ?>">

        <div class="modal-content">
            <div class="modal-header">
                <a data-dismiss="modal" class="close"><i class="fa fa-close"></i></a>
                <h3><i class="fa fa-pencil text-primary"></i> <?php _trans('manual_attendance'); ?></h3>
                <p class="text-muted" style="margin-bottom: 0;">
                    <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?>
                    (<code><?php echo html_escape($employee->employee_number); ?></code>)
                </p>
            </div>

            <div class="modal-body">
                <div id="modal-alert" class="alert alert-danger hidden"></div>

                <div class="form-group">
                    <label for="attendance_date"><?php _trans('date'); ?> *</label>
                    <input type="text" name="attendance_date" id="attendance_date" class="form-control datepicker"
                           value="<?php echo $date; ?>" required>
                </div>

                <div class="form-group">
                    <label for="status"><?php _trans('status'); ?> *</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="present" <?php echo ($attendance && $attendance->status == 'present') ? 'selected' : ''; ?>><?php _trans('present'); ?></option>
                        <option value="late" <?php echo ($attendance && $attendance->status == 'late') ? 'selected' : ''; ?>><?php _trans('late'); ?></option>
                        <option value="leave" <?php echo ($attendance && $attendance->status == 'leave') ? 'selected' : ''; ?>><?php _trans('leave'); ?></option>
                        <option value="sick" <?php echo ($attendance && $attendance->status == 'sick') ? 'selected' : ''; ?>><?php _trans('sick'); ?></option>
                        <option value="half_day" <?php echo ($attendance && $attendance->status == 'half_day') ? 'selected' : ''; ?>><?php _trans('half_day'); ?></option>
                        <option value="absent" <?php echo ($attendance && $attendance->status == 'absent') ? 'selected' : ''; ?>><?php _trans('absent'); ?></option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="clock_in_time"><?php _trans('clock_in'); ?> Time (HH:MM)</label>
                            <input type="time" name="clock_in_time" id="clock_in_time" class="form-control"
                                   value="<?php echo ($attendance && $attendance->clock_in) ? date('H:i', strtotime($attendance->clock_in)) : '08:30'; ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="clock_out_time"><?php _trans('clock_out'); ?> Time (HH:MM)</label>
                            <input type="time" name="clock_out_time" id="clock_out_time" class="form-control"
                                   value="<?php echo ($attendance && $attendance->clock_out) ? date('H:i', strtotime($attendance->clock_out)) : '17:00'; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes"><?php _trans('notes'); ?></label>
                    <textarea name="notes" id="notes" class="form-control" rows="2"><?php echo $attendance ? html_escape($attendance->notes) : ''; ?></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> <?php _trans('cancel'); ?>
                </button>
                <button type="button" id="btn-save-manual" class="btn btn-success">
                    <i class="fa fa-check"></i> <?php _trans('save'); ?>
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        $('#btn-save-manual').click(function () {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $('#modal-alert').addClass('hidden').text('');

            $.post('<?php echo site_url('attendance/save_manual_attendance'); ?>', $('#form-manual-attendance').serialize(), function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#modal-manual-attendance').modal('hide');
                    window.location.reload();
                } else {
                    $('#modal-alert').removeClass('hidden').text(data.error);
                    $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?php _trans('save'); ?>');
                }
            }).fail(function () {
                $('#modal-alert').removeClass('hidden').text('Network error. Please try again.');
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?php _trans('save'); ?>');
            });
        });
    });
</script>
