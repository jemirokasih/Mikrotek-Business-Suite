<script>
$(function () {
    function getCsrfCookie() {
        var name = '<?php echo config_item("csrf_cookie_name") ?: "ip_csrf_cookie"; ?>=' ;
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return '';
    }

    $('.btn-action-decision').click(function () {
        var decision = $(this).data('decision');
        var notes = $('#admin_notes').val();

        if (decision === 'rejected' && !notes.trim()) {
            alert('Mohon isi alasan penolakan pada kolom Catatan!');
            $('#admin_notes').focus();
            return false;
        }

        var activeToken = getCsrfCookie() || (typeof csrf_token_value !== 'undefined' ? csrf_token_value : '');

        $.post("<?php echo site_url('reimbursements/ajax/approve_reimbursement'); ?>", {
            reimbursement_id: "<?php echo $reimbursement->reimbursement_id; ?>",
            status: decision,
            admin_notes: notes,
            _ip_csrf: activeToken
        }, function (data) {
            var response = typeof data === 'object' ? data : JSON.parse(data);
            if (response.success === 1) {
                window.location.reload();
            } else {
                alert(response.error || 'Gagal memproses peninjauan klaim.');
            }
        });
    });
});
</script>

<div id="modal-approve-reimbursement" class="modal col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">

        <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                <i class="fa fa-check-square-o" style="color: #10b981;"></i> Peninjauan Klaim Reimburse
            </h4>
        </div>

        <div class="modal-body" style="padding: 20px;">
            <?php _csrf_field(); ?>

            <div class="well well-sm" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px;">
                <h5 style="margin: 0 0 5px 0; font-weight: 700; color: #1e293b;"><?php echo htmlsc($reimbursement->reimbursement_title); ?></h5>
                <p style="margin: 0; color: #475569; font-size: 13px;">
                    Pemohon: <strong><?php echo htmlsc($reimbursement->user_name); ?></strong><br/>
                    Nominal: <strong style="color: #059669;">Rp <?php echo format_currency($reimbursement->amount); ?></strong> (Tgl: <?php echo date_from_mysql($reimbursement->reimbursement_date); ?>)
                </p>
            </div>

            <div class="form-group">
                <label for="admin_notes" style="font-weight: 600; color: #334155;">Catatan / Alasan Penolakan (Opsional jika disetujui, Wajib jika ditolak)</label>
                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Tuliskan catatan persetujuan atau alasan penolakan..." style="border-radius: 8px;"></textarea>
            </div>
        </div>

        <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center;">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn btn-danger btn-action-decision" data-decision="rejected" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-times"></i> Tolak Klaim
                </button>
                <button type="button" class="btn btn-success btn-action-decision" data-decision="approved" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-check"></i> Setujui Klaim
                </button>
            </div>
        </div>

    </div>
</div>
