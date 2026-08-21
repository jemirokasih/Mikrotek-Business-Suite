<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('employees'); ?></h1>

    <div class="headerbar-item pull-right">
        <?php if (has_permission('employees', 'create')) : ?>
            <a class="btn btn-sm btn-primary" href="<?php echo site_url('employees/form'); ?>">
                <i class="fa fa-plus"></i> <?php _trans('add_employee'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="content" class="table-content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <ul class="nav nav-tabs nav-tabs-noborder">
        <li class="<?php echo $status == 'active' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('employees/status/active'); ?>"><?php _trans('active'); ?></a>
        </li>
        <li class="<?php echo $status == 'linked' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('employees/status/linked'); ?>"><?php _trans('linked_user_account'); ?></a>
        </li>
        <li class="<?php echo $status == 'inactive' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('employees/status/inactive'); ?>"><?php _trans('inactive'); ?></a>
        </li>
        <li class="<?php echo $status == 'all' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('employees/status/all'); ?>"><?php _trans('all'); ?></a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><?php _trans('employee_number'); ?></th>
                    <th><?php _trans('name'); ?></th>
                    <th><?php _trans('department'); ?></th>
                    <th><?php _trans('job_title'); ?></th>
                    <th><?php _trans('contact_details'); ?></th>
                    <th><?php _trans('user_account_status'); ?></th>
                    <th><?php _trans('status'); ?></th>
                    <th class="text-right"><?php _trans('options'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($employees)) : ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <em>No employee records found.</em>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($employees as $employee) : ?>
                        <tr>
                            <td>
                                <code><?php echo html_escape($employee->employee_number); ?></code>
                            </td>
                            <td>
                                <strong>
                                    <a href="<?php echo site_url('employees/view/' . $employee->employee_id); ?>">
                                        <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?>
                                    </a>
                                </strong>
                            </td>
                            <td><?php echo html_escape($employee->department ?: '-'); ?></td>
                            <td><?php echo html_escape($employee->job_title ?: '-'); ?></td>
                            <td>
                                <div><i class="fa fa-envelope-o text-muted"></i> <?php echo html_escape($employee->email); ?></div>
                                <?php if ($employee->mobile || $employee->phone) : ?>
                                    <small class="text-muted"><i class="fa fa-phone"></i> <?php echo html_escape($employee->mobile ?: $employee->phone); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($employee->user_id) : ?>
                                    <span class="label label-info">
                                        <i class="fa fa-user"></i> Linked: <?php echo html_escape($employee->user_name); ?>
                                    </span>
                                <?php else : ?>
                                    <span class="label label-default">
                                        <i class="fa fa-user-times"></i> No Account
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($employee->active) : ?>
                                    <span class="label label-success"><?php _trans('active'); ?></span>
                                <?php else : ?>
                                    <span class="label label-danger"><?php _trans('inactive'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <div class="options btn-group">
                                    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="<?php echo site_url('employees/view/' . $employee->employee_id); ?>">
                                                <i class="fa fa-eye fa-margin"></i> <?php _trans('view'); ?>
                                            </a>
                                        </li>
                                        <?php if (has_permission('employees', 'edit')) : ?>
                                            <li>
                                                <a href="<?php echo site_url('employees/form/' . $employee->employee_id); ?>">
                                                    <i class="fa fa-edit fa-margin"></i> <?php _trans('edit'); ?>
                                                </a>
                                            </li>
                                            <?php if ( ! $employee->user_id) : ?>
                                                <li>
                                                    <a href="#" class="btn-create-user-account" data-employee-id="<?php echo $employee->employee_id; ?>">
                                                        <i class="fa fa-user-plus fa-margin"></i> <?php _trans('create_user_account'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (has_permission('employees', 'delete')) : ?>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="<?php echo site_url('employees/delete/' . $employee->employee_id); ?>"
                                                   onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                                                    <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modal-placeholder"></div>

<script>
$(function () {
    $('.btn-create-user-account').click(function (e) {
        e.preventDefault();
        var employeeId = $(this).data('employee-id');
        $('#modal-placeholder').load('<?php echo site_url('employees/modal_create_user_account'); ?>', { employee_id: employeeId }, function () {
            $('#modal-create-user-account').modal('show');
        });
    });
});
</script>
