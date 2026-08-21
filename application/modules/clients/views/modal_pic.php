<script>
    $(function () {
        $('#modal-pic').modal('show');

        $('#modal-pic').on('shown.bs.modal', function () {
            $('#pic_name').focus();
        });

        $('#btn_submit_pic').click(function (e) {
            e.preventDefault();
            show_loader();
            $.post("<?php echo site_url('clients/ajax/save_pic'); ?>", {
                client_id: $('#modal_client_id').val(),
                client_pic_id: $('#modal_client_pic_id').val(),
                pic_name: $('#pic_name').val(),
                pic_position: $('#pic_position').val(),
                pic_email: $('#pic_email').val(),
                pic_phone: $('#pic_phone').val(),
                pic_notes: $('#pic_notes').val(),
                _ip_csrf: $('#modal-pic input[name="_ip_csrf"]').val()
            }, function (data) {
                var response = json_parse(data, <?php echo (int) IP_DEBUG; ?>);
                if (response.success === 1) {
                    $('#modal-pic').modal('hide');
                    $('#modal-placeholder').html('');
                    if (typeof reload_client_pics === 'function') {
                        reload_client_pics();
                    } else {
                        window.location.reload();
                    }
                } else {
                    $('.form-group').removeClass('has-error');
                    for (var key in response.validation_errors) {
                        if (response.validation_errors.hasOwnProperty(key)) {
                            $('#' + key).closest('.form-group').addClass('has-error');
                        }
                    }
                }
                close_loader();
            });
        });
    });
</script>

<div id="modal-pic" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2"
     role="dialog" aria-labelledby="modal_pic_label" aria-hidden="true">
    <form class="modal-content" id="modal_pic_form" onsubmit="return false;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
            <h4 class="panel-title"><?php echo isset($pic) && $pic->client_pic_id ? trans('edit_pic') : trans('add_pic'); ?></h4>
        </div>
        <div class="modal-body">
            <?php _csrf_field(); ?>
            <input type="hidden" name="client_id" id="modal_client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="client_pic_id" id="modal_client_pic_id" value="<?php echo isset($pic) ? $pic->client_pic_id : ''; ?>">

            <div class="form-group">
                <label for="pic_name"><?php _trans('pic_name'); ?> *</label>
                <input type="text" name="pic_name" id="pic_name" class="form-control"
                       value="<?php echo isset($pic) ? htmlsc($pic->pic_name) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="pic_position"><?php _trans('pic_position'); ?></label>
                <input type="text" name="pic_position" id="pic_position" class="form-control"
                       value="<?php echo isset($pic) ? htmlsc($pic->pic_position) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="pic_email"><?php _trans('pic_email'); ?></label>
                <input type="email" name="pic_email" id="pic_email" class="form-control"
                       value="<?php echo isset($pic) ? htmlsc($pic->pic_email) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="pic_phone"><?php _trans('pic_phone'); ?></label>
                <input type="text" name="pic_phone" id="pic_phone" class="form-control"
                       value="<?php echo isset($pic) ? htmlsc($pic->pic_phone) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="pic_notes"><?php _trans('pic_notes'); ?></label>
                <textarea name="pic_notes" id="pic_notes" class="form-control" rows="3"><?php echo isset($pic) ? htmlsc($pic->pic_notes) : ''; ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="btn-group">
                <button class="btn btn-success" id="btn_submit_pic" type="button">
                    <i class="fa fa-check"></i> <?php _trans('submit'); ?>
                </button>
                <button class="btn btn-danger" type="button" data-dismiss="modal">
                    <i class="fa fa-times"></i> <?php _trans('cancel'); ?>
                </button>
            </div>
        </div>
    </form>
</div>
