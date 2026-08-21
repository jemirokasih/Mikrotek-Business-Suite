<div id="headerbar">
    <h1 class="headerbar-title"><?php echo trans('user_roles'); ?></h1>

    <div class="headerbar-item pull-right">
        <?php if (has_permission('roles', 'create')) : ?>
            <a class="btn btn-sm btn-primary" href="<?php echo site_url('roles/form'); ?>">
                <i class="fa fa-plus"></i> <?php echo trans('new'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="content" class="table-content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo trans('role_name'); ?></th>
                    <th><?php echo trans('description'); ?></th>
                    <th><?php echo trans('options'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role) : ?>
                    <tr>
                        <td><strong><?php echo html_escape($role->role_name); ?></strong></td>
                        <td><?php echo html_escape($role->role_description); ?></td>
                        <td>
                            <div class="options btn-group">
                                <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-cog"></i> <?php echo trans('options'); ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if (has_permission('roles', 'edit')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('roles/form/' . $role->role_id); ?>">
                                                <i class="fa fa-edit fa-margin"></i> <?php echo trans('edit'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (has_permission('roles', 'delete')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('roles/delete/' . $role->role_id); ?>" onclick="return confirm('<?php echo trans('delete_record_warning'); ?>');">
                                                <i class="fa fa-trash-o fa-margin"></i> <?php echo trans('delete'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($roles)) : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted"><?php echo trans('no_records'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
