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

    $('.datepicker').datepicker({
        format: '<?php echo date_format_datepicker(); ?>',
        autoclose: true,
        todayHighlight: true
    });

    $('#form_pay_reimbursement').on('submit', function (e) {
        e.preventDefault();

        var $btn = $('#btn_submit_pay');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $('#modal-pay-error-container').hide().empty();

        var id = $('#pay_reimbursement_id').val();
        var dateVal = $('#payment_date').val();
        var method = $('#payment_method').val();

        var payload = {
            reimbursement_id: id,
            payment_date: dateVal,
            payment_method: method,
            _ip_csrf: getCsrfCookie()
        };

        $.post("<?php echo site_url('reimbursements/ajax/pay_reimbursement'); ?>", payload, function (data) {
            $btn.prop('disabled', false).html('<i class="fa fa-money"></i> Tandai Lunas & Cairkan');
            if (data.success === 1) {
                window.location.reload();
            } else {
                $('#modal-pay-error-container').html('<div class="alert alert-danger">' + (data.error || 'Gagal memproses pembayaran.') + '</div>').show();
            }
        }, 'json').fail(function () {
            $btn.prop('disabled', false).html('<i class="fa fa-money"></i> Tandai Lunas & Cairkan');
            $('#modal-pay-error-container').html('<div class="alert alert-danger">Terjadi kesalahan jaringan/server.</div>').show();
        });
    });
});
</script>

<div id="modal-pay-reimbursement" class="modal fade" role="dialog" aria-labelledby="modal-pay-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <form id="form_pay_reimbursement">
            <input type="hidden" name="reimbursement_id" id="pay_reimbursement_id" value="<?php echo htmlsc($reimbursement->reimbursement_id); ?>">

            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-pay-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                    <i class="fa fa-credit-card text-success"></i> Pencairan / Pembayaran Lunas Klaim
                </h4>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div id="modal-pay-error-container" style="display: none;"></div>

                <div class="well well-sm" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; margin-bottom: 15px;">
                    <p style="margin-bottom: 5px;"><strong>No. Klaim:</strong> <code><?php echo htmlsc($reimbursement->reimbursement_number); ?></code></p>
                    <p style="margin-bottom: 5px;"><strong>Judul Klaim:</strong> <?php echo htmlsc($reimbursement->reimbursement_title); ?></p>
                    <p style="margin-bottom: 0;"><strong>Nominal Dicairkan:</strong> <span style="font-weight: 700; color: #16a34a; font-size: 16px;"><?php echo format_currency($reimbursement->amount); ?></span></p>
                </div>

                <div class="form-group">
                    <label for="payment_date" style="font-weight: 600; color: #334155;">Tanggal Pencairan / Transfer <span class="text-danger">*</span></label>
                    <input type="text" name="payment_date" id="payment_date" class="form-control datepicker" value="<?php echo date_from_mysql(date('Y-m-d')); ?>" required style="border-radius: 8px;">
                </div>

                <div class="form-group">
                    <label for="payment_method" style="font-weight: 600; color: #334155;">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_method" id="payment_method" class="form-control" style="border-radius: 8px;">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Kas Kecil / Cash">Kas Kecil / Cash</option>
                        <option value="Payroll / Gaji Bulanan">Payroll / Gaji Bulanan</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-success" id="btn_submit_pay" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-money"></i> Tandai Lunas & Cairkan
                </button>
            </div>
        </form>
    </div>
    </div>
</div>
