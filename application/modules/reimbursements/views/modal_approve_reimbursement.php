<script>
$(function () {
    function getCsrfCookie() {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf('ip_csrf_cookie=') === 0) {
                return decodeURIComponent(cookie.substring('ip_csrf_cookie='.length));
            }
        }
        return '';
    }

    $('#form_approve_reimbursement').on('submit', function (e) {
        e.preventDefault();

        var $btn = $('#btn_submit_approve');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $('#modal-approve-error-container').hide().empty();

        var id = $('#approve_reimbursement_id').val();
        var status = $('#approve_status').val();
        var adminNotes = $('#admin_notes').val();

        var payload = {
            reimbursement_id: id,
            status: status,
            admin_notes: adminNotes,
            _ip_csrf: getCsrfCookie()
        };

        $.post("<?php echo site_url('reimbursements/ajax/approve_reimbursement'); ?>", payload, function (data) {
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Simpan Peninjauan');
            if (data.success === 1) {
                window.location.reload();
            } else {
                $('#modal-approve-error-container').html('<div class="alert alert-danger">' + (data.error || 'Gagal memproses peninjauan.') + '</div>').show();
            }
        }, 'json').fail(function () {
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Simpan Peninjauan');
            $('#modal-approve-error-container').html('<div class="alert alert-danger">Terjadi kesalahan jaringan/server.</div>').show();
        });
    });
});
</script>

<div id="modal-approve-reimbursement" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <form id="form_approve_reimbursement">
            <input type="hidden" name="reimbursement_id" id="approve_reimbursement_id" value="<?php echo htmlsc($reimbursement->reimbursement_id); ?>">

            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                    <i class="fa fa-gavel text-primary"></i> Peninjauan Klaim Reimburse (Finance / Admin)
                </h4>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div id="modal-approve-error-container" style="display: none;"></div>

                <div class="well well-sm" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 15px;">
                    <p style="margin-bottom: 5px;"><strong>No. Klaim:</strong> <code><?php echo htmlsc($reimbursement->reimbursement_number); ?></code></p>
                    <p style="margin-bottom: 5px;"><strong>Judul Klaim:</strong> <?php echo htmlsc($reimbursement->reimbursement_title); ?></p>
                    <p style="margin-bottom: 0;"><strong>Nominal:</strong> <span style="font-weight: 700; color: #16a34a; font-size: 15px;"><?php echo format_currency($reimbursement->amount); ?></span></p>
                </div>

                <div class="form-group">
                    <label for="approve_status" style="font-weight: 600; color: #334155;">Keputusan Peninjauan <span class="text-danger">*</span></label>
                    <select name="status" id="approve_status" class="form-control" style="border-radius: 8px; font-weight: 600;">
                        <option value="approved">Disetujui (Approved)</option>
                        <option value="rejected">Ditolak (Rejected)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="admin_notes" style="font-weight: 600; color: #334155;">Catatan Finance / Peninjau</label>
                    <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Tuliskan catatan alasan persetujuan atau penolakan..." style="border-radius: 8px;"></textarea>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn_submit_approve" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-check"></i> Simpan Peninjauan
                </button>
            </div>
        </form>
    </div>
</div>
