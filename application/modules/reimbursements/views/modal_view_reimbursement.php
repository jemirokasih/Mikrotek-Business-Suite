<div id="modal-view-reimbursement" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                <i class="fa fa-file-text-o" style="color: #3b82f6;"></i> Detail Klaim Reimburse: <code><?php echo htmlsc($reimbursement->reimbursement_number); ?></code>
            </h4>
        </div>

        <div class="modal-body" style="padding: 20px;">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <table class="table table-bordered table-striped" style="margin-bottom: 15px; border-radius: 8px; overflow: hidden;">
                        <tr>
                            <td style="width: 40%; font-weight: 600; background: #f8fafc;">Status Klaim</td>
                            <td>
                                <?php if ($reimbursement->status === 'pending') : ?>
                                    <span class="label label-warning" style="font-size: 11px; padding: 4px 8px;">Pending</span>
                                <?php elseif ($reimbursement->status === 'approved') : ?>
                                    <span class="label label-info" style="font-size: 11px; padding: 4px 8px; background: #3b82f6;">Disetujui</span>
                                <?php elseif ($reimbursement->status === 'paid') : ?>
                                    <span class="label label-success" style="font-size: 11px; padding: 4px 8px;">Lunas</span>
                                <?php elseif ($reimbursement->status === 'rejected') : ?>
                                    <span class="label label-danger" style="font-size: 11px; padding: 4px 8px;">Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Judul Klaim</td>
                            <td style="font-weight: 700; color: #0f172a;"><?php echo htmlsc($reimbursement->reimbursement_title); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Nominal (Rp)</td>
                            <td style="font-weight: 700; color: #16a34a; font-size: 15px;">Rp <?php echo format_currency($reimbursement->amount); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Pemohon</td>
                            <td><?php echo htmlsc($reimbursement->user_name ?: 'Karyawan'); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Kategori</td>
                            <td><span class="label label-default" style="background: #e2e8f0; color: #334155; border-radius: 4px;"><?php echo htmlsc($reimbursement->category); ?></span></td>
                        </tr>
                    </table>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <table class="table table-bordered table-striped" style="margin-bottom: 15px; border-radius: 8px; overflow: hidden;">
                        <tr>
                            <td style="width: 40%; font-weight: 600; background: #f8fafc;">Tgl Transaksi</td>
                            <td><?php echo date_from_mysql($reimbursement->reimbursement_date); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Tgl Pengajuan</td>
                            <td><?php echo date('d/m/Y H:i', strtotime($reimbursement->date_created)); ?></td>
                        </tr>
                        <?php if ($reimbursement->approved_at) : ?>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Disetujui Oleh</td>
                            <td><?php echo htmlsc($reimbursement->approver_name ?: 'Finance'); ?> (<?php echo date('d/m/Y H:i', strtotime($reimbursement->approved_at)); ?>)</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($reimbursement->payment_date) : ?>
                        <tr>
                            <td style="font-weight: 600; background: #f8fafc;">Tgl Pencairan</td>
                            <td><?php echo date_from_mysql($reimbursement->payment_date); ?> (<?php echo htmlsc($reimbursement->payment_method ?: 'Cash'); ?>)</td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php if (!empty($reimbursement->description)) : ?>
            <div class="well well-sm" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                <h5 style="margin-top: 0; font-weight: 700; color: #334155;"><i class="fa fa-info-circle text-primary"></i> Keterangan & Rincian Pengeluaran</h5>
                <p style="margin-bottom: 0; color: #475569; white-space: pre-wrap;"><?php echo htmlsc($reimbursement->description); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($reimbursement->admin_notes)) : ?>
            <div class="well well-sm" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                <h5 style="margin-top: 0; font-weight: 700; color: #1e40af;"><i class="fa fa-comment text-primary"></i> Catatan Peninjauan / Finance</h5>
                <p style="margin-bottom: 0; color: #1e3a8a; white-space: pre-wrap;"><?php echo htmlsc($reimbursement->admin_notes); ?></p>
            </div>
            <?php endif; ?>

            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-weight: 600; color: #334155; display: block;">Dokumen Lampiran Nota / Struk:</label>
                <?php if (!empty($reimbursement->attachment)) : ?>
                    <?php
                        $ext = strtolower(pathinfo($reimbursement->attachment, PATHINFO_EXTENSION));
                        $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
                    ?>
                    <div style="margin-top: 10px; text-align: center; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1;">
                        <?php if ($is_image) : ?>
                            <img src="<?php echo base_url('uploads/reimbursements/' . htmlsc($reimbursement->attachment)); ?>" alt="Struk" style="max-width: 100%; max-height: 350px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);" />
                        <?php else : ?>
                            <i class="fa fa-file-pdf-o text-danger" style="font-size: 48px; margin-bottom: 10px;"></i>
                            <p style="margin-bottom: 10px; font-weight: 600; color: #334155;"><?php echo htmlsc($reimbursement->attachment); ?></p>
                        <?php endif; ?>
                        <div style="margin-top: 12px;">
                            <a href="<?php echo site_url('reimbursements/download_attachment/' . $reimbursement->reimbursement_id); ?>" class="btn btn-sm btn-primary" style="border-radius: 6px; font-weight: 600;">
                                <i class="fa fa-download"></i> Unduh File Lampiran Nota
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <p class="text-muted" style="font-style: italic;">Tidak ada dokumen lampiran nota yang diunggah.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
        </div>
    </div>
</div>
