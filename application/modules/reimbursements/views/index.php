<div id="headerbar">
    <div class="headerbar-left" style="display: flex; align-items: center; gap: 10px;">
        <h1 class="headerbar-title" style="font-size: 18px; font-weight: 700; margin: 0;">
            <i class="fa fa-money" style="color: #3b82f6;"></i> <?php echo trans('reimbursements') ?: 'Klaim Reimburse'; ?>
        </h1>
    </div>

    <div class="headerbar-item pull-right">
        <button type="button" class="btn btn-sm btn-primary btn-create-reimbursement" style="border-radius: 8px; font-weight: 600;">
            <i class="fa fa-plus"></i> <?php echo trans('create_reimbursement') ?: 'Ajukan Reimburse'; ?>
        </button>
    </div>
</div>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<!-- KPI Summary Cards -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 15px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Klaim</span>
                    <h3 style="margin: 5px 0 0 0; font-weight: 800; color: #1e293b; font-size: 22px;"><?php echo number_format($kpi_stats->total_count ?? 0); ?></h3>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 20px;">
                    <i class="fa fa-file-text-o"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 15px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 600; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px;">Menunggu Persetujuan</span>
                    <h3 style="margin: 5px 0 0 0; font-weight: 800; color: #d97706; font-size: 22px;"><?php echo number_format($kpi_stats->pending_count ?? 0); ?></h3>
                    <small style="color: #64748b; font-size: 11px;">Rp <?php echo format_currency($kpi_stats->total_pending_amount ?? 0); ?></small>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 20px;">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 15px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 600; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px;">Disetujui</span>
                    <h3 style="margin: 5px 0 0 0; font-weight: 800; color: #2563eb; font-size: 22px;"><?php echo number_format($kpi_stats->approved_count ?? 0); ?></h3>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #dbeafe; display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 20px;">
                    <i class="fa fa-check-circle-o"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 15px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 600; color: #059669; text-transform: uppercase; letter-spacing: 0.5px;">Total Lunas</span>
                    <h3 style="margin: 5px 0 0 0; font-weight: 800; color: #059669; font-size: 22px;"><?php echo number_format($kpi_stats->paid_count ?? 0); ?></h3>
                    <small style="color: #059669; font-weight: 600; font-size: 11px;">Rp <?php echo format_currency($kpi_stats->total_approved_amount ?? 0); ?></small>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #d1fae5; display: flex; align-items: center; justify-content: center; color: #059669; font-size: 20px;">
                    <i class="fa fa-money"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content" class="table-content">
    <!-- Status Filter Buttons -->
    <div class="btn-group btn-group-sm" style="margin-bottom: 15px;">
        <a href="<?php echo site_url('reimbursements/index/all'); ?>" class="btn <?php echo $status === 'all' ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px 0 0 6px;">
            Semua (<?php echo number_format($kpi_stats->total_count ?? 0); ?>)
        </a>
        <a href="<?php echo site_url('reimbursements/index/pending'); ?>" class="btn <?php echo $status === 'pending' ? 'btn-primary' : 'btn-default'; ?>">
            Pending (<?php echo number_format($kpi_stats->pending_count ?? 0); ?>)
        </a>
        <a href="<?php echo site_url('reimbursements/index/approved'); ?>" class="btn <?php echo $status === 'approved' ? 'btn-primary' : 'btn-default'; ?>">
            Disetujui (<?php echo number_format($kpi_stats->approved_count ?? 0); ?>)
        </a>
        <a href="<?php echo site_url('reimbursements/index/paid'); ?>" class="btn <?php echo $status === 'paid' ? 'btn-primary' : 'btn-default'; ?>">
            Lunas (<?php echo number_format($kpi_stats->paid_count ?? 0); ?>)
        </a>
        <a href="<?php echo site_url('reimbursements/index/rejected'); ?>" class="btn <?php echo $status === 'rejected' ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 0 6px 6px 0;">
            Ditolak (<?php echo number_format($kpi_stats->rejected_count ?? 0); ?>)
        </a>
    </div>

    <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: #ffffff;">
        <div class="table-responsive">
            <table class="table table-hover table-striped" style="margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 13px;">
                        <th style="padding: 12px 15px;">No. Klaim</th>
                        <th style="padding: 12px 15px;">Pemohon</th>
                        <th style="padding: 12px 15px;">Judul Klaim & Kategori</th>
                        <th style="padding: 12px 15px;">Tgl Pengeluaran</th>
                        <th style="padding: 12px 15px; text-align: right;">Nominal</th>
                        <th style="padding: 12px 15px; text-align: center;">Nota / Struk</th>
                        <th style="padding: 12px 15px; text-align: center;">Status</th>
                        <th style="padding: 12px 15px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reimbursements)) : ?>
                        <?php foreach ($reimbursements as $item) : ?>
                            <tr style="vertical-align: middle;">
                                <td style="padding: 12px 15px; font-weight: 700; color: #0f172a;">
                                    <code><?php echo htmlsc($item->reimbursement_number); ?></code>
                                </td>
                                <td style="padding: 12px 15px;">
                                    <strong><?php echo htmlsc($item->user_name); ?></strong><br/>
                                    <small style="color: #64748b;"><?php echo htmlsc($item->user_email); ?></small>
                                </td>
                                <td style="padding: 12px 15px;">
                                    <span style="font-weight: 600; color: #1e293b;"><?php echo htmlsc($item->reimbursement_title); ?></span><br/>
                                    <span class="label label-default" style="font-size: 10px; background: #e2e8f0; color: #334155; border-radius: 4px; padding: 2px 6px;">
                                        <?php echo htmlsc($categories[$item->category] ?? $item->category); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; color: #475569;">
                                    <?php echo date_from_mysql($item->reimbursement_date); ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: right; font-weight: 700; color: #0f172a; font-size: 14px;">
                                    Rp <?php echo format_currency($item->amount); ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    <?php if (!empty($item->attachment)) : ?>
                                        <a href="<?php echo site_url('reimbursements/download_attachment/' . $item->reimbursement_id); ?>" class="btn btn-xs btn-default" style="border-radius: 6px;" title="Unduh Nota">
                                            <i class="fa fa-paperclip" style="color: #3b82f6;"></i> Struk
                                        </a>
                                    <?php else : ?>
                                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    <?php if ($item->status === 'pending') : ?>
                                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                            <i class="fa fa-clock-o"></i> Pending
                                        </span>
                                    <?php elseif ($item->status === 'approved') : ?>
                                        <span class="label label-info" style="font-size: 11px; padding: 4px 8px; border-radius: 6px; background: #3b82f6;">
                                            <i class="fa fa-check"></i> Disetujui
                                        </span>
                                    <?php elseif ($item->status === 'paid') : ?>
                                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                            <i class="fa fa-check-circle"></i> Lunas
                                        </span>
                                    <?php elseif ($item->status === 'rejected') : ?>
                                        <span class="label label-danger" style="font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                                            <i class="fa fa-times"></i> Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: right;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px;">
                                            Aksi <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" style="font-size: 13px;">
                                            <li>
                                                <a href="#" class="btn-view-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>">
                                                    <i class="fa fa-eye" style="width: 18px; color: #3b82f6;"></i> Detail Klaim
                                                </a>
                                            </li>
                                            <?php if ($is_admin) : ?>
                                                <?php if ($item->status === 'pending') : ?>
                                                    <li role="separator" class="divider"></li>
                                                    <li>
                                                        <a href="#" class="btn-approve-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>">
                                                            <i class="fa fa-check-square-o" style="width: 18px; color: #10b981;"></i> Tinjau & Setujui
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($item->status === 'approved') : ?>
                                                    <li role="separator" class="divider"></li>
                                                    <li>
                                                        <a href="#" class="btn-pay-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>">
                                                            <i class="fa fa-money" style="width: 18px; color: #10b981;"></i> Tandai Lunas
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: #64748b;">
                                <i class="fa fa-folder-open-o" style="font-size: 36px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                Belum ada riwayat pengajuan klaim reimburse.
                            </td>
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
    // Function to open create modal
    function openCreateModal() {
        $('#modal-placeholder').empty().load("<?php echo site_url('reimbursements/ajax/modal_create_reimbursement'); ?>", function () {
            $('#modal-create-reimbursement').modal('show');
        });
    }

    // Open Create Modal on button click
    $(document).on('click', '.btn-create-reimbursement', function (e) {
        e.preventDefault();
        openCreateModal();
    });

    // Check URL parameter action=create
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        openCreateModal();
    }

    // Open View Modal
    $(document).on('click', '.btn-view-reimbursement', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').load("<?php echo site_url('reimbursements/ajax/modal_view_reimbursement'); ?>", { reimbursement_id: id }, function () {
            $('#modal-view-reimbursement').modal('show');
        });
    });

    // Open Approve Modal
    $(document).on('click', '.btn-approve-reimbursement', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').load("<?php echo site_url('reimbursements/ajax/modal_approve_reimbursement'); ?>", { reimbursement_id: id }, function () {
            $('#modal-approve-reimbursement').modal('show');
        });
    });

    // Open Pay Modal
    $(document).on('click', '.btn-pay-reimbursement', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').load("<?php echo site_url('reimbursements/ajax/modal_pay_reimbursement'); ?>", { reimbursement_id: id }, function () {
            $('#modal-pay-reimbursement').modal('show');
        });
    });
});
</script>
