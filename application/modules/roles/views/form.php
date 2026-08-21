<form method="post">
    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title"><?php echo trans('user_roles'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">
        <?php $this->layout->load_view('layout/alerts'); ?>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo trans('role_details'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="role_name"><?php echo trans('role_name'); ?> *</label>
                            <input type="text" name="role_name" id="role_name" class="form-control"
                                   value="<?php echo html_escape($this->mdl_roles->form_value('role_name')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="role_description"><?php echo trans('description'); ?></label>
                            <textarea name="role_description" id="role_description" class="form-control" rows="3"><?php echo html_escape($this->mdl_roles->form_value('role_description')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo trans('permissions_matrix'); ?>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?php echo trans('module'); ?></th>
                            <th class="text-center"><?php echo trans('view'); ?></th>
                            <th class="text-center"><?php echo trans('create'); ?></th>
                            <th class="text-center"><?php echo trans('edit'); ?></th>
                            <th class="text-center"><?php echo trans('delete'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matrix as $mod_key => $mod_data) : ?>
                            <tr>
                                <td><strong><?php echo html_escape($mod_data['label']); ?></strong></td>
                                <?php foreach (['view', 'create', 'edit', 'delete'] as $act) : ?>
                                    <td class="text-center">
                                        <?php if (in_array($act, $mod_data['actions'])) : ?>
                                            <?php
                                            $checked = isset($role_permissions[$mod_key][$act]) && $role_permissions[$mod_key][$act];
                                            ?>
                                            <input type="checkbox" name="permissions[<?php echo $mod_key; ?>][<?php echo $act; ?>]" value="1" <?php echo $checked ? 'checked' : ''; ?>>
                                        <?php else : ?>
                                            <span class="text-muted">&mdash;</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
