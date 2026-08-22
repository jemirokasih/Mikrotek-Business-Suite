<div id="headerbar" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between;">
    <h1 class="headerbar-title" style="font-weight: 700; color: #0f172a; font-size: 20px; margin: 0;">
        <i class="fa fa-money text-primary" style="margin-right: 8px;"></i> Modul Klaim Reimbursement
    </h1>
    <div class="headerbar-item">
        <button type="button" class="btn btn-primary btn-create-reimbursement" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;">
            <i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Ajukan Reimburse Baru
        </button>
    </div>
</div>

<div id="content" class="container-fluid" style="padding: 20px; background: #f8fafc; min-height: 85vh;">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <!-- KPI Summary Cards -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 15px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Pengajuan</div>
                <div style="font-size: 24px; font-weight: 800; color: #0f172a; margin-top: 5px;"><?php echo (int) ($kpi_stats->total_count ?? 0); ?> Klaim</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #fed7aa; padding: 15px; background: #fff7ed; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; font-weight: 600; color: #c2410c; text-transform: uppercase;">Menunggu Persetujuan</div>
                <div style="font-size: 24px; font-weight: 800; color: #ea580c; margin-top: 5px;"><?php echo (int) ($kpi_stats->pending_count ?? 0); ?> Klaim</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #bfdbfe; padding: 15px; background: #eff6ff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; font-weight: 600; color: #1e40af; text-transform: uppercase;">Disetujui (Approved)</div>
                <div style="font-size: 24px; font-weight: 800; color: #2563eb; margin-top: 5px;"><?php echo (int) ($kpi_stats->approved_count ?? 0); ?> Klaim</div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #bbf7d0; padding: 15px; background: #f0fdf4; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; font-weight: 600; color: #15803d; text-transform: uppercase;">Lunas (Paid)</div>
                <div style="font-size: 24px; font-weight: 800; color: #16a34a; margin-top: 5px;"><?php echo format_currency($kpi_stats->total_approved_amount ?? 0); ?></div>
            </div>
        </div>
    </div>

    <!-- Status Tabs & Data Table -->
    <div class="panel panel-default" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: #ffffff;">
        <div class="panel-heading" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 15px;">
            <ul class="nav nav-pills navbar-left" style="margin-bottom: 0;">
                <li class="<?php echo ($status === 'all') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reimbursements/index/all'); ?>" style="border-radius: 8px; font-weight: 600;">Semua Status</a>
                </li>
                <li class="<?php echo ($status === 'pending') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reimbursements/index/pending'); ?>" style="border-radius: 8px; font-weight: 600;">Pending</a>
                </li>
                <li class="<?php echo ($status === 'approved') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reimbursements/index/approved'); ?>" style="border-radius: 8px; font-weight: 600;">Disetujui</a>
                </li>
                <li class="<?php echo ($status === 'paid') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reimbursements/index/paid'); ?>" style="border-radius: 8px; font-weight: 600;">Lunas</a>
                </li>
                <li class="<?php echo ($status === 'rejected') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reimbursements/index/rejected'); ?>" style="border-radius: 8px; font-weight: 600;">Ditolak</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped" style="margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 13px;">
                        <th style="padding: 12px 15px;">No. Klaim</th>
                        <th style="padding: 12px 15px;">Judul Klaim & Kategori</th>
                        <th style="padding: 12px 15px;">Pemohon</th>
                        <th style="padding: 12px 15px;">Tgl Transaksi</th>
                        <th style="padding: 12px 15px; text-align: right;">Nominal</th>
                        <th style="padding: 12px 15px; text-align: center;">Nota</th>
                        <th style="padding: 12px 15px; text-align: center;">Status</th>
                        <th style="padding: 12px 15px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reimbursements)) : ?>
                        <?php foreach ($reimbursements as $item) : ?>
                            <tr style="vertical-align: middle;">
                                <td style="padding: 12px 15px; font-weight: 700;">
                                    <code><?php echo htmlsc($item->reimbursement_number); ?></code>
                                </td>
                                <td style="padding: 12px 15px;">
                                    <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlsc($item->reimbursement_title); ?></strong><br/>
                                    <span class="label label-default" style="font-size: 10px; background: #e2e8f0; color: #334155; border-radius: 4px; padding: 2px 6px;">
                                        <?php echo htmlsc($item->category); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; color: #334155;">
                                    <i class="fa fa-user text-muted" style="margin-right: 4px;"></i> <?php echo htmlsc($item->user_name ?: 'Karyawan'); ?>
                                </td>
                                <td style="padding: 12px 15px; color: #475569;">
                                    <?php echo date_from_mysql($item->reimbursement_date); ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: right; font-weight: 800; color: #0f172a; font-size: 14px;">
                                    <?php echo format_currency($item->amount); ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    <?php if (!empty($item->attachment)) : ?>
                                        <a href="<?php echo site_url('reimbursements/download_attachment/' . $item->reimbursement_id); ?>" class="btn btn-xs btn-default" style="border-radius: 6px;" title="Unduh Nota">
                                            <i class="fa fa-paperclip text-primary"></i> Struk
                                        </a>
                                    <?php else : ?>
                                        <span style="color: #94a3b8;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    <?php if ($item->status === 'pending') : ?>
                                        <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">Pending</span>
                                    <?php elseif ($item->status === 'approved') : ?>
                                        <span class="label label-info" style="font-size: 11px; padding: 4px 8px; background: #3b82f6;">Disetujui</span>
                                    <?php elseif ($item->status === 'paid') : ?>
                                        <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">Lunas</span>
                                    <?php elseif ($item->status === 'rejected') : ?>
                                        <span class="label label-danger" style="font-size: 11px; padding: 4px 8px;">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 15px; text-align: right;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-default btn-view-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>" style="border-radius: 4px;" title="Detail Klaim">
                                            <i class="fa fa-eye text-primary"></i> Detail
                                        </button>

                                        <?php if ($is_admin) : ?>
                                            <?php if ($item->status === 'pending') : ?>
                                                <button type="button" class="btn btn-xs btn-primary btn-approve-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>" style="border-radius: 4px; font-weight: 600;" title="Tinjau & Setujui">
                                                    <i class="fa fa-gavel"></i> Tinjau
                                                </button>
                                            <?php elseif ($item->status === 'approved') : ?>
                                                <button type="button" class="btn btn-xs btn-success btn-pay-reimbursement" data-id="<?php echo $item->reimbursement_id; ?>" style="border-radius: 4px; font-weight: 600;" title="Cairkan Pembayaran">
                                                    <i class="fa fa-credit-card"></i> Cairkan
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($is_admin || $item->status === 'pending') : ?>
                                            <a href="<?php echo site_url('reimbursements/delete/' . $item->reimbursement_id); ?>" class="btn btn-xs btn-default" onclick="return confirm('Apakah Anda yakin ingin menghapus klaim ini?');" style="border-radius: 4px; color: #ef4444;" title="Hapus Klaim">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: #64748b;">
                                <i class="fa fa-inbox" style="font-size: 36px; margin-bottom: 10px; color: #cbd5e1; display: block;"></i>
                                Tidak ada data pengajuan reimburse yang ditemukan.
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
    $('#modal-placeholder').empty();

    <?php if ($this->input->get('action') === 'create') : ?>
    $('#modal-placeholder').load('<?php echo site_url('reimbursements/ajax/modal_create_reimbursement'); ?>', function () {
        $('#modal-create-reimbursement').modal('show');
    });
    <?php endif; ?>

    $('.btn-create-reimbursement').click(function (e) {
        e.preventDefault();
        $('#modal-placeholder').empty().load('<?php echo site_url('reimbursements/ajax/modal_create_reimbursement'); ?>', function () {
            $('#modal-create-reimbursement').modal('show');
        });
    });

    $('.btn-view-reimbursement').click(function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').empty().load('<?php echo site_url('reimbursements/ajax/modal_view_reimbursement'); ?>', { reimbursement_id: id }, function () {
            $('#modal-view-reimbursement').modal('show');
        });
    });

    $('.btn-approve-reimbursement').click(function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').empty().load('<?php echo site_url('reimbursements/ajax/modal_approve_reimbursement'); ?>', { reimbursement_id: id }, function () {
            $('#modal-approve-reimbursement').modal('show');
        });
    });

    $('.btn-pay-reimbursement').click(function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modal-placeholder').empty().load('<?php echo site_url('reimbursements/ajax/modal_pay_reimbursement'); ?>', { reimbursement_id: id }, function () {
            $('#modal-pay-reimbursement').modal('show');
        });
    });
});
</script>
