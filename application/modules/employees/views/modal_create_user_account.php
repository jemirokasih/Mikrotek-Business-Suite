<div id="modal-create-user-account" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2"
     role="dialog" aria-labelledby="modal-create-user-account" aria-hidden="true">
    <form id="form-create-user-account">
        <input type="hidden" name="employee_id" value="<?php echo $employee->employee_id; ?>">

        <div class="modal-content">
            <div class="modal-header">
                <a data-dismiss="modal" class="close"><i class="fa fa-close"></i></a>
                <h3><i class="fa fa-user-plus text-primary"></i> <?php _trans('create_user_account'); ?></h3>
                <p class="text-muted" style="margin-bottom: 0;">
                    Provision system login account for <strong><?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?></strong> (<code><?php echo html_escape($employee->employee_number); ?></code>).
                </p>
            </div>

            <div class="modal-body">

                <div id="modal-user-account-error" class="alert alert-danger hidden"></div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="modal_user_name"><?php _trans('name'); ?> *</label>
                            <input type="text" name="user_name" id="modal_user_name" class="form-control"
                                   value="<?php echo html_escape(trim($employee->first_name . ' ' . $employee->last_name)); ?>" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="modal_user_email"><?php _trans('email'); ?> *</label>
                            <input type="email" name="user_email" id="modal_user_email" class="form-control"
                                   value="<?php echo html_escape($employee->email); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="modal_user_type"><?php _trans('user_type'); ?> *</label>
                            <select name="user_type" id="modal_user_type" class="form-control">
                                <option value="3">Staff / Custom Role</option>
                                <option value="1">Administrator</option>
                                <option value="2">Guest</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6" id="group-role-select">
                        <div class="form-group">
                            <label for="modal_user_role_id"><?php _trans('user_role'); ?></label>
                            <select name="user_role_id" id="modal_user_role_id" class="form-control">
                                <option value=""><?php _trans('none'); ?></option>
                                <?php foreach ($roles as $role) : ?>
                                    <option value="<?php echo $role->role_id; ?>">
                                        <?php echo html_escape($role->role_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="modal_user_password"><?php _trans('password'); ?> *</label>
                            <input type="password" name="user_password" id="modal_user_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="modal_user_password_confirm"><?php _trans('password_verify'); ?> *</label>
                            <input type="password" name="user_password_confirm" id="modal_user_password_confirm" class="form-control" required>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> <?php _trans('cancel'); ?>
                </button>
                <button type="submit" id="btn-submit-create-user" class="btn btn-success">
                    <i class="fa fa-check"></i> Create &amp; Link Account
                </button>
            </div>
        </div>
    </form>
</div>

<script>
$(function () {
    $('#form-create-user-account').submit(function (e) {
        e.preventDefault();
        var $btn = $('#btn-submit-create-user');
        var $error = $('#modal-user-account-error');

        $error.addClass('hidden').text('');
        $btn.prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> Creating...');

        $.post('<?php echo site_url('employees/create_user_account'); ?>', $(this).serialize(), function (response) {
            var data = typeof response === 'string' ? JSON.parse(response) : response;
            if (data.success) {
                window.location.reload();
            } else {
                $error.removeClass('hidden').text(data.error || 'An error occurred while creating the user account.');
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Create &amp; Link Account');
            }
        }).fail(function () {
            $error.removeClass('hidden').text('Network error. Please try again.');
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Create &amp; Link Account');
        });
    });
});
</script>
