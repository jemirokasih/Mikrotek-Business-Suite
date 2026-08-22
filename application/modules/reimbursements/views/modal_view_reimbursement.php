<div id="modal-view-reimbursement" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">

        <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                <i class="fa fa-file-text-o" style="color: #3b82f6;"></i> Detail Klaim Reimburse <code><?php echo htmlsc($reimbursement->reimbursement_number); ?></code>
            </h4>
        </div>

        <div class="modal-body" style="padding: 20px;">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-xs-12 col-sm-6">
                    <p style="margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 600;">PEMOHON:</p>
                    <h5 style="margin: 0; font-weight: 700; color: #1e293b;"><?php echo htmlsc($reimbursement->user_name); ?></h5>
                    <small style="color: #64748b;"><?php echo htmlsc($reimbursement->user_email); ?></small>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <p style="margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 600;">STATUS KLAIM:</p>
                    <?php if ($reimbursement->status === 'pending') : ?>
                        <span class="label label-warning" style="font-size: 12px; padding: 5px 10px;">Pending</span>
                    <?php elseif ($reimbursement->status === 'approved') : ?>
                        <span class="label label-info" style="font-size: 12px; padding: 5px 10px; background: #3b82f6;">Disetujui</span>
                    <?php elseif ($reimbursement->status === 'paid') : ?>
                        <span class="label label-success" style="font-size: 12px; padding: 5px 10px;">Lunas</span>
                    <?php elseif ($reimbursement->status === 'rejected') : ?>
                        <span class="label label-danger" style="font-size: 12px; padding: 5px 10px;">Ditolak</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 15px; background: #f8fafc;">
                <div class="row">
                    <div class="col-xs-12 col-sm-8">
                        <h4 style="margin: 0 0 5px 0; font-weight: 700; color: #0f172a;"><?php echo htmlsc($reimbursement->reimbursement_title); ?></h4>
                        <span class="label label-default" style="background: #cbd5e1; color: #334155;"><?php echo htmlsc($reimbursement->category); ?></span>
                        <span style="font-size: 12px; color: #64748b; margin-left: 8px;">Tgl Transaksi: <?php echo date_from_mysql($reimbursement->reimbursement_date); ?></span>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-right">
                        <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block;">NOMINAL:</span>
                        <span style="font-size: 20px; font-weight: 800; color: #0f172a;">Rp <?php echo format_currency($reimbursement->amount); ?></span>
                    </div>
                </div>
                <?php if (!empty($reimbursement->description)) : ?>
                    <hr style="margin: 10px 0; border-color: #cbd5e1;">
                    <p style="margin: 0; font-size: 13px; color: #334155; line-height: 1.6;"><?php echo nl2br(htmlsc($reimbursement->description)); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($reimbursement->admin_notes) || !empty($reimbursement->approver_name)) : ?>
                <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px; background: #ffffff; margin-top: 10px;">
                    <b style="color: #475569;">Catatan Evaluasi / Peninjau:</b>
                    <?php if (!empty($reimbursement->approver_name)) : ?>
                        <small style="color: #64748b; display: block;">Ditinjau oleh <?php echo htmlsc($reimbursement->approver_name); ?> pada <?php echo date_from_mysql($reimbursement->approved_at, true); ?></small>
                    <?php endif; ?>
                    <?php if (!empty($reimbursement->admin_notes)) : ?>
                        <p style="margin: 5px 0 0 0; color: #1e293b; font-size: 13px;"><?php echo nl2br(htmlsc($reimbursement->admin_notes)); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($reimbursement->attachment)) : ?>
                <div style="margin-top: 15px;">
                    <label style="font-weight: 600; color: #334155; display: block; margin-bottom: 8px;">Pratinjau Struk / Nota Pembayaran:</label>
                    <?php
                    $ext = strtolower(pathinfo($reimbursement->attachment, PATHINFO_EXTENSION));
                    $attachment_url = base_url('uploads/reimbursements/' . $reimbursement->attachment);
                    ?>
                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) : ?>
                        <div style="text-align: center; background: #000000; padding: 10px; border-radius: 8px;">
                            <img src="<?php echo $attachment_url; ?>" style="max-height: 380px; max-width: 100%; border-radius: 4px;" alt="Nota Struk">
                        </div>
                    <?php else : ?>
                        <div class="well text-center" style="margin-bottom: 0;">
                            <i class="fa fa-file-pdf-o" style="font-size: 32px; color: #ef4444;"></i><br/>
                            <a href="<?php echo site_url('reimbursements/download_attachment/' . $reimbursement->reimbursement_id); ?>" class="btn btn-sm btn-primary" style="margin-top: 10px;">
                                Unduh File Nota PDF
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
        </div>

    </div>
</div>
