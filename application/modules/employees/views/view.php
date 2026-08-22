<div id="headerbar">
    <h1 class="headerbar-title">
        <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?>
        <small>(<code><?php echo html_escape($employee->employee_number); ?></code>)</small>
    </h1>

    <div class="headerbar-item pull-right">
        <?php if (has_permission('employees', 'edit')) : ?>
            <a class="btn btn-sm btn-primary" href="<?php echo site_url('employees/form/' . $employee->employee_id); ?>">
                <i class="fa fa-edit"></i> <?php _trans('edit'); ?>
            </a>
            <a class="btn btn-sm <?php echo $employee->active ? 'btn-warning' : 'btn-success'; ?>"
               href="<?php echo site_url('employees/toggle_status/' . $employee->employee_id); ?>">
                <i class="fa <?php echo $employee->active ? 'fa-ban' : 'fa-check'; ?>"></i>
                <?php echo $employee->active ? trans('deactivate') : trans('activate'); ?>
            </a>
        <?php endif; ?>
        <a class="btn btn-sm btn-default" href="<?php echo site_url('employees'); ?>">
            <i class="fa fa-arrow-left"></i> <?php _trans('back'); ?>
        </a>
    </div>
</div>

<div id="content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <!-- Top Summary Banner -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-12 col-md-7">
            <div class="panel panel-default" style="margin-bottom: 0;">
                <div class="panel-body">
                    <div class="media">
                        <div class="media-left">
                            <span class="img-circle bg-primary text-white" style="display: inline-block; width: 64px; height: 64px; line-height: 64px; text-align: center; font-size: 28px; font-weight: bold; background-color: #337ab7; color: #fff;">
                                <?php echo mb_strtoupper(mb_substr($employee->first_name, 0, 1)); ?>
                            </span>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading" style="margin-top: 5px; font-weight: bold;">
                                <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?>
                                <?php if ($employee->active) : ?>
                                    <span class="label label-success"><?php _trans('active'); ?></span>
                                <?php else : ?>
                                    <span class="label label-danger"><?php _trans('inactive'); ?></span>
                                <?php endif; ?>
                            </h3>
                            <p class="text-muted" style="font-size: 14px; margin-bottom: 5px;">
                                <strong><?php echo html_escape($employee->job_title ?: 'Employee'); ?></strong>
                                <?php if ($employee->department) : ?>
                                    &bull; <?php echo html_escape($employee->department); ?>
                                <?php endif; ?>
                                <?php if ($employee->company_name) : ?>
                                    &bull; <i class="fa fa-building-o"></i> <?php echo html_escape($employee->company_name); ?>
                                <?php endif; ?>
                            </p>
                            <p class="text-muted" style="font-size: 12px; margin-bottom: 0;">
                                <i class="fa fa-envelope-o"></i> <?php echo html_escape($employee->email); ?>
                                <?php if ($employee->mobile || $employee->phone) : ?>
                                    &nbsp;&bull;&nbsp; <i class="fa fa-phone"></i> <?php echo html_escape($employee->mobile ?: $employee->phone); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Account Link Widget -->
        <div class="col-xs-12 col-md-5">
            <div class="panel panel-default" style="margin-bottom: 0;">
                <div class="panel-heading">
                    <i class="fa fa-user-circle-o fa-margin"></i> <?php _trans('user_account_status'); ?>
                </div>
                <div class="panel-body">
                    <?php if ($employee->user_id) : ?>
                        <div class="alert alert-info" style="margin-bottom: 10px; padding: 10px;">
                            <i class="fa fa-check-circle fa-margin"></i>
                            <strong>Linked User Account:</strong> <?php echo html_escape($employee->user_name); ?>
                            <br><small><i class="fa fa-envelope"></i> <?php echo html_escape($employee->user_email); ?></small>
                        </div>
                        <?php if (has_permission('users', 'edit')) : ?>
                            <a href="<?php echo site_url('users/form/' . $employee->user_id); ?>" class="btn btn-sm btn-default btn-block">
                                <i class="fa fa-cog"></i> Manage User Account
                            </a>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="alert alert-warning" style="margin-bottom: 10px; padding: 10px;">
                            <i class="fa fa-exclamation-triangle fa-margin"></i>
                            <strong>No User Account Linked</strong>
                            <br><small>This employee does not have system login access yet.</small>
                        </div>
                        <?php if (has_permission('employees', 'edit')) : ?>
                            <button type="button" class="btn btn-sm btn-primary btn-block btn-create-user-account" data-employee-id="<?php echo $employee->employee_id; ?>">
                                <i class="fa fa-user-plus"></i> <?php _trans('create_user_account'); ?>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Groups -->
    <div class="row">
        <!-- Personal Information -->
        <div class="col-xs-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-user fa-margin"></i> <?php _trans('personal_information'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                        <tr>
                            <td style="width: 40%;"><strong><?php _trans('employee_number'); ?></strong></td>
                            <td><code><?php echo html_escape($employee->employee_number); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('full_name'); ?></strong></td>
                            <td><?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('gender'); ?></strong></td>
                            <td><?php echo ucfirst(html_escape($employee->gender ?: '-')); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('birth_place'); ?> / <?php _trans('birth_date'); ?></strong></td>
                            <td>
                                <?php echo html_escape($employee->birth_place ?: '-'); ?>
                                <?php if ($employee->birth_date) : ?>
                                    (<?php echo date_from_mysql($employee->birth_date); ?>)
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('national_id'); ?></strong></td>
                            <td><?php echo html_escape($employee->national_id ?: '-'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="col-xs-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-envelope fa-margin"></i> <?php _trans('contact_details'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                        <tr>
                            <td style="width: 40%;"><strong><?php _trans('email'); ?></strong></td>
                            <td><a href="mailto:<?php echo html_escape($employee->email); ?>"><?php echo html_escape($employee->email); ?></a></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('mobile'); ?></strong></td>
                            <td><?php echo html_escape($employee->mobile ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('phone'); ?></strong></td>
                            <td><?php echo html_escape($employee->phone ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('address'); ?></strong></td>
                            <td>
                                <?php echo html_escape($employee->address_1); ?>
                                <?php if ($employee->address_2) {
                                    echo '<br>' . html_escape($employee->address_2);
                                } ?>
                                <?php if ($employee->city || $employee->state || $employee->zip_code) : ?>
                                    <br><?php echo html_escape(implode(', ', array_filter([$employee->city, $employee->state, $employee->zip_code]))); ?>
                                <?php endif; ?>
                                <?php if ($employee->country) {
                                    echo '<br>' . html_escape($employee->country);
                                } ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Employment Information -->
        <div class="col-xs-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-briefcase fa-margin"></i> <?php _trans('employment_information'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                        <tr>
                            <td style="width: 40%;"><strong><?php _trans('company'); ?></strong></td>
                            <td><?php echo html_escape($employee->company_name ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('department'); ?></strong></td>
                            <td><?php echo html_escape($employee->department ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('job_title'); ?></strong></td>
                            <td><?php echo html_escape($employee->job_title ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('employment_status'); ?></strong></td>
                            <td><span class="label label-default"><?php echo mb_strtoupper(str_replace('_', ' ', html_escape($employee->employment_status))); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('join_date'); ?></strong></td>
                            <td><?php echo $employee->join_date ? date_from_mysql($employee->join_date) : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bank & Payroll Details -->
        <div class="col-xs-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bank fa-margin"></i> <?php _trans('bank_payroll_details'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                        <tr>
                            <td style="width: 40%;"><strong><?php _trans('bank_name'); ?></strong></td>
                            <td><?php echo html_escape($employee->bank_name ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('account_number'); ?></strong></td>
                            <td><code><?php echo html_escape($employee->bank_account_number ?: '-'); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('bank_account_holder'); ?></strong></td>
                            <td><?php echo html_escape($employee->bank_account_holder ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _trans('tax_id'); ?></strong></td>
                            <td><?php echo html_escape($employee->tax_id ?: '-'); ?></td>
                        </tr>
                        <?php if ($employee->notes) : ?>
                        <tr>
                            <td><strong><?php _trans('notes'); ?></strong></td>
                            <td><em><?php echo nl2br(html_escape($employee->notes)); ?></em></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reimbursement History Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: #ffffff;">
                <div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 15px; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="panel-title" style="font-weight: 700; font-size: 15px; color: #0f172a; margin: 0;">
                        <i class="fa fa-money text-primary" style="margin-right: 5px;"></i> Riwayat Reimburse / Klaim Pengeluaran
                    </h3>
                    <button type="button" class="btn btn-xs btn-primary btn-create-reimbursement-for-emp" data-employee-id="<?php echo $employee->employee_id; ?>" style="border-radius: 6px; font-weight: 600;">
                        <i class="fa fa-plus"></i> Ajukan Reimburse
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 12px;">
                                <th style="padding: 10px 15px;">No. Klaim</th>
                                <th style="padding: 10px 15px;">Judul Klaim & Kategori</th>
                                <th style="padding: 10px 15px;">Tgl Transaksi</th>
                                <th style="padding: 10px 15px; text-align: right;">Nominal</th>
                                <th style="padding: 10px 15px; text-align: center;">Nota</th>
                                <th style="padding: 10px 15px; text-align: center;">Status</th>
                                <th style="padding: 10px 15px; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reimbursements)) : ?>
                                <?php foreach ($reimbursements as $item) : ?>
                                    <tr style="vertical-align: middle;">
                                        <td style="padding: 10px 15px; font-weight: 700;">
                                            <code><?php echo html_escape($item->reimbursement_number); ?></code>
                                        </td>
                                        <td style="padding: 10px 15px;">
                                            <strong style="color: #1e293b;"><?php echo html_escape($item->reimbursement_title); ?></strong><br/>
                                            <span class="label label-default" style="font-size: 10px; background: #e2e8f0; color: #334155; border-radius: 4px;">
                                                <?php echo html_escape($item->category); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 10px 15px; color: #475569;">
                                            <?php echo date_from_mysql($item->reimbursement_date); ?>
                                        </td>
                                        <td style="padding: 10px 15px; text-align: right; font-weight: 700; color: #0f172a;">
                                            Rp <?php echo format_currency($item->amount); ?>
                                        </td>
                                        <td style="padding: 10px 15px; text-align: center;">
                                            <?php if (!empty($item->attachment)) : ?>
                                                <a href="<?php echo site_url('reimbursements/download_attachment/' . $item->reimbursement_id); ?>" class="btn btn-xs btn-default" style="border-radius: 6px;">
                                                    <i class="fa fa-paperclip text-primary"></i> Struk
                                                </a>
                                            <?php else : ?>
                                                <span style="color: #94a3b8;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 10px 15px; text-align: center;">
                                            <?php if ($item->status === 'pending') : ?>
                                                <span class="label label-warning" style="font-size: 10px; padding: 3px 6px;">Pending</span>
                                            <?php elseif ($item->status === 'approved') : ?>
                                                <span class="label label-info" style="font-size: 10px; padding: 3px 6px; background: #3b82f6;">Disetujui</span>
                                            <?php elseif ($item->status === 'paid') : ?>
                                                <span class="label label-success" style="font-size: 10px; padding: 3px 6px;">Lunas</span>
                                            <?php elseif ($item->status === 'rejected') : ?>
                                                <span class="label label-danger" style="font-size: 10px; padding: 3px 6px;">Ditolak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 10px 15px; text-align: right;">
                                            <button type="button" class="btn btn-xs btn-default btn-view-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>" style="border-radius: 4px;">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center" style="padding: 25px; color: #64748b;">
                                        Belum ada riwayat pengajuan reimburse untuk karyawan ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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

    $('.btn-create-reimbursement-for-emp').click(function (e) {
        e.preventDefault();
        var empId = $(this).data('employee-id');
        $('#modal-placeholder').load('<?php echo site_url('reimbursements/ajax/modal_create_reimbursement'); ?>', { employee_id: empId }, function () {
            $('#modal-create-reimbursement').modal('show');
        });
    });

    $(document).on('click', '.btn-view-reimbursement', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').load('<?php echo site_url('reimbursements/ajax/modal_view_reimbursement'); ?>', { reimbursement_id: id }, function () {
            $('#modal-view-reimbursement').modal('show');
        });
    });
});
</script>
