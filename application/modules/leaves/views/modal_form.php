<div id="modal-apply-leave" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-apply-leave-title" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <a data-dismiss="modal" class="close"><i class="fa fa-close"></i></a>
            <h3 id="modal-apply-leave-title"><?php _trans('apply_leave'); ?></h3>
        </div>

        <form id="form-apply-leave">
            <?php _csrf_field(); ?>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="leave_type"><?php _trans('leave_type'); ?> *</label>
                            <select name="leave_type" id="leave_type" class="form-control" required>
                                <?php foreach ($leave_types as $key => $name) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group has-feedback">
                            <label for="start_date"><?php _trans('start_date'); ?> *</label>
                            <div class="input-group">
                                <input type="text" name="start_date" id="start_date" class="form-control datepicker" required value="<?php echo date(date_format_setting()); ?>" autocomplete="off">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div class="form-group has-feedback">
                            <label for="end_date"><?php _trans('end_date'); ?> *</label>
                            <div class="input-group">
                                <input type="text" name="end_date" id="end_date" class="form-control datepicker" required value="<?php echo date(date_format_setting()); ?>" autocomplete="off">
                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="reason"><?php _trans('reason'); ?></label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="State your reason for leave..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn btn-success" id="btn-save-leave" type="submit">
                        <i class="fa fa-check"></i> <?php _trans('submit'); ?>
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
    $('.datepicker').datepicker({
        format: '<?php echo date_format_datepicker(); ?>',
        autoclose: true,
        todayHighlight: true
    });

    $('#form-apply-leave').submit(function (e) {
        e.preventDefault();
        var $btn = $('#btn-save-leave');
        $btn.prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> Submitting...');

        $.post("<?php echo site_url('leaves/save'); ?>", $(this).serialize(), function (data) {
            var response = typeof data === 'string' ? JSON.parse(data) : data;
            if (response.success === 1) {
                $('#modal-apply-leave').modal('hide');
                window.location.reload();
            } else {
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?php _trans('submit'); ?>');
                $('.has-error').removeClass('has-error');
                for (var key in response.validation_errors) {
                    $('#' + key).parent().addClass('has-error');
                }
                alert('Please correct validation errors.');
            }
        }).fail(function () {
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> <?php _trans('submit'); ?>');
            alert('Network error. Please try again.');
        });
    });
});
</script>
